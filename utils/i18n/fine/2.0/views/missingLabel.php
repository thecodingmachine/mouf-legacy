<h1>Translations</h1>

<form action="editLabel">
<input type="hidden" name="backto" value="<?php echo plainstring_to_htmlprotected(ROOT_URL."mouf/editLabels/missinglabels?name=".$this->msgInstanceName."&selfedit=".$this->selfedit); ?>" />
<input type="hidden" name="msginstancename" value="<?php echo plainstring_to_htmlprotected($this->msgInstanceName); ?>" />
<input type="hidden" name="selfedit" value="<?php echo plainstring_to_htmlprotected($this->selfedit); ?>" />
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
			echo "<a href='editLabel?key=".plainstring_to_htmlprotected($key)."&language=".plainstring_to_htmlprotected($language)."&backto=".urlencode(plainstring_to_htmlprotected(ROOT_URL."mouf/editLabels/missinglabels?name=".$this->msgInstanceName."&selfedit=".$this->selfedit))."&msginstancename=".plainstring_to_htmlprotected($this->msgInstanceName)."&selfedit=".plainstring_to_htmlprotected($this->selfedit)."'>";
			if ($msgsForKey[$language] != null) {
				echo "<img src='".ROOT_URL."plugins/utils/i18n/fine/1.0/views/images/checkOk.png' alt='ok' title='".plainstring_to_htmlprotected($msgsForKey[$language])."'/>";
			} else {
				echo "<img src='".ROOT_URL."plugins/utils/i18n/fine/1.0/views/images/cancel.png' alt='No label provided' title='No label provided' />";
			}
			echo "</a>";
			
			echo "</td>";
		}
		echo "</tr>";
	}
	?>
</table>
