<?php
class Tiempo
{
	private $CI;
	
	public function __construct()
	{
		$this->CI =& get_instance();
	}

	function transcurrido()
	{
		$this->CI =&get_instance();
		
		//~ $this->CI->session->sess_destroy();  // Para borrar sesión en ocasiones que en las que no querramos borrar todos los datos del navegador
		
		// Si estamos logueados verificamos si el tiempo de la sesión ya ha expirado
		if(isset($this->CI->session->userdata['logged_in'])){
			
			$user_id = $this->CI->session->userdata['logged_in']['id'];
		
			// Control de tiempo de sesión
			//~ print_r($this->config);
			//~ echo $this->CI->config->item('sess_time_to_update');
			
			$fechaGuardada = $this->CI->session->userdata['logged_in']['time'];  // Variable de sesión con la hora de inicio
			$tiempo_limite = $this->CI->config->item('sess_time_to_update');  // Variable global de configuración para actualizar id de sesión
			$fecha_actual = date('Y-m-d H:i:s');
			$tiempo_transcurrido = (strtotime($fecha_actual)-strtotime($fechaGuardada));  // Tiempo transcurrido
			// FORMA ANTERIOR E IMPRECISA
			//~ $ahora = time();  // Hora actual
			//~ $tiempo_transcurrido = ($ahora-$fechaGuardada);  // Tiempo transcurrido

			// Comparamos el tiempo transcurrido  
			if($tiempo_transcurrido >= $tiempo_limite)
			{
				
				// Si el tiempo de la sesión ha alcanzado o excedido el límite configurado
				//~ if($this->CI->db->simple_query("DELETE FROM user_sessions WHERE user_id=".$user_id)){
				if($this->CI->db->simple_query("UPDATE user_sessions SET status=0, d_update='".$fecha_actual."' WHERE user_id=".$user_id)){
					
					// Destruimos la sesión y devolvemos a la página de inicio
					$this->CI->session->sess_destroy();
					//~ echo "<script>alert('El tiempo de su sesión a caducado, inicie sesión nuevamente...');</script>";
					//~ echo "<script>window.location.href = '/';</script>";
					
				}else{
					
					echo "Error en consulta";
					exit();
					
				}				
				
			}else{
				
				// Si aún no ha expirado el tiempo de la sesión actualizamos la hora de la sesión 
				$this->CI->session->userdata['logged_in']['time'] = $fecha_actual;
				
			}
			
		}
		
	}
	
}
/*
/end hooks/home.php
*/
