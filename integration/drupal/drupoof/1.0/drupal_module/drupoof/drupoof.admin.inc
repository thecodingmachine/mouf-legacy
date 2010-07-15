<?php

/**
 * Displays a page with the list of all nodes instances declared in Mouf
 * 
 */
function drupoof_nodes_instances_list() {
	//require_once ROOT_PATH."mouf/Moufspector.php";
	
	$moufManager = MoufManager::getMoufManager();
	
	//$componentsList = Moufspector::getComponentsList("DrupalNode");
	$instancesList = $moufManager->findInstances("DrupalNode");

	$okInstanceList = array();
	$newInstanceList = array();
	$toSyncInstanceList = array();
	
	foreach ($instancesList as $instanceName) {
		$moufNode = $moufManager->getInstance($instanceName);
		/* @var $moufNode DrupalNode */
		$drupalNode = drupoof_node_load_by_instance_name($instanceName);
		
		if ($drupalNode == null) {
			//$html .= "New node: ".$instanceName."<br/>";
			$newInstanceList[] = $instanceName;
		} else if ($drupalNode->changed != $moufNode->changed) {
			$toSyncInstanceList[] = $instanceName;
			//$html .= "Needs to be updated in Drupal: ".$instanceName."<br/>";
		} else {
			$okInstanceList[] = $instanceName;
			//$html .= "Correctly synchronized: ".$instanceName."<br/>";
		}
	}
	
	drupal_add_js("
				function setInstanceName(instanceName) {
					jQuery('#instance').val(instanceName);
				}
				", "inline");
	
	$html = "<form action='".url("/admin/settings/drupoof/synchronize")."' method='post'>";
	$html .= "<input type='hidden' name='instance' id='instance' />";
	$html .= "<table><tr><th>".t("Instance name")."</th><th>".t("Status")."</th><th>".t("Action")."</th></tr>";
	
	
	foreach ($newInstanceList as $instanceList) {
		$html .= "<tr><td>".check_plain($instanceName)."</td><td>New</td><td><button type='submit' onclick='setInstanceName(\"".addslashes(check_plain($instanceName))."\")'>Create node in Drupal</button></td></tr>";
	}
	foreach ($toSyncInstanceList as $instanceList) {
		// TODO: protect HTML
		$html .= "<tr><td>".check_plain($instanceName)."</td><td>Update needed</td><td><button type='submit' onclick='setInstanceName(\"".addslashes(check_plain($instanceName))."\")'>Update in Drupal</button></td></tr>";
	}
	foreach ($okInstanceList as $instanceList) {
		// TODO: protect HTML
		$html .= "<tr><td>".check_plain($instanceName)."</td><td>Synchronized</td><td></td></tr>";
	}
	
	$html .= "</table>";
	$html .= "</form>";
	return $html;
}

/**
 * Function called when a synchronization from Mouf to Drupal is requested from the main screen.
 */
function drupoof_synchronize_to_drupal() {
	$instanceName = $_REQUEST['instance'];
	
	$moufManager = MoufManager::getMoufManager();
	$moufNode = $moufManager->getInstance($instanceName);
	/* @var $moufNode DrupalNode */
	
	$moufNode->saveToDrupalDb();
	
	header("Location: ".url('/admin/settings/drupoof'));
	//$form_state['redirect'] = 'admin/settings/drupoof';
	return;
}

function drupoof_admin() {
	/*drupal_add_js(drupal_get_path('module', 'drupoof')."/tracker/apideo/apideo.js", 'module', 'header', FALSE, FALSE);
	drupal_add_js("Apideo.onError(function(category, type, msg) {
					document.getElementById('drupoof_key_message_error').innerHTML = msg;
					checkApideoKeyError();
					})", "inline");
	drupal_add_js("var checkApideoKeyMakeInit = true;
				function checkApideoKey() {
						checkApideoKeyWait();
						var key = document.getElementById('edit-key').value;
						var conn = Apideo.connect(key);
						var room = conn.joinRoom('room_test_drupoof_drupal_validate');
						room.onLoad(function() {
							conn.quitRoom(room);
							checkApideoKeyOk();
						})
					}
				function checkApideoKeyWait() {
					document.getElementById('drupoof_key_message_wait').style.display = 'block';
					document.getElementById('drupoof_key_message_error').style.display = 'none';
					document.getElementById('drupoof_key_message_ok').style.display = 'none';
					document.getElementById('button_test_key').disabled = 'disabled';
					}
				function checkApideoKeyError() {
					document.getElementById('drupoof_key_message_wait').style.display = 'none';
					document.getElementById('drupoof_key_message_error').style.display = 'block';
					document.getElementById('drupoof_key_message_ok').style.display = 'none';
					document.getElementById('button_test_key').disabled = '';
					checkApideoKeyMakeInit = true;
					}
				function checkApideoKeyOk() {
					document.getElementById('drupoof_key_message_wait').style.display = 'none';
					document.getElementById('drupoof_key_message_error').style.display = 'none';
					document.getElementById('drupoof_key_message_ok').style.display = 'block';
					document.getElementById('button_test_key').disabled = '';
					checkApideoKeyMakeInit = true;
					}
				function checkApideoKeyInit() {
					if(checkApideoKeyMakeInit == true) {
						document.getElementById('drupoof_key_message_wait').style.display = 'none';
						document.getElementById('drupoof_key_message_error').style.display = 'none';
						document.getElementById('drupoof_key_message_ok').style.display = 'none';
						checkApideoKeyMakeInit = false;
					}
					}", "inline");
	
	
	
	$default = drupoof_load_conf("key");
	$form["key"] = array(
		'#type' => 'textfield',
		'#title' => t('Apideo Key'),
		'#default_value' => $default,
		'#description' => t('Apideo key. You can get it on <a href="http://www.apideo.com">Apideo</a>.'),
		'#attributes' => array('onkeyup' => "checkApideoKeyInit()")
	);
	
	$form["check_key_message"] = array(
		'#type' => 'item',
		'#value' => '<div id="drupoof_key_message_error" class="error" style="display: none"></div>
					<div id="drupoof_key_message_wait" class="warning" style="display: none">Wait</div>
					<div id="drupoof_key_message_ok" class="ok" style="display: none">OK</div>',
	);
	
	$form["check_key"] = array(
		'#type' => 'item',
		'#value' => '<input type="button" id="button_test_key" name="Test" value="Test" onclick="checkApideoKey();" />',
	);
	
	$form['cobrowsing'] = array(
      '#type' => 'fieldset',
      '#title' => t('Cobrowsing'),
      '#collapsible' => TRUE,
      '#collapsed' => FALSE,
    );
	
	$default = drupoof_load_conf("cobrowsing");
	$form["cobrowsing"]["enable_cobrowsing"] = array(
		'#type' => 'checkbox',
		'#title' => t('Enable Cobrowsing'),
		'#default_value' => $default,
		'#description' => t(''),
	);
	
	$form['cobrowsing']['default'] = array(
      '#type' => 'fieldset',
      '#title' => t('Element by default'),
      '#collapsible' => TRUE,
      '#collapsed' => FALSE,
    );
	
    foreach (drupoof_get_options() as $key => $value) {
    	drupoof_share_option_admin($form, $key, $value);
    }
	
	$form["submit"] = array(
		'#type' => 'submit',
		'#value' => t("Save"),
	);*/
	
	$form["key"] = array(
		'#type' => 'textfield',
		'#title' => t('Apideo Key'),
		'#default_value' => $default,
		'#description' => t('Apideo key. You can get it on <a href="http://www.apideo.com">Apideo</a>.'),
		//'#attributes' => array('onkeyup' => "checkApideoKeyInit()")
	);
	
	return $form;
}

function drupoof_share_option_admin(&$form, $key, $element) {
	$default = drupoof_load_conf($key);
	$type = $element["type"];
	if(!$type)
		$type = 'checkbox';
	$form["cobrowsing"]["default"]['drupoof_'.$key] = array(
		'#type' => $type,
		'#title' => t($element["title"]),
		'#default_value' => $default,
		'#description' => t($element["description"]),
	);
}

function drupoof_admin_validate($form, &$form_state) {
}

function drupoof_admin_submit($form, &$form_state) {
	$values = $form_state['values'];
	drupoof_conf_update("key", $values["key"]);
	drupoof_conf_update("cobrowsing", $values["enable_cobrowsing"]);
	foreach (drupoof_get_options() as $key => $value) {
    	drupoof_conf_update($key, $values["drupoof_".$key]);
    }

	drupal_set_message(t('Api contact your customer configuration has been saved.'));
	$form_state['redirect'] = 'admin/settings/drupoof';
	return;
}

function drupoof_info_list() {
	$results = drupoof_load_all_info();
	$return = '<table>'."\n";
	$return .= '<tr>'
				.'<th>'.t('Name').'</th>'."\n"
				.'<th>'.t('Key').'</th>'."\n"
				.'<th>'.t('Type').'</th>'."\n"
				.'<th>'.t('Validate').'</th>'."\n"
				.'<th></th>'."\n"
				.'<th></th>'."\n"
			.'</tr>';
	if($results) {
		foreach ($results as $result) {
			$return .= '<tr>'
						.'<td>'.$result->name.'</td>'."\n"
						.'<td>'.$result->key.'</td>'."\n"
						.'<td>'.$result->type.'</td>'."\n"
						.'<td>'.$result->validate.'</td>'."\n"
						.'<td>'.l(t('Edit'), 'admin/settings/drupoof/additional/edit/'.$result->id).'</td>'."\n"
						.'<td>'.l(t('Delete'), 'admin/settings/drupoof/additional/delete/'.$result->id).'</td>'."\n"
					.'</tr>';
		}
	}
	$return .= '</table>';
	return $return;
}

function drupoof_info_add($form_state, $type, $id = null) {
	if($id) {
		$values = drupoof_load_id_info($id);
		$form["#insert"] = $id;
	}
	else {
		$values = null;
		$form["#insert"] = "";
	}
		
	$form["key"] = array(
		'#type' => 'textfield',
		'#title' => t('Key'),
		'#default_value' => $values->key,
		'#description' => t(''),
	);
	$form["name"] = array(
		'#type' => 'textfield',
		'#title' => t('Name'),
		'#default_value' => $values->name,
		'#description' => t(''),
	);

	$types = array('text' => t('Text'),
					'link' => t('Link'),
					'number' => t('Number'),
					'date' => t('Date'));
	$form["type"] = array(
		'#type' => 'select',
		'#title' => t('Type'),
		'#options' => $types,
		'#default_value' => $values->type,
		'#description' => t(''),
	);
	
	$validates = array('session' => 'Session',
					'page' => 'Page',);

	$form["validate"] = array(
		'#type' => 'radios',
		'#title' => t('Validate'),
		'#options' => $validates,
		'#default_value' => ($values->validate?$values->validate:"session"),
		'#description' => t(''),
	);
	
	$form["submit"] = array(
		'#type' => 'submit',
		'#value' => t("Save"),
	);
	
	$form['cancel'] = array(
		'#type' => 'markup',
		'#value' => l(t('Cancel'), 'admin/settings/drupoof/additional')
	);
			
	return $form;
}

function drupoof_info_add_validate($form, &$form_state) {
	$item = $form_state['values'];
	$value = $form["#insert"];
	$and = "";
	if($value)
		$and = " AND id != ".$value;
	if (preg_match('/[^a-z0-9_]/', $item['key'])) {
		form_set_error('key', t('The key may only consist of lowercase letters, numbers, and hyphens.'));
	}
	if (strlen($item['key']) > 25) {
		form_set_error('key', format_plural(25, "The key can't be longer than 1 character.", "The key can't be longer than @count characters."));
	}
    // We will add 'menu-' to the menu name to help avoid name-space conflicts.
    $count = db_fetch_object(db_query("SELECT COUNT(`id`) count FROM {drupoof_info} WHERE `key` = '%s'".$and, $item['key']));
   	$count = $count->count;
    if(($count > 0 && !$value) || ($count > 1 && $value)) {
		form_set_error('key', t('The key already exists.'));
    }
}

function drupoof_info_add_submit($form, &$form_state) {
	$values = $form_state['values'];
	drupoof_info_update($values["key"], $values["type"], $values["name"], $values["validate"], $form["#insert"]);
	$form_state['redirect'] = 'admin/settings/drupoof/additional';
	return;
}

/**
 * Menu callback; check access and get a confirm form for deletion of a custom menu.
 */
function drupoof_info_delete(&$form_state, $id) {
  $form['#info_id'] = $id;
  $value = drupoof_load_id_info($id);
  $caption = '<p>'. t('This action cannot be undone.') .'</p>';
  return confirm_form($form, t('Are you sure you want to delete: ').$value->name.'?', 'admin/settings/drupoof/additional', $caption, t('Delete'));
}

/**
 * Delete a custom menu and all items in it.
 */
function drupoof_info_delete_submit($form, &$form_state) {
  $id = $form['#info_id'];
  $form_state['redirect'] = 'admin/settings/drupoof/additional';
  db_query("DELETE FROM {drupoof_info} WHERE id = '%d'", $id);
  db_query("DELETE FROM {drupoof_info_node} WHERE drupoof_info_id = '%d'", $id);
  cache_clear_all();
  drupal_set_message(t('The additional info has been deleted.'));
}

?>