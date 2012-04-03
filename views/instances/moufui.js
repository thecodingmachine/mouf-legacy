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
				html = "<h1>Type "+type+"</h1>";
				if (displayInstances !== null) {
					html += "<h2>Instances</h2>";
					html += "<div class='instanceList'>";
					
					i=0;
					for (var key in instances) {
						html += "<div class='instance_"+i+"'></div>";
						i++;
						html += "</div>";
					}
					html += "</div>";
				}
				if (displayClasses !== null) {
					html += "<h2>Classes</h2>";
					html += "<div class='classList'>";
					i=0;
					for (var key in classes) {
						html += "<div class='class_"+i+"'></div>";
						i++;
						html += "</div>";
					}
					html += "</div>";
				}
				jQuery(targetSelector).append(html);
				
				// Now, let's render the elements in the instance.
				if (displayInstances !== null) {
					i=0;
					for (var key in instances) {
						var instance = instances[key];
						instance.render(jQuery(targetSelector).find(".instance_"+i));
						i++;
					}
				}
				if (displayClasses !== null) {
					i=0;
					for (var key in classes) {
						var classDescriptor = classes[key];
						classDescriptor.render(jQuery(targetSelector).find(".class_"+i));
						//instance.render(jQuery(targetSelector).find(".instance_"+i));
						i++;
					}
				}

			}).onError(function(e) {
				addMessage("<pre>"+e+"</pre>", "error");
			});
		} 
	}
})();
