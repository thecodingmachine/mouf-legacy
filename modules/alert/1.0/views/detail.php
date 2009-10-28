<h1>Alert detail</h1>

<form action="validate" method="post">
<input type="hidden" name="id" value="<?php echo $this->alertBean->getId(); ?>" />

<div>
	<label for="date">Date:</label>
	<span id="date">
		<?php 
		echo date(iMsg('date.format.long'), $this->alertBean->getDateAsTimestamp());
		?>
	</span>
</div>
<?php if ($this->alertBean->getValidated()) { ?>
<div>
	<label for="dateValidation">Validated on:</label>
	<span id="dateValidation"><?php echo date(iMsg('date.format.long'), $this->alertBean->getValidationDateAsTimestamp()); ?></span>
</div>
<?php } ?>
<div>
	<label for="title">Title:</label><span id="title"><?php echo $this->alertBean->getTitle() ?></span>
</div>
<div>
	<label for="message">Message:</label><span id="title"><?php echo $this->alertBean->getMessage() ?></span>
</div>
<div>
	<label for="category">Category:</label><span id="category">
	<?php 
	if ($this->alertBean->getCategory()!=null) {
		echo $this->alertBean->getCategory(); 
	} else {
		echo "<em>none</em>";
	}
	?></span>
</div>
<div>
	<label for="level">Level:</label><span id="level">
	<?php 
	if ($this->alertBean->getLevel()!==null) {
		echo $this->alertBean->getLevel(); 
	} else {
		echo "<em>N/A</em>";
	}
	?></span>
</div>
<div>
<button name="action" value="validate">Validate alert</button>
<button name="action" value="back">Back to alerts list</button>
</div>
</form>