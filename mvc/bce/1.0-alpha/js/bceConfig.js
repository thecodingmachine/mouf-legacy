var formMethods = [ "GET" , "POST" ];

var refreshFKDaoSettings = {
	'id_getter':{
		field : "linkedIdGetter",
     	defaultSelect : "getId|get_id",
        methodType : "getter"
	},
    'label_getter':{
    	field : "linkedLabelGetter",
		defaultSelect : "getLabel|getValue",
    	methodType : "getter"
    },
    'data_method':{
    	field : "dataMethod",
		defaultSelect : "getList|getValues|getAll",
    	methodType : "any"
    }
};

var newId = 0;

var refreshM2MmappingDaoSettings = [
	{
		field : "mappingIdGetter",
     	defaultSelect : "getId|get_id",
        methodType : "getter"
	},
	{
		field : "mappingLeftKeySetter",
		defaultSelect : "left",
		methodType : "setter"
	},
	{
		field : "mappingRightKeyGetter",
		defaultSelect : "right",
		methodType : "getter"
	},
	{
		field : "mappingRightKeySetter",
		defaultSelect : "right",
		methodType : "setter"
	},
    {
    	field : "beanValuesMethod",
		defaultSelect : null,
    	methodType : "any"
    }
];

var refreshM2MlinkedDaoSettings = [
	{
		field : "linkedIdGetter",
     	defaultSelect : "getId|get_id",
        methodType : "getter"
	},
	{
		field : "linkedLabelGetter",
		defaultSelect : "getLabel|getValue",
		methodType : "getter"
	},
    {
    	field : "dataMethod",
		defaultSelect : "getList|getValues|getAll",
    	methodType : "any"
    }
];

var existingFields = []; 
var fieldElements = [];
var newFieldElements = [];
var fkRefreshCalls = [];
var idDesc = null;

var emptyM2MField = {
		mappingDaoName : "",
		mappingDaoData : {
			beanClassFields : [],
			daoMethods : []
		},
		beanValuesMethod : "",
		mappingIdGetter : "",
		mappingLeftKeySetter : "",
		mappingRightKeyGetter : "",
		mappingRightKeySetter : "",
		linkedDaoName : "",
		linkedDaoData : {
			beanClassFields : [],
			daoMethods : []
		},
		linkedIdGetter : "",
		linkedLabelGetter : "",
		dataMethod : "",
		type : "m2m",
		name : "",
		renderer : null,
		formatter : null,
		fieldName : "",
		label : "",
		validators : [],
		isPK : false
}; 

var isNewform = false;

function refershValues(element, instanceName){
	isNewform = true;
	
	jQuery.ajax({
		url: bceSettings.rootUrl + "mouf/bceadmin/setDao",
		data: "dao=" + jQuery(element).val() + "&instance=" + instanceName,
		success: function(data){
			if (data == "1"){
				initInstance(instanceName);
			}else{
				alert('error!');
			}
		},
		error: function(error){
			alert('error!');
		}
	});
}

function initInstance(instanceName){
	jQuery.ajax({
	  url: bceSettings.rootUrl + "plugins/mvc/bce/1.0-alpha/direct/bce_utils.php",
	  data: "q=instanceData&n="+instanceName,
	  success: completeInstanceData,
	  error: function(error){jQuery("#data").html();},
	  dataType: 'json'
	});
}

