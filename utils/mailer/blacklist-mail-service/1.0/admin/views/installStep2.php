<?php /* @var $this DBMailServiceInstallController */ ?>

<h1>Configure the Blacklist mail service</h1>

<form action="install">
<input type="hidden" id="selfedit" name="selfedit" value="<?php echo plainstring_to_htmlprotected($this->selfedit) ?>" />

<?php 
MoufHtmlHelper::drawInstancesDropDown("DB Connection", "datasource", "DB_ConnectionInterface", false, $this->datasourceInstanceName);
?>


<div>
	<button name="action" value="install" type="submit">Next</button>
</div>
</form>