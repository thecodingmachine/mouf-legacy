/**
 * The object if charge of managing all instances.
 */
var MoufInstanceManager = (function () {
	var _instances = {};
	var _classes = {};
	// List of JS files containing renderers that have been loaded so far
	var _jsrenderers = {};
	// List of CSS files containing renderers that have been loaded so far
	var _cssrenderers = {};
	
	/**
	 * All Ajax calls return the same response (an array containing classes and instances descriptions).
	 * This function is in charge of analyzing this result.
	 */
	var handleUniversalResponse = function(json, callback) {
		
		var nbFilesToLoad = 0;
		
		if (json.classes) {
			for (var className in json.classes) {
				var myClass = new MoufClass(json.classes[className]);
				_classes[className] = myClass;
			}
			
			// Now, that all class are loaded, let's make a second loop to load renderers
			for (var className in json.classes) {
				// Let's check if there are any renderers. If yes, let's load them.
				var myClass = _classes[className];
				var annotations = myClass.getAnnotations();
				var renderers = annotations['Renderer'];
				if (renderers) {
					for (var i=0; i<renderers.length; i++) {
						var renderer = renderers[i];
						try {
							var jsonRenderer = jQuery.parseJSON(renderer);
						} catch (e) {
							throw "Invalid @Renderer annotation sent. The @Renderer must have a JSON object attached.\nAnnotation found: @Renderer "+renderer+"\nError detected:"+e;
						}
						// Let's load JS files for the renderer
						var jsFiles;
						if (jsonRenderer['jsFiles']) {
							jsFiles = jsonRenderer['jsFiles'];
						} else {
							jsFiles = [];
						}
						if (jsonRenderer['jsFile']) {
							jsFiles.push(jsonRenderer['jsFile']);
						}
						for (var i=0; i<jsFiles.length; i++) {
							var jsFile = jsFiles[i];
							if (_jsrenderers[jsFile]) {
								continue;
							}
					        var scriptElem = document.createElement('script');
					        scriptElem.type = 'text/javascript';
					        scriptElem.async = true;
					        var fileUrl;
					        if (jsFile.indexOf("http://") == 0 || jsFile.indexOf("https://") == 0) {
					        	fileUrl = jsFile;
					        } else {
					        	fileUrl = MoufInstanceManager.rootUrl+'../'+jsFile;
					        }
					        scriptElem.src = fileUrl;
			
					        nbFilesToLoad++;
					        // Now, let's make sure we call the callback when everything is loaded.
					        if (scriptElem.readyState){  //IE
					        	var thisClass = myClass;
					        	var thisRendererName = jsonRenderer['object'];
					        	scriptElem.onreadystatechange = function(){
					                if (scriptElem.readyState == "loaded" ||
					                		scriptElem.readyState == "complete"){
					                	scriptElem.onreadystatechange = null;
					                	nbFilesToLoad--;
					                	
					                	// Let's add the renderer to the possible renderer of this class.
						        		thisClass.renderers.push(window[thisRendererName]);

					                	if (nbFilesToLoad == 0) {
					                		callback();
					                	}
					                }
					            };
					        } else {  //Others
					        	var thisClass = myClass;
					        	var thisRendererName = jsonRenderer['object'];
					        	scriptElem.onload = function(){
					        		nbFilesToLoad--;

					        		// Let's add the renderer to the possible renderer of this class.
					        		thisClass.renderers.push(window[thisRendererName]);

				                	if (nbFilesToLoad == 0) {
				                		callback();
				                	}
					            };
					        }
					        
					        //var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(scriptElem, s);
					        document.getElementsByTagName("head")[0].appendChild(scriptElem);
							_jsrenderers[jsFile] = true;
						}
						
						// Let's load CSS files for the renderer
						var cssFiles;
						if (jsonRenderer['cssFiles']) {
							cssFiles = jsonRenderer['cssFiles'];
						} else {
							cssFiles = [];
						}
						if (jsonRenderer['cssFile']) {
							cssFiles.push(jsonRenderer['cssFile']);
						}
						for (var i=0; i<cssFiles.length; i++) {
							var cssFile = cssFiles[i];
							if (_cssrenderers[cssFile]) {
								continue;
							}
							var fileref=document.createElement("link");
							fileref.setAttribute("rel", "stylesheet")
							fileref.setAttribute("type", "text/css")
							var fileUrl;
					        if (cssFile.indexOf("http://") == 0 || cssFile.indexOf("https://") == 0) {
					        	fileUrl = cssFile;
					        } else {
					        	fileUrl = MoufInstanceManager.rootUrl+'../'+cssFile;
					        }
					        fileref.setAttribute("href", fileUrl)
					        document.getElementsByTagName("head")[0].appendChild(fileref);
							_cssrenderers[cssFile] = true;
						}

					}
				}
			}
		}
		if (json.instances) {
			for (var instanceName in json.instances) {
				var instance = new MoufInstance(json.instances[instanceName]);
				_instances[instanceName] = instance;
			}
		}
		if (nbFilesToLoad == 0) {
			callback();
		}
	}
	
	// Let's return the public object
	return {
		
		rootUrl : "/mouf/mouf/",
		/**
		 * Adds an array of instances, defined as json object.
		 * 
		 * e.g.:
		 * instances = {
		 * 		"instanceName": {
		 * 			"class": className,
		 * 			"fieldProperties": [
		 * 					...
		 * 				]
		 * 				...
		 * 				SEE MoufManager documentation for more, this is the array stored there.
		 * 		},
		 * 		"notFullyLoadedInstance": {
		 * 			"class": className,
		 * 			"incomplete": true
		 * 		}
		 *	}
		 */
		addInstances : function(instances) {
			for (var key in instances) {
				_instances[key] = new MoufInstance(instances[key]);
			}
		},
		
		getInstance : function(instanceName) {
			var promise = new Mouf.Promise();
			
			if (_instances[instanceName] && !_instances[instanceName].incomplete) {
				promise.triggerSuccess(window, _instances[instanceName]);
			} else {
				jQuery.ajax(this.rootUrl+"direct/get_instance_details.php", {
					data: {
						name: instanceName,
						encode: "json"
					}
				}).fail(function(e) {
					promise.triggerError(window, e);
				}).done(function(result) {
					/*try {
						var json = jQuery.parseJSON(result);
					} catch (e) {
						promise.triggerError(window, result);
					}
					handleUniversalResponse(json);*/
					if (typeof(result) == "string") {
						promise.triggerError(window, result);
						return;
					}
					try {
						handleUniversalResponse(result, function() {
							promise.triggerSuccess(window, _instances[instanceName]);
						});
					} catch (e) {
						promise.triggerError(window, e);
						throw e;
					}
				});
			}
			return promise;
		},
		
		getClass : function(className) {
			var promise = new Mouf.Promise();
			
			if (_classes[className] && !_classes[className].incomplete) {
				promise.triggerSuccess(window, _classes[className]);
			} else {
				jQuery.ajax(this.rootUrl+"direct/get_class.php", {
					data: {
						"class": className,
						encode: "json"
					}
				}).fail(function(e) {
					promise.triggerError(window, e);
				}).done(function(result) {
					try {
						var json = jQuery.parseJSON(result);
					} catch (e) {
						promise.triggerError(window, result);
						return;
					}
					try {
						handleUniversalResponse(json, function() {
							promise.triggerSuccess(window, _classes[className]);
						});
					} catch (e) {
						promise.triggerError(window, e);
						throw e;
					}
					
				});
			}
			return promise;
		},
		
		/**
		 * Returns the class passed in parameter. This class must have previously been loaded (through getClass or getInstance), otherwise,
		 * an exception will be triggered.
		 */
		getLocalClass : function(className) {
			if (_classes[className]) {
				return _classes[className];
			} else {
				throw "Unable to find class '"+className+"' locally. It should have been loaded first (through getClass or getInstance)";
			}
		}

	};
})();

