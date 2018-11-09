<?php
class Acceso
{
	private $controladores = array();  // Variable que contendrá la lista de controladores
	private $CI;
	
	public function __construct()
	{
		$this->CI =& get_instance();
	}

	function identificado()
	{
		$this->CI =&get_instance();
		$this->listar_controladores("application/controllers/");
		$controllersprivados = $this->controladores;  // Controladores restringidos sin logueo
		//~ print_r($controllersprivados);
		$controllerspermitidos = array();  // Controladores permitidos para el usuario logueado
		$accionespermitidas = array();  // Ids de las acciones (módulos) permitidos para el usuario logueado
		$rutaspermitidas = array();  // Rutas permitidas para el usuario logueado
		
		//~ $this->CI->session->sess_destroy();  // Para borrar sesión en ocasiones que en las que no querramos borrar todos los datos del navegador
		
		// Si estamos logueados e intenamos volver al login o admin nos redirige al home
		if(isset($this->CI->session->userdata['logged_in']) && ($this->CI->router->method == 'login' || $this->CI->router->method == 'admin')){
			redirect('home');
		}
		
		// Si no estamos logueados e intentamos acceder a un controlador restringido nos redirige al login
		if(!isset($this->CI->session->userdata['logged_in']) && $this->CI->router->method != 'login' && in_array($this->CI->router->class, $controllersprivados)){
			redirect('login');
		}
		
		// Si no estamos logueados como cliente e intentamos acceder al área de perfil
		if(!isset($this->CI->session->userdata['logged_in_public']) && $this->CI->router->method == 'public_perfil'){
			redirect('public');
		}
		
		// Si estamos logueados validamos los controladores y métodos permitidos según el perfil del usuario
		if(isset($this->CI->session->userdata['logged_in']) && ($this->CI->router->method != 'login' || $this->CI->router->method != 'admin')){
			//~ print_r($this->CI->session->userdata('logged_in'));echo "<br>";
			// Recorrido de los datos del usuario
			foreach($this->CI->session->userdata('logged_in') as $clave => $userdata){
				
				//Incluimos por defecto el controlador Welcome
				$controllerspermitidos[] = 'Welcome';
				
				if($clave == "acciones"){
					foreach($userdata as $accion){
						// Si el usuario no es administrador capturamos los datos de la acción haciendo referencia con el indice 0,
						// de lo contrario no será necesario indicar ningún indice
						//if($this->CI->session->userdata('logged_in')['admin'] == 0){
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
					foreach($userdata as $franquicias){
						//~ foreach($franquicias as $franquicia){
							//~ echo $franquicia->name;
						//~ }
					}
				}else{
					//~ print_r($userdata);
					//~ echo "<br>";
				}
				
			}
			//~ Si ingresamos en un controlador al que no tenemos acceso
			if(!in_array($this->CI->router->class, $controllerspermitidos)){
				//~ echo "Acceso denegado...";
				//~ exit();
				redirect('home');
			}
			
		}
		
	}
	
	// Método público para obterner una lista de controladores
    function listar_controladores($ruta)
    {
        // abrir un directorio y listarlo recursivo
        if (is_dir($ruta)) {
            if ($dh = opendir($ruta)) {
                while (($file = readdir($dh)) !== false) {
					//esta línea la utilizaríamos si queremos listar todo lo que hay en el directorio
					//mostraría tanto archivos como directorios
                    if ($file!="." && $file!=".."){
                        $controlador = str_replace('.php', '', $file);
                        $controllersbase = array('Welcome', 'CLogin', 'CUserPublic', 'CMessages', 'CMigrations', 'CDolarToday');  // Controladores a ignorar
                        if(!in_array($controlador, $controllersbase) && $controlador != 'index.html'){
							$this->controladores[] = $controlador;
						}
                    }
                }
                closedir($dh);
            }
        }else{
            echo "<br>No es ruta valida";
        }
        return $this->controladores;
    }
}
/*
/end hooks/home.php
*/