function completeInstanceData(data){

	var filterdMethods = _getFilteredMethods(data.daoData);
	
	getters = filterdMethods['getters'];
	setters = filterdMethods['setters'];
	bceSettings.mainBeanTableName = data.mainBeanTableName;
	
	var fields = data.descriptors;
	for (var i=0; i<fields.length; i++){
		var field = fields[i];
		if (field.type != "m2m" && field.type != "custom") existingFields.push(field.getter);
		fieldElements[field.name] = field;
	}
	
	//Test getter for existing fields!!
	for (var newFieldName in data.daoData.beanClassFields){
		var newField = data.daoData.beanClassFields[newFieldName];
		var newGetter = newField.getter;
		
		if (existingFields.indexOf(newGetter.name) != -1 || (data.idFieldDescriptor && newGetter.name == data.idFieldDescriptor.getter)){
			continue;
		}
		
		var notIdDesc = !newField.isPk; 
		
		newField.asDescriptor.active = isNewform && newField.asDescriptor.active;
		
		if (notIdDesc){
			var tmpField = newField.asDescriptor;
			if (tmpField.type == 'fk'){
				fkRefreshCalls.push(tmpField.name);
			}
			newFieldElements[newFieldName] = newField.asDescriptor;
			
		}else if(!data.idFieldDescriptor){
			idDesc = newField.asDescriptor;
		}
	}
	
	var idHtml = null;
	if (data.idFieldDescriptor){
		idHtml = _fieldHtml(data.idFieldDescriptor, "idField", 1, null, true);
		idDesc = data.idFieldDescriptor;
	}else if (idDesc){
		idHtml = _fieldHtml(idDesc, "idField", 1, null, true);
	}
	
	if (idHtml){
		jQuery('#id_desc').html(idHtml);
	}
	
	for (var fieldName in fieldElements){
		var field = fieldElements[fieldName];
		if (field.type != null ){
			jQuery("#data").append(_fieldHtml(field));
		}
	}
	
	for (var fieldName in newFieldElements){
		var field = newFieldElements[fieldName];
		if (field.type != null ){
			jQuery("#data").append(_fieldHtml(field, "new"));
		}
	}
	
	//initialize form configuration
	var formActionField = _getSimpleValueWrapper("Form Action URL", "action", "attr", data.action, 2);
	var formMethodField = _getListValueWrapper("Method", "method", "attr", data.method, formMethods, null, 2);
	
	if (!data.validationHandler){
		data.validationHandler = validationHandlers[0];
	}
	if (!data.renderer){
		data.renderer = formRenderers[0];
	}
	
	var validatorField = _getListValueWrapper("Validate Handler", "validate", "attr", data.validationHandler, validationHandlers, null, 2);
	var rendererField = _getListValueWrapper("Form Renderer", "renderer", "attr", data.renderer, formRenderers, null, 2);
	
	jQuery("#data_add").append(formActionField);
	jQuery("#data_add").append(formMethodField);
	
	for ( var attributeName in data.attributes) {
		var attributeValue = data.attributes[attributeName];
		jQuery("#data_add").append(_getSimpleValueWrapper(attributeName, attributeName, "attr", attributeValue, 2));
	}
	
	

	jQuery("#data_add").append(jQuery('<br/>').css('clear', 'both'));

	jQuery("#data_add").append(validatorField);
	jQuery("#data_add").append(rendererField);
	
	jQuery(".field-data").each(function(){
		jQuery(this).slideUp();
	});
	
	callFkDaoSelectRefresh();
	
	jQuery( ".sortable" ).sortable({handle: ".field-title"});
	jQuery(".expand" ).live('click', function(event){
		jQuery(".field-data").each(function(){
			jQuery(this).slideUp();
		});
		
		var elem = jQuery(event.target).closest(".field-title");
		if (!elem.next(".field-data").is(':visible')){
			elem.next(".field-data").slideDown();
			jQuery(event.target).addClass('expanded');
		}else{
			elem.next(".field-data").slideUp();
			jQuery(event.target).removeClass('expanded');
		}
	});
	initUI();
}

function initUI(){
	jQuery( ".sortable" ).sortable("refresh");
	jQuery(".multiselect").multiselect({searchable: false, dividerLocation: 0.5});
	jQuery( "#tabs" ).tabs().addClass( "ui-tabs-vertical ui-helper-clearfix" );
	jQuery( "#tabs li" ).removeClass( "ui-corner-top" ).addClass( "ui-corner-left" );
}

