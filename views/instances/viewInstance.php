<?php
/* @var $this MoufAjaxInstanceController */

?>

<style>
div.editInstance {
	float: right;
}

div.classComment {
	word-wrap: pre;
}
</style>

<div id="messages"></div>

<div id="instance" class="instance"></div>

<div id="renderedInstance"></div>

<script type="text/javascript">
MoufInstanceManager.getInstance(<?php echo json_encode($this->instanceName) ?>).then(function(instance) {
	/*instance.getClass().then(function(myClass) {*/
		var myClass = MoufInstanceManager.getLocalClass(instance.getClassName());
	
		var html = "";
		html += "<div class='editInstance'>edit</div>";
		html += "<h1>Instance: "+<?php echo json_encode($this->instanceName) ?>+"</h1>";
		html += "<h2>Class: "+instance.getClassName()+"</h2>";
		//html += "<div class='instanceComment'>"+instance.getComment()+"</div>";
		html += "<div class='parentClass'>Parent class: "+myClass.getParentClassName()+"</div>";
		html += "<div class='classComment'>"+myClass.getComment()+"</div>";
		var annotations = myClass.getAnnotations();
		
		for (var key in annotations) {
			html += "<div class='annotation'>"+key+"</div>";
		}
		
		html += "<h2>Properties</h2>";
		var properties = myClass.getProperties();

		for (var i=0; i<properties.length; i++) {
			html += "<div class='property'>"+properties[i].getName()+"</div>";
			var annotations = properties[i].getAnnotations();
			html += "<ul>";
			for (var key in annotations) {
				html += "<li>"+key+"</li>";
			}
			html += "</ul>";
		}
		
		html += "<h2>Methods</h2>";
		var methods = myClass.getMethods();

		for (var i=0; i<methods.length; i++) {
			html += "<div class='method'>"+methods[i].getName()+"</div>";
		}

		html += "<h2>Mouf Properties</h2>";
		var moufProperties = instance.getProperties();
		 
		/*for (var i=0; i<moufProperties.length; i++) {
			html += "<div class='moufproperty'>"+moufProperties[i].getName()+"</div>";
		}*/
		for (var name in moufProperties) {
			html += "<div class='moufproperty'>"+moufProperties[name].getName()+" - "+moufProperties[name].getValue()+" - "+moufProperties[name].getMoufProperty().name+"</div>";
		}

		html += "<hr/>\n";

		var moufProperties = myClass.getMoufProperties();
		for (var i=0; i<moufProperties.length; i++) {
			var moufProperty = moufProperties[i];
			html += "<div>Mouf property: "+moufProperty.getPropertyName()+" - "+moufProperty.getType()+" - value: "+moufProperty.getValueForInstance(instance)+"</div>";
		} 
		
		
		jQuery("#instance").append(html);

		instance.render(jQuery("#renderedInstance"));
		
		//alert(myClass.getProperty('noConflictMode').getComment());
		//alert(myClass.getMethod('toHtml').getComment());
	/*}).onError(function(e) {
		addMessage("Error while loading class: <pre>"+e+"</pre>", "error");
	});*/
	
	/*alert("SUCCESS "+instance.getClassName());
	var properties = instance.getProperties();
	for (var key in properties) {
		alert(key+" value is "+properties[key].getValue());
		alert(key+" origin is "+properties[key].getOrigin());
	}
	instance.getClass().then(function(myClass) {
		alert(myClass.getProperty('noConflictMode').getComment());
		alert(myClass.getMethod('toHtml').getComment());
	}).onError(function(e) {
		addMessage("Error while loading class: <pre>"+e+"</pre>", "error");
	});*/
	
}).onError(function(e) {
	addMessage("<pre>"+e+"</pre>", "error");
});

</script>