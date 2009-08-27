<?php
class UserService{

	private static $currentUser = null;

	/**
	 * Checks whether a user is logged or not.
	 * If not, the user is redirected to the /admindeo page.
	 */
	public static function checkLogged() {
		$is_logged = SessionUtils::isLogged();
		if(!$is_logged){
			header("Location:".ROOT_URL);
			exit;
		}
	}

	/**
	 * Checks whether a user is admin or not.
	 * If not, the user is redirected to the /admindeo page.
	 */
	public static function checkAdmin() {
		$is_admin = SessionUtils::isAdmin();
		if(!$is_admin){
			MessageController::redirectToMessage('userservice.notadmin.title','userservice.notadmin.text');
		}
	}

	public static function ConnectUser($login) {
		self::$currentUser = self::findUser($login);
		if (self::$currentUser != null) {
			SessionUtils::logout();
			SessionUtils::setUserLogin($login);
			SessionUtils::setUserId(self::$currentUser->id);
			SessionUtils::setGroupId(self::$currentUser->group_id);
			SessionUtils::setAccountId(self::$currentUser->account_id);
			return true;
		} else {
			return false;
		}
	}
	
	public static function BecomeUser($user) {
		SessionUtils::logout();
		SessionUtils::setUserLogin($user->login);
		SessionUtils::setUserId($user->id);
		SessionUtils::setGroupId($user->group_id);
		SessionUtils::setAccountId($user->account_id);
	}

	/**
	 * Log the user via a token.
	 * @return bool true on success, false on failure.
	 */
	public static function loginViaToken($userId, $token) {
		$result = self::validateUserAccount($userId, $token);
		if ($result==true) SessionUtils::setTokenLogin(true);
		return $result;
	}

	/**
	 * Returns the User DBM object for the current user, or null if the user is not logged.
	 *
	 */
	public static function getUser(){
		if (self::$currentUser != null)
		return self::$currentUser;

		$login = SessionUtils::getUserLogin();
		if ($login == null)
		return null;

		self::$currentUser = self::findUser($login);
		return self::$currentUser;
	}

	/**
	 * Returns a user object from its login.
	 * If the account for the user is not active or if the user does not exist, returns null.
	 *
	 * @param string $login
	 * @return DBM_Object
	 */
	public static function findUser($login){
		$users = DBM_Object::getObjects("users", new DBM_EqualFilter("users", "login", $login));
		if(count($users)==1) {
			$user = $users[0];
			$account = DBM_Object::getObject("accounts", $user->account_id);
//			if ($account->active!=1) $user = null;
		}else $user = null;
		return $user;
	}

	/**
	 * Recover the user account.
	 *
	 * @param int $user_id
	 * @return DBM_Object
	 */
	public static function getAccount($user_id=null) {
		if(!$user_id){
			$user = self::getUser();
		}else{
			$user = UserService::getUserById($user_id);
		}
		$account = UserService::getAccountById($user->account_id);
		return $account;
	}

	public static function getApideokeys() {

		$account = self::getAccount();
		$apideokeys = DBM_Object::getObjects("apideokeys", new DBM_EqualFilter("apideokeys", "account_id", $account->id));

		return $apideokeys;
	}

	public static function getPlans(){
		$apideokeys = self::getApideokeys();
		$plans = new DBM_ObjectArray();
		if(count($apideokeys)>0){
			foreach ($apideokeys as $apideokey) {
				$plan = DBM_Object::getObject("plans", $apideokey->plan_id);
				$plans->append($plan);
			}
		}
		return $plans;
	}

	public static function getPlanApideokeys(){
		$plans = array();
		$apideokeys = self::getApideokeys();
		if(count($apideokeys)>0){
			foreach ($apideokeys as $apideokey) {
				$plan = DBM_Object::getObject("plans", $apideokey->plan_id);
				$planSubscr->apideokey = $apideokey;
				$planSubscr->plan = $plan;
				$plans[] = $planSubscr;
			}
		}
		return $plans;
	}

	public static function getPlan ($sub_plan_id) {
		// FIXME: ça peut pas marcher!!!! $account_plan_id n'est pas setté
		$account_plan = DBM_Object::getObject("plans",$account_plan_id);
		$plans = DBM_Object::getObjects("plans", new DBM_EqualFilter("plans", "id", $account_plan->plan_id));
		if(count($plans)==1){
			$plan = $plans[0];
		}
		return $plan;
	}

	public static function checkLoginExists($login) {
		$exist = DBM_Object::getObjects("users", new DBM_EqualFilter("users", "login", $login));
		return (count($exist)>0);
	}


	public static function checkNicknameExists($nickname) {
		$exist = DBM_Object::getObjects("users", new DBM_EqualFilter("users", "nickname", $nickname));
		return (count($exist)>0);
	}


	public static function validateUserAccount($userId, $token){
		$user = UserService::getUserById($userId);
		$account = UserService::getAccountById($user->account_id);
		//var_dump($user);
		if ($user->token==$token){
			try{
				DB_Connection::$main_db->autoCommit(false);
				if ($account->active==0){
					$account->active = 1;
					//	create LDAP USER
					self::createLdapUser($user);
					$user->token='';
					$filters = array();
					$freeplan = self::getFreePlan();
					$filters[] = new DBM_EqualFilter('apideokeys', 'account_id', $account->id);
					$filters[] = new DBM_EqualFilter('apideokeys', 'plan_id', $freeplan);
					$free_sub = DBM_Object::getObjects('apideokeys', $filters);
					$free_sub->is_active = 1;
					DB_Connection::$main_db->commit();
					$result = UserService::ConnectUser($user->login);
					if (!$result) {
						Log::error("Unable to connect user '".$user->login."'");
						throw new Exception("Unable to connect user '".$user->login."'");
					}
						return TRUE;
				}
				return FALSE;
			}catch (Exception $e){
				DB_Connection::$main_db->rollback();
				return FALSE;
			}
		}else{
			return FALSE;
		}
	}

