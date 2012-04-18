// A mini jQuery plugin to be able to use the draggable feature in "live" mode.
(function ($) {
   $.fn.liveDraggable = function (opts) {
      this.live("mouseover", function() {
         if (!$(this).data("init")) {
            $(this).data("init", true).draggable(opts);
         }
      });
      return $();
   };
}(jQuery));


/**
 * The MoufUI object is used to display recurrent parts of the Mouf interface
 */
var MoufUI = (function () {
	
	var _bin = jQuery("<div/>")
				.addClass("bin")
				.hide();
	jQuery(function() {
		_bin.appendTo(jQuery("body"));
		
	});
	jQuery("<div/>").text("Drop here to delete")
		.addClass("binText")
		.appendTo(_bin);
	_bin.sortable({
		
	});
	
	return {
		/**
		 * Displays the list of instances and/or the list of classes that
		 * are a subtype of "type"
		 */
		displayInstanceOfType : function(targetSelector, type, displayInstances, displayClasses) {
			MoufInstanceManager.getInstanceListByType(type).then(function(instances, classes) {
				jQuery("<h1/>").text("Type "+type).appendTo(targetSelector);
				jQuery("<h2/>").text("Instances").appendTo(targetSelector);
				var instanceListDiv = jQuery("<div/>").addClass("instanceList").appendTo(targetSelector);
				for (var key in instances) {
					var instance = instances[key];
					instance.render().appendTo(instanceListDiv);
				}
				jQuery("<h2/>").text("Classes").appendTo(targetSelector);
				var classListDiv = jQuery("<div/>").addClass("classList").appendTo(targetSelector);
				for (var key in classes) {
					var classDescriptor = classes[key];
					classDescriptor.render().appendTo(classListDiv);
				}
			}).onError(function(e) {
				addMessage("<pre>"+e+"</pre>", "error");
			});
		},
		showBin: function() {
			_bin.slideDown();
		},
		hideBin: function() {
			_bin.slideUp();
		},
		showSourceFile: function(fileName, line) {
			var container = jQuery("<div/>").attr('title', fileName);
			jQuery.ajax({
				url: MoufInstanceManager.rootUrl+"direct/get_source_file.php",
				data: {
					file: fileName
				}
			}).fail(function(e) {
				var msg = e;
				if (e.responseText) {
					msg = "Status code: "+e.status+" - "+e.statusText+"\n"+e.responseText;
				}
				addMessage("<pre>"+msg+"</pre>", "error");
			}).done(function(result) {
				var pre = jQuery("<pre/>").text(result).addClass("brush:php").appendTo(container);
				container.appendTo(jQuery("body"));
				$( container ).dialog({
					height: jQuery(window).height()*0.9,
					width: jQuery(window).width()*0.9,
					zIndex: 20000,
					modal: true,
					close: function() {
						container.remove();
					}
				});
				SyntaxHighlighter.highlight();
			});
		}
	}
})();