function _fieldHtml(field, addClass, fieldNames, editName, isIdDesc){
	if (field.type == "custom"){
		return  "<div id='wrapper-"+ field.name +"' class='field-bloc base'>" +
					"<div class='field-title'>" +
						"<div style='float: left;'>" +
							"<div style='margin-left: 30px; display: block; float: left'>&nbsp</div>" +
							"<div style='float: left'><input type='checkbox' checked='checked' name='fields["+ field.name +"][active]'></div>"+
							"<input type='hidden' name='fields["+ field.name +"][new]' value='false'/>"+
							"<input type='hidden' name='fields["+ field.name +"][type]' value='"+ field.type +"' />"+
							"<input type='hidden' name='fields["+ field.name +"][instanceName]' value='"+ field.name +"'/>"+
							"<div class='name-val'>"+ field.name +"</div>" +
							"<div style='float: left'>&nbsp;&nbsp;[custom]</div>" +
						"</div>" +
						"<div style='clear:both'></div>" +
					"</div>" +
					"<br style='clear:both'>" +
				"<div>";
	}
	
	var strAddClass = addClass ? " "+addClass : "";
	
	var setFK = field.type == "base" && isIdDesc != true ? "<button class='naked set-fk' onclick='setFK(\""+ field.name +"\");return false;' title='Set FK'>&nbsp;</button>" : "";
	var setPK = field.type == "base" && idDesc == null && isIdDesc != true ? "<button class='naked set-pk' onclick='setPK(\""+ field.name +"\");return false;' title='Set PK'>&nbsp;</button>" : "";
	
	var name = !editName ? field.name : "<input type='text' name='" + 'fields['+field.name+'][instanceNameInput]' + "' value=''/>";
	
	var nameAttr = _getFieldNames(fieldNames, "active", field.name);
	var checkActive = "<input type='checkbox' name='"+ nameAttr +"' "+ (field.active ? "checked='checked'" : "") +"/>";
	
	var html = "<div class='field-title'><div style='float: left;'><div class='expand'></div><div style='float: left'>" + checkActive + "</div><div class='name-val'>" + name + "</div>" + "</div><div style='float: right'>"+setFK+setPK+"</div><div style='clear:both'></div></div>" +
		"<div class='field-data'>"+	_html(_getFieldElements(field, fieldNames));
	
	switch (field.type){
		case "base":
			html += _html(_getBaseElements(field, fieldNames));
			break;
		case "fk":
			html += _html(_getBaseElements(field, fieldNames));
			html += "<br style='clear: both'/>"+_html(_getFKElements(field, fieldNames));
			break;
		case "m2m":
			html += "<br style='clear: both'/>"+_html(_getM2mElements(field, fieldNames));
			break;
	}
	html += "</div>";
	
	return "<div class='field-bloc "+field.type+strAddClass+"' id='wrapper-"+field.name+"'>"+
		html +
	"<br style='clear:both'/></div>";
}

function _html(fields){
	var html = '';
	for ( var i = 0;i < fields.length; i++) {
		field = fields[i];
		html += field.html();
	}
	return html;
}

function _getFieldElements(field, fieldNames){
	var name = _getSimpleValueWrapper("Field Name", "fieldname", field.name, field.fieldName, fieldNames); 
	var label =_getSimpleValueWrapper("Label", "label", field.name, field.label, fieldNames);
	var renderer =_getListValueWrapper("Renderer", "renderer", field.name, field.renderer, renderers, null, fieldNames);
	var formatter =_getListValueWrapper("formatter", "formatter", field.name, field.formatter, formatters, null, fieldNames);
	var validatorsElem =_getMultiListValueWrapper("Validators", "validators", field.name, field.validators, validators, fieldNames);
	
	var br = jQuery("<div/>").append(jQuery("<br/>").css('clear', 'both'));
	var br2 = br.clone();
	
	var nameAttr = _getFieldNames(fieldNames, "type", field.name);
	var typeElem = jQuery('<input/>').attr('type', "hidden").attr('name', nameAttr).val(field.type);
	typeElemWrap = jQuery("<div/>").append(typeElem);

	var nameAttr = _getFieldNames(fieldNames, "new", field.name);
	var isNewElem = jQuery('<input/>').attr('type', "hidden").attr('name', nameAttr).val(field.is_new);
	isNewElemWrap = jQuery("<div/>").append(isNewElem);
	
	var nameAttr = _getFieldNames(fieldNames, "instanceName", field.name);
	var instanceName = jQuery('<input/>').attr('type', "hidden").attr('name', nameAttr).val(field.name);
	instanceNameWrap = jQuery("<div/>").append(instanceName);
	
	
	
	return [name, label, renderer, formatter, br, validatorsElem, br2, typeElemWrap, isNewElemWrap, instanceNameWrap];
}

function _getBaseElements(field, fieldNames){
	var getter =  _getListValueWrapper("Getter", "getter", field.name, field.getter, getters, null, fieldNames);
	var setter = _getListValueWrapper("Setter", "setter", field.name, field.setter, setters, null, fieldNames);
	return [getter, setter];
}

