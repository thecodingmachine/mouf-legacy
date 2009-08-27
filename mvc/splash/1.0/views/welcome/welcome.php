<?php
function welcome($error = null, $error_type = null, $login = null) {
	if(!SessionUtils::isLogged()) {
		?>
	<div>
		<h1 class="admindeo"><span style="color: #000">WELCOME TO A</span>D<span
			style="color: #000">MINDEO</span></h1>
	</div>
		<div style="float: left;padding-right: 15px">
			<div class="stats-key" style="float: left;width: 435px;height: 300px;">
			<form action="<?php echo ROOT_URL ?>Login/logon" id="logon_form" method="post" onsubmit="return validateLogon()">
				<div id="log-on-member">
					<br />
					<?php eMsg('logon.allready.member'); ?>
				</div>
				<div style="padding: 20px;border: 1px solid white; width: 350px;margin: auto">
					<table>
						<tr>
							<td class="table-header"><?php eMsg('logon.email');?> : </td>
							<td>
								<?php if(!is_null($login)) { ?>
									<input size="35" type="text" value="<?php echo $login; ?>" id="loEmail" name="loEmail"/>
								<?php } else { ?>
									<input size="35" type="text" value="you@your_domain.com" onfocus="erase(this);" id="loEmail" name="loEmail"/>
								<?php } ?>
							</td>
						</tr>
						<tr>
							<td class="table-header"><?php eMsg('logon.password');?> : </td>
							<td><input size="35" type="password" id="loPass" name="loPass"/></td>
						</tr>
					</table>
					<br />
					<button id="sign_up_button" type="submit">Login</button>
					<!-- 
					<div id="sign_up_button"><input type="image"  value="     "/></div>
					 -->
					<?php 
					if($error) {
						switch($error_type) {
						case 'notfound': 
							echo '<p class="small_error_bis" id="display_error">';
							//login.notfound_notactivated
							eMsg('login.invalid');
							echo '</p>';
							break;
						case 'account_not_active': 
							echo '<p class="small_error_bis" id="display_error">';
							//login.notfound_notactivated
							eMsg('account.not.active');
							eMsg('<a href="'.ROOT_URL.'Login/sendNewRegistrationMail?login='.$login.'">'.iMsg('send.registration_mail').'</a>');
							echo '</p>';
							break;
						default:
							echo '<p class="small_error_bis" id="display_error">';
							eMsg('login.error');
							echo '</p>';
						}
					}
					?>
					<p class="small_error" id="emailError"><?php eMsg('email.invalid'); ?></p>
					<p class="small_error" id="passEmpty"><?php  eMsg('password.empty'); ?></p>
					<div style="display: none"></div>
				</div>
			</form>
			<br/>
			<form id="formForgot" action="Welcome/sendForgotPassword" method="post">
				<div  onclick="EffectUtils.showHideDiv('divEnterEmail')" style="cursor: pointer;padding-left: 40px">
					<a name="forgot_pwd" href="#forgot_pwd"><?php eMsg('logon.forgot.password'); ?></a>
				</div>
				<div id="divEnterEmail" style="display: none; text-align:center; padding-left: 20px">
					<br />
					<div style="padding: 20px;border: 1px solid white; width: 350px;">
						<input name="forgotEmail" id="forgotEmail" type="text" value="<?php eMsg('welcome.forgot.ask.email')?>" onclick="if (this.value=='<?php eMsg('welcome.forgot.ask.email')?>') this.value=''"/>
						<input type="button" onclick="validateEmail()" value="OK"/>
						<p class="small_error" id="emailError2"><?php eMsg('email.invalid'); ?></p>
					</div>
				</div>
			</form>
			<br />
			</div>
		</div>
		<div class="stats-key" style="float: left;width: 450px;height: 300px">
			<form action="Login/Subscribe" id="signup_form" method="post">
			<div>
				<br />
					<div id="log-on-sign-up"><?php eMsg("logon.not.member"); ?></div>
				<div style="padding: 19px;border: 1px solid white; width: 350px;margin: auto">
					<div style="width: 400px; margin: auto;">
						<p style="padding:30px"><a href="<?php echo ROOT_URL."Plan/choose/" ?>"><?php eMsg("logon.signup"); ?></a></p>
					</div>
				</div>
			</div>
			</form>
		</div>
		<?php
	}else {
		header("Location:".ROOT_URL."Account/");
		exit;
	}
		?>
	<div style="clear: both;"></div>
	<?php
}

function jsWelcome() {
?>
	<script type="text/javascript">
	<!--
	function erase(box){
		if (box.value=='you@your_domain.com'){
			box.value='';
		}
	}

	function validateLogon(){
		email = document.getElementById('loEmail').value;
		password = document.getElementById('loPass').value;
		emailOk = Validator.validateEmail(email, 'emailError');
		passOk = false;

		if (password=='') {
			document.getElementById('passEmpty').style.display = 'block';
		} else {
			document.getElementById('passEmpty').style.display = 'none';
			passOk = true;
		}
		if (emailOk && passOk) {
			return true;
		} else {
			//return false;
			if(document.getElementById('display_error') != null)
				document.getElementById('display_error').style.display = 'none';
		}
		return false;
	}

	function validateEmail(){
		email = document.getElementById('forgotEmail').value;
		if (Validator.validateEmail(email, 'emailError2')) document.getElementById('formForgot').submit();
	}
	-->
</script>
<?php
}
?>
