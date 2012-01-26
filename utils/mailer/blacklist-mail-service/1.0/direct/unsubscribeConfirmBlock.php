<?php
require_once dirname(__FILE__).'/../../../../../../Mouf.php';

$translate = Mouf::getBlacklistTranslationService();

$blackListMailService = Mouf::getBlacklistMailService();
$dbMailService = $blackListMailService->forwardTo;
$dbMail = $dbMailService->getMailByHashKey(get("id"));
?>
<h1><?php echo $translate->getTranslation("blacklistmailservice.confirm.title"); ?></h1>

<?php echo $translate->getTranslation("blacklistmailservice.confirm.text"); ?>

<form action="<?php echo ROOT_URL ?>plugins/utils/mailer/blacklist-mail-service/1.0/direct/unsubscribe.php" method="post">
	<input name="id" type="hidden" value="<?php echo get("id"); ?>" />
	<input name="name" type="hidden" value="<?php echo get("name"); ?>" />

<?php 
if ($dbMail->getCategory() || $dbMail->getType()) {
	// There is a category or a type, let's add the user if he wants to unsubscribe for all or just this type.
?>
	<div><input checked="checked" name="scope" type="radio" value="bycategory" id="bycategoryunsubscribe" /><label for="bycategoryunsubscribe">Unsubscribe from this mailing list</label></div>
	<div><input name="scope" type="radio" value="all" id="allunsubscribe" /><label for="allunsubscribe">Unsubscribe from this mailing list and any other mailing list we might send to you</label></div>
<?php 
}
?>


	<button type="submit"><?php echo $translate->getTranslation("blacklistmailservice.confirm.button"); ?></button>
</form>