/**
 * Let's define the MoufInstance class.
 * The constructor takes a JSON object that comes straight from MoufManager inner representation.
 * 	SEE MoufManager documentation for more, this is the array stored there.
 *  
 * @class
 */
var MoufInstance = function(json) {
	this.json = json;
	this.properties = {};
	var jsonProperties = this.json["properties"];
	for (var propertyName in jsonProperties) {
		this.properties[propertyName] = new MoufInstanceProperty(propertyName, jsonProperties[propertyName], this);
	}
}

MoufInstance.prototype.getClassName = function() {
	return this.json["class"];
}

/**
 * Returns the MoufClass representing the instance... as a promise!!!
 */
MoufInstance.prototype.getClass = function() {
	return MoufInstanceManager.getClass(this.getClassName());
}

MoufInstance.prototype.getName = function() {
	return this.json["name"];
}

/**
 * Returns an array of objects of type MoufInstanceProperty that represents the property of this instance.
 */
MoufInstance.prototype.getProperties = function() {
	return this.properties;
}

/**
 * Returns an object of type MoufInstanceProperty that represents the property of this instance.
 */
MoufInstance.prototype.getProperty = function(propertyName) {
	return this.properties[propertyName];
}

/**
 * Renders the instance to the display, inside the target element.
 */
