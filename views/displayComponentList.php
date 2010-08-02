<?php 
$files = $this->moufManager->getRegisteredComponentFiles();
?>
<h1>List of included component files</h1>

<?php
$errors = $this->analyzeErrors;

if (isset($errors["errorType"])) {
	echo "<div class='error'>".$errors["errorMsg"].'</div>';
}
?>

<p>Below is the list of files that will be automatically <em>required</em> by Mouf when you include the <code>Mouf.php</code> file.
Those files should not output directly something when included.</p>

<form action="save">

<input type="hidden" name="selfedit" id="selfedit" value="<?php echo $this->selfedit; ?>" />

<div id="noFiles" class="notice" style="display:none">You have not selected any files yet for inclusion.</div>
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
	if (!empty($files)) {
		foreach ($files as $file) {
			echo "addFile('".plainstring_to_htmlprotected($file)."');\n";
		}
	} else {
		echo "jQuery('#noFiles').show();\n";
	}

	?>


	
	jQuery('#filesList').sortable({handle:'.moveable'});

	jQuery('#fileTreeContainer').fileTree({ 
		//root: '<?php echo str_replace("\\", "/", realpath(dirname(__FILE__)."/../../")) ?>', 
		root: '',
		script: '<?php echo ROOT_URL ?>/plugins/javascript/jquery/jqueryFileTree/1.01/connectors/jqueryFileTree.php'
		}, addFile);

	
	//jQuery.ui.dialog.defaults.bgiframe = true;
	jQuery(function() {
		jQuery("#dialog").dialog({ autoOpen: false, width: 400, height: 500 });
	});
 	
});

function showDialog() {
	jQuery("#dialog").dialog("open");
}

counter = 0;

function addFile(fileName) {
	var html = "<div id='file"+counter+"' class='file'>";
	html += "<div class='moveable'></div>";
	html += "<div class='phpfileicon'></div>";
	html += "<div class='trash' onclick='deleteFile(\"file"+counter+"\")'></div>";
	html += fileName;
	// Todo: protect the value of the hidden tag.
	html += "<input type='hidden' name='files[]' value='"+fileName+"' />";
	html += "</div>";
	counter++;
	jQuery('#filesList').append(html);
	jQuery('#noFiles').hide();
}

function deleteFile(id) {
	jQuery('#'+id).remove();
	if (jQuery('#noFiles > div').size() == 0) {
		jQuery('#noFiles').show();
	}
}
</script>

