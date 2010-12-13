<b>Search</b>
<form action="<?php echo ROOT_URL?>mouf/search">
	<input type="text" name="query" value="<?php echo plainstring_to_htmlprotected(get("query")); ?>" />
	<input type="hidden" name="selfedit" value="<?php echo plainstring_to_htmlprotected(get("selfedit")); ?>" />
	<button type="submit">Go</button>
</form>