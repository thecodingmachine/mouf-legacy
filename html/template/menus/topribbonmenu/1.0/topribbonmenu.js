jQuery(document).ready(function() {
	var i=0;
	var active = false;
	jQuery('.submenu_element').each(function(index) {
		i ++;
		if(jQuery(this).hasClass('active')) {
			topribbonSubmenuList(i)
			active = true;
		}
	 });
	if(active == false) {
		if(jQuery('#submenu_element_1').length)
			topribbonSubmenuList(1);
	}

});

function topribbonSubmenuList(id) {
	jQuery('.submenu_list').hide();
	jQuery('.submenu_element').removeClass('active');
	jQuery('#submenu_list_element_'+id).show();
	jQuery('#submenu_element_'+id).addClass('active');
	return false;
}