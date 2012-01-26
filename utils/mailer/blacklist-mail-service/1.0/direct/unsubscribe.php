<?php
require_once dirname(__FILE__).'/../../../../../../Mouf.php';

$translate = Mouf::getBlacklistTranslationService();
$blackListMailService = Mouf::getBlacklistMailService();
$dbMailService = $blackListMailService->forwardTo;
$dbMail = $dbMailService->getMailByHashKey(get("id"));
$toRecipients = $dbMail->getToRecipients();
if (count($toRecipients) == 0) {
	throw new BlackListMailServiceException("Error while unsubscribing user: the mail does not have a recipient.");
}
if (count($toRecipients) > 1) {
	throw new BlackListMailServiceException("Error while unsubscribing user: the mail has more than one recipient.");
}
/* @var $toRecipient MailAddressInterface */
$toRecipient = $toRecipients[0];

if (get('scope')=='all') {
	$blackListMailService->unsubscribe($toRecipient->getMail());
} else {
	$blackListMailService->unsubscribe($toRecipient->getMail(), $dbMail->getCategory(), $dbMail->getType());
}

echo $translate->getTranslation("blacklistmailservice.confirm.thankyou");
?>