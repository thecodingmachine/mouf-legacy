<?php 
$files = $this->moufManager->getRegisteredComponentFiles();
?>
<h1>List of included component files</h1>

<?php
$includesAnalyze = $this->analyzeErrors;

if (isset($includesAnalyze["errorType"])) {
	echo "<div class='error'>".$includesAnalyze["errorMsg"].'</div>';
}
?>

<p>Below is the list of files that will be automatically <em>required</em> by Mouf when you include the <code>Mouf.php</code> file.
Those files should not output directly something when included.</p>

<form action="save" method="POST">

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
			if (isset($includesAnalyze['classes'][$file])) {
				$classList = array_values($includesAnalyze['classes'][$file]);
			} else {
				$classList = null;
			}
			if (isset($includesAnalyze['functions'][$file])) {
				$functionList = array_values($includesAnalyze['functions'][$file]);
			} else {
				$functionList = null;
			}
			if (isset($includesAnalyze['interfaces'][$file])) {
				$interfaceList = array_values($includesAnalyze['interfaces'][$file]);
			} else {
				$interfaceList = null;
			}
			echo "addFile('".plainstring_to_htmlprotected($file)."', null, ".json_encode($classList).", ".json_encode($functionList).", ".json_encode($interfaceList).");\n";
		}
	} else {
		echo "jQuery('#noFiles').show();\n";
	}

	?>
	jQuery(".viewdetails").click(function(ev) {
		jQuery(this).parent().find(".details").show();
		ev.preventDefault();
	});

	
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

/**
 * @param $fileName The file name
 * @param $errorMsg The error message (or null if no error)
 * @param $classList The list of the classes defined by this file
 */
function addFile(fileName, errorMsg, classList, functionList, interfaceList) {
	var html = "<div id='file"+counter+"' class='file'>";
	html += "<div class='moveable'></div>";
	html += "<div class='phpfileicon'></div>";
	html += "<div class='trash' onclick='deleteFile(\"file"+counter+"\")'></div>";
	html += "<div class='viewdetails'><a href='#'>view details</a></div>";

	if(functionList.length == 0 && (interfaceList.length > 0 || classList.length > 0)) {
		html += "<div class='autoloadable'>autoloadable</div>";
	}
	if (errorMsg != null) {
		html += "<div class='error'>"+errorMsg+"</div>";
	}
	html += fileName;
	// Todo: protect the value of the hidden tag.
	html += "<input type='hidden' name='files[]' value='"+fileName+"' />";


	if (interfaceList.length > 0) {
		html += "<div class='details'>Defined interfaces:<ul>";
		for (var i=0; i<interfaceList.length; i++) {
			html += "<li>"+interfaceList[i]+"</li>";
		}
		html += "</ul></div>";
	} else {
		html += "<div class='details'>No interfaces defined in that file</div>";
	}
	
	if (classList.length > 0) {
		html += "<div class='details'>Defined classes:<ul>";
		for (var i=0; i<classList.length; i++) {
			html += "<li>"+classList[i]+"</li>";
		}
		html += "</ul></div>";
	} else {
		html += "<div class='details'>No classes defined in that file</div>";
	}

	if (functionList.length > 0) {
		html += "<div class='details'>Defined functions:<ul>";
		for (var i=0; i<functionList.length; i++) {
			html += "<li>"+functionList[i]+"</li>";
		}
		html += "</ul></div>";
	} else {
		html += "<div class='details'>No functions defined in that file</div>";
	}

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