	public static function getFreePlan(){
		//TODO for the while always return the account with code defined 'FREE_PLAN_CODE'
		$freePlans = DBM_Object::getObjects('plans', "code='".DB_FREE_PLAN_CODE."'");
		return $freePlans[0]->id or die("Error retrieving free plan (either no free plan or more then one plan with code".DB_FREE_PLAN_CODE);
	}
	public static function getUserById($userId){
		return DBM_Object::getObject('users', $userId);
	}

	public static function getUserByEmail($email){
		$result = DBM_Object::getObjects('users', new DBM_EqualFilter('users', "email", $email));
		if (count($result)>1) die('multiple users found with same email');
		else if (count($result)==0) return null;
		else return $result[0];
	}

	public static function getAccountById($accountId){
		return DBM_Object::getObject('accounts', $accountId);
	}
	public static function getApideokeyById($accountId){
		$subs = DBM_Object::getObjects('apideokeys', new DBM_EqualFilter('apideokeys',"id",$accountId));
		if (count($subs)==1){
			$sub = $subs[0];
		}else $sub = null;
		return $sub;
	}

	public static function getUserRole($user){
		if ($user->group_id==SessionUtils::$ADMINGROUPID) return LDAP_ADMIN_GROUP;
		else{
			$apideokeys = DBM_Object::getObjects('apideokeys', new DBM_EqualFilter('apideokeys', "account_id", $user->account_id));
			$isFree = true;
			foreach ($apideokeys as $apideokey){
				$plan = DBM_Object::getObject('plans', $apideokey->plan_id);
				if ($plan->is_free==0){
					$isFree = false;
					break;
				}
			}
			return $isFree?LDAP_USER_GROUP:LDAP_CUSTOMER_GROUP;
		}
	}

	public static function createLdapUser($user){
		if (USE_LDAP){
			require_once 'include/utils/ldap/Ldap.php';
			require_once 'include/utils/ldap/LdapUser.php';
			$ldapClient = Ldap::getInstance();
			$ldapUser = new LdapUser($user->email, $user->nickname, self::getUserRole($user), $user->email, $user->password);
			$ldapClient->addUser($ldapUser);
		}
	}

	public static function modifyLdapUser($user){
		if (USE_LDAP){
			require_once 'include/utils/ldap/Ldap.php';
			require_once 'include/utils/ldap/LdapUser.php';
			$ldapClient = Ldap::getInstance();
			$ldapUser = new LdapUser($user->email, $user->nickname, self::getUserRole($user), $user->email, $user->password);
			if (!$ldapClient->isExist($user->email)){
				self::createLdapUser($user);
			}else{
				$ldapClient->modifyUser($ldapUser);			
			}
		}
	}

	public static function getAllButMe($user){
		$filters = array();
		$filters[] = new DBM_EqualFilter('users', 'account_id', $user->account_id);
		$filters[] = new DBM_NotFilter(new DBM_EqualFilter('users', 'id', $user->id));
		Log::trace(DBM_Object::explainSQLGetObjects('users', $filters));
		$users = DBM_Object::getObjects('users', $filters, 'email ASC');
		return $users;
	}

	public static function createUser($email, $nickname, $password, $accountId, $accpectNews){
		try{
			DB_Connection::$main_db->autoCommit(false);
			$user = DBM_Object::getNewObject('users');
			$user->email = $email;
			$user->nickname = $nickname;
			$user->password = $password;
			$user->login = $email;
			$user->group_id = AdminBag::getUserGroup();
			$user->account_id = $accountId;
			$user->newsletter = $accpectNews!=null;
			self::createLdapUser($user);
			DB_Connection::$main_db->commit();
			$message = iMsg('new_user_add.success', $user->email);
		}catch (Exception $e){
			$message = iMsg('new_user_add.fail', $user->email);
			DB_Connection::$main_db->rollback();
		}
		return $message;
	}

	/**
	 * returns true if the user is authorized to deal with this apideokey
	 *
	 * @param integer $apideokeyId
	 * @return boolean
	 */
	public static function isMyApideokey($apideokeyId) {
		if(!$apideokeyId) return false;
		$bool = false;
		$user = self::getUser();
		$apideokey = self::getApideokeyById($apideokeyId);
		if($apideokey) {
			$target_account = self::getAccountById($apideokey->account_id);
			if($target_account->id==$user->account_id) $bool = true;
		}
		return $bool;
	}

	/**
	 * returns true if the account is the account of the current user.
	 *
	 * @param integer $accountId
	 * @return boolean
	 */
	public static function isMyAccount($accountId) {
		if(!$accountId) return false;
		$bool = false;
		$user = self::getUser();
		$account = self::getAccountById($accountId);
		if($account) {
			$target_account = self::getAccountById($user->account_id);
			if($target_account->id == $account->id
					&& $target_account->active == 1) 
				$bool = true;
		}
		return $bool;
	}
	/**
	 * Tells in the user exists in LDAP
	 *
	 * @param DBM_Object $user The DBM_OBJECT representation of the users tabel
	 * @return TRUE if the user exists in LDAP, else FALSE
	 */
	public static function existsInLdap($user){
		require_once 'include/utils/ldap/Ldap.php';
		require_once 'include/utils/ldap/LdapUser.php';
		if (USE_LDAP) return Ldap::getInstance()->isExist($user->email);
		else return true;
	}
}
?>
