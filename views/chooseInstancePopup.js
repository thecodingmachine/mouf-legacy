/**
 * This file contains a function to display a popup to choose from existing instances.
 * 
 */

function chooseInstancePopup(type, url, title, selfedit) {

	jQuery.getJSON("../direct/get_instances.php",{class: type, encode:"json", selfedit:selfedit?"true":"false", ajax: 'true'}, function(j){
		chooseInstancePopupOnComponentsListLoaded(j, type, url, title, selfedit);
	});
	
	
	
}

function chooseInstancePopupOnComponentsListLoaded(instancesList, type, url, title, selfedit) {
	
	alert(instancesList.length);
	return;
	
	var options = '';
    for (var i = 0; i < j.length; i++) {
      options += '<option value="' + j[i] + '">' + j[i] + '</option>';
    }
    jQuery("select#instanceClassDialog").html(options);
    
    if (j.length == 0) {
	      jQuery("#noMatchingComponent").html("You have no class with the @Component annotation that inherits/implements '"+type+"'. You should try to <a href='../packagetransfer/'>download</a>/<a href='../packages/'>enable</a> a package that provides a component implement the "+type+" class/interface.");
	      jQuery("#noMatchingComponent").show();
    }
	
	if (jQuery('#chooseInstancePopup').size() == 0) {
		jQuery('body').append("<div id='chooseInstancePopup' style='width: 600px; height: 400px'></div>");
	}
	
	jQuery('#chooseInstancePopup').attr('title', title);
	
	
	// TODO: protect title.
	var html = "<div>\
		<label for='instanceNameDialog'>Instance name:</label><input type='text' name='newInstanceNameDialog' id='newInstanceNameDialog' /> \
		</div>\
		\
		<div>\
		<label for='instanceClass'>Class:</label>\
		<select name='instanceClassDialog' id='instanceClassDialog'>\
		</select>\
		</div>\
		\
		<div class='error' id='noMatchingComponent' style='display:none'></div>\
		\
		<input type='button' value='Create' onclick='onCreateNewInstance(); return false;' />";

	jQuery('#chooseInstancePopup').html(html);
	
	
	

	lastSelectBox = dropdown;
	
	jQuery("#newInstanceName").val("");
	jQuery("#bindToProperty").val(propertyName);
	
	jQuery("#dialog").dialog("open");
}
