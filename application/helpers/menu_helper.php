<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}
if (!function_exists('menu')) {

    //~ function menu($datos_sesion) {
    function menu() {
        $ci = & get_instance();
        $controllerspermitidos = array();  // Controladores permitidos para el usuario logueado
		$accionespermitidas = array();  // Ids de las acciones (módulos) permitidos para el usuario logueado
		$rutaspermitidas = array();  // Rutas permitidas para el usuario logueado
		
		// Si estamos logueados validamos los controladores y métodos permitidos según el perfil y usuario
		if(isset($ci->session->userdata['logged_in'])){
			// Recorrido de los datos del usuario
			foreach($ci->session->userdata('logged_in') as $clave => $userdata){
				if($clave == "acciones"){
					foreach($userdata as $accion){
						// Si el usuario no es administrador capturamos los datos de la acción haciendo referencia con el indice 0,
						// de lo contrario no será necesario indicar ningún indice
						//if($ci->session->userdata('logged_in')['admin'] == 0){
							$controllerspermitidos[] = $accion[0]->class;
							$accionespermitidas[] = $accion[0]->id;
							$rutaspermitidas[] = $accion[0]->route;
						/*}else{
							$controllerspermitidos[] = $accion->class;
							$accionespermitidas[] = $accion->id;
							$rutaspermitidas[] = $accion->route;
						}*/
					}
				}else if($clave == "permisos"){
					foreach($userdata as $permiso){
						$controllerspermitidos[] = $permiso[0]->class;
						$accionespermitidas[] = $permiso[0]->id;
						$rutaspermitidas[] = $permiso[0]->route;
					}
				}else if($clave == "franquicias"){
					//~ foreach($userdata as $franquicias){
						//~ foreach($franquicias as $franquicia){
							//~ echo $franquicia->name;
						//~ }
					//~ }
				}else if($clave == "servicios"){
					//~ foreach($userdata as $servicios){
						//~ foreach($servicios as $servicio){
							//~ echo $servicio->name;
						//~ }
					//~ }
				}else{
					//~ echo $clave." - ";
					//~ print_r($userdata);
				}				
			}
			
		}
		
        ?>

		<!-- Cargamos los menús y submenús correspondientes al perfil -->
		<?php 
		// Imprimiendo los menús
		
		// Primero transformamos los objetos del arreglo de sesión de menus en elementos de tipo array 
		// valiendonos de json_decode y json_encode. Vale destacar que en el segundo parámetro de json_decode 
		// es donde especificamos con el argumento true que debe devolver los datos como arreglo. Si se deja vacío o en false
		// el segundo parámetro de json_decode, nos devuelve los datos como objetos.
		$array = json_decode( json_encode( $ci->session->userdata('logged_in')['menus'] ), true );
		
		$aux = array();
		
		// Armamos un arreglo con todas las posiciones del los distintos elementos del menú	
		foreach($array as $modulo){
			$aux[] = $modulo[0]['order'];
		}
		
		//~ print_r($aux);
		//~ 
		//~ echo count($aux);
		
		if(count($aux) > 0){
		
			// Usando el arreglo de posiciones $aux, reordenamos el menú con la función de php array_multisort()
			array_multisort($aux, SORT_ASC, $array);
			
			// Volvemos a transformar el menú en un objeto
			$array = json_decode( json_encode( $array ), false );
			
			//~ foreach($ci->session->userdata('logged_in')['menus'] as $menus){
			foreach($array as $menus){		
				
				foreach($menus as $menu){
					//~ print_r($menu);
					//~ exit();
		?>
				<li class="<?php echo str_replace(" ", "_", $menu->name); ?> menu">
					<?php 
					if($menu->route != ""){
						echo "<a href='".base_url().$menu->route."'><i class='".$menu->logo."'></i> <span class='nav-label'>".$ci->lang->line($menu->name)."</span></a>";
					}else{
						echo "<a ><i class='".$menu->logo."'></i> <span class='nav-label'>".$ci->lang->line($menu->name)."</span><span class='fa arrow'></span></a>";
					}
					
					// Verificamos si hay submenús para el menú
					$num_submenus = 0;  // Contador de submenús
					foreach($ci->session->userdata('logged_in')['submenus'] as $submenus){
						foreach($submenus as $submenu){
							if($submenu->menu_id == $menu->id){
								$num_submenus += 1;
							}
						}
					}
					if($num_submenus > 0){
						echo "<ul class='nav nav-second-level collapse'>";
						// Imprimiendo los submenús
						foreach($ci->session->userdata('logged_in')['submenus'] as $submenus){
							foreach($submenus as $submenu){
								if($submenu->menu_id == $menu->id){
									echo "<li class='".str_replace(" ", "_", $submenu->name)." submenu'><a href='".base_url().$submenu->route."'>".$ci->lang->line($submenu->name)."</a></li>";
								}
							}
						}
						echo "</ul>";
					}
					?>
				</li>
		<?php
				}
			}
		}
		?>
		<!-- Cargamos los menús y submenús correspondientes al perfil -->

        <?php
    }

}

