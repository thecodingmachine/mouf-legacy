function propertySelectChange(dropdown, propertyName, type) {
	if (dropdown.value == 'newInstance') {
		// new instance was selected, let's display a dialog box to create the instance.
		dropdown.selectedIndex=0;
		displayCreateInstanceDialog(dropdown, propertyName, type);
	}
}

function displayCreateInstanceDialog(dropdown, propertyName, type) {
	jQuery.getJSON("../direct/get_components_list.php",{type: type, encode:"json", selfedit:jQuery('#selfedit').val(), ajax: 'true'}, function(j){
		
	      var options = '';
	      for (var i = 0; i < j.length; i++) {
	        options += '<option value="' + j[i] + '">' + j[i] + '</option>';
	      }
	      jQuery("select#instanceClassDialog").html(options);
	});

	lastSelectBox = dropdown;
	
	jQuery("#newInstanceName").val("");
	jQuery("#bindToProperty").val(propertyName);
	
	jQuery("#dialog").dialog("open");
}

/**
 * Called when the user clicks the 'create new instance' button.
 * @return
 */
function onCreateNewInstance() {
	
	// Let's modify the select box to have it contain the new instance that will be created.
	// TODO protect against script injection.
	jQuery(lastSelectBox).html("<option value='"+jQuery("#newInstanceNameDialog").val()+"'>"+jQuery("#newInstanceNameDialog").val()+"</option>")
	
	jQuery("#createNewInstance").val("true");
	jQuery("#newInstanceName").val(jQuery("#newInstanceNameDialog").val());
	jQuery("#instanceClass").val(jQuery("#instanceClassDialog").val());
	
	jQuery("#componentForm").submit();
}

/**
 * Called when the select dropdown for the source of the property is modified.
 * 
 * @param selectBox
 * @return
 */
function onSourceChange(selectDropDown) {
	if (selectDropDown.value == "string") {
		jQuery("#propertySourceDiv").show();
		jQuery("#requestSourceDiv").hide();
		jQuery("#sessionSourceDiv").hide();
		jQuery("#configSourceDiv").hide();
	} else if (selectDropDown.value == "request") {
		jQuery("#propertySourceDiv").hide();
		jQuery("#requestSourceDiv").show();
		jQuery("#sessionSourceDiv").hide();
		jQuery("#configSourceDiv").hide();
	} else if (selectDropDown.value == "session") {
		jQuery("#propertySourceDiv").hide();
		jQuery("#requestSourceDiv").hide();
		jQuery("#sessionSourceDiv").show();
		jQuery("#configSourceDiv").hide();
	} else if (selectDropDown.value == "config") {
		jQuery("#propertySourceDiv").hide();
		jQuery("#requestSourceDiv").hide();
		jQuery("#sessionSourceDiv").hide();
		jQuery("#configSourceDiv").show();
	}
}

/**
 * Called when the user clicks on the toolbox to edit a "string" property (and bind it to a config option/request parameter/...)
 * 
 * @param propertyName
 * @return
 */
function onPropertyOptionsClick(propertyName) {
	jQuery("#dialogPropertyOptions").dialog("open");	
}

