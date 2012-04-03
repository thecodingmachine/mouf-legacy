/**
 * The MoufSubProperty is an object designed to allow easy usage of field renderers in an array.
 * An array has its own field renderer. The array field renderer itself calls field renderers for
 * each value to renderer, passing the MoufSubProperty object (instead of the MoufProperty object)
 * 
 * @param moufProperty
 * @param key
 * @returns
 */
var MoufSubProperty = function(moufProperty, key) {
	this.subProperty = true;
	this.parentMoufProperty = moufProperty;
	this.key = key;
}

/**
 * Returns the name of the property.
 */
MoufSubProperty.prototype.getName = function() {
	return this.parentMoufProperty.getName();
}

/**
 * Returns the comment of the property.
 */
MoufSubProperty.prototype.getComment = function() {
	return this.parentMoufProperty.getComment();
}

/**
 * Retrieves the annotations of the property, as a JSON object:
 * {
 * 	"annotationName", [param1, param2....]
 * }
 * There are as many params as there are annotations
 */
MoufSubProperty.prototype.getAnnotations = function() {
	return this.parentMoufProperty.getAnnotations();
}

/**
 * Returns true if the property has a default value.
 */
MoufSubProperty.prototype.hasDefault = function() {
	return this.parentMoufProperty.hasDefault();
}

/**
 * Returns the default value of the property.
 */
MoufSubProperty.prototype.getDefault = function() {
	return this.parentMoufProperty.getDefault();
}

/**
 * Returns true if this property has the @Property annotation.
 */
MoufSubProperty.prototype.hasPropertyAnnotation = function() {
	return this.parentMoufProperty.hasPropertyAnnotation();
}

/**
 * Returns the name of the property (if this method has a @Property annotation).
 */
MoufSubProperty.prototype.getPropertyName = function() {
	return this.parentMoufProperty.getPropertyName();
}

/**
 * Returns the type of the property (as defined in the @var annotation).
 */
MoufSubProperty.prototype.getType = function() {
	return this.parentMoufProperty.getSubType();
}

/**
 * Returns the type of the array's value if the type of the annotation is an array (as defined in the @var annotation).
 */
MoufSubProperty.prototype.getSubType = function() {
	return null;
}

/**
 * Returns the type of the array's key if the type of the annotation is an associative array (as defined in the @var annotation).
 */
MoufSubProperty.prototype.getKeyType = function() {
	return null;
}

/**
 * Returns true if the type of the property is an array.
 */
MoufSubProperty.prototype.isArray = function() {
	return this.getType() == 'array';
}

/**
 * Returns true if the type of the property is an associative array.
 */
MoufSubProperty.prototype.isAssociativeArray = function() {
	return false;
}


/**
 * Returns the MoufInstanceProperty of a property for the instance passed in parameter (available if this property has a @Property annotation)
 */
MoufSubProperty.prototype.getMoufInstanceProperty = function(instance) {
	// FIXME: we should also have a MoufInstanceSubProperty object!!!
	return this.parentMoufProperty.getMoufInstanceProperty(instance);
}

/**
 * Returns the value of a property for the instance passed in parameter (available if this property has a @Property annotation)
 */
MoufSubProperty.prototype.getValueForInstance = function(instance) {
	var values =  this.parentMoufProperty.getValueForInstance(instance);
	return values[this.key];
}


/**
 * The default renderer if no renderer is to be found.
 * This renderer is in charge of rendering instances (small, medium, big) and classes.
 */
