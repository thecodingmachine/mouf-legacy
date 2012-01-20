<?php 
/*@var $this DBMailServiceListController */
?>
<script type="text/javascript">
jQuery(document).ready(function() {
	jQuery("table.mails tr").click(function(evt) {
		var mailId = evt.currentTarget.getAttribute("data-mailid");
		window.location = "<?php echo ROOT_URL ?>mouf/dbmailservice/view?id="+mailId+"&selfedit=<?php echo $this->selfedit ?>&instanceName=<?php echo plainstring_to_urlprotected($this->instanceName) ?>&fullTextSearch=<?php echo plainstring_to_urlprotected($this->fullTextSearch) ?>&offset=<?php echo plainstring_to_urlprotected($this->offset) ?>";
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
	cursor: pointer;
}

table.mails tr td {
	white-space:nowrap;
	overflow: hidden;
}
</style>

<h1>Outgoing mails</h1>
<form>
	<input type="hidden" name="instanceName" value="<?php echo plainstring_to_htmlprotected($this->instanceName); ?>" />
	<input type="hidden" name="selfedit" value="<?php echo plainstring_to_htmlprotected($this->selfedit); ?>" />
	<label for="fullTextMailSearch">Search:</label>
	<input type="text" name="fullTextSearch" id="fullTextMailSearch" value="<?php echo plainstring_to_htmlprotected($this->fullTextSearch); ?>" />
	<button name="search" value="" type="submit">Search</button>

<table class="mails" >
	<tr>
		<th style="width:10%">Category:</th>
		<th style="width:10%">Type:</th>
		<th style="width:20%">To / CC / BCC:</th>
		<th style="width:45%">Title:</th>
		<th style="width:15%">Date:</th>
	</tr>
<?php foreach ($this->mailList as $mail): ?>
	<tr data-mailid="<?php echo $mail['id']; ?>">
		<td title="<?php echo plainstring_to_htmlprotected($mail['category']); ?>"><?php echo plainstring_to_htmlprotected($mail['category']); ?></td>
		<td title="<?php echo plainstring_to_htmlprotected($mail['mail_type']); ?>"><?php echo plainstring_to_htmlprotected($mail['mail_type']); ?></td>
		<td title="<?php echo plainstring_to_htmlprotected($mail['tos']); ?>"><?php echo plainstring_to_htmlprotected($mail['tos']); ?></td>
		<td title="<?php echo plainstring_to_htmlprotected($mail['title']); ?>"><?php echo plainstring_to_htmlprotected($mail['title']); ?></td>
		<td title="<?php echo plainstring_to_htmlprotected($mail['sent_date']); ?>"><?php echo plainstring_to_htmlprotected($mail['sent_date']); ?></td>
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
