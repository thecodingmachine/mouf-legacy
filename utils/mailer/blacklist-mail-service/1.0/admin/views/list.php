<?php 
/*@var $this DBMailServiceListController */
?>
<script type="text/javascript">
jQuery(document).ready(function() {
	jQuery("table.mails tr td a.cancel").click(function(evt) {
		var blacklistid = evt.currentTarget.getAttribute("data-blacklistid");
		var category = evt.currentTarget.getAttribute("data-category");
		var type = evt.currentTarget.getAttribute("data-type");
		var mailaddress = evt.currentTarget.getAttribute("data-mailaddress");

		if (confirm("Are you sure you want to remove this mail address from the black list?")) {
			window.location = "<?php echo ROOT_URL ?>mouf/blacklistmailservice/delete?mailaddress="+encodeURIComponent(mailaddress)+"&category="+encodeURIComponent(category)+"&type="+encodeURIComponent(type)+"&selfedit=<?php echo $this->selfedit ?>&instanceName=<?php echo plainstring_to_urlprotected($this->instanceName) ?>&fullTextSearch=<?php echo plainstring_to_urlprotected($this->fullTextSearch) ?>&offset=<?php echo plainstring_to_urlprotected($this->offset) ?>";
		} else {
			return false;
		}
	});
});
</script>

<style>
table.mails {
	width: 100%;
	table-layout: fixed;
}

table.mails tr:nth-child(even) {
	background-color: #ffffff;
}

table.mails tr:nth-child(odd) {
	background-color: #eeeeee;
}

table.mails tr:first-child {
	background-color: #dddddd;
}

table.mails tr:hover {
	background-color: #cccccc;
}

table.mails tr td {
	white-space:nowrap;
	overflow: hidden;
}
</style>

<h1>Blacklisted mails</h1>
<form>
	<input type="hidden" name="instanceName" value="<?php echo plainstring_to_htmlprotected($this->instanceName); ?>" />
	<input type="hidden" name="selfedit" value="<?php echo plainstring_to_htmlprotected($this->selfedit); ?>" />
	<label for="fullTextMailSearch">Search:</label>
	<input type="text" name="fullTextSearch" id="fullTextMailSearch" value="<?php echo plainstring_to_htmlprotected($this->fullTextSearch); ?>" />
	<button name="search" value="" type="submit">Search</button>

<table class="mails" >
	<tr>
		<th style="width:20%">Category:</th>
		<th style="width:20%">Type:</th>
		<th style="width:30%">Mail address:</th>
		<th style="width:20%">Date:</th>
		<th style="width:10%">Cancel:</th>
	</tr>
<?php foreach ($this->mailList as $mail): ?>
	<tr>
		<td title="<?php echo plainstring_to_htmlprotected($mail['category']); ?>"><?php echo plainstring_to_htmlprotected($mail['category']); ?></td>
		<td title="<?php echo plainstring_to_htmlprotected($mail['mail_type']); ?>"><?php echo plainstring_to_htmlprotected($mail['mail_type']); ?></td>
		<td title="<?php echo plainstring_to_htmlprotected($mail['mail_address']); ?>"><?php echo plainstring_to_htmlprotected($mail['mail_address']); ?></td>
		<td title="<?php echo plainstring_to_htmlprotected($mail['blacklist_date']); ?>"><?php echo plainstring_to_htmlprotected($mail['blacklist_date']); ?></td>
		<td><a href="#" class="cancel" data-blacklistid="<?php echo $mail['id']; ?>"
			data-category="<?php echo plainstring_to_htmlprotected($mail['category']); ?>"
			data-type="<?php echo plainstring_to_htmlprotected($mail['mail_type']); ?>"
			data-mailaddress="<?php echo plainstring_to_htmlprotected($mail['mail_address']); ?>"
			>Cancel</a></td>
	</tr>
<?php endforeach; ?>
</table>
<?php if ($this->offset > 0): ?>
	<button name="offset" value="<?php echo $this->offset - DBMailServiceListController::PAGE_SIZE ?>" type="submit">Previous</button>
<?php endif; ?>
<?php if (count($this->mailList) == DBMailServiceListController::PAGE_SIZE): ?>
	<button name="offset" value="<?php echo $this->offset + DBMailServiceListController::PAGE_SIZE ?>" type="submit">Next</button>
<?php endif; ?>
</form>
