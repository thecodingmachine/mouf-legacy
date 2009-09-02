<h1>List of included component files</h1>

<form action="save">

<input type="hidden" name="selfedit" id="selfedit" value="<?php echo $this->selfedit; ?>" />

<div id="filesList">
</div>

<button onclick="showDialog()" type="button">Add a new file</button>

<button type="submit">Save</button>

</form>

<div id="dialog" title="Select your file">
	<p>Please select the PHP file to be added to the require_once list.</p>
	<div id="fileTreeContainer"></div>
</div>


<script type="text/javascript">
jQuery(document).ready( function() {
	<?php 

	$files = $this->moufManager->getRegisteredComponentFiles();

	foreach ($files as $file) {
		//echo "<div style='clear:both'><div class='moveable'></div>";
		//echo $file;
		//echo "<input type='hidden' value='".plainstring_to_htmlprotected($file)."' />";
		//echo "</div>";
		echo "addFile('".plainstring_to_htmlprotected($file)."');\n";
	}


	?>


	
	jQuery('#filesList').sortable({handle:'.moveable'});

	jQuery('#fileTreeContainer').fileTree({ 
		//root: '<?php echo str_replace("\\", "/", realpath(dirname(__FILE__)."/../../")) ?>', 
		root: '',
		script: '<?php ROOT_URL ?>/mouf/plugins/javascript/jquery/jqueryFileTree/1.01/connectors/jqueryFileTree.php'
		}, addFile);

	
	//jQuery.ui.dialog.defaults.bgiframe = true;
	jQuery(function() {
		jQuery("#dialog").dialog({ autoOpen: false });
	});
 	
});

function showDialog() {
	jQuery("#dialog").dialog("open");
}

counter = 0;

function addFile(fileName) {
	var html = "<div id='file"+counter+"' class='file'>";
	html += "<div class='moveable'></div>";
	html += "<div class='trash' onclick='deleteFile(\"file"+counter+"\")'></div>";
	html += fileName;
	// Todo: protect the value of the hidden tag.
	html += "<input type='hidden' name='files[]' value='"+fileName+"' />";
	html += "</div>";
	counter++;
	jQuery('#filesList').append(html);
}

function deleteFile(id) {
	jQuery('#'+id).remove();
}
</script>

