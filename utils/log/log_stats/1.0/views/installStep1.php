<?php /* @var $this TdbmInstallController */ ?>
<h1>Setting up LogStats</h1>

<p>Log Stats will now be configured. The log_stats component will install a stats table above one of your db_logger table.</p>
<p>The Log Stats install procedure will create a new stats table that will contain aggregated data about your logs.</p>

<form action="configure">
	<input type="hidden" name="selfedit" value="<?php echo $this->selfedit ?>" />
	<button>Configure LogStats</button>
</form>
<form action="skip">
	<input type="hidden" name="selfedit" value="<?php echo $this->selfedit ?>" />
	<button>Skip</button>
</form>