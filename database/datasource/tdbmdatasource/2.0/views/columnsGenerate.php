<?php /* @var $this MoufJqGridInstanceController */ ?>
<h1>Generate column instances</h1>

<p>By clicking the link below, you will automatically generate the instances representing the columns of the datasource, so you don't have to build each column yourself.</p>

<form action="generate" method="post">
<input type="hidden" id="name" name="name" value="<?php echo plainstring_to_htmlprotected($this->instanceName) ?>" />
<input type="hidden" id="selfedit" name="selfedit" value="<?php echo plainstring_to_htmlprotected($this->selfedit) ?>" />

<label>Instances prefix:</label><input type="text" name="prefix" value="<?php echo plainstring_to_htmlprotected($this->dsPrefix) ?>"></input>

<div>
	<button name="action" value="generate" type="submit">Generate column instances</button>
</div>
</form>