jQuery(document).ready(function() {
	jQuery("div.topslidermenu").mouseenter(function() {
		jQuery("div.topslidermenu div.topslidermenumaincontent").slideDown("fast");
	})
	jQuery("div.topslidermenu").mouseleave(function() {
		jQuery("div.topslidermenu div.topslidermenumaincontent").slideUp("fast");
	})
	
	jQuery("div.topslidermenutabs > ul > li").mouseenter(function(evt) {
		// Let's count the position of the menu.
		var target = evt.target;
		var i=1;
		var elem = target;
		while (elem.previousElementSibling != null) {
			elem = elem.previousElementSibling;
			i++;
		}
		// We are in position "i".
		jQuery("div.topslidermenu div.topslidermenumaincontent ul.menu").hide();
		jQuery("div.topslidermenu div.topslidermenutabs ul li").removeClass("topslidermenuactive");
		jQuery("#topslidermenu_item"+i).show();
		jQuery(target).addClass("topslidermenuactive");
	})
});