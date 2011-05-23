<?php /* @var $this DbLoggerInstallController */ ?>
<h1>Setting up DbLogger</h1>

<p>By clicking the link below, you will create the table for DbLogger (if the table does not already exists).</p>

<form action="install" method="post">
<input type="hidden" id="selfedit" name="selfedit" value="<?php echo plainstring_to_htmlprotected($this->selfedit) ?>" />

<?php 
MoufHtmlHelper::drawInstancesDropDown("DB Logger", "dblogger", "DbLogger", false, "dbLogger");
?>

<div>
<label>Table name:</label><input type="text" name="tablename" value="<?php echo plainstring_to_htmlprotected($this->tableName) ?>"></input>
</div>

<div>
	<button name="action" type="submit">Install LogStats</button>
</div>
</form>