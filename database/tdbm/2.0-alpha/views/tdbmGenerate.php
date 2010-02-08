<h1>Generate DAOs</h1>

<p>By clicking the link below, you will automatically generate DAOs and Beans for TDBM. These beans and DAOs will be written in the /dao and /dao/beans directory.</p>

<form action="generate" method="post">
<input type="hidden" id="name" name="name" value="<?php echo plainstring_to_htmlprotected($this->instanceName) ?>" />
<input type="hidden" id="selfedit" name="selfedit" value="<?php echo plainstring_to_htmlprotected($this->selfedit) ?>" />

<label>DaoFactory class name:</label><input type="text" name="daofactoryclassname" value="DaoFactory"></input>
<label>DaoFactory instance name:</label><input type="text" name="daofactoryinstancename" value="daoFactory"></input>

<div>
	<button name="action" value="generate" type="submit">Generate DAOs</button>
</div>
</form>