MoufInstance.prototype.render = function(target) {
	// TODO: improve this to take into account all renderers, ...
	var classDescriptor = MoufInstanceManager.getLocalClass(this.getClassName());
	var renderers = classDescriptor.getRenderers();
	var renderer = renderers[0];
	var callback = renderer.getRenderers()["small"].renderer;
	callback(this, target);
} 

/**
 * Let's define the MoufInstanceProperty class, that defines the value of a property/method having a @Property annotation.
 */
var MoufInstanceProperty = function(propertyName, json, parent) {
	this.name = propertyName;
	this.json = json;
	this.parent = parent;
}

/**
 * Returns the value for this property.
 */
MoufInstanceProperty.prototype.getName = function() {
	return this.name;
}

/**
 * Returns the value for this property.
 */
MoufInstanceProperty.prototype.getValue = function() {
	return this.json['value'];
}

/**
 * Returns the origin for this property.
 */
MoufInstanceProperty.prototype.getOrigin = function() {
	return this.json['origin'];
}

/**
 * Returns the metadata for this property.
 */
MoufInstanceProperty.prototype.getMetaData = function() {
	return this.json['metadata'];
}

/**
 * Returns a MoufProperty or a MoufMethod object representing the class property/method that holds the @Property annotation.
 */
MoufInstanceProperty.prototype.getMoufProperty = function() {
	var classDescriptor = MoufInstanceManager.getLocalClass(this.parent.getClassName());
	if (classDescriptor.getProperty(this.name) != null) {
		return classDescriptor.getProperty(this.name);
	} else if (classDescriptor.getMethod(this.name) != null) {
		return classDescriptor.getMethod(this.name);
	} else {
		throw "Error, unknown mouf property "+this.name;
	}
}

/**
 * Let's define the MoufClass class, that defines a PHP class.
 */
var MoufClass = function(json) {
	this.json = json;

	this.properties = [];
	this.propertiesByName = {};
	var jsonProperties = this.json["properties"];
	for (var i=0; i<jsonProperties.length; i++) {
		var moufProperty = new MoufProperty(jsonProperties[i]);
		this.properties.push(moufProperty);
		this.propertiesByName[moufProperty.getName()] = moufProperty;
	}

	this.methods = [];
	this.methodsByName = {};
	var jsonMethods = this.json["methods"];
	for (var i=0; i<jsonMethods.length; i++) {
		var moufMethod = new MoufMethod(jsonMethods[i]);
		this.methods.push(moufMethod);
		this.methodsByName[moufMethod.getName()] = moufMethod;
	}
	
	this.renderers = [];
}

/**
 * Returns the name of the class.
 */
MoufClass.prototype.getName = function() {
	return this.json['name'];
}

/**
 * Returns the name of the parent class.
 */
MoufClass.prototype.getParentClassName = function() {
	return this.json['extend'];
}

/**
 * Returns the parent class.
 */
MoufClass.prototype.getParentClass = function() {
	var parentClassName = this.getParentClassName();
	if (parentClassName != null) {
		return MoufInstanceManager.getLocalClass(parentClassName);
	} else {
		return null;
	}
}

/**
 * Returns the comments of the class.
 */
MoufClass.prototype.getComment = function() {
	return this.json['comment']['comment'];
}

/**
 * Returns the annotations of the class, as a JSON object, excluding the parent classes:
 * {
 * 	"annotationName", [param1, param2....]
 * }
 * There are as many params as there are annotations
 */
MoufClass.prototype.getLocalAnnotations = function() {
	return this.json['comment']['annotations'];
}

/**
 * Retrieves the annotations of the class, as a JSON object, including the parent classes, and pass those to a callback:
 * {
 * 	"annotationName", [param1, param2....]
 * }
 * There are as many params as there are annotations
 */
MoufClass.prototype.getAnnotations = function() {
	var annotations = this.json['comment']['annotations']; 
	
	var thisClass = this;
	do {
		var parentClass = thisClass.getParentClass()
		if (parentClass == null) {
			break;
		}
		var parentAnnotations = parentClass.getAnnotations();
		for (var key in parentAnnotations) {
			if (annotations[key] == null) {
				annotations[key] = [];
			}
			annotations[key].concat(parentAnnotations[key]);
		}
		
		thisClass = parentClass;
	} while (true);
	
	return annotations;
}

