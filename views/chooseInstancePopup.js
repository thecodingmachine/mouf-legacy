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
	
	// Only one item, let's go to the target URL directly.
	if (instancesList.length == 1) {
		window.location = url+instancesList[0];
		return;
	}
	

	if (jQuery('#chooseInstancePopup').size() == 0) {
		jQuery('body').append("<div id='chooseInstancePopup' style='height: 400px;display:none'></div>");
		jQuery("#chooseInstancePopup").dialog({width:500});
	}
	
	jQuery('#chooseInstancePopup').attr('title', title);

	var html = "";
	if (instancesList.length == 0) {
		html += "<div class='warning' id='noMatchingComponent' >You should create an instance implementing or extending the <code>"+type+"</code> class/interface.</div>";
	} else {
		var html = "<div>\
			<p>Please select an instance.</p>\
			<label for='instanceClass'>Instance name:</label>\
			<select name='selectedInstancePopup' id='selectedInstancePopup'>";
		
		for (var i=0; i<instancesList.length; i++) {
			html += "<option>"+instancesList[i]+"</option>";
		}
			
		html += "</select>\
			</div>";
	
			
		html += "<input type='button' id='chooseInstancePopupButton' value='Go' />";
	}

	jQuery('#chooseInstancePopup').html(html);
	jQuery('#chooseInstancePopupButton').unbind('click');
	jQuery('#chooseInstancePopupButton').click(function() {
		window.location = url+jQuery("#selectedInstancePopup").val();
	})
	
	jQuery("#chooseInstancePopup").dialog('open');
	
	
}
