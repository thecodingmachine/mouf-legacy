<?php
/*
 * Copyright (c) 2012 David Negrier
 * 
 * See the file LICENSE.txt for copying permission.
 */

?>
<h1>Missing labels screen</h1>

<form action="editLabel">
<input type="hidden" name="backto" value="<?php echo plainstring_to_htmlprotected(ROOT_URL."mouf/editLabels/missinglabels"); ?>" />
<p>Add a new label: <input type="text" name="key" value="" />
<select name="language">
<?php
foreach ($this->languages as $language) {
	echo "<option value='".plainstring_to_htmlprotected($language)."'>$language</option>";
}
?>
</select>
<button type="submit">Add</button>
</p>
</form>

<table>
	<tr>
		<th>Key</th>
		<?php
		foreach ($this->languages as $language) {
			echo "<th>$language</th>";
		}
		?>
	</tr>
	<?php
	foreach ($this->msgs as $key => $msgsForKey) {
		echo "<tr><td>$key</td>";
		foreach ($this->languages as $language) {
			echo "<td>";
			if ($msgsForKey[$language] != null) {
				echo "<a href='editLabel?key=".plainstring_to_htmlprotected($key)."&language=".plainstring_to_htmlprotected($language)."&backto=".plainstring_to_htmlprotected(ROOT_URL."mouf/editLabels/missinglabels")."'>" .
						"<img src='".ROOT_URL."plugins/utils/i18n/fine/1.0/views/images/checkOk.png' alt='ok' title='".plainstring_to_htmlprotected($msgsForKey[$language])."'/>" .
					"</a>";
			} else {
				echo "<a href='editLabel?key=".plainstring_to_htmlprotected($key)."&language=".plainstring_to_htmlprotected($language)."&backto=".plainstring_to_htmlprotected(ROOT_URL."mouf/editLabels/missinglabels")."'>" .
						"<img src='".ROOT_URL."plugins/utils/i18n/fine/1.0/views/images/cancel.png' alt='No label provided' title='No label provided' />" .
					"</a>";
			}
			echo "</td>";
		}
		echo "</tr>";
	}
	?>
</table>
