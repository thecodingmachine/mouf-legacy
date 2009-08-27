<script type="text/javascript">
<?php
$phpJitNodes = $this->getJitJson($this->instanceName);
//$phpJitNodes = $this->getJitJsonAllInstances();
if (count($phpJitNodes)>1) {
?>
	jsonNodes = <?php echo json_encode($phpJitNodes); ?>;
	Event.observe(window, 'load', initJit);
<?php
}
?>

lastPropDisplayed = "";
dropDownCnt = 0;

/*
 * Adds a new drowdown list dynamically inside element "element".
 * name is the name of the select box.
 * jsonList is the content of the list to add:
 * jsonList = [{id:0, text:"Mr"}, {id:1, text:"Mrs"}]
 */
function addNewDropDown(element, name, jsonList, defaultValue, hasKey, defaultKey) {
	var str = "";
	str += "<div id='"+name+"_mouf_dropdown_"+dropDownCnt+"'>";
	if (defaultValue != "") {
		str += "<span id='"+name+"_mouf_dropdown_text_"+dropDownCnt+"'>";
		if (hasKey) {
			str += defaultKey;
			str += "=&gt;";
		}
		str += "<a href='displayComponent?name="+defaultValue+"&amp;selfedit=<?php echo $this->selfedit ?>'>"+defaultValue+"</a>";
		str += '<a onclick="document.getElementById(\''+name+'_mouf_dropdown_text_'+dropDownCnt+'\').style.display=\'none\';document.getElementById(\''+name+"_mouf_dropdown_dropdown_"+dropDownCnt+'\').style.display=\'inline\';" ><img src="<?php echo ROOT_URL; ?>/mouf/views/images/pencil.png" alt="edit" /></a>';
		str += "</span>";
		str += "<span id='"+name+"_mouf_dropdown_dropdown_"+dropDownCnt+"' style='display:none'>";
	}
	if (hasKey) {
		str += "<input type='text' name='moufKeyFor"+name+"[]' value=\""+defaultKey+"\">";
		str += "=&gt;";
	}
	str += "<select name='"+name+"[]'>";
	jsonList.each(function(option) {
		var selected = "";
		if (option.id == defaultValue) {
			selected = ' selected="true"';
		}
		str += "<option value='"+option.id+"' "+selected+">"+option.text+"</option>";
	});
	str += "</select>";
	str += "<a onclick='$(\""+name+"_mouf_dropdown_"+dropDownCnt+"\").remove()'><img src=\"<?php echo ROOT_URL ?>mouf/views/images/cross.png\"></a>";
	if (defaultValue != "") {
		str += "</span>";
	}
	str += "</div>";
	element.insert(str);
	dropDownCnt++;
}

/*
 * Adds a new textbox dynamically inside element "element".
 * name is the name of the select box.
 * defaultvalue its default value
 */
function addNewTextBox(element, name, defaultValue, hasKey, defaultKey) {
	var str = "";
	str += "<div id='"+name+"_mouf_dropdown_"+dropDownCnt+"'>";
	if (hasKey) {
		str += "<input type='text' name='moufKeyFor"+name+"[]' value=\""+defaultKey+"\">";
		str += "=&gt;";
	}
	str += "<input type='text' name='"+name+"[]' value=\""+defaultValue+"\">";
	str += "<a onclick='$(\""+name+"_mouf_dropdown_"+dropDownCnt+"\").remove()'><img src=\"<?php echo ROOT_URL ?>mouf/views/images/cross.png\"></a>";
	str += "</div>";
	element.insert(str);
	dropDownCnt++;
}

function deleteInstance() {
	if (window.confirm("Are you sure you want to delete this instance?")) { // Clic sur OK
        document.getElementById("delete").value="1";
        document.getElementById("componentForm").submit();
    }
}
</script>

<form action="saveComponent" method="post" id="componentForm">

<h1>Component 
	<span id="instanceNameText"><?php echo $this->instanceName ?></span>
	<span id="instanceNameTextbox" style="display:none" ><input type="text" name="instanceName" value="<?php echo plainstring_to_htmlprotected($this->instanceName) ?>" /></span>
</h1>
<input type="hidden" name="originalInstanceName" value="<?php echo plainstring_to_htmlprotected($this->instanceName) ?>" />
<input type="hidden" name="delete" id="delete" value="0" />
<input type="hidden" name="selfedit" id="selfedit" value="<?php echo $this->selfedit; ?>" />
<a id="modifyInstanceLink" onclick="document.getElementById('modifyInstanceLink').style.visibility='hidden';document.getElementById('instanceNameText').style.display='none';document.getElementById('instanceNameTextbox').style.display='inline';">Modify component name</a>
<a onclick="deleteInstance()">Delete this instance</a>

