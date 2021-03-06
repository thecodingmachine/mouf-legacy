<?php
/*
 * Copyright (c) 2012 David Negrier
 * 
 * See the file LICENSE.txt for copying permission.
 */

function editLabel($key, $label, $language, $messagesArray, $is_success, $backto) {
?>
<h1>Label edition screen</h1>

<form action="saveLabel" method="post">
	<?php if ($is_success) { ?>
		<p style="color:green">The label has been successfully updated.</p>
	<?php } ?>

	<p>Label for key '<?php echo $key ?>' in language <?php echo $language ?>:</p>
	<input type="hidden" name="key" value="<?php echo plainstring_to_htmlprotected($key) ?>" />
	<input type="hidden" name="language" value="<?php echo plainstring_to_htmlprotected($language) ?>" />
	<input type="hidden" name="backto" value="<?php echo plainstring_to_htmlprotected($backto) ?>" />
	<!--<input type="text" name="label" value="<?php echo plainstring_to_htmlprotected($label) ?>" size="80" /><br/>-->
	<textarea name="label" rows="5" cols="80"><?php echo plainstring_to_htmlprotected($label) ?></textarea><br/>
	<input type="submit" name="save" value="Save" />
	<?php if ($backto != null) { ?>
	<input type="submit" name="back" value="Back to application" />
	<?php } ?>

	<p>This message in other languages:</p>
	<table>
		<tr>
			<th style="width:100px">Key</th>
			<th>Messages</th>
			<th>Edit</th>
		</tr>
		<?php foreach ($messagesArray as $language=>$value) { ?>
		<tr>
			<td><?php echo plainstring_to_htmlprotected($language) ?></td>
			<td><?php echo plainstring_to_htmlprotected($value) ?></td>
			<td><a href="editLabel?key=<?php echo plainstring_to_htmlprotected($key) ?>&amp;language=<?php echo plainstring_to_htmlprotected($language) ?>&amp;backto=<?php echo plainstring_to_htmlprotected($backto) ?>">Edit</a></td>
		</tr>
		<?php } ?>
	</table>
</form>
<?php
}