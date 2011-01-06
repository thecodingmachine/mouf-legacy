<style type="text/css">
	table#fin_searchlabel th {
		background-color: #CCCCCC;
	}
</style>
<form action="searchLabel" style="float:right;">
	<input type="hidden" name="backto" value="<?php echo plainstring_to_htmlprotected(ROOT_URL."mouf/editLabels/missinglabels?name=".$this->msgInstanceName."&selfedit=".$this->selfedit); ?>" />
	<input type="hidden" name="msginstancename" value="<?php echo plainstring_to_htmlprotected($this->msgInstanceName); ?>" />
	<input type="hidden" name="selfedit" value="<?php echo plainstring_to_htmlprotected($this->selfedit); ?>" />
	<p style="text-align: left">Search: <br /><input type="text" name="search" value="" /><br />
		<select name="language_search" style="float: left">
			<option value="">--</option>
			<?php
			foreach ($this->languages as $language) {
				echo "<option value='".plainstring_to_htmlprotected($language)."'>$language</option>";
			}
			?>
		</select>
		<button type="submit" style="float: right">Search</button>
	</p>
</form>
<h1>Search</h1>
<p>You search: <b><?php echo plainstring_to_htmlprotected($this->search) ?></b></p>
<p>For language: <b><?php echo ($this->language_search?$this->language_search:"all") ?></b></p>
<?php if($this->error) {?>
	no research
<?php } else {?>
	<table id="fin_searchlabel" style="clear: both; width: 100%">
	<?php
	foreach ($this->results as $key => $languages) {
		echo "<tr><th colspan='3'>".$key."</th></tr>";
		foreach ($languages as $lang => $value) {
			echo '<tr>
					<td style="width: 20px">
						<a href="editLabel?key='.plainstring_to_htmlprotected($key).'&language='.plainstring_to_htmlprotected($lang).'&backto='.urlencode(plainstring_to_htmlprotected(ROOT_URL."mouf/editLabels/missinglabels?name=".$this->msgInstanceName."&selfedit=".$this->selfedit)).'&msginstancename='.plainstring_to_htmlprotected($this->msgInstanceName).'&selfedit='.plainstring_to_htmlprotected($this->selfedit).'">
							<img src="'.ROOT_URL.'plugins/utils/icons/crystalproject/1.0/actions/configure.png" alt="edit" />
						</a>
					</td>
					<td style="width: 48px"><b>'.$lang.'</b></td>
					<td>'.plainstring_to_htmlprotected($value).'</td>
				</tr>';
		}
		echo '<tr><td>&nbsp;</td></tr>';
	}
	?>
	</table>
<?php } ?>