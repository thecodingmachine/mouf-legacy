




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
	var renderStringField = function(moufInstanceProperty) {
		// FIXME: les renderers devraient prendre en paramètre des moufInstanceProperty
		// On devra donc implémenter un moufInstanceSubProperty pour les représentations des valeurs d'une array d'une instance.
		// Puis implémenter un moufInstanceProperty->setValue
		var name = moufInstanceProperty.getName();
		var value = moufInstanceProperty.getValue();
		//var moufInstanceProperty = moufProperty.getMoufInstanceProperty(instance);
		//var value = moufProperty.getValueForInstance(instance);
		
		var elem = jQuery("<input/>").attr('name', name)
			.attr("value", value)
			.change(function() {
				moufInstanceProperty.setValue(jQuery(this).val());
				//alert("value changed in "+findInstance(jQuery(this)).getName() + " for property "+name);
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
	 * 
	 */
	var renderArrayField = function(moufInstanceProperty) {
		var name = moufInstanceProperty.getName();
		var values = moufInstanceProperty.getValue();
		var moufProperty = moufInstanceProperty.getMoufProperty();
		var elem = jQuery("<div/>");
		var sortable = jQuery("<div/>").addClass('array');
		sortable.appendTo(elem);

		if (!moufProperty.isAssociativeArray())  {
			if (values instanceof Array) {
				moufInstanceProperty.forEachArrayElement(function(instanceSubProperty) {
					var fieldElem = jQuery("<div/>").addClass('fieldContainer')
						.data("key", instanceSubProperty.getKey())
						.appendTo(sortable);
						
					var sortableElem = jQuery("<div/>").addClass('sortable');
					jQuery("<div/>").addClass('moveable').appendTo(fieldElem);
					fieldRenderer = getFieldRenderer(instanceSubProperty.getMoufProperty().getType(), instanceSubProperty.getMoufProperty().getKeyType(), instanceSubProperty.getMoufProperty().getSubType());
					var rowElem = fieldRenderer(instanceSubProperty);
					rowElem.appendTo(fieldElem);
				});
			}
			var subtype = moufProperty.getSubType();
			// If this is a known primitive type, let's display a "add a value" button
			if (fieldsRenderer[subtype]) {
				jQuery("<div/>").addClass('addavalue')
					.text("Add a value")
					.appendTo(elem)
					.click(function() {
						var renderer = getFieldRenderer(subtype, null, null);
						// key=null (since we are not an associative array), and we init the value to null too.
						var moufNewSubInstanceProperty = moufInstanceProperty.addArrayElement(null, null);
						
						var fieldElem = jQuery("<div/>").addClass('fieldContainer')
							.data("key", moufNewSubInstanceProperty.getKey())
							.appendTo(sortable);
						
						var sortableElem = jQuery("<div/>").addClass('sortable');
						jQuery("<div/>").addClass('moveable').appendTo(fieldElem);
						
						var rowElem = fieldRenderer(moufNewSubInstanceProperty);
						rowElem.appendTo(fieldElem);
					});
			}
		} else {
			// If this is an associative array
			// Check that value is not null
			if (values instanceof Array) {
				moufInstanceProperty.forEachArrayElement(function(instanceSubProperty) {
					var fieldElem = jQuery("<div/>").addClass('fieldContainer')
						.data("key", instanceSubProperty.getKey())
						.appendTo(sortable);
						
					var sortableElem = jQuery("<div/>").addClass('sortable');
					jQuery("<div/>").addClass('moveable').appendTo(fieldElem);
					fieldRenderer = getFieldRenderer(instanceSubProperty.getMoufProperty().getType(), instanceSubProperty.getMoufProperty().getKeyType(), instanceSubProperty.getMoufProperty().getSubType());
					var rowElem = fieldRenderer(instanceSubProperty);
					
					jQuery("<input/>")
						.addClass("key")
						.val(instanceSubProperty.getKey())
						.appendTo(fieldElem)
						.change(function() {
							// Set's the key if changed
							instanceSubProperty.setKey(jQuery(this).val());							
						});
					jQuery("<span>=&gt;</span>").appendTo(fieldElem);
					
					rowElem.appendTo(fieldElem);
				});
			}
			var subtype = moufProperty.getSubType();
			// If this is a known primitive type, let's display a "add a value" button
			if (fieldsRenderer[subtype]) {
				jQuery("<div/>").addClass('addavalue')
					.text("Add a value")
					.appendTo(elem)
					.click(function() {
						var renderer = getFieldRenderer(subtype, null, null);
						// key="" (since we are an associative array), and we init the value to null too.
						var moufNewSubInstanceProperty = moufInstanceProperty.addArrayElement("", null);
						
						var fieldElem = jQuery("<div/>").addClass('fieldContainer')
							.data("key", moufNewSubInstanceProperty.getKey())
							.appendTo(sortable);
						
						var sortableElem = jQuery("<div/>").addClass('sortable');
						jQuery("<div/>").addClass('moveable').appendTo(fieldElem);
						
						jQuery("<input/>").addClass("key").appendTo(fieldElem).change(function() {
							// Set's the key if changed
							moufNewSubInstanceProperty.setKey(jQuery(this).val());							
						});
;
						jQuery("<span>=&gt;</span>").appendTo(fieldElem);

						var rowElem = fieldRenderer(moufNewSubInstanceProperty);
						rowElem.appendTo(fieldElem);
					});
			}
		}
		var _startPosition = null;
		sortable.sortable({
			start: function(event, ui) {
				_startPosition = jQuery(ui.item).index();
				MoufUI.showBin();
			},
			stop: function(event, ui) {
				MoufUI.hideBin();
			},
			update: function(event, ui) {
				// When moving an element graphically, let's apply the change in the instances list.
				var newPosition = jQuery(ui.item).index();
				moufInstanceProperty.reorderArrayElement(_startPosition, newPosition);
				// This is because the "remove" trigger might be called after the "update" trigger. In that case, _startPosition must point to the new position.
				_startPosition = newPosition;
			},
			remove: function(event, ui) {
				// When an element graphically is dropped in the bin, let's apply the change in the instances list.
				moufInstanceProperty.removeArrayElement(_startPosition);
			},
			// Elements of this sortable can be dropped in the bin.
			connectWith: "div.bin"
		});
		
		return elem;
	}
	
	/**
	 * A list of primitive type fields that can be renderered.
	 */
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
		var renderers = classDescriptor.getAnnotations()['Renderer'];
		if (renderers) {
			var renderer = renderers[0];
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
					 * Result is returned as a jQuery in-memory DOM object
					 */
					renderer: function(instance) {
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
						
						//wrapper.appendTo(parent);
						return wrapper;
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
					renderer: function(instance) {
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

							var moufInstanceProperty = moufProperty.getMoufInstanceProperty(instance);
							fieldRenderer(moufInstanceProperty).appendTo(fieldElem);
							
							fieldGlobalElem.appendTo(propertiesList);
						}
						propertiesList.appendTo(wrapper);
						
						//wrapper.appendTo(parent);
						return wrapper;
					}
				}
			}
		},
		/**
		 * Renders the class described be "classDescriptor" in the "parent" css selector.
		 */
		renderClass : function(classDescriptor) {
			var wrapper = getClassWrapper(classDescriptor).addClass("class smallclass")
			   										 .html("new <b>"+classDescriptor.getName()+"</b>()");
			
			var renderer = getRendererAnnotation(classDescriptor);
			if (renderer != null) {
				if (renderer.smallLogo != null) {
					jQuery(wrapper).css("background-image", "url("+MoufInstanceManager.rootUrl+"../"+renderer.smallLogo+")");
				}
			}

			//wrapper.appendTo(parent);
			return wrapper;
		}
	}
})();