function _getFKElements(field, fieldNames){
	var filterdMethods = _getFilteredMethods(field.daoData);
	fieldGetters = filterdMethods['getters'];
	
	var linkedDao = _getListValueWrapper("Linked Dao", "linkedDao", field.name, field.daoName, daos, "refreshFKDaoSettings", fieldNames);
	var daoMethods = _getDaoMethods(field.daoData);
	var dataMethod = _getListValueWrapper("dataMethod", "dataMethod", field.name, field.dataMethod, daoMethods, null, fieldNames);
	
	var linkedIdGetter = _getListValueWrapper("Linked id Getter", "linkedIdGetter", field.name, field.linkedIdGetter, fieldGetters, null, fieldNames);
	var linkedLabelGetter = _getListValueWrapper("Linked label Getter", "linkedLabelGetter", field.name, field.linkedLabelGetter, fieldGetters, null, fieldNames);
	
	return [linkedDao, dataMethod, linkedIdGetter, linkedLabelGetter];
}
function _getM2mElements(field, fieldNames){
	var filterdMethods = _getFilteredMethods(field.mappingDaoData);
	var fieldGetters = filterdMethods['getters'];
	var fieldSetters = filterdMethods['setters'];
	var mappingDaoMethods = _getDaoMethods(field.mappingDaoData);
	
	var mappingDao = _getListValueWrapper("Mappging Dao", "mappingDao", field.name, field.mappingDaoName, daos, "refreshM2MmappingDaoSettings", fieldNames);
	var mappingIdGetter = _getListValueWrapper("Mapping Id Getter", "mappingIdGetter", field.name, field.mappingIdGetter, fieldGetters, null,fieldNames);
	var mappingLeftKeySetter = _getListValueWrapper("Mapping Left Key Setter", "mappingLeftKeySetter", field.name, field.mappingLeftKeySetter, fieldSetters, null, fieldNames);
	var mappingRightKeyGetter = _getListValueWrapper("Mapping Right Key Getter", "mappingRightKeyGetter", field.name, field.mappingRightKeyGetter, fieldGetters, null, fieldNames);
	var mappingRightKeySetter = _getListValueWrapper("Mapping Right Key Setter", "mappingRightKeySetter", field.name, field.mappingRightKeySetter, fieldSetters, null, fieldNames);
	var beanValuesMethod = _getListValueWrapper("Beans values method", "beanValuesMethod", field.name, field.beanValuesMethod, mappingDaoMethods, null, fieldNames);
	
	var br = jQuery("<div/>").append(jQuery("<br/>").css('clear', 'both'));
	
	var filterdMethods = _getFilteredMethods(field.linkedDaoData);
	var fieldGetters = filterdMethods['getters'];
	var fieldSetters = filterdMethods['setters'];
	var daoMethods = _getDaoMethods(field.linkedDaoData);
	
	var linkedDao = _getListValueWrapper("Linked Dao", "linkedDao", field.name, field.linkedDaoName, daos, "refreshM2MlinkedDaoSettings", fieldNames);
	var linkedIdGetter = _getListValueWrapper("linkedIdGetter", "linkedIdGetter", field.name, field.linkedIdGetter, fieldGetters, null, fieldNames);
	var linkedLabelGetter = _getListValueWrapper("linkedLabelGetter", "linkedLabelGetter", field.name, field.linkedLabelGetter, fieldGetters, null, fieldNames);
	var dataMethod = _getListValueWrapper("dataMethod", "dataMethod", field.name, field.dataMethod, daoMethods, null, fieldNames);
	
	return [mappingDao, mappingIdGetter, mappingLeftKeySetter, mappingRightKeyGetter, mappingRightKeySetter,beanValuesMethod, br, linkedDao, linkedIdGetter, linkedLabelGetter, dataMethod];
}

function _getFieldNames(fieldNames, prop, name){
	if (!fieldNames || fieldNames == 0) return 'fields['+name+']['+prop+']';
	else if (fieldNames == 1){
		return 'idField['+prop+']';
	}else if (fieldNames == 2){
		return  'config['+prop+']';
	}
}