<h2>Class <?php echo $this->className ?></h2>

<div><?php echo $this->reflectionClass->getDocCommentWithoutAnnotations(); ?></div>

<h3>Properties:</h3>
<div class='half'>
<?php 
foreach ($this->properties as $property) {
	echo "<div onmouseover='if (lastPropDisplayed!=\"\") {document.getElementById(lastPropDisplayed).style.display=\"none\";}; lastPropDisplayed=\"".$property->getName()."_doc_div_mouf\";     document.getElementById(\"".$property->getName()."_doc_div_mouf\").style.display=\"block\";'>\n";
	
	if ($property->hasAnnotation("Compulsory")) {
		$compulsory = "*";
	}
	$propertyName = $property->getName();
	echo '<label for="'.$propertyName.'">'.$propertyName.$compulsory."</label>";
	
	if ($property->hasAnnotation("OneOf")) {
		$oneOfs = $property->getAnnotations("OneOf");
		$oneOfValues = $oneOfs[0]->getPossibleValues();
		if ($property->hasAnnotation("OneOfText")) {
			$oneOfTexts = $property->getAnnotations("OneOfText");
			$oneOfTextValues = $oneOfTexts[0]->getPossibleValues();
		} else {
			$oneOfTextValues = $oneOfValues;
		}
		
		// TODO: YAAARGL: C'est plus que la default value qu'il faut!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
		// Il faut aussi la valeur de l'instance, la vraie!
		
		//$defaultValue = $this->instance->$propertyName;

		$defaultValue = $this->getValueForProperty($propertyName);
		
		//$defaultValue = MoufDefaultValueGetter::getDefaultValue($this->className, $this->propertyName);
//var_dump($defaultValue);	
		echo '<select id="'.$property->getName().'" name="'.$property->getName().'" >';
		echo '<option value=""></option>';
		for ($i=0; $i<count($oneOfValues); $i++) {
			if ($oneOfValues[$i] == $defaultValue) {
				$selected = 'selected="true"';
			} else {
				$selected = '';
			}
			echo '<option value="'.plainstring_to_htmlprotected($oneOfValues[$i]).'" '.$selected.'>'.$oneOfTextValues[$i].'</option>';
		}
		echo '</select>';
		
	} else if ($property->hasAnnotation("var")) {
		$varTypes = $property->getAnnotations("var");
		$varTypeAnnot = $varTypes[0];
		$varType = $varTypeAnnot->getType();
		$lowerVarType = strtolower($varType);
		if ($lowerVarType == "string" || $lowerVarType == "bool" || $lowerVarType == "boolean" || $lowerVarType == "int" || $lowerVarType == "integer" || $lowerVarType == "double" || $lowerVarType == "float" || $lowerVarType == "real" || $lowerVarType == "mixed" || $lowerVarType == "callback") {
			$defaultValue = $this->getValueForProperty($propertyName);
		
			echo '<input type="text" id="'.$property->getName().'" name="'.$property->getName().'" value="'.plainstring_to_htmlprotected($defaultValue).'"/>';
		} else if ($lowerVarType == "array") {
			$recursiveType = $varTypeAnnot->getSubType();
			
			if ($recursiveType == "string" || $recursiveType == "bool" || $recursiveType == "boolean" || $recursiveType == "int" || $recursiveType == "integer" || $recursiveType == "double" || $recursiveType == "float" || $recursiveType == "real" || $recursiveType == "mixed" || $recursiveType == "callback") {
				
				echo "<div class='moufFormList'>";
				// The div that will contain each array.
				echo "<div id='".$property->getName()."_mouf_array'>";
				
				echo "</div>";
				
				echo "<script>";
				echo "Event.observe(window, 'load', function() {\n";
				//$defaultValues = $this->instance->$propertyName;
				//$defaultValues = $this->reflectionClass->getDefault();
				$defaultValues = $this->getValueForProperty($propertyName);
				$isAssociative = $varTypeAnnot->isAssociativeArray();
				
				if (is_array($defaultValues)) {
					foreach ($defaultValues as $defaultKey=>$defaultValue) {
						if ($isAssociative) {
							echo "addNewTextBox($(\"".$property->getName()."_mouf_array\"), \"".$property->getName()."\", \"$defaultValue\", true, \"$defaultKey\");\n";
						} else {
							echo "addNewTextBox($(\"".$property->getName()."_mouf_array\"), \"".$property->getName()."\", \"$defaultValue\", false, \"\");\n";
						}
					}
				}
				echo "\n});\n";
				echo "</script>";
				echo "<a onclick='addNewTextBox($(\"".$property->getName()."_mouf_array\"), \"".$property->getName()."\", \"\", ".(($isAssociative)?"true":"false").", \"\");'>Add a value</a>";
				echo "</div>";
				echo "<div style='clear:both'></div>";
				
			} else {
				// Ok, an array of objects, gogogo!
				// note: we do not handle array of arrays, sorry....
				
				// Let's try to find any instances that could match this type.
				$instances = $this->findInstances($recursiveType);
				$instanceNameArray = array();
				// Let's build a JSON object from it:
				foreach ($instances as $instance) {
					$instanceNameArray[] = array("id"=>$instance, "text"=>$instance);
				}
				$jsonArray = json_encode($instanceNameArray);
				
				
				echo "<div class='moufFormList'>";
				// The div that will contain each array.
				echo "<div id='".$property->getName()."_mouf_array'>";
				
				echo "</div>";
				
				echo "<script>";
				echo "Event.observe(window, 'load', function() {\n";
				$defaultValues = $this->moufManager->getBoundComponentsOnProperty($this->instanceName, $property->getName());
				$isAssociative = $varTypeAnnot->isAssociativeArray();
				
								
				if (is_array($defaultValues)) {
					foreach ($defaultValues as $defaultKey=>$defaultValue) {
						if ($isAssociative) {
							echo "addNewDropDown($(\"".$property->getName()."_mouf_array\"), \"".$property->getName()."\", $jsonArray, \"$defaultValue\", true, \"$defaultKey\");\n";
						} else {
							echo "addNewDropDown($(\"".$property->getName()."_mouf_array\"), \"".$property->getName()."\", $jsonArray, \"$defaultValue\", false, \"\");\n";
						}
					}
				}
				echo "\n});\n";
				echo "</script>";
				
				
				//$jsonArray = addslashes($jsonArray);
				//[{id:0, text:\"toto\"}, {id:1, text:\"tata\"}]
				echo "<a onclick='addNewDropDown($(\"".$property->getName()."_mouf_array\"), \"".$property->getName()."\", $jsonArray, \"\", ".(($isAssociative)?"true":"false").", \"\");'>Add a component</a>";
				
				echo "</div>";
				echo "<div style='clear:both'></div>";
				
			}
		} else {
			// Ok, there is a type, and it's not an array of types
			// Let's try to find any instances that could match this type.
			$instances = $this->findInstances($varType);
			
			$defaultValue = $this->moufManager->getBoundComponentsOnProperty($this->instanceName, $property->getName());

			$defaultDisplaySelect = "";
			if ($defaultValue != null) {
				echo '<span id="'.$property->getName().'_mouf_link" >';
				echo '<a href="displayComponent?name='.plainstring_to_htmlprotected($defaultValue).'&amp;selfedit='.$this->selfedit.'">'.$defaultValue.'</a>';
				echo '<a onclick="document.getElementById(\''.$property->getName().'_mouf_link\').style.display=\'none\';document.getElementById(\''.$property->getName().'\').style.display=\'inline\';" ><img src="'.ROOT_URL.'/mouf/views/images/pencil.png" alt="edit" /></a>';
				echo "</span>\n";
				$defaultDisplaySelect = 'style="display:none"';
			}
			
			echo '<select id="'.$property->getName().'" name="'.$property->getName().'" '.$defaultDisplaySelect.' >';
			echo '<option value=""></option>';
			foreach ($instances as $instanceName) {
				if ($instanceName == $defaultValue) {
					$selected = 'selected="true"';
				} else {
					$selected = '';
				}
				echo '<option value="'.plainstring_to_htmlprotected($instanceName).'" '.$selected.'>'.$instanceName.'</option>';
			}
			echo '</select>';
		}
		
		
	} else {
		//$defaultValue = $this->instance->$propertyName;
		//$defaultValue = $this->reflectionClass->getProperty($propertyName)->getDefault();
		$defaultValue = $this->getValueForProperty($propertyName);
		
		echo '<input type="text" id="'.$property->getName().'" name="'.$property->getName().'" value="'.plainstring_to_htmlprotected($defaultValue).'" />';
	}
	echo "</div>\n";
	
	//echo "<div>".$property->getDocCommentWithoutAnnotations()."</div>";
}
?>
</div>

<div class='half'>
<?php 
foreach ($this->properties as $property) {
	echo "<div id='".$property->getName()."_doc_div_mouf' style='display:none'>\n";
	echo "<h2>Property ".$property->getName()."</h2>";
	echo $property->getDocCommentWithoutAnnotations();
	echo "</div>\n";
}
?>
</div>

<div style="clear:both">
<input type="submit" value="Save" />
</div>

</form>

<div id="infovis" style="height:600px;width:600px"></div>    
<div id="log"></div>
<div id="inner-details"></div>
