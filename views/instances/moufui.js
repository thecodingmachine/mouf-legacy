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
		} 
	}
})();
