<?php 
# get correct id for plugin
$thisfile = basename(__FILE__, ".php");
$xml_components = null;
$c_path = GSDATAOTHERPATH.'/XML_components/';

# register plugin
register_plugin(
	$thisfile,
	'Nonix XML Components',
	'1.0',
	'Nonix',
	'http://nonix.ru/',
	'Custom XML components for Your templates. 1) install plugin; 2) Log in into admin panel and push tab "XML Components"; 3) Create or modify Your XML component (which is simple XML file); 4) Push "Save" button; 5) Use it in Your template via return_xml_component(&lt;name&gt;) function. This function returns simple string, not XML object!',
	'admin',
	'XML_components_configure'
);

if ($_POST['mode']){
	if ($_POST['mode'] == 'getComponentsList'){
		print get_components_list();
	}
	if ($_POST['mode'] == 'getComponentContent'){
		print return_xml_component($_POST['name']);
	}
	if ($_POST['mode'] == 'saveComponent'){
		print save_component($_POST['name'], $_POST['val']);
	}
	if ($_POST['mode'] == 'saveEmptyComponent'){
		print save_component($_POST['name'], '<?xml version="1.0" ?>');
	}
	exit;
}

add_action('nav-tab', 'createSideMenu', array($thisfile, 'XML Components'));

function XML_components_configure(){
	global $c_path;
	if (!is_dir($c_path)) mkdir($c_path);
	print file_get_contents(GSPLUGINPATH.'XML_components/template.html');
}

function return_xml_component($name){
	global $c_path;
	$name = basename($name);
	return file_get_contents($c_path.$name.'.xml');
}

function get_components_list(){
	global $c_path;
	$files = array_diff(scandir($c_path), array('..', '.'));
	$out = [];
	foreach ($files as $file) {
		$path_info = pathinfo($file);
		if ($path_info['extension'] == 'xml'){
			$out[] = $path_info['filename'];
		}
	}
	return json_encode($out);
}

function save_component($name, $value){
	global $c_path;
	file_put_contents($c_path.$name.'.xml', $value);
	$res = [];
	$res['status'] = 'ok';
	return json_encode($res);
}


?>