/**
 * Returns the list of all Mouf properties (properties and setters with a @Property annotation)
 */
MoufClass.prototype.getMoufProperties = function() {
	var moufProperties = [];
	
	var properties = this.getProperties();
	for (var i=0; i<properties.length; i++) {
		var property = properties[i]; 
		if (property.hasPropertyAnnotation()) {
			moufProperties.push(property);
		}
	}

	var methods = this.getMethods();
	for (var i=0; i<methods.length; i++) {
		var method = methods[i]; 
		if (method.hasPropertyAnnotation()) {
			moufProperties.push(method);
		}
	}
	return moufProperties;
}

/**
 * Returns a list of renderer objects.
 */
MoufClass.prototype.getRenderers = function() {
	return this.renderers;
}

/**
 * Returns an array of objects of type MoufProperty that represents the property of this class.
 */
MoufClass.prototype.getProperties = function() {
	return this.properties;
}

/**
 * Returns an object of type MoufProperty that represents the property of this class.
 */
MoufClass.prototype.getProperty = function(propertyName) {
	return this.propertiesByName[propertyName];
}

/**
 * Returns an array of objects of type MoufMethod that represent the methods of this class.
 */
MoufClass.prototype.getMethods = function() {
	return this.methods;
}

/**
 * Returns an object of type MoufMethod that represents a method of this class.
 */
MoufClass.prototype.getMethod = function(methodName) {
	return this.methodsByName[methodName];
}

/**
 * Let's define the MoufProperty class, that defines a PHP field in a class (does not have to have the @Property annotation)
 */
var MoufProperty = function(json) {
	this.json = json;
}

/**
 * Returns the name of the property.
 */
MoufProperty.prototype.getName = function() {
	return this.json['name'];
}

/**
 * Returns the comment of the property.
 */
MoufProperty.prototype.getComment = function() {
	return this.json['comment']['comment'];
}

/**
 * Retrieves the annotations of the property, as a JSON object:
 * {
 * 	"annotationName", [param1, param2....]
 * }
 * There are as many params as there are annotations
 */
MoufProperty.prototype.getAnnotations = function() {
	return this.json['comment']['annotations']; 
}

/**
 * Returns true if the property has a default value.
 */
MoufProperty.prototype.hasDefault = function() {
	return typeof(this.json['default']) != "undefined";
}

/**
 * Returns the default value of the property.
 */
MoufProperty.prototype.getDefault = function() {
	return this.json['default'];
}

/**
 * Returns true if this property has the @Property annotation.
 */
MoufProperty.prototype.hasPropertyAnnotation = function() {
	return this.json['moufProperty'];
}

/**
 * Returns the name of the property (if this method has a @Property annotation).
 */
MoufProperty.prototype.getPropertyName = function() {
	return this.json['name'];
}

/**
 * Returns the type of the property (as defined in the @var annotation).
 */
MoufProperty.prototype.getType = function() {
	return this.json['type'];
}

/**
 * Returns the type of the array's value if the type of the annotation is an array (as defined in the @var annotation).
 */
MoufProperty.prototype.getSubType = function() {
	return this.json['subtype'];
}

/**
 * Returns the type of the array's key if the type of the annotation is an associative array (as defined in the @var annotation).
 */
MoufProperty.prototype.getKeyType = function() {
	return this.json['keytype'];
}

/**
 * Returns true if the type of the property is an array.
 */
MoufProperty.prototype.isArray = function() {
	return this.json['type'] == 'array';
}

/**
 * Returns true if the type of the property is an associative array.
 */
MoufProperty.prototype.isAssociativeArray = function() {
	return (this.json['type'] == 'array' && this.json['keytype']);
}

/**
 * Returns the value of a property for the instance passed in parameter (available if this property has a @Property annotation)
 */
MoufProperty.prototype.getValueForInstance = function(instance) {
	return instance.getProperty(this.json['name']).getValue();
}

/**
 * Let's define the MoufMethod class, that defines a PHP method in a class (does not have to have the @Property annotation)
 */
var MoufMethod = function(json) {
	this.json = json;
	this.parameters = [];
	this.parametersByName = {};
	var jsonParameters = this.json["parameters"];
	for (var i=0; i<jsonParameters.length; i++) {
		var parameter = new MoufParameter(jsonParameters[i]);
		this.parameters.push(parameter);
		this.parametersByName[parameter.name] = parameter;
	}
}

/**
 * Returns the name of the method.
 */