function _getSimpleValueWrapper(label, prop, name, value, fieldNames){
	var fieldNameAttr = _getFieldNames(fieldNames, prop, name);
	var divElem = jQuery("<div/>").addClass('field-value-bloc');
	divElem.append(jQuery("<label>").html(label));
	var input = jQuery("<input/>").attr('type', 'text').attr('id', name+"_"+prop).attr('name', fieldNameAttr);
	input.attr('value',value);
	divElem.append(input);
	return jQuery("<div/>").append(divElem);
}

function _getListValueWrapper(label, prop, name, selectValue, list, settings, fieldNames){
	var onchangehtml = settings ? "refreshBeanMethods("+settings+", \""+name+"\", this.value)" : "";
	var fieldNameAttr = _getFieldNames(fieldNames, prop, name);
	
	var divElem = jQuery("<div/>").addClass('field-value-bloc').append(jQuery("<label>").html(label));
	
	var selectElem = jQuery("<select/>").attr('id', name+"_"+prop).attr("name", fieldNameAttr).attr("onchange", onchangehtml);
	selectElem.append(jQuery("<option/>").attr('value', '').html(' - none - '));
	
	for (var i = 0; i < list.length ; i++){
		var opt = list[i];
		selectElem.append(jQuery("<option/>").attr('value', opt).html(opt).attr('selected', selectValue == opt));
	}
	divElem.append(selectElem);
	

	return jQuery("<div/>").append(divElem);
}

function _getMultiListValueWrapper(label, prop, name, selectValues, list, fieldNames){
	var fieldNameAttr = _getFieldNames(fieldNames, prop, name);

	var finalList = list;
	if (selectValues){
		finalList = selectValues.slice(0);
		for (var i = 0; i < list.length ; i++){
			var opt = list[i];
			if (selectValues.indexOf(opt) == -1) finalList.push(opt);
		}
	}
	
	var selectElem = jQuery("<select/>")
		.attr("name", fieldNameAttr+"[]").addClass("multiselect")
		.attr("id", name+"_"+prop)
		.attr("multiple", true);
	for (var i = 0; i < finalList.length ; i++){
		var opt = finalList[i];
		var optionElem = jQuery("<option/>").val(opt).text(opt);
		if (selectValues && selectValues.indexOf(opt) != -1) {
			optionElem.attr("selected", true);
		}
		optionElem.appendTo(selectElem);
	}
	
	var divElem = jQuery("<div/>").addClass('field-value-bloc');
	jQuery("<label/>").text(label).appendTo(divElem);
	selectElem.appendTo(divElem);

	return jQuery("<div/>").append(divElem);
}

function refreshBeanMethods(settings, fieldName, newDaoName){
	jQuery.ajax({
	  url: bceSettings.rootUrl + "plugins/mvc/bce/1.0-alpha/direct/bce_utils.php",
	  data: "q=daoData&n="+newDaoName,
	  success: function (data){
		  completeDaoFields(data, settings, fieldName);
	  },
	  error: function(error){jQuery("#error").html(error);},
	  dataType: 'json'
	});
}

function completeDaoFields(data, settings, fieldName){
	var filterdMethods = _getFilteredMethods(data);
	var getters = filterdMethods['getters'];
	var setters = filterdMethods['setters'];
	var all = _getDaoMethods(data);

	
	for(var key in settings){
		if (typeof(key) == 'undefined') continue;
		var setting = settings[key];
		var selectElem = jQuery("#"+fieldName+"_"+setting.field);
		var methods = null;
		if (setting.methodType == "getter"){
			methods = getters;
		}else if (setting.methodType == "setter"){
			methods = setters;
		}else{
			methods = all;
		}
		_fillSelectOptions(methods, selectElem, setting.defaultSelect);
	}
}

function _fillSelectOptions(values, selectElem, defaultValueType){
	if (defaultValueType !== null){
		var pattern = defaultValueType;
		if (pattern == "left"){
			pattern = bceSettings.mainBeanTableName;
		}else if (pattern == "right"){
			pattern = "^((?!"+bceSettings.mainBeanTableName+"|[gs]etId).)*$";
		}
		var match = new RegExp(pattern, 'gi');
	}
	
	selectElem.children().remove();
	var selectVal = "";
	jQuery.each(values, function(i, value) {
		var optElem = jQuery("<option/>").val(value).text(value);
		if (defaultValueType && match.exec(value) && selectVal == ""){
			selectVal = value;
			optElem.attr('seleted', true);
		}
		optElem.appendTo(selectElem);
	});
	selectElem.val(selectVal);
}

