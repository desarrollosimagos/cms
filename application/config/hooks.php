<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| Hooks
| -------------------------------------------------------------------------
| This file lets you define "hooks" to extend CI without hacking the core
| files.  Please see the user guide for info:
|
|	https://codeigniter.com/user_guide/general/hooks.html
|
*/

// Hook de idiomas		
$hook['post_controller_constructor'] = array(
    'class'    => 'LanguageLoader',
    'function' => 'initialize',
    'filename' => 'LanguageLoader.php',
    'filepath' => 'hooks'
);

//~ // Hook de tiempos
//~ $hook['post_controller'][] = array(
	//~ 'class'    => 'Tiempo',
	//~ 'function' => 'transcurrido',
	//~ 'filename' => 'Tiempo.php',
	//~ 'filepath' => 'hooks'
//~ );

// Hook de accesos
$hook['post_controller'] = array(
	'class'    => 'Acceso',
	'function' => 'identificado',
	'filename' => 'Acceso.php',
	'filepath' => 'hooks'
);
