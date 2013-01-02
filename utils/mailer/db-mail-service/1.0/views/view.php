<?php 
/*@var $this DBMailServiceListController */
?>
<h1>Mail detail</h1>
<script type="text/javascript">
function setIframeHeight(height) {
	jQuery("#htmlBodyMail").height(height);
}
</script>
<style>
iframe#htmlBodyMail {
	border: solid 1px #cccccc;
}
pre#textbody {
	border: solid 1px #cccccc;
}
</style>
<?php mailViewDisplayBackButton($this); ?>
<div>
<label>From:</label>
<span><?php echo '<a href="mailto:'.$this->mail->getFrom()->getMail().'">'.plainstring_to_htmlprotected($this->mail->getFrom()).'</a>'; ?></span>
</div>
<?php
displayMailAddresses($this->mail->getToRecipients(), "To");
displayMailAddresses($this->mail->getCcRecipients(), "Cc");
displayMailAddresses($this->mail->getBccRecipients(), "Bcc");
?>
<div>
<label>Title:</label>
<span><?php echo plainstring_to_htmlprotected($this->mail->getTitle()); ?></span>
</div>
<div>
<label>Date:</label>
<span><?php echo date('r', $this->mail->getDateSent()); ?></span>
</div>

<?php if ($this->mail->getBodyHtml()) { ?>
<div>
<iframe id="htmlBodyMail" width="100%" height="300" src="getHtmlBody?id=<?php echo $this->mail->getDbId() ?>&selfedit=<?php echo $this->selfedit ?>&instanceName=<?php echo plainstring_to_urlprotected($this->instanceName) ?>"></iframe>
</div>
<?php } ?>

<div>
<?php if ($this->mail->getBodyText()) { ?>
<a href="#" id="toggletextmessage">View/hide text message</a>
<script type="text/javascript">
jQuery(document).ready(function() {
	jQuery("#toggletextmessage").click(function() {
		jQuery("#textbody").toggle();
		return false;
	});
	jQuery("#textbody").hide();
});
</script>
<?php } ?>
<pre id="textbody">
<?php echo $this->mail->getBodyText(); ?>
</pre>
</div>

<?php 
mailViewDisplayBackButton($this);

function displayMailAddresses(array $mailAddressList, $label) {
	if (!empty($mailAddressList)) {
?>
	<div>
	<label><?php echo $label ?>:</label>
	<span>
	<?php
	$mails = array();
	foreach ($mailAddressList as $recipient) {
		$mails[] = '<a href="mailto:'.$recipient->getMail().'">'.plainstring_to_htmlprotected($recipient).'</a>';
	}
	echo implode(", ", $mails);
	?>
	</span>
	</div>
<?php 
	}
}

function mailViewDisplayBackButton($controller) {
?>
<form action="." method="get">
	<input type="hidden" name="instanceName" value="<?php echo plainstring_to_htmlprotected($controller->instanceName); ?>" />
	<input type="hidden" name="selfedit" value="<?php echo plainstring_to_htmlprotected($controller->selfedit); ?>" />
	<input type="hidden" name="fullTextSearch" value="<?php echo plainstring_to_htmlprotected($controller->fullTextSearch); ?>" />
	<input type="hidden" name="offset" value="<?php echo plainstring_to_htmlprotected($controller->offset); ?>" />
	<button type="submit">Back</button>
</form>
<?php 
}
?>