function _getFilteredMethods(daoData){
	var methods = [];
	methods['getters'] = [];
	methods['setters'] = [];
	if (daoData){
		jQuery.each(daoData.beanClassFields, function(i, field) {
			methods['getters'].push(field.getter.name);
			methods['setters'].push(field.setter.name);
		});
	}
	return methods;
}

function _getDaoMethods(daoData){
	var methods = [];
	if (daoData) {
		jQuery.each(daoData.daoMethods, function(i, method) {
			methods.push(method);
		});
	}
	return methods;
}

function addM2MBlock(){
	emptyM2MField.fieldName = "newField_"+newId;
	emptyM2MField.name = "newField_"+newId;
	var html = _fieldHtml(emptyM2MField, "new", 0, true);
	jQuery("#data").append(html);
	newId ++;
	
	initUI("wrapper-" + emptyM2MField.name);
}

function setPK(fieldName){
	var field = fieldElements[fieldName];
	if (!field) field = newFieldElements[fieldName];
	
	var idHtml = _fieldHtml(field, "idField", 1, null, true);
	
	jQuery( "#tabs" ).tabs('select', 1);
	
	jQuery("#wrapper-"+fieldName).remove();
	jQuery('#id_desc').html(idHtml);
	
	jQuery('.set-pk').each(function(){
		jQuery(this).remove();
	});
	
	initUI("wrapper-"+fieldName);
}

function setFK2(event){
	var fieldName = event.data.elem;
	setFK(fieldName);
}

function setFK(fieldName){
	var field = fieldElements[fieldName];
	
	if (field.type != "base") return;
	
	var elements = _getFKElements(field);
	
	jQuery("#wrapper-"+fieldName+" .field-data").append(jQuery("<br/>").css('clear', 'both'));
	jQuery.each(elements, function(i, element) {
		jQuery("#wrapper-"+fieldName+" .field-data").append(element);
	});
	
	field.type = "fk";
	
	jQuery("#wrapper-"+fieldName+" .field-title button.set-fk").each(function(){
		jQuery(this).removeClass("set-fk").addClass('unset-fk').unbind('click').click({elem : fieldName}, unSetFK);
	});
}

function unSetFK(event){
	var fieldName = event.data.elem;
	
	var field = fieldElements[fieldName];
	
	jQuery('#'+fieldName+'_linkedDao').parent().parent().remove();
	jQuery('#'+fieldName+'_dataMethod').parent().parent().remove();
	jQuery('#'+fieldName+'_linkedIdGetter').parent().parent().remove();
	jQuery('#'+fieldName+'_linkedLabelGetter').parent().parent().remove();
	
	field.type = "base";
	
	jQuery("#wrapper-"+fieldName+" .field-data br").last().remove();
	
	jQuery("#wrapper-"+fieldName+" .field-title button.unset-fk").each(function(){
		jQuery(this).removeClass("unset-fk").addClass('set-fk').unbind('click').click({elem : fieldName}, setFK2);
	});
}

function callFkDaoSelectRefresh(){
	jQuery.each(fkRefreshCalls, function(i, fieldName) {
		var linkedIdGetterElem = jQuery("#" + fieldName + "_linkedIdGetter");
		var linkedLabelGetterElem = jQuery("#" + fieldName + "_linkedLabelGetter");
		
		_selectFromSettings(linkedIdGetterElem, refreshFKDaoSettings.id_getter);
		_selectFromSettings(linkedLabelGetterElem, refreshFKDaoSettings.label_getter);
	});
}

function _selectFromSettings(elem, setting){
	var defaultValueType = setting.defaultSelect;
	if (defaultValueType !== null){
		var pattern = defaultValueType;
		if (pattern == "left"){
			pattern = bceSettings.mainBeanTableName;
		}else if (pattern == "right"){
			pattern = "^((?!"+bceSettings.mainBeanTableName+"|[gs]etId).)*$";
		}
		var match = new RegExp(pattern, 'gi');
	}
	selectVal = "";
	elem.children().each(function (){
		var optElem = jQuery(this);
		var optValue = optElem.val();
		if (defaultValueType && match.exec(optValue) && selectVal == ""){
			selectVal = optValue;
			optElem.attr('seleted', true);
		}
	});
	
	elem.val(selectVal);
}