var MoufDefaultRenderer = (function () {

	/**
	 * Returns the wrapper DIV element in which the class will be stored.
	 * The wrapper DIV will have appropriate CSS classes to handle drag'n'drop.
	 * The wrapper is returned as an "in-memory" jQuery element.
	 */
	var getClassWrapper = function(classDescriptor) {
		var subclassOf = classDescriptor.json["implements"];
		var parentClass = classDescriptor;
		do {
			subclassOf.push(parentClass.getName());
			parentClass = parentClass.getParentClass();
		} while (parentClass);
		var cssClass = "";
		for (var i = 0; i<subclassOf.length; i++) {
			cssClass += "mouftype_"+subclassOf[i] + " ";
		}
		return jQuery("<div/>").addClass(cssClass);
	}
	
	/**
	 * Returns the wrapper DIV element in which the instance will be stored.
	 * The wrapper DIV will have appropriate CSS classes to handle drag'n'drop, the "instance" class,
	 * and additional jQuery "data" attached to find back the name of the instance.
	 * The wrapper is returned as an "in-memory" jQuery element.
	 */
	var getInstanceWrapper = function(instanceDescriptor) {
		var classDescriptor = MoufInstanceManager.getLocalClass(instanceDescriptor.getClassName());

		return getClassWrapper(classDescriptor).addClass("instance").data("instance", instanceDescriptor);
	}
	

	/**
	 * Protects HTML special chars
	 */
	var htmlEncode = function(value){
		return jQuery('<div/>').text(value).html();
	}

	/**
	 * Unprotects HTML special chars
	 */
	var htmlDecode = function(value){
	  return jQuery('<div/>').html(value).text();
	}

	/**
	 * Renders a text input, for the instance "instance", and the property moufProperty.
	 * The "in-memory" jQuery object for the field is returned.
	 */
	var renderStringField = function(instance, moufProperty) {
		// FIXME: les renderers devraient prendre en paramètre des moufInstanceProperty
		// On devra donc implémenter un moufInstanceSubProperty pour les représentations des valeurs d'une array d'une instance.
		// Puis implémenter un moufInstanceProperty->setValue
		var name = moufProperty.getPropertyName();
		var moufInstanceProperty = moufProperty.getMoufInstanceProperty(instance);
		var value = moufProperty.getValueForInstance(instance);
		
		var elem = jQuery("<input/>").attr('name', name)
			.attr("value", value)
			.change(function() {
				alert("value changed in "+findInstance(jQuery(this)).getName() + " for property "+name);
			});
		
		return elem;
		// TODO: how to manage this in an array????
		// gestion des array<array>?
		// name=myarr[0][4]?
		// On pourrait empiler les modifs et les soumettre au bout de quelques secondes....
	}
	
	/**
	 * Renders an array of fields, for the instance "instance", and the property moufProperty.
	 * The "in-memory" jQuery object for the field is returned.
	 */
	var renderArrayField = function(instance, moufProperty) {
		var name = moufProperty.getPropertyName();
		var moufInstanceProperty = moufProperty.getMoufInstanceProperty(instance);
		var values = moufProperty.getValueForInstance(instance);
		
		var elem = jQuery("<div/>").addClass('array');

		if (!moufProperty.isAssociativeArray())  {
			for (var i=0; i<values.length; i++) {
				var fieldElem = jQuery("<div/>").addClass('fieldContainer')
					.data("key", i)
					.appendTo(elem);
					
				var sortableElem = jQuery("<div/>").addClass('sortable');
				jQuery("<div/>").addClass('moveable').appendTo(fieldElem);
				var subProperty = new MoufSubProperty(moufProperty, i);
				fieldRenderer = getFieldRenderer(subProperty.getType(), subProperty.getKeyType(), subProperty.getSubType());
				var rowElem = fieldRenderer(instance, subProperty);
				rowElem.appendTo(fieldElem);
			}
		} else {
			// TODO
		}
		elem.sortable();
		
		// TODO
		return elem;
	}
	
	var fieldsRenderer = {
		"string" : renderStringField,
		"array" : renderArrayField
		// TODO: continue here
	}
	
	/**
	 * Returns the field renderer method for the field whose class is "name"
	 */
	var getFieldRenderer = function(type, subtype, keytype) {
		if (fieldsRenderer[type]) {
			return fieldsRenderer[type];
		} else {
			// TODO: manage subtype and keytype
			// TODO: default should be to display the corresponding renderer.
			return fieldsRenderer["string"];
		}
	}
	
	/**
	 * This function will return the instance whose "elem" html element is part of.
	 */
	var findInstance = function(elem) {
		var currentElem = elem;
		do {
			var instance = currentElem.data("instance");
			if (!instance) {
				currentElem = currentElem.parent(); 
			}
		} while (!instance && currentElem);
		return instance;
	}
	
	/**
	 * This function will return the moufProperty whose "elem" html element is part of.
	 */
	var findMoufProperty = function(elem) {
		var currentElem = elem;
		do {
			var moufProperty = currentElem.data("moufProperty");
			if (!moufProperty) {
				currentElem = currentElem.parent(); 
			}
		} while (!moufProperty && currentElem);
		return moufProperty;
	} 
	
	/**
	 * Returns the renderer annotation.
	 */
	var getRendererAnnotation = function(classDescriptor) {
		var renderer = classDescriptor.getAnnotations()['Renderer'][0];
		if (renderer != null) {
			try {
				var jsonRenderer = jQuery.parseJSON(renderer);
			} catch (e) {
				throw "Invalid @Renderer annotation sent. The @Renderer must have a JSON object attached.\nAnnotation found: @Renderer "+renderer+"\nError detected:"+e;
			}
			return jsonRenderer;
		}
		return null;
	}
	
	return {
		/**
		 * Returns the list of renderers supported by this renderer.
		 */
		getRenderers : function() {
			return {
				"small" : {
					title: "Small",
					/**
					 * Renders the instance in "small" version.
					 * 
					 */
					renderer: function(instance, parent) {
						var classDescriptor = MoufInstanceManager.getLocalClass(instance.getClassName());
						
						var wrapper = getInstanceWrapper(instance).addClass("smallinstance")
												   .text(instance.getName());
						
						// Let's add the small logo image (if any).
						// Is there a logo to display? Let's see in the smallLogo property of the renderer annotation, if any.
						var renderer = getRendererAnnotation(classDescriptor);
						if (renderer != null) {
							if (renderer.smallLogo != null) {
								wrapper.css("background-image", "url("+MoufInstanceManager.rootUrl+"../"+renderer.smallLogo+")");
							}
						}
						
						wrapper.appendTo(parent);
					}
				},
				"medium" : {
					title: "Medium",
					renderer: function(instance, parent) {
						alert('TODO');
					}
				},
				"big" : {
					title: "Big",
					renderer: function(instance, parent) {
						var classDescriptor = MoufInstanceManager.getLocalClass(instance.getClassName());
						
						var wrapper = getInstanceWrapper(instance).addClass("biginstance");
						
						jQuery("<h1/>").text('Instance "'+instance.getName()+'"').appendTo(wrapper);
						jQuery("<h2/>").text('Class "'+instance.getClassName()+'"').appendTo(wrapper);
						jQuery("<div/>").addClass("classComment").html(classDescriptor.getComment()).appendTo(wrapper);

						jQuery("<h2/>").text('Properties').appendTo(wrapper);
						var propertiesList = jQuery("<div/>").addClass('propertieslist');
						
						// For each Mouf property, let's display a field.
						var moufProperties = classDescriptor.getMoufProperties();
						for (var i=0; i<moufProperties.length; i++) {
							var moufProperty = moufProperties[i];
							var fieldGlobalElem = jQuery("<div/>");
							jQuery("<label/>").text(moufProperty.getPropertyName()).appendTo(fieldGlobalElem);
							var fieldElem = jQuery("<div/>").addClass('fieldContainer')
								.data("moufProperty", moufProperty)
								.appendTo(fieldGlobalElem);

							var fieldRenderer = getFieldRenderer(moufProperty.getType(), moufProperty.getSubType(), moufProperty.getKeyType());
							
							fieldRenderer(instance, moufProperty).appendTo(fieldElem);
							
							fieldGlobalElem.appendTo(propertiesList);
						}
						propertiesList.appendTo(wrapper);
						
						wrapper.appendTo(parent);						
					}
				}
			}
		},
		/**
		 * Renders the class described be "classDescriptor" in the "parent" css selector.
		 */
		renderClass : function(classDescriptor, parent) {
			var wrapper = getClassWrapper(classDescriptor).addClass("class smallclass")
			   										 .html("new <b>"+classDescriptor.getName()+"</b>()");
			
			var renderer = getRendererAnnotation(classDescriptor);
			if (renderer != null) {
				if (renderer.smallLogo != null) {
					jQuery(wrapper).css("background-image", "url("+MoufInstanceManager.rootUrl+"../"+renderer.smallLogo+")");
				}
			}

			wrapper.appendTo(parent);
		}
	}
})();