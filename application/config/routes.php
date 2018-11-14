<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/
$route['default_controller'] = 'welcome';
$route['detail_events'] = 'Welcome/detail_events';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

$route['login'] = 'CLogin/login/';
$route['logout'] = 'CLogin/logout/';
$route['home'] = 'Home/home/';
$route['admin'] = 'Welcome/admin/';

/* Perfiles */
$route['profile'] = 'CPerfil';
$route['profile/register'] = 'CPerfil/register';
$route['profile/edit/(:num)'] = 'CPerfil/edit/$1';
$route['profile/delete/(:num)'] = 'CPerfil/delete/$1';
$route['profileuser'] = 'CProfileUser';
/*   Users */
$route['users'] = 'CUser';
$route['users/register'] = 'CUser/register';
$route['users/register_public'] = 'CUserPublic/register';
$route['users/add_public'] = 'CUserPublic/add';
$route['users/new_passwd'] = 'CUserPublic/new_password';
$route['users/send_mail_change'] = 'CUserPublic/send_mail_change';
$route['users/change_password'] = 'CUserPublic/change_password';
$route['users/edit/(:num)'] = 'CUser/edit/$1';
$route['users/change_passwd'] = 'CChangePasswd/index';
$route['users/update_passwd'] = 'CChangePasswd/update_passwd';
$route['users/update_session'] = 'CUser/transcurrido';
$route['confirm_mail'] = 'CUserPublic/validar_mail';
$route['update_password'] = 'CUserPublic/update_password';
/*   Menús */
$route['menus'] = 'CMenus';
$route['menus/register'] = 'CMenus/register';
$route['menus/edit/(:num)'] = 'CMenus/edit/$1';
$route['menus/delete/(:num)'] = 'CMenus/delete/$1';
/*   Submenús */
$route['submenus'] = 'CSubMenus';
$route['submenus/register'] = 'CSubMenus/register';
$route['submenus/edit/(:num)'] = 'CSubMenus/edit/$1';
$route['submenus/delete/(:num)'] = 'CSubMenus/delete/$1';
/*   Acciones */
$route['actions'] = 'CAcciones';
$route['actions/register'] = 'CAcciones/register';
$route['actions/edit/(:num)'] = 'CAcciones/edit/$1';
$route['actions/delete/(:num)'] = 'CAcciones/delete/$1';
/*   Transacciones */
$route['transactions'] = 'CFondoPersonal';
$route['transactions/register'] = 'CFondoPersonal/register';
$route['transactions/edit/(:num)'] = 'CFondoPersonal/edit/$1';
$route['transactions/delete/(:num)'] = 'CFondoPersonal/delete/$1';
$route['transactions/validar'] = 'CFondoPersonal/validar_transaccion';
$route['transactions_json'] = 'CFondoPersonal/ajax_transactions';
$route['import_lb'] = 'CImport';
$route['import_lb/check_api_account/(:num)'] = 'CImport/check_api_account/$1';
$route['import_lb/edit'] = 'CImport/edit';
/*   Cuentas */
$route['accounts'] = 'CCuentas';
$route['accounts/register'] = 'CCuentas/register';
$route['accounts/view/(:num)'] = 'CCuentas/view/$1';
$route['accounts/edit/(:num)'] = 'CCuentas/edit/$1';
$route['accounts/delete/(:num)'] = 'CCuentas/delete/$1';
$route['accounts/search'] = 'CCuentas/seeker';
/*   Resumen */
$route['dashboard'] = 'CResumen';
$route['dashboard/register'] = 'CResumen/register';
$route['dashboard/edit/(:num)'] = 'CResumen/edit/$1';
$route['dashboard/delete/(:num)'] = 'CResumen/delete/$1';
$route['dashboard/fondos_json'] = 'CResumen/fondos_json';
$route['dashboard/transactions_json_columns'] = 'CResumen/load_columns_transactions';
$route['dashboard/transactions_json_rows'] = 'CResumen/load_rows_transactions';
$route['dashboard/transactions_json'] = 'CResumen/ajax_transactions';
/*   Monedas */
$route['coins'] = 'CCoins';
$route['coins/register'] = 'CCoins/register';
$route['coins/edit/(:num)'] = 'CCoins/edit/$1';
$route['coins/delete/(:num)'] = 'CCoins/delete/$1';
/*   Asociaciones */
$route['relate_users'] = 'CRelateUsers';
$route['relate_users/register'] = 'CRelateUsers/register';
$route['relate_users/edit/(:num)'] = 'CRelateUsers/edit/$1';
$route['relate_users/delete/(:num)'] = 'CRelateUsers/delete/$1';
/* Grupos de Usuarios */
$route['user_groups'] = 'CUserGroups';
$route['user_groups/register'] = 'CUserGroups/register';
$route['user_groups/edit/(:num)'] = 'CUserGroups/edit/$1';
$route['user_groups/delete/(:num)'] = 'CUserGroups/delete/$1';
/*   Proyectos */
$route['events'] = 'CProjects';
$route['events/register'] = 'CProjects/register';
$route['events/view/(:num)'] = 'CProjects/view/$1';
$route['events/edit/(:num)'] = 'CProjects/edit/$1';
$route['events/delete/(:num)'] = 'CProjects/delete/$1';
$route['events/search'] = 'CProjects/seeker';
$route['events/inscription'] = 'CInscription/register';
$route['share_profit'] = 'CShareProfit';
$route['share_profit/share'] = 'CShareProfit/share';
/*   Público */
$route['start'] = 'Welcome/start';
$route['possibilities'] = 'Welcome/possibilities';
$route['investments'] = 'Welcome/investments';
$route['investments/detail/(:num)'] = 'Welcome/detail_projects/$1';
$route['contacts'] = 'Welcome/contacts';

/*   Bitácora */
$route['bitacora'] = 'CBitacora/index';
$route['bitacora/fondos_json'] = 'CBitacora/ajax_bitacora';

/*   API DolarToday */
$route['dolarvef'] = 'CDolarToday/index';


/*   Migraciones */
$route['migrar'] = 'CMigrations';

/*assets*/
$route['assets/(:any)'] = 'assets/$1';
