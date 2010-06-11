<h1>Add/edit constant</h1>

<form action="registerConstant" method="post">
<?php // TODO: add selfedti field!
?>
<div>
<label>Name:</label>
<input name="name" value="<?php echo plainstring_to_htmlprotected($this->name); ?>" />
</div>

<div>
<label>Default value:</label>
<input name="defaultvalue" value="<?php echo plainstring_to_htmlprotected($this->defaultvalue); ?>" />
</div>

<div>
<label>Comments:</label>
<textarea name="comment"><?php echo plainstring_to_htmlprotected($this->comment); ?></textarea>
</div>

<?php // Type ?>

<div>
<button type="submit">Save</button>
<a href=".">Cancel</button>
</div>
</form>