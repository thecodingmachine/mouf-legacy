<?php /* @var $this TdbmInstallController */ ?>
<h1>Configure TDBM</h1>

<p>TDBM will generate DAOs for you. You can access those DAOs using the Mouf::getDaoFactory() method.</p>

<form action="install" method="post">
<input type="hidden" id="selfedit" name="selfedit" value="<?php echo plainstring_to_htmlprotected($this->selfedit) ?>" />

<label>Dao directory:</label><input type="text" name="daodirectory" value="<?php echo plainstring_to_htmlprotected($this->daoDirectory) ?>"></input>
<label>Bean directory:</label><input type="text" name="beandirectory" value="<?php echo plainstring_to_htmlprotected($this->beanDirectory) ?>"></input>

<div>
	<button name="action" value="install" type="submit">Install TDBM</button>
</div>
</form>