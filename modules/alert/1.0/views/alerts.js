/**
 * Validates the alert by calling the server and then refreshing the datagrid.
 * 
 * @param alert_id
 * @return
 */
function validateAlert(alert_id) {
	jQuery.post("validateAlert", {id:alert_id}, function() {
		// On answer, reload the grid.
		jQuery("#alertGrid").trigger("reloadGrid");
	})
	
	
}