if (!function_exists('validar_acciones')) {
	// Método para validar la visualización de iconos y botones de registro, edición y borrado
    function validar_acciones(){
		$ci = & get_instance();
		
		// Si estamos logueados validamos los controladores y métodos permitidos según el perfil y usuario
		if(isset($ci->session->userdata['logged_in'])){
			$id_user = $ci->session->userdata('logged_in')['id'];
			$id_profile = $ci->session->userdata('logged_in')['profile_id'];
			// Recorrido de los datos del usuario
			foreach($ci->session->userdata('logged_in') as $clave => $userdata){
				if($clave == "acciones"){
					foreach($userdata as $accion){
						// Si el usuario no es administrador capturamos los datos de la acción haciendo referencia con el indice 0,
						// de lo contrario no será necesario indicar ningún indice
						if($ci->router->class == $accion[0]->class){
							// Si estamos en el método index hacemos los respectivos bloqueos
							if($ci->router->method == 'index' || $ci->router->method == 'view'){
								// Consultamos los parámetros en base de datos
								$query_permissions = $ci->db->get_where('profile_actions', array('profile_id'=>$id_profile, 'action_id'=>$accion[0]->id));
								$permisos = $query_permissions->row()->parameter_permit;
								$crear = $permisos[0];
								$editar = $permisos[1];
								$borrar = $permisos[2];
								$validar = $permisos[3];
								
								// Bloqueamos los elementos que correspondan
								if($crear == '0'){
									echo "<script>
									 $(document).ready(function () {
										$('.btn-primary').hide();
									 });
									</script>";
								}
								if($editar == '0'){
									echo "<script>
									 $(document).ready(function () {
										$('.fa-edit').hide();
										$('.editar').hide();
									 });
									</script>";
								}
								if($borrar == '0'){
									echo "<script>
									 $(document).ready(function () {
										$('.fa-trash-o').hide();
										$('.quitar').hide();
									 });
									</script>";
								}
								if($validar == '0'){
									echo "<script>
									 $(document).ready(function () {
										$('.fa-check').hide();
										$('.validar').hide();
									 });
									</script>";
								}
							}
						}
						
					}
				}else if($clave == "permisos"){
					foreach($userdata as $permiso){
						if($ci->router->class == $permiso[0]->class){
							// Si estamos en el método index hacemos los respectivos bloqueos
							if($ci->router->method == 'index' || $ci->router->method == 'view'){
								// Consultamos los parámetros en base de datos
								$query_permissions = $ci->db->get_where('permissions', array('user_id'=>$id_user, 'action_id'=>$permiso[0]->id));
								$permisos = $query_permissions->row()->parameter_permit;
								$crear = $permisos[0];
								$editar = $permisos[1];
								$borrar = $permisos[2];
								$validar = $permisos[3];
								
								// Bloqueamos los elementos que correspondan
								if($crear == '0'){
									echo "<script>
									 $(document).ready(function () {
										$('.btn-primary').hide();
									 });
									</script>";
								}
								if($editar == '0'){
									echo "<script>
									 $(document).ready(function () {
										$('.fa-edit').hide();
										$('.editar').hide();
									 });
									</script>";
								}
								if($borrar == '0'){
									echo "<script>
									 $(document).ready(function () {
										$('.fa-trash-o').hide();
										$('.quitar').hide();
									 });
									</script>";
								}
								if($validar == '0'){
									echo "<script>
									 $(document).ready(function () {
										$('.fa-check').hide();
										$('.validar').hide();
									 });
									</script>";
								}
							}
						}
					}
				}
			}
		}
	}
}

if (!function_exists('validar_acceso_publico')) {
	// Método para validar la visualización de iconos y botones de registro, edición y borrado
    function validar_acceso_publico(){
		$ci = & get_instance();
		
		// Si estamos logueados validamos los controladores y métodos permitidos según el perfil y usuario
		if(isset($ci->session->userdata['logged_in_public'])){
			echo "<script>
			 $(document).ready(function () {
				$('li#li_inicio').hide();
				$('li#li_cerrar').show();
				$('li#li_perfil').show();
				$('span#span_perfil').text('".$ci->session->userdata['logged_in_public']['username']."');
			 });
			</script>";
		}else{
			echo "<script>
			 $(document).ready(function () {
				$('li#li_inicio').show();
				$('li#li_cerrar').hide();
				$('li#li_perfil').hide();
			 });
			</script>";
		}
	}	
}