MoufMethod.prototype.getName = function() {
	return this.json['name'];
}

/**
 * Returns the modifier of the method (can be public, protected, private)
 */
MoufMethod.prototype.getModifier = function() {
	return this.json['modifier'];
}

/**
 * Returns whether the method is static or not
 */
MoufMethod.prototype.getStatic = function() {
	return this.json['static'];
}

/**
 * Returns whether the method is abstract or not
 */
MoufMethod.prototype.getAbstract = function() {
	return this.json['abstract'];
}

/**
 * Returns whether the method is a constructor or not
 */
MoufMethod.prototype.getAbstract = function() {
	return this.json['constructor'];
}

/**
 * Returns whether the method is final or not
 */
MoufMethod.prototype.getAbstract = function() {
	return this.json['final'];
}

/**
 * Returns the method comments
 */
MoufMethod.prototype.getComment = function() {
	return this.json['comment']['comment'];
}

/**
 * Retrieves the annotations of the method, as a JSON object:
 * {
 * 	"annotationName", [param1, param2....]
 * }
 * There are as many params as there are annotations
 */
MoufMethod.prototype.getAnnotations = function() {
	return this.json['comment']['annotations']; 
}

/**
 * Returns true if this property has the @Property annotation.
 */
MoufMethod.prototype.hasPropertyAnnotation = function() {
	return this.json['moufProperty'];
}

/**
 * Returns the type of the property (as defined in the @var annotation).
 */
MoufMethod.prototype.getType = function() {
	return this.json['type'];
}

/**
 * Returns the type of the array's value if the type of the annotation is an array (as defined in the @var annotation).
 */
MoufMethod.prototype.getSubType = function() {
	return this.json['subtype'];
}

/**
 * Returns the type of the array's key if the type of the annotation is an associative array (as defined in the @var annotation).
 */
MoufMethod.prototype.getKeyType = function() {
	return this.json['keytype'];
}

/**
 * Returns true if the type of the property is an array.
 */
MoufMethod.prototype.isArray = function() {
	return this.json['type'] == 'array';
}

/**
 * Returns true if the type of the property is an associative array.
 */
MoufMethod.prototype.isAssociativeArray = function() {
	return (this.json['type'] == 'array' && this.json['keytype']);
}

/**
 * Returns the name of the property (if this method has a @Property annotation).
 */
MoufMethod.prototype.getPropertyName = function() {
	var methodName = this.json['name'];
	if (methodName.indexOf("set") !== 0) {
		throw "Error while creating MoufPropertyDescriptor. A @Property annotation must be set to methods that start with 'set'. For instance: setName, and setPhone are valid @Property setters. "+methodName+" is not a valid setter name.";
	}
	propName1 = methodName.substr(3);
	if (propName1 == "") {
		throw "Error while creating MoufPropertyDescriptor. A @Property annotation cannot be put on a method named 'set'. It must be put on a method whose name starts with 'set'. For instance: setName, and setPhone are valid @Property setters.";
	}
	propName2 = propName1.substr(0,1).toLowerCase()+propName1.substr(1);
	return propName2;
}

/**
 * Returns the value of a property for the instance passed in parameter (available if this method has a @Property annotation)
 */
MoufMethod.prototype.getValueForInstance = function(instance) {
	return instance.getProperty(this.json['name']).getValue();
}

/**
 * Returns an array of objects of type MoufInstanceProperty that represents the property of this instance.
 */
MoufMethod.prototype.getParameters = function() {
	return this.parameters;
}

/**
 * Returns an object of type MoufInstanceProperty that represents the property of this instance.
 */
MoufMethod.prototype.getParameter = function(propertyName) {
	return this.properties[propertyName];
}


/**
 * Let's define the MoufParameter class, that defines a PHP parameter in a method.
 */
var MoufParameter = function(json) {
	this.json = json;
}

/**
 * Returns the name of the parameter.
 */
MoufParameter.prototype.getName = function() {
	return this.json['name'];
}

/**
 * Returns whether the parameter has a default value.
 */
MoufParameter.prototype.hasDefault = function() {
	return this.json['hasDefault'];
}

/**
 * Returns the default value.
 */
MoufParameter.prototype.hasDefault = function() {
	return this.json['default'];
}

/**
 * Returns whether the parameter is typed as an array.
 */
MoufParameter.prototype.isArray = function() {
	return this.json['isArray'];
}

/**
 * Returns the class name of the parameter, if any.
 */
MoufParameter.prototype.getClassName = function() {
	return this.json['class'];
}
