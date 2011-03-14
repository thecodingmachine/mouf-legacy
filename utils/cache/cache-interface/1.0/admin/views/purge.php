<h1>Purge all caches</h1>

<?php if ($this->done == "true"):?>
<div class="good">All caches have successfully been purged.</div>
<script type="text/javascript">
setTimeout(function() {
	jQuery('.good').fadeOut(3000);
}, 7000);
</script>

<?php endif; ?>

<p>Click the button below to purge all caches defined in Mouf.</p>

<form action="purge" method="post">
	<input type="hidden" name="selfedit" value="<?php echo plainstring_to_htmlprotected($this->selfedit); ?>" />
	<button type="submit">Purge all caches</button>
</form>