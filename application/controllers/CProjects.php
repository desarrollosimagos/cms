<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class CProjects extends CI_Controller {
	
	private $coin_rate;  // Para almacenar la tasa de cambio del dólar a bolívares
	
	private $coin_openexchangerates;  // Para almacenar la tasa de cambio del dólar a las distintas divisas
	
	private $coin_coinmarketcap;  // Para almacenar la tasa de cambio del dólar a las distintas divisas
	
	// Mensaje de resultado de api de dolartoday
	private $coin_rate_message = array(
		'type' => '',
		'message' => ''
	);
	
	// Mensaje de resultado de api de openexchangerates
	private $openexchangerates_message = array(
		'type' => '',
		'message' => ''
	);
	
	// Mensaje de resultado de api de coinmarketcap
	private $coinmarketcap_message = array(
		'type' => '',
		'message' => ''
	);
	
	public function __construct() {
        parent::__construct();
       
		// Load database
        $this->load->model('MProjects');
        $this->load->model('MCuentas');
        $this->load->model('MCoins');
        $this->load->model('MCoinRate');
        
        // Load coin rate
        $this->load_rate();  // Load coin rate from api
        $this->coin_rate = $this->show_rate();  // Load coin rate from database
        $this->coin_openexchangerates = $this->load_openexchangerates();  // Load rates from openexchangerates api
        $this->coin_coinmarketcap = $this->load_rates_coinmarketcap();  // Load rates from coinmarketcap api
		
    }
	
	public function index()
	{
		$this->load->view('base');
		$data['ident'] = "Inversiones";
		$data['ident_sub'] = "Inversiones";
		
		$listar = array();
		
		$proyectos = $this->MProjects->obtener();
		
		// Perfil del usuario logueado
		$perfil_id = $this->session->userdata('logged_in')['profile_id'];
		
		// Id del usuario logueado
		$user_id = $this->session->userdata('logged_in')['id'];
		
		foreach($proyectos as $proyecto ){
			
			// Proceso de búsqueda de fotos asociadas al proyecto
			$num_fotos = $this->MProjects->buscar_photos($proyecto->id);
			$num_fotos = count($num_fotos);
			
			// Proceso de búsqueda de notificaciones asociadas al proyecto
			$num_news = $this->MProjects->buscar_noticias($proyecto->id);
			$num_news = count($num_news);
			
			// Proceso de búsqueda de documentos asociados al proyecto
			$num_docs = $this->MProjects->buscar_documentos($proyecto->id);
			$num_docs = count($num_docs);
			
			// Proceso de búsqueda de lecturas recomendadas asociadas al proyecto
			$num_readings = $this->MProjects->buscar_lecturas($proyecto->id);
			$num_readings = count($num_readings);
			
			// Proceso de búsqueda de grupos de inversores asociados al proyecto
			$groups = $this->MProjects->buscar_grupos($proyecto->id);
			$groups_names = "";
			foreach($groups as $group){
				$groups_names .= $group->name.",";
			}
			$groups_names = substr($groups_names, 0, -1);
			
			// Proceso de búsqueda de transacciones asociados al proyecto para calcular el porcentaje recaudado
			$transacctions = $this->MProjects->buscar_transacciones($proyecto->id);
			if($proyecto->amount_r != null && $proyecto->amount_r > 0){
				$porcentaje = (float)$transacctions[0]->ingresos/(float)$proyecto->amount_r*100;
			}else{
				$porcentaje = "null";
			}
			
			$data_proyecto = array(
				'id' => $proyecto->id,
				'name' => $proyecto->name,
				'description' => $proyecto->description,
				'type' => $proyecto->type,
				'valor' => $proyecto->valor,
				'amount_r' => $proyecto->amount_r,
				'amount_min' => $proyecto->amount_min,
				'amount_max' => $proyecto->amount_max,
				'date' => $proyecto->date,
				'date_r' => $proyecto->date_r,
				'date_v' => $proyecto->date_v,
				'coin' => $proyecto->coin_avr." (".$proyecto->coin.")",
				'status' => $proyecto->status,
				'num_fotos' => $num_fotos,
				'num_news' => $num_news,
				'num_docs' => $num_docs,
				'num_readings' => $num_readings,
				'groups_names' => $groups_names,
				'percentage_collected' => $porcentaje
			);
			
			// Si el perfil no es de administrador ni plataforma ni gestor verificamos si el proyecto tiene transacciones asociadas al usuario logueado
			if($perfil_id != 1 && $perfil_id != 2 && $perfil_id != 5){
				// Buscamos si hay transacciones ligadas al usuario logueado y a la vez al proyecto actual
				$existencia = $this->MProjects->buscar_transacciones_user_project($user_id, $proyecto->id);
				
				if(count($existencia) > 0){
					$listar[] = $data_proyecto;
				}
			}else{
				$listar[] = $data_proyecto;
			}
			
		}
		
		// Conversión a objeto
		$listar = json_decode( json_encode( $listar ), false );
		
		//~ print_r($listar);
		
		$data['listar'] = $listar;
		
		// Filtro para cargar las vistas según el perfil del usuario logueado
		$perfil_folder = "";
		if($perfil_id == 1 || $perfil_id == 2){
			$perfil_folder = 'plataforma/';
		}else if($perfil_id == 3){
			$perfil_folder = 'inversor/';
		}else if($perfil_id == 4){
			$perfil_folder = 'asesor/';
		}else if($perfil_id == 5){
			$perfil_folder = 'gestor/';
		}else{
			redirect('login');
		}
		$this->load->view($perfil_folder.'projects/lista', $data);
		$this->load->view('footer');
	}
	
	// Método para buscar proyectos por nombre o fecha
	public function seeker(){
		
		$buscar = $this->input->post('search');
		
		$listar = array();
		
		$proyectos = $this->MProjects->obtener_filtrado($buscar);
		
		// Perfil del usuario logueado
		$perfil_id = $this->session->userdata('logged_in')['profile_id'];
		
		// Id del usuario logueado
		$user_id = $this->session->userdata('logged_in')['id'];
		
		// Consutamos los datos extra y construimos la data a imprimir
		foreach($proyectos as $proyecto ){
			
			// Proceso de búsqueda de grupos de inversores asociados al proyecto
			$groups = $this->MProjects->buscar_grupos($proyecto->id);
			$groups_names = "";
			foreach($groups as $group){
				$groups_names .= $group->name.",";
			}
			$groups_names = substr($groups_names, 0, -1);
			
			// Proceso de búsqueda de transacciones asociados al proyecto para calcular el porcentaje recaudado
			$transacctions = $this->MProjects->buscar_transacciones($proyecto->id);
			if($proyecto->amount_r != null && $proyecto->amount_r > 0){
				$porcentaje = (float)$transacctions[0]->ingresos/(float)$proyecto->amount_r*100;
			}else{
				$porcentaje = "null";
			}
			
			$data_proyecto = array(
				'id' => $proyecto->id,
				'name' => $proyecto->name,
				'description' => $proyecto->description,
				'type' => $proyecto->type,
				'valor' => $proyecto->valor,
				'amount_r' => $proyecto->amount_r,
				'amount_min' => $proyecto->amount_min,
				'amount_max' => $proyecto->amount_max,
				'date' => $proyecto->date,
				'date_r' => $proyecto->date_r,
				'date_v' => $proyecto->date_v,
				'coin' => $proyecto->coin_avr." (".$proyecto->coin.")",
				'status' => $proyecto->status,
				'groups_names' => $groups_names,
				'percentage_collected' => $porcentaje
			);
			
			// Si el perfil no es de administrador ni plataforma ni gestor verificamos si el proyecto tiene transacciones asociadas al usuario logueado
			if($perfil_id != 1 && $perfil_id != 2 && $perfil_id != 5){
				// Buscamos si hay transacciones ligadas al usuario logueado y a la vez al proyecto actual
				$existencia = $this->MProjects->buscar_transacciones_user_project($user_id, $proyecto->id);
				
				if(count($existencia) > 0){
					$listar[] = $data_proyecto;
				}
			}else{
				$listar[] = $data_proyecto;
			}
			
		}
		
		// Conversión a objeto
		$listar = json_decode( json_encode( $listar ), false );
		
		$data['listar'] = $listar;
		
		foreach($data['listar'] as $proyecto){
		?>
		<tr class="scroll">
			<td class="project-status">
				<?php if($proyecto->status == 1) { ?>
				<span class="label label-primary"><?php echo $this->lang->line('list_status1_projects'); ?></span>
				<?php }else{ ?>
				<span class="label label-default"><?php echo $this->lang->line('list_status2_projects'); ?></span>
				<?php } ?>
			</td>
			<td class="project-title">
				<a href="<?php echo base_url() ?>projects/view/<?= $proyecto->id; ?>"><?php echo $proyecto->name; ?></a>
				<br/>
				<small>Created <?php echo $proyecto->date; ?></small>
				<br>
				<?php if($this->session->userdata('logged_in')['profile_id'] == 1 || $this->session->userdata('logged_in')['profile_id'] == 2) { ?>
				<small><?php echo $proyecto->groups_names; ?></small>
				<?php } ?>
			</td>
			<td class="project-completion">
					<small>
						<?php echo $this->lang->line('list_completed_projects'); ?>: 
						<?php 
						if($proyecto->amount_r == null){
							echo "&infin;";
							$percentage = 0;
						}else{
							if($proyecto->percentage_collected > 0){
								echo round($proyecto->percentage_collected, 2)."%";
								$percentage = round($proyecto->percentage_collected, 2);
							}else{
								echo "0%";
								$percentage = 0;
							}
						}
						?>
					</small>
					<div class="progress progress-mini">
						<div style="width: <?php echo $percentage; ?>%;" class="progress-bar"></div>
					</div>
			</td>
			<td class="project-title">
				<?php echo $proyecto->coin; ?>
			</td>
			<td class="project-actions">
				<a href="<?php echo base_url() ?>projects/view/<?= $proyecto->id; ?>" title="<?php echo $this->lang->line('list_view_projects'); ?>" class="btn btn-white btn-sm"><i class="fa fa-folder"></i> <?php echo $this->lang->line('list_view_projects'); ?> </a>
				<a href="<?php echo base_url() ?>projects/edit/<?= $proyecto->id; ?>" title="<?php echo $this->lang->line('list_edit_projects'); ?>" class="btn btn-white btn-sm"><i class="fa fa-pencil"></i> <?php echo $this->lang->line('list_edit_projects'); ?> </a>
				<a id='<?php echo $proyecto->id; ?>' title='<?php echo $this->lang->line('list_delete_projects'); ?>' class="btn btn-danger btn-sm borrar"><i class="fa fa-trash"></i> <?php echo $this->lang->line('list_delete_projects'); ?> </a>
			</td>
		</tr>
		<?php
		}
		
	}
	
	public function register()
	{
		$this->load->view('base');
		$data['ident'] = "Inversiones";
		$data['ident_sub'] = "Inversiones";
		$data['monedas'] = $this->MCoins->obtener();
		$data['project_types'] = $this->MProjects->obtenerTipos();
		
		// Filtro para cargar las vistas según el perfil del usuario logueado
		$perfil_id = $this->session->userdata('logged_in')['profile_id'];
		$perfil_folder = "";
		if($perfil_id == 1 || $perfil_id == 2){
			$perfil_folder = 'plataforma/';
		}else if($perfil_id == 3){
			$perfil_folder = 'inversor/';
		}else if($perfil_id == 4){
			$perfil_folder = 'asesor/';
		}else if($perfil_id == 5){
			$perfil_folder = 'gestor/';
		}else{
			redirect('login');
		}
		$this->load->view($perfil_folder.'projects/registrar', $data);
		$this->load->view('footer');
	}
	
	// Método para guardar un nuevo registro
    public function add() {
		
		$fecha = $this->input->post('date');
		$fecha = explode("/", $fecha);
		$fecha = $fecha[2]."-".$fecha[1]."-".$fecha[0];
		
		$fecha_r = $this->input->post('date_r');
		$fecha_r = explode("/", $fecha_r);
		$fecha_r = $fecha_r[2]."-".$fecha_r[1]."-".$fecha_r[0];
		
		$fecha_v = $this->input->post('date_v');
		$fecha_v = explode("/", $fecha_v);
		$fecha_v = $fecha_v[2]."-".$fecha_v[1]."-".$fecha_v[0];
		
		$publico = false;
		if($this->input->post('public') == "on"){
			$publico = true;
		}
		
		$datos = array(
			'name' => $this->input->post('name'),
			'description' => $this->input->post('description'),
			'type' => $this->input->post('type'),
            'valor' => $this->input->post('valor'),
            'amount_r' => $this->input->post('amount_r'),
            'amount_min' => $this->input->post('amount_min'),
            'amount_max' => $this->input->post('amount_max'),
            'date' => $fecha,
            'date_r' => $fecha_r,
            'date_v' => $fecha_v,
            'public' => $publico,
            'coin_id' => $this->input->post('coin_id'),
            'status' => 1,
            //~ 'user_id' => $this->session->userdata('logged_in')['id'],
            'd_create' => date('Y-m-d H:i:s')
        );
        
        $result = $this->MProjects->insert($datos);
        
        //~ echo $result;
        
        // Si el proyecto fue registrado satisfactoriamente registramos las photos
        if ($result != 'existe') {
			
			// Sección para el registro de las fotos en la ruta establecida para tal fin (assets/img/projects)
			$ruta = getcwd();  // Obtiene el directorio actual en donde se esta trabajando
			
			//~ // print_r($_FILES);
			$i = 0;
			
			$errors = 0;
			
			foreach($_FILES['imagen']['name'] as $imagen){
				
				if($imagen != ""){
					
					// Obtenemos la extensión
					$ext = explode(".",$imagen);
					$ext = $ext[1];
					$datos2 = array(
						'project_id' => $result,
						'photo' => "photo".($i+1)."_".$result.".".$ext,
						'd_create' => date('Y-m-d')
					);
					
					$insertar_photo = $this->MProjects->insert_photo($datos2);
					
					if (!move_uploaded_file($_FILES['imagen']['tmp_name'][$i], $ruta."/assets/img/projects/photo".($i+1)."_".$result.".".$ext)) {
						
						$errors += 1;
						
					}
					
				}
				$i++;  // Incrementamos
			}
			
			// Sección para el registro de los documentos en la ruta establecida para tal fin (assets/documents)
			$j = 0;
			
			$errors2 = 0;
			
			foreach($_FILES['documento']['name'] as $documento){
				
				if($documento != ""){
					
					// Obtenemos la extensión
					$ext = explode(".",$documento);
					$ext = $ext[1];
					$datos3 = array(
						'project_id' => $result,
						'description' => "document".($j+1)."_".$result.".".$ext,
						'd_create' => date('Y-m-d')
					);
					
					$insertar_documento = $this->MProjects->insert_document($datos3);
					
					if (!move_uploaded_file($_FILES['documento']['tmp_name'][$j], $ruta."/assets/documents/document".($j+1)."_".$result.".".$ext)) {
						
						$errors2 += 1;
						
					}
					
				}
				$j++;  // Incrementamos
			}
			
			// Sección para el registro de las lecturas recomendadas en la ruta establecida para tal fin (assets/readings)
			$k = 0;
			
			$errors3 = 0;
			
			foreach($_FILES['lectura']['name'] as $lectura){
				
				if($lectura != ""){
					
					// Obtenemos la extensión
					$ext = explode(".",$lectura);
					$ext = $ext[1];
					$datos4 = array(
						'project_id' => $result,
						'description' => "reading".($k+1)."_".$result.".".$ext,
						'd_create' => date('Y-m-d')
					);
					
					$insertar_lectura = $this->MProjects->insert_reading($datos4);
					
					if (!move_uploaded_file($_FILES['lectura']['tmp_name'][$k], $ruta."/assets/readings/reading".($k+1)."_".$result.".".$ext)) {
						
						$errors3 += 1;
						
					}
					
				}
				$k++;  // Incrementamos
			}
			
			if($errors > 0){
				
				echo '{"response":"error2"}';
				
			}else if($errors2 > 0){
				
				echo '{"response":"error3"}';
				
			}else if($errors3 > 0){
				
				echo '{"response":"error4"}';
				
			}else{
				
				echo '{"response":"ok"}';
				
			}
       
        }else{
			
			echo '{"response":"error1"}';
			
		}
    }
	
	// Método para ver detalles
    public function view() {
		
		$this->load->view('base');
		$data['ident'] = "Inversiones";
		$data['ident_sub'] = "Inversiones";
        $data['id'] = $this->uri->segment(3);
        $data['ver'] = $this->MProjects->obtenerProyecto($data['id']);
        $data['fotos_asociadas'] = $this->MProjects->obtenerFotos($data['id']);
        $data['documentos_asociados'] = $this->MProjects->obtenerDocumentos($data['id']);
        $data['lecturas_asociadas'] = $this->MProjects->obtenerLecturas($data['id']);
        $data['project_types'] = $this->MProjects->obtenerTipos();
        $data['project_transactions'] = $this->MProjects->obtenerTransacciones($data['id']);
        $data['project_transactions_coins'] = $this->fondos_json_coins($data['id']);
        $data['project_transactions_gen'] = $this->fondos_json_project($data['id']);
        $data['project_transactions_users'] = $this->fondos_json_users($data['id']);
        
		// Mensaje de la api de dolartoday
		$data['coin_rate_message'] = $this->coin_rate_message;
		
		// Mensaje de la api de openexchangerates
		$data['openexchangerates_message'] = $this->openexchangerates_message;
		
		// Mensaje de la api de coinmarketcap
		$data['coinmarketcap_message'] = $this->coinmarketcap_message;
		
		// Proceso de búsqueda de los inversores asociados al proyecto
		$investors = $this->MProjects->buscar_inversores($data['id']);
		$num_investors = count($investors);
		
		$data['investors'] = $investors;
		
		// Datos base de los inversores
		$data_investors = array();
		foreach($investors as $investor){
			$data_investors[] = array(
				'username' => $investor->username,
				'name_user' => $investor->name,
				'alias' => $investor->alias,
				'image' => $investor->image
			);
		}
		
		$data['data_investors'] = $data_investors;
		
		// Proceso de búsqueda de transacciones asociadas al proyecto para calcular el porcentaje recaudado
		$transacctions = $this->MProjects->buscar_transacciones($data['id']);
		if($data['ver'][0]->amount_r != null && $data['ver'][0]->amount_r > 0){
			$porcentaje = (float)$transacctions[0]->ingresos/(float)$data['ver'][0]->amount_r*100;
		}else{
			$porcentaje = 0;
		}
		
		$data['porcentaje_r'] = $porcentaje;
		
		
		// Obtenemos el valor en dólares de las distintas divisas
		//~ // Con el uso de @ evitamos la impresión forzosa de errores que hace file_get_contents()
		//~ $ct = @file_get_contents("https://openexchangerates.org/api/latest.json?app_id=65148900f9c2443ab8918accd8c51664");
		//~ if($ct){
			//~ $get = file_get_contents("https://openexchangerates.org/api/latest.json?app_id=65148900f9c2443ab8918accd8c51664");
			// Se decodifica la respuesta JSON
			//~ $exchangeRates = json_decode($get, true);
		//~ } else {
			//~ $get = file_get_contents("https://openexchangerates.org/api/latest.json?app_id=1d8edbe4f5d54857b1686c15befc4a85");
			// Se decodifica la respuesta JSON
			//~ $exchangeRates = json_decode($get, true);
		//~ }
		// Con el segundo argumento lo decodificamos como un arreglo multidimensional y no como un arreglo de objetos
		$exchangeRates = $this->coin_openexchangerates;
		
		// Colectando los symbolos de todas las cryptomonedas soportadas por la plataforma de coinmarketcap
		//~ $get2 = file_get_contents("https://api.coinmarketcap.com/v1/ticker/");
		//~ $exchangeRates2 = json_decode($get2, true);
		$exchangeRates2 = $this->coin_coinmarketcap;
		$valor1anycoin = 0;
		$rate = $data['ver'][0]->coin_avr;
		$rates = array();
		foreach($exchangeRates2 as $divisa){
			if ($divisa['symbol'] == $rate){
				
				// Obtenemos el valor de la cryptomoneda del usuario en dólares
				$valor1anycoin = $divisa['price_usd'];
			}
			$rates[] = $divisa['symbol'];  // Colectamos los símbolos de todas las cryptomonedas
		}
		
		// Valor de 1 dólar en bolívares
		//~ $get3 = file_get_contents("https://s3.amazonaws.com/dolartoday/data.json");
		//~ $exchangeRates3 = json_decode($get3, true);
		//~ // Con el segundo argumento lo decodificamos como un arreglo multidimensional y no como un arreglo de objetos
		//~ $valor1vef = $exchangeRates3['USD']['transferencia'];
		$valor1vef = $this->coin_rate;
		
		// Transformamos el valor de 1 dólar a la divisa correspondiente del proyecto para las posteriores conversiones de montos a dólares
		if (in_array($rate, $rates)) {
		
			$currency_project = 1/(float)$valor1anycoin;  // Tipo de moneda del proyecto visualizado
			
		} else if($rate == 'VEF') {
		
			$currency_project = $valor1vef;  // Tipo de moneda del proyecto visualizado
		
		} else {
			
			$currency_project = $exchangeRates['rates'][$rate];  // Tipo de moneda del proyecto visualizado
			
		}
		
		// Proceso de búsqueda de las cuentas asociadas al proyecto con el monto disponible de cada una
		$listar_cuentas = array();
		
		$cuentas = $this->MProjects->buscar_cuentas($data['id']);
		
		foreach($cuentas as $cuenta){
						
			// Proceso de búsqueda de grupos de inversores asociados a la cuenta
			$groups = $this->MCuentas->buscar_grupos($cuenta->id);
			$groups_names = "";
			foreach($groups as $group){
				$groups_names .= $group->name.",";
			}
			$groups_names = substr($groups_names, 0, -1);
			
			// Proceso de búsqueda de transacciones asociadas a la cuenta para calcular los montos totales y parciales
			// Suma general de la tabla 'transactions'
			$sum_transacctions = $this->MCuentas->sumar_transacciones($cuenta->id, 'transactions t');
			// Suma condicionada de la tabla 'transactions'
			$find_transactions = $this->MCuentas->buscar_transacciones($cuenta->id, 'transactions t');
			$capital_disponible_moneda_cuenta = 0;
			$capital_disponible_parcial = 0;
			$capital_disponible_moneda_proyecto = 0;
			if(count($find_transactions) > 0){
				foreach($find_transactions as $t1){
					if($t1->status == 'approved' && $t1->project_id == $data['id']){
						if($t1->type == "invest" && $t1->amount < 0){
							$capital_disponible_moneda_cuenta += 0;
						}else{
							// Si la moneda de la cuenta es el bolívar y la transacción es anterior al 20-08-2018, se hace una reconversión
							if($cuenta->coin_avr == 'VEF' && strtotime($t1->date) < strtotime("2018-08-20 00:00:00")){
								$capital_disponible_moneda_cuenta += ($t1->amount/100000);
							}else{
								$capital_disponible_moneda_cuenta += $t1->amount;
							}
						}
					}
					$relations = $this->MCuentas->buscar_transaction_relation($t1->id);
					if(count($relations) == 0){
						if($t1->type == "withdraw" || $t1->type == "deposit"){
							$capital_disponible_parcial += $t1->amount;
						}
					}
				}
			}
			
			// Conversión del monto de cada cuenta a dólares
			$currency_account = $cuenta->coin_avr;  // Tipo de moneda de la cuenta
			
			// Si el tipo de moneda de la transacción es alguna cryptomoneda (BTC, LTC, BCH, ect.) o Bolívares (VEF) hacemos la conversión usando una api más acorde
			if (in_array($currency_account, $rates)) {
				
				// Primero convertimos el valor de la cryptodivisa
				$valor1anycoin = 0;
				$i = 0;
				$rate = $currency_account;
				foreach($exchangeRates2 as $divisa){
					if ($divisa['symbol'] == $rate){
						$i+=1;
						
						// Obtenemos el valor de la cryptomoneda de la transacción en dólares
						$valor1anycoin = $divisa['price_usd'];
					}
				}
				
				$trans_usd = (float)$capital_disponible_moneda_cuenta*(float)$valor1anycoin;
				
			}else if($currency_account == 'VEF'){
				
				$trans_usd = (float)$capital_disponible_moneda_cuenta/(float)$valor1vef;
				
			}else{
				
				$trans_usd = (float)$capital_disponible_moneda_cuenta/$exchangeRates['rates'][$currency_account];
				
			}
			
			// Conversión del monto de cada cuenta a la divisa del proyecto
			$capital_disponible_moneda_proyecto = $trans_usd * $currency_project; 
			$capital_disponible_moneda_proyecto = round($capital_disponible_moneda_proyecto, $data['ver'][0]->coin_decimals);
			$capital_disponible_moneda_proyecto = $capital_disponible_moneda_proyecto." ".$data['ver'][0]->coin_symbol;
			
			$data_cuenta = array(
				'id' => $cuenta->id,
				'owner' => $cuenta->owner,
				'alias' => $cuenta->alias,
				'number' => $cuenta->number,
				'type' => $cuenta->type,
				'description' => $cuenta->description,
				'amount' => $cuenta->amount,
				'capital_disponible_moneda_cuenta' => $capital_disponible_moneda_cuenta,
				'capital_disponible_parcial' => $capital_disponible_parcial,
				'capital_disponible_moneda_proyecto' => $capital_disponible_moneda_proyecto,
				'status' => $cuenta->status,
				'coin' => $cuenta->coin,
				'coin_avr' => $cuenta->coin_avr,
				'coin_symbol' => $cuenta->coin_symbol,
				'coin_decimals' => $cuenta->coin_decimals,
				'tipo_cuenta' => $cuenta->tipo_cuenta,
				'd_create' => $cuenta->d_create,
				'groups_names' => $groups_names
			);
			
			$listar_cuentas[] = $data_cuenta;
			
		}  // Cierre del for each de cuentas
		
		// Conversión a objeto
		$listar_cuentas = json_decode( json_encode( $listar_cuentas ), false );
		
		$data['cuentas'] = $listar_cuentas;
		
		// Filtro para cargar las vistas según el perfil del usuario logueado
		$perfil_id = $this->session->userdata('logged_in')['profile_id'];
		$perfil_folder = "";
		if($perfil_id == 1 || $perfil_id == 2){
			$perfil_folder = 'plataforma/';
		}else if($perfil_id == 3){
			$perfil_folder = 'inversor/';
		}else if($perfil_id == 4){
			$perfil_folder = 'asesor/';
		}else if($perfil_id == 5){
			$perfil_folder = 'gestor/';
		}else{
			redirect('login');
		}
        $this->load->view($perfil_folder.'projects/ver', $data);
		$this->load->view('footer');
    }
	
	// Método para editar
    public function edit() {
		
		$this->load->view('base');
		$data['ident'] = "Inversiones";
		$data['ident_sub'] = "Inversiones";
        $data['id'] = $this->uri->segment(3);
        $data['editar'] = $this->MProjects->obtenerProyecto($data['id']);
        $data['monedas'] = $this->MCoins->obtener();
        $data['fotos_asociadas'] = $this->MProjects->obtenerFotos($data['id']);
        $data['documentos_asociados'] = $this->MProjects->obtenerDocumentos($data['id']);
        $data['lecturas_asociadas'] = $this->MProjects->obtenerLecturas($data['id']);
        $data['project_types'] = $this->MProjects->obtenerTipos();
        
        // Filtro para cargar las vistas según el perfil del usuario logueado
		$perfil_id = $this->session->userdata('logged_in')['profile_id'];
		$perfil_folder = "";
		if($perfil_id == 1 || $perfil_id == 2){
			$perfil_folder = 'plataforma/';
		}else if($perfil_id == 3){
			$perfil_folder = 'inversor/';
		}else if($perfil_id == 4){
			$perfil_folder = 'asesor/';
		}else if($perfil_id == 5){
			$perfil_folder = 'gestor/';
		}else{
			redirect('login');
		}
        $this->load->view($perfil_folder.'projects/editar', $data);
		$this->load->view('footer');
    }
	
	// Método para actualizar
    public function update() {
		
		$fecha = $this->input->post('date');
		$fecha = explode("/", $fecha);
		$fecha = $fecha[2]."-".$fecha[1]."-".$fecha[0];
		
		$fecha_r = $this->input->post('date_r');
		$fecha_r = explode("/", $fecha_r);
		$fecha_r = $fecha_r[2]."-".$fecha_r[1]."-".$fecha_r[0];
		
		$fecha_v = $this->input->post('date_v');
		$fecha_v = explode("/", $fecha_v);
		$fecha_v = $fecha_v[2]."-".$fecha_v[1]."-".$fecha_v[0];
		
		$publico = false;
		if($this->input->post('public') == "on"){
			$publico = true;
		}
		
		$datos = array(
			'id' => $this->input->post('id'),
			'name' => $this->input->post('name'),
			'description' => $this->input->post('description'),
			'type' => $this->input->post('type'),
            'valor' => $this->input->post('valor'),
            'amount_r' => $this->input->post('amount_r'),
            'amount_min' => $this->input->post('amount_min'),
            'amount_max' => $this->input->post('amount_max'),
            'date' => $fecha,
            'date_r' => $fecha_r,
            'date_v' => $fecha_v,
            'public' => $publico,
            'coin_id' => $this->input->post('coin_id'),
            'd_update' => date('Y-m-d H:i:s')
		);
		
        $result = $this->MProjects->update($datos);
        
        if ($result) {
			
			// Sección para el registro del archivo en la ruta establecida para tal fin (assets/img/productos)
			$ruta = getcwd();  // Obtiene el directorio actual en donde se esta trabajando
			
			//~ print_r($_FILES);
			$i = 0;  // Indice de la imágen
			
			$errors = 0;
			
			foreach($_FILES['imagen']['name'] as $imagen){
				
				if($imagen != ""){
					
					// Obtenemos la extensión
					$ext = explode(".",$imagen);
					$ext = $ext[1];
					$datos2 = array(
						'project_id' => $_POST['id'],
						'photo' => "photo".($i+1)."_".$_POST['id'].".".$ext,
						'd_create' => date('Y-m-d')
					);
					
					//~ echo "photo".($i+1)."_".$_POST['id'].".".$ext;
					$insertar_photo = $this->MProjects->insert_photo($datos2);
					
					if (!move_uploaded_file($_FILES['imagen']['tmp_name'][$i], $ruta."/assets/img/projects/photo".($i+1)."_".$_POST['id'].".".$ext)) {
						
						$errors += 1;
						
					}
					
				}
				$i++;  // Incrementamos
			}
			
			// Sección para el registro de los documentos en la ruta establecida para tal fin (assets/documents)
			$j = 0;
			
			$errors2 = 0;
			
			foreach($_FILES['documento']['name'] as $documento){
				
				if($documento != ""){
					
					// Obtenemos la extensión
					$ext = explode(".",$documento);
					$ext = $ext[1];
					$datos3 = array(
						'project_id' => $_POST['id'],
						'description' => "document".($j+1)."_".$_POST['id'].".".$ext,
						'd_create' => date('Y-m-d')
					);
					
					$insertar_documento = $this->MProjects->insert_document($datos3);
					
					if (!move_uploaded_file($_FILES['documento']['tmp_name'][$j], $ruta."/assets/documents/document".($j+1)."_".$_POST['id'].".".$ext)) {
						
						$errors2 += 1;
						
					}
					
				}
				$j++;  // Incrementamos
				
			}
			
			// Sección para el registro de las lecturas recomendadas en la ruta establecida para tal fin (assets/readings)
			$k = 0;
			
			$errors3 = 0;
			
			foreach($_FILES['lectura']['name'] as $lectura){
				
				if($lectura != ""){
					
					// Obtenemos la extensión
					$ext = explode(".",$lectura);
					$ext = $ext[1];
					$datos4 = array(
						'project_id' => $_POST['id'],
						'description' => "reading".($k+1)."_".$_POST['id'].".".$ext,
						'd_create' => date('Y-m-d')
					);
					
					$insertar_lectura = $this->MProjects->insert_reading($datos4);
					
					if (!move_uploaded_file($_FILES['lectura']['tmp_name'][$k], $ruta."/assets/readings/reading".($k+1)."_".$_POST['id'].".".$ext)) {
						
						$errors3 += 1;
						
					}
					
				}
				$k++;  // Incrementamos
				
			}
			
			if($errors > 0){
				
				echo '{"response":"error2"}';
				
			}else if($errors2 > 0){
				
				echo '{"response":"error3"}';
				
			}else if($errors3 > 0){
				
				echo '{"response":"error4"}';
				
			}else{
				
				echo '{"response":"ok"}';
				
			}
			
        }else{
			
			echo '{"response":"error1"}';
			
		}
    }
    
	// Método para eliminar
	function delete($id) {
		
		// Primero verificamos si está asociado a algún grupo
		$search_assoc = $this->MProjects->obtenerProyectoGrupo($id);
		
		if(count($search_assoc) > 0){
			
			echo '{"response":"existe"}';
			
		}else{
			
			$result = $this->MProjects->delete($id);
			
			if($result){
				
				echo '{"response":"ok"}';
				
			}else{
				
				echo '{"response":"error"}';
				
			}
			
		}
        
    }
    
    // Método que consulta las transacciones asociadas a un proyecto
    public function fondos_json_users($project_id)
    {
		
		$data_project = $this->MProjects->obtenerProyecto($project_id);  // Datos del proyecto
		
		// Obtenemos el valor en dólares de las distintas divisas
		// Con el uso de @ evitamos la impresión forzosa de errores que hace file_get_contents()
		//~ $ct = @file_get_contents("https://openexchangerates.org/api/latest.json?app_id=65148900f9c2443ab8918accd8c51664");
		//~ if($ct){
			//~ $get = file_get_contents("https://openexchangerates.org/api/latest.json?app_id=65148900f9c2443ab8918accd8c51664");
			// Se decodifica la respuesta JSON
			//~ $exchangeRates = json_decode($get, true);
		//~ } else {
			//~ $get = file_get_contents("https://openexchangerates.org/api/latest.json?app_id=1d8edbe4f5d54857b1686c15befc4a85");
			// Se decodifica la respuesta JSON
			//~ $exchangeRates = json_decode($get, true);
		//~ }
		// Con el segundo argumento lo decodificamos como un arreglo multidimensional y no como un arreglo de objetos
		$exchangeRates = $this->coin_openexchangerates;
		
		//~ // Valor de 1 btc en dólares
		//~ $get2 = file_get_contents("https://api.coinmarketcap.com/v1/ticker/");
		//~ $exchangeRates2 = json_decode($get2, true);
		//~ // Con el segundo argumento lo decodificamos como un arreglo multidimensional y no como un arreglo de objetos
		//~ $valor1btc = $exchangeRates2[0]['price_usd'];
		
		// Colectando los symbolos de todas las cryptomonedas soportadas por la plataforma de coinmarketcap
		//~ $get2 = file_get_contents("https://api.coinmarketcap.com/v1/ticker/");
		//~ $exchangeRates2 = json_decode($get2, true);
		$exchangeRates2 = $this->coin_coinmarketcap;
		$valor1anycoin = 0;
		$i = 0;
		$rate = $data_project[0]->coin_avr;
		$rates = array();
		foreach($exchangeRates2 as $divisa){
			if ($divisa['symbol'] == $rate){
				$i+=1;
				
				// Obtenemos el valor de la cryptomoneda del usuario en dólares
				$valor1anycoin = $divisa['price_usd'];
			}
			$rates[] = $divisa['symbol'];  // Colectamos los símbolos de todas las cryptomonedas
		}
		
		// Valor de 1 dólar en bolívares
		//~ $get3 = file_get_contents("https://s3.amazonaws.com/dolartoday/data.json");
		//~ $exchangeRates3 = json_decode($get3, true);
		//~ // Con el segundo argumento lo decodificamos como un arreglo multidimensional y no como un arreglo de objetos
		//~ $valor1vef = $exchangeRates3['USD']['transferencia'];
		$valor1vef = $this->coin_rate;
		
		if (in_array($data_project[0]->coin_avr, $rates)) {
		
			$currency_project = 1/(float)$valor1anycoin;  // Tipo de moneda del proyecto
			
		} else if($data_project[0]->coin_avr == 'VEF') {
		
			$currency_project = $valor1vef;  // Tipo de moneda del proyecto
		
		} else {
			
			$currency_project = $exchangeRates['rates'][$data_project[0]->coin_avr];  // Tipo de moneda del proyecto
			
		}
		
        
        $resumen_users = array();  // Para el resultado final (Listado de usuarios con sus respectivos resúmenes)
        
        $transactions = $this->MProjects->obtenerTransacciones($project_id);  // Listado de transacciones
        
        $ids_users = array();  // Para almacenar los ids de los usuarios que han registrado fondos
        
        // Colectamos los ids de los usuarios de las transacciones generales
        foreach($transactions as $fondo){
			
			if(!in_array($fondo->user_id, $ids_users)){
				$ids_users[] = $fondo->user_id;
			}
			
		}
		
		// Armamos una lista de fondos por usuario y lo almacenamos en el arreglo '$resumen_users'
		foreach($ids_users as $id_user){
			
			$resumen_user = array(
				'name' => '',
				'alias' => '',
				'username' => '',
				'capital_payback' => 0,
				'capital_invested' => 0,
				'returned_capital' => 0,
				'retirement_capital_available' => 0,
				'pending_capital' => 0,
				'pending_entry' => 0,
				'pending_exit' => 0,
				'approved_capital' => 0
			);
			
			foreach($transactions as $fondo){
				
				if($fondo->user_id == $id_user){
					
					// Conversión de cada monto a dólares
					$currency = $fondo->coin_avr;  // Tipo de moneda de la transacción
					
					// Si el tipo de moneda de la transacción es alguna cryptomoneda (BTC, LTC, BCH, ect.) o Bolívares (VEF) hacemos la conversión usando una api más acorde
					if (in_array($currency, $rates)) {
						
						// Primero convertimos el valor de la cryptodivisa
						$valor1anycoin = 0;
						$i = 0;
						$rate = $this->session->userdata('logged_in')['coin_iso'];
						foreach($exchangeRates2 as $divisa){
							if ($divisa['symbol'] == $rate){
								$i+=1;
								
								// Obtenemos el valor de la cryptomoneda de la transacción en dólares
								$valor1anycoin = $divisa['price_usd'];
							}
						}
						
						$trans_usd = (float)$fondo->amount*(float)$valor1anycoin;
						
					}else if($currency == 'VEF'){
						
						// Si la moneda de la transacción es el bolívar y la transacción es anterior al 20-08-2018, se hace una reconversión
						if(strtotime($fondo->date) < strtotime("2018-08-20 00:00:00")){
							$trans_usd = (float)($fondo->amount/100000)/(float)$valor1vef;
						}else{
							$trans_usd = (float)$fondo->amount/(float)$valor1vef;
						}
						
					}else{
						
						$trans_usd = (float)$fondo->amount/$exchangeRates['rates'][$currency];
						
					}
					
					// Si el usuario es de perfil administrador o plataforma
					if($this->session->userdata('logged_in')['profile_id'] == 1 || $this->session->userdata('logged_in')['profile_id'] == 2){
						$resumen_user['name'] = $fondo->name;
						$resumen_user['alias'] = $fondo->alias;
						$resumen_user['username'] = $fondo->username;
						if($fondo->status == 'waiting'){
							if($fondo->type == 'deposit'){
								$resumen_user['pending_capital'] += $trans_usd;
								$resumen_user['pending_entry'] += $trans_usd;
							}else if($fondo->type == 'sell'){
								$resumen_user['pending_exit'] += $trans_usd;
							}else if($fondo->type == 'withdraw'){
								$resumen_user['pending_capital'] += $trans_usd;
								$resumen_user['pending_exit'] += $trans_usd;
							}
						}
						if($fondo->status == 'approved'){
							$resumen_user['approved_capital'] += $trans_usd;
							if($fondo->type == 'invest'){
								$resumen_user['retirement_capital_available'] += $trans_usd;
							}else if($fondo->type == 'deposit'){
								$resumen_user['capital_invested'] += $trans_usd;
								$variable1 = "projects.type"; $condicional = "="; $variable2 = $data_project[0]->type; $segmento = "invest";
								$reglas = $this->MProjects->buscar_rules($variable1, $condicional, $variable2, $segmento);  // Listado de reglas
								if(count($reglas) > 0){
									if($reglas[0]->result == "true"){
										$resumen_user['retirement_capital_available'] += $trans_usd;
									}
								}
							}else if($fondo->type == 'sell'){
								$resumen_user['retirement_capital_available'] += $trans_usd;
							}else if($fondo->type == 'profit'){
								$resumen_user['returned_capital'] += $trans_usd;
								$resumen_user['retirement_capital_available'] += $trans_usd;
							}else if($fondo->type == 'expense'){
								$resumen_user['retirement_capital_available'] += $trans_usd;
							}else if($fondo->type == 'withdraw'){
								$resumen_user['retirement_capital_available'] += $trans_usd;
							}
						}
					}else{
						$resumen_user['name'] = $fondo->name;
						$resumen_user['alias'] = $fondo->alias;
						$resumen_user['username'] = $fondo->username;
						if($fondo->status == 'waiting'){
							if($fondo->type == 'deposit'){
								$resumen_user['pending_capital'] += $trans_usd;
								$resumen_user['pending_entry'] += $trans_usd;
							}else if($fondo->type == 'sell'){
								$resumen_user['pending_exit'] += $trans_usd;
							}else if($fondo->type == 'withdraw'){
								$resumen_user['pending_capital'] += $trans_usd;
								$resumen_user['pending_exit'] += $trans_usd;
							}
						}
						if($fondo->status == 'approved'){
							$resumen_user['approved_capital'] += $trans_usd;
							if($fondo->type == 'invest'){
								$resumen_user['retirement_capital_available'] += $trans_usd;
							}else if($fondo->type == 'deposit'){
								$resumen_user['capital_invested'] += $trans_usd;
								$variable1 = "projects.type"; $condicional = "="; $variable2 = $data_project[0]->type; $segmento = "invest";
								$reglas = $this->MProjects->buscar_rules($variable1, $condicional, $variable2, $segmento);  // Listado de reglas
								if(count($reglas) > 0){
									if($reglas[0]->result == "true"){
										$resumen_user['retirement_capital_available'] += $trans_usd;
									}
								}
							}else if($fondo->type == 'sell'){
								$resumen_user['retirement_capital_available'] += $trans_usd;
							}else if($fondo->type == 'profit'){
								$resumen_user['returned_capital'] += $trans_usd;
								$resumen_user['retirement_capital_available'] += $trans_usd;
							}else if($fondo->type == 'expense'){
								$resumen_user['retirement_capital_available'] += $trans_usd;
							}else if($fondo->type == 'withdraw'){
								$resumen_user['retirement_capital_available'] += $trans_usd;
							}
						}
					}
				}
				
			}  // Cierre del for each de transacciones
			
			$decimals = 2;
			if($this->session->userdata('logged_in')['coin_decimals'] != ""){
				$decimals = $data_project[0]->coin_decimals;
			}
			$symbol = $data_project[0]->coin_avr;
			
			// Conversión de los montos a la divisa del usuario
			$resumen_user['capital_invested'] *= $currency_project; 
			$resumen_user['capital_invested'] = round($resumen_user['capital_invested'], $decimals);
			$resumen_user['capital_invested'] = $resumen_user['capital_invested']." ".$symbol;
			
			$resumen_user['returned_capital'] *= $currency_project; 
			$resumen_user['returned_capital'] = round($resumen_user['returned_capital'], $decimals);
			$resumen_user['returned_capital'] = $resumen_user['returned_capital']." ".$symbol;
			
			$resumen_user['retirement_capital_available'] *= $currency_project; 
			$resumen_user['retirement_capital_available'] = round($resumen_user['retirement_capital_available'], $decimals);
			$resumen_user['retirement_capital_available'] = $resumen_user['retirement_capital_available']." ".$symbol;
			
			$resumen_user['pending_capital'] *= $currency_project; 
			$resumen_user['pending_capital'] = round($resumen_user['pending_capital'], $decimals);
			$resumen_user['pending_capital'] = $resumen_user['pending_capital']." ".$symbol;
			
			$resumen_user['pending_entry'] *= $currency_project; 
			$resumen_user['pending_entry'] = round($resumen_user['pending_entry'], $decimals);
			$resumen_user['pending_entry'] = $resumen_user['pending_entry']." ".$symbol;
			
			$resumen_user['pending_exit'] *= $currency_project; 
			$resumen_user['pending_exit'] = round($resumen_user['pending_exit'], $decimals);
			$resumen_user['pending_exit'] = $resumen_user['pending_exit']." ".$symbol;
			
			$resumen_user['approved_capital'] *= $currency_project; 
			$resumen_user['approved_capital'] = round($resumen_user['approved_capital'], $decimals);
			$resumen_user['approved_capital'] = $resumen_user['approved_capital']." ".$symbol;
			
			$resumen_users[] = $resumen_user;
			
		}
		
        return json_decode(json_encode($resumen_users), false);  // Esto retorna un arreglo de objetos
    }
    
    
	public function fondos_json_project($project_id)
    {
		
		$data_project = $this->MProjects->obtenerProyecto($project_id);  // Datos del proyecto
		
		// Obtenemos el valor en dólares de las distintas divisas
		// Con el uso de @ evitamos la impresión forzosa de errores que hace file_get_contents()
		//~ $ct = @file_get_contents("https://openexchangerates.org/api/latest.json?app_id=65148900f9c2443ab8918accd8c51664");
		//~ if($ct){
			//~ $get = file_get_contents("https://openexchangerates.org/api/latest.json?app_id=65148900f9c2443ab8918accd8c51664");
			// Se decodifica la respuesta JSON
			//~ $exchangeRates = json_decode($get, true);
		//~ } else {
			//~ $get = file_get_contents("https://openexchangerates.org/api/latest.json?app_id=1d8edbe4f5d54857b1686c15befc4a85");
			// Se decodifica la respuesta JSON
			//~ $exchangeRates = json_decode($get, true);
		//~ }
		// Con el segundo argumento lo decodificamos como un arreglo multidimensional y no como un arreglo de objetos
		$exchangeRates = $this->coin_openexchangerates;
		
		// Colectando los symbolos de todas las cryptomonedas soportadas por la plataforma de coinmarketcap
		//~ $get2 = file_get_contents("https://api.coinmarketcap.com/v1/ticker/");
		//~ $exchangeRates2 = json_decode($get2, true);
		$exchangeRates2 = $this->coin_coinmarketcap;
		$valor1anycoin = 0;
		$i = 0;
		$rate = $data_project[0]->coin_avr;
		$rates = array();
		foreach($exchangeRates2 as $divisa){
			if ($divisa['symbol'] == $rate){
				$i+=1;
				
				// Obtenemos el valor de la cryptomoneda del usuario en dólares
				$valor1anycoin = $divisa['price_usd'];
			}
			$rates[] = $divisa['symbol'];  // Colectamos los símbolos de todas las cryptomonedas
		}
		
		// Valor de 1 dólar en bolívares
		//~ $get3 = file_get_contents("https://s3.amazonaws.com/dolartoday/data.json");
		//~ $exchangeRates3 = json_decode($get3, true);
		//~ // Con el segundo argumento lo decodificamos como un arreglo multidimensional y no como un arreglo de objetos
		//~ $valor1vef = $exchangeRates3['USD']['transferencia'];
		$valor1vef = $this->coin_rate;
		
		if (in_array($data_project[0]->coin_avr, $rates)) {
		
			$currency_project = 1/(float)$valor1anycoin;  // Tipo de moneda del proyecto
			
		} else if($data_project[0]->coin_avr == 'VEF') {
		
			$currency_project = $valor1vef;  // Tipo de moneda del proyecto
		
		} else {
			
			$currency_project = $exchangeRates['rates'][$data_project[0]->coin_avr];  // Tipo de moneda del proyecto
			
		}
		
		if ($this->session->userdata('logged_in')['coin_iso'] == 'BTC') {
		
			$currency_user = 1/(float)$valor1anycoin;  // Tipo de moneda del usuario logueado
			
		} else if($this->session->userdata('logged_in')['coin_iso'] == 'VEF') {
		
			$currency_user = $valor1vef;  // Tipo de moneda del usuario logueado
		
		} else {
			
			$currency_user = $exchangeRates['rates'][$this->session->userdata('logged_in')['coin_iso']];  // Tipo de moneda del usuario logueado
			
		}
        
        $fondos_details = $this->MProjects->obtenerTransacciones($project_id);  // Listado de fondos detallados
		
		$resumen = array(
			'capital_payback' => 0,
			'capital_invested' => 0,
			'capital_invested_deposit' => 0,
			'capital_invested_sell' => 0,
			'returned_capital' => 0,
			'retirement_capital_available' => 0,
			'capital_payback_user' => 0,
			'capital_invested_user' => 0,
			'returned_capital_user' => 0,
			'expense_capital' => 0,
			'expense_capital_user' => 0,
			'retirement_capital_available_user' => 0
		);
		
		// Si el usuario es de perfil administrador o plataforma
		if($this->session->userdata('logged_in')['profile_id'] == 1 || $this->session->userdata('logged_in')['profile_id'] == 2){
			
			// CÁLCULOS DEL RESUMEN GENERAL
			
			// Capital en Proyecto
			$deposit_waiting = 0;
			$expense_waiting = 0;
			$profit_waiting = 0;
			$withdraw_waiting = 0;
			$invest_waiting = 0;
			$sell_waiting = 0;
			$deposit_approved = 0;
			$expense_approved = 0;
			$profit_approved = 0;
			$withdraw_approved = 0;
			$invest_approved = 0;
			$sell_approved = 0;
			
			foreach($fondos_details as $fondo){
					
				// Conversión de cada monto a dólares
				$currency = $fondo->coin_avr;  // Tipo de moneda de la transacción
				
				// Si el tipo de moneda de la transacción es alguna cryptomoneda (BTC, LTC, BCH, ect.) o Bolívares (VEF) hacemos la conversión usando una api más acorde
				if (in_array($currency, $rates)) {
					
					// Primero convertimos el valor de la cryptodivisa
					$valor1anycoin = 0;
					$i = 0;
					$rate = $currency;
					foreach($exchangeRates2 as $divisa){
						if ($divisa['symbol'] == $rate){
							$i+=1;
							
							// Obtenemos el valor de la cryptomoneda de la transacción en dólares
							$valor1anycoin = $divisa['price_usd'];
						}
					}
					
					$trans_usd = (float)$fondo->amount*(float)$valor1anycoin;
					
				}else if($currency == 'VEF'){
					
					// Si la moneda de la transacción es el bolívar y la transacción es anterior al 20-08-2018, se hace una reconversión
					if(strtotime($fondo->date) < strtotime("2018-08-20 00:00:00")){
						$trans_usd = (float)($fondo->amount/100000)/(float)$valor1vef;
					}else{
						$trans_usd = (float)$fondo->amount/(float)$valor1vef;
					}
					
				}else{
					
					$trans_usd = (float)$fondo->amount/$exchangeRates['rates'][$currency];
					
				}
				
				if($fondo->status == 'approved'){
					// Suma de depósitos
					if($fondo->type == 'deposit'){
						$deposit_approved += $trans_usd;
					}
					// Suma de gastos
					if($fondo->type == 'expense'){
						$expense_approved += $trans_usd;
					}
					// Suma de ganancias
					if($fondo->type == 'profit'){
						$profit_approved += $trans_usd;
					}
					// Suma de retiros
					if($fondo->type == 'withdraw'){
						$withdraw_approved += $trans_usd;
					}
					// Suma de inversiones
					if($fondo->type == 'invest'){
						if($fondo->amount > 0){
							$invest_approved += $trans_usd;
						}
					}
					// Suma de ventas
					if($fondo->type == 'sell'){
						$sell_approved += $trans_usd;
					}
				}
				
			}  // Cierre del for each de transacciones para capital en cuenta
			
			$resumen['retirement_capital_available'] += $deposit_approved + $expense_approved + $profit_approved + $withdraw_approved + $invest_approved + $sell_approved;
			$resumen['retirement_capital_available_user'] += $deposit_approved + $expense_approved + $profit_approved + $withdraw_approved + $invest_approved + $sell_approved;
			
			// Capital Invertido
			$deposit_approved = 0;
			$sell_approved = 0;
			
			foreach($fondos_details as $fondo){
					
				// Conversión de cada monto a dólares
				$currency = $fondo->coin_avr;  // Tipo de moneda de la transacción
				
				// Si la moneda de la transacción difiere de la del proyecto
				if($currency != $data_project[0]->coin_avr){
					
					// Si el tipo de moneda de la transacción es alguna cryptomoneda (BTC, LTC, BCH, ect.) o Bolívares (VEF) hacemos la conversión usando una api más acorde
					if (in_array($currency, $rates)) {
						
						// Primero convertimos el valor de la cryptodivisa
						$valor1anycoin = 0;
						$i = 0;
						$rate = $currency;
						foreach($exchangeRates2 as $divisa){
							if ($divisa['symbol'] == $rate){
								$i+=1;
								
								// Obtenemos el valor de la cryptomoneda de la transacción en dólares
								$valor1anycoin = $divisa['price_usd'];
							}
						}
						
						// Si el campo de tasa 'rate' es mayor a cero
						if((float)$fondo->rate > 0){
							$trans_usd = (float)$fondo->amount*(float)$fondo->rate;
							//~ $trans_usd *= (float)$valor1anycoin;
						}else{
							$trans_usd = (float)$fondo->amount*(float)$valor1anycoin;
						}
						
					}else if($currency == 'VEF'){
						
						// Si la moneda de la transacción es el bolívar y la transacción es anterior al 20-08-2018, se hace una reconversión
						if(strtotime($fondo->date) < strtotime("2018-08-20 00:00:00")){
							// Si el campo de tasa 'rate' es mayor a cero
							if((float)$fondo->rate > 0){
								$trans_usd = (float)($fondo->amount/100000)*(float)$fondo->rate;
								//~ $trans_usd /= (float)$valor1vef;
							}else{
								$trans_usd = (float)($fondo->amount/100000)/(float)$valor1vef;
							}
						}else{
							// Si el campo de tasa 'rate' es mayor a cero
							if((float)$fondo->rate > 0){
								$trans_usd = (float)$fondo->amount*(float)$fondo->rate;
								//~ $trans_usd /= (float)$valor1vef;
							}else{
								$trans_usd = (float)$fondo->amount/(float)$valor1vef;
							}
						}
						
					}else{
						
						// Si el campo de tasa 'rate' es mayor a cero
						if((float)$fondo->rate > 0){
							$trans_usd = (float)$fondo->amount*(float)$fondo->rate;
							//~ $trans_usd /= (float)$exchangeRates['rates'][$currency];
						}else{
							$trans_usd = (float)$fondo->amount/$exchangeRates['rates'][$currency];
						}
						
					}
					
				}else{
					
					// Si el tipo de moneda de la transacción es alguna cryptomoneda (BTC, LTC, BCH, ect.) o Bolívares (VEF) hacemos la conversión usando una api más acorde
					if (in_array($currency, $rates)) {
						
						// Primero convertimos el valor de la cryptodivisa
						$valor1anycoin = 0;
						$i = 0;
						$rate = $currency;
						foreach($exchangeRates2 as $divisa){
							if ($divisa['symbol'] == $rate){
								$i+=1;
								
								// Obtenemos el valor de la cryptomoneda de la transacción en dólares
								$valor1anycoin = $divisa['price_usd'];
							}
						}
						
						$trans_usd = (float)$fondo->amount*(float)$valor1anycoin;
						
					}else if($currency == 'VEF'){
						
						// Si la moneda de la transacción es el bolívar y la transacción es anterior al 20-08-2018, se hace una reconversión
						if(strtotime($fondo->date) < strtotime("2018-08-20 00:00:00")){
							$trans_usd = (float)($fondo->amount/100000)/(float)$valor1vef;
						}else{
							$trans_usd = (float)$fondo->amount/(float)$valor1vef;
						}
						
					}else{
						
						$trans_usd = (float)$fondo->amount/$exchangeRates['rates'][$currency];
						
					}
					
				}
				
				if($fondo->status == 'approved'){
					// Suma de depósitos
					if($fondo->type == 'invest'){
						$deposit_approved += $trans_usd;
					}
					// Suma de ventas
					if($fondo->type == 'sell'){
						$sell_approved += $trans_usd;
					}
				}
				
			}  // Cierre del for each de transacciones para capital invertido
			
			$resumen['capital_invested'] += $deposit_approved + $sell_approved;
			$resumen['capital_invested_user'] += $deposit_approved + $sell_approved;
			
			// Dividendo
			$profit_approved = 0;
			
			foreach($fondos_details as $fondo){
					
				// Conversión de cada monto a dólares
				$currency = $fondo->coin_avr;  // Tipo de moneda de la transacción
				
				// Si el tipo de moneda de la transacción es alguna cryptomoneda (BTC, LTC, BCH, ect.) o Bolívares (VEF) hacemos la conversión usando una api más acorde
				if (in_array($currency, $rates)) {
					
					// Primero convertimos el valor de la cryptodivisa
					$valor1anycoin = 0;
					$i = 0;
					$rate = $currency;
					foreach($exchangeRates2 as $divisa){
						if ($divisa['symbol'] == $rate){
							$i+=1;
							
							// Obtenemos el valor de la cryptomoneda de la transacción en dólares
							$valor1anycoin = $divisa['price_usd'];
						}
					}
					
					$trans_usd = (float)$fondo->amount*(float)$valor1anycoin;
					
				}else if($currency == 'VEF'){
					
					// Si la moneda de la transacción es el bolívar y la transacción es anterior al 20-08-2018, se hace una reconversión
					if(strtotime($fondo->date) < strtotime("2018-08-20 00:00:00")){
						$trans_usd = (float)($fondo->amount/100000)/(float)$valor1vef;
					}else{
						$trans_usd = (float)$fondo->amount/(float)$valor1vef;
					}
					
				}else{
					
					$trans_usd = (float)$fondo->amount/$exchangeRates['rates'][$currency];
					
				}
				
				if($fondo->status == 'approved'){
					// Suma de ganancias
					if($fondo->type == 'profit'){
						$profit_approved += $trans_usd;
					}
				}
				
			}  // Cierre del for each de transacciones para el dividendo
			
			$resumen['returned_capital'] += $profit_approved;
			$resumen['returned_capital_user'] += $profit_approved;
			
			// Gastos
			$expense_approved = 0;
			
			foreach($fondos_details as $fondo){
					
				// Conversión de cada monto a dólares
				$currency = $fondo->coin_avr;  // Tipo de moneda de la transacción
				
				// Si la moneda de la transacción difiere de la del proyecto
				if($currency != $data_project[0]->coin_avr){
					
					// Si el tipo de moneda de la transacción es alguna cryptomoneda (BTC, LTC, BCH, ect.) o Bolívares (VEF) hacemos la conversión usando una api más acorde
					if (in_array($currency, $rates)) {
						
						// Primero convertimos el valor de la cryptodivisa
						$valor1anycoin = 0;
						$i = 0;
						$rate = $currency;
						foreach($exchangeRates2 as $divisa){
							if ($divisa['symbol'] == $rate){
								$i+=1;
								
								// Obtenemos el valor de la cryptomoneda de la transacción en dólares
								$valor1anycoin = $divisa['price_usd'];
							}
						}
						
						// Si el campo de tasa 'rate' es mayor a cero
						if((float)$fondo->rate > 0){
							$trans_usd = (float)$fondo->amount*(float)$fondo->rate;
							//~ $trans_usd *= (float)$valor1anycoin;
						}else{
							$trans_usd = (float)$fondo->amount*(float)$valor1anycoin;
						}
						
					}else if($currency == 'VEF'){
						
						// Si la moneda de la transacción es el bolívar y la transacción es anterior al 20-08-2018, se hace una reconversión
						if(strtotime($fondo->date) < strtotime("2018-08-20 00:00:00")){
							// Si el campo de tasa 'rate' es mayor a cero
							if((float)$fondo->rate > 0){
								$trans_usd = (float)($fondo->amount/100000)*(float)$fondo->rate;
								//~ $trans_usd /= (float)$valor1vef;
							}else{
								$trans_usd = (float)($fondo->amount/100000)/(float)$valor1vef;
							}
						}else{
							// Si el campo de tasa 'rate' es mayor a cero
							if((float)$fondo->rate > 0){
								$trans_usd = (float)$fondo->amount*(float)$fondo->rate;
								//~ $trans_usd /= (float)$valor1vef;
							}else{
								$trans_usd = (float)$fondo->amount/(float)$valor1vef;
							}
						}
						
					}else{
						
						// Si el campo de tasa 'rate' es mayor a cero
						if((float)$fondo->rate > 0){
							$trans_usd = (float)$fondo->amount*(float)$fondo->rate;
							//~ $trans_usd /= (float)$exchangeRates['rates'][$currency];
						}else{
							$trans_usd = (float)$fondo->amount/$exchangeRates['rates'][$currency];
						}
						
					}
					
				}else{
					
					// Si el tipo de moneda de la transacción es alguna cryptomoneda (BTC, LTC, BCH, ect.) o Bolívares (VEF) hacemos la conversión usando una api más acorde
					if (in_array($currency, $rates)) {
						
						// Primero convertimos el valor de la cryptodivisa
						$valor1anycoin = 0;
						$i = 0;
						$rate = $currency;
						foreach($exchangeRates2 as $divisa){
							if ($divisa['symbol'] == $rate){
								$i+=1;
								
								// Obtenemos el valor de la cryptomoneda de la transacción en dólares
								$valor1anycoin = $divisa['price_usd'];
							}
						}
						
						$trans_usd = (float)$fondo->amount*(float)$valor1anycoin;
						
					}else if($currency == 'VEF'){
						
						// Si la moneda de la transacción es el bolívar y la transacción es anterior al 20-08-2018, se hace una reconversión
						if(strtotime($fondo->date) < strtotime("2018-08-20 00:00:00")){
							$trans_usd = (float)($fondo->amount/100000)/(float)$valor1vef;
						}else{
							$trans_usd = (float)$fondo->amount/(float)$valor1vef;
						}
						
					}else{
						
						$trans_usd = (float)$fondo->amount/$exchangeRates['rates'][$currency];
						
					}
					
				}
				
				if($fondo->status == 'approved'){
					// Suma de ganancias
					if($fondo->type == 'expense'){
						$expense_approved += $trans_usd;
					}
				}
				
			}  // Cierre del for each de transacciones para el dividendo
			
			$resumen['expense_capital'] += $expense_approved;
			$resumen['expense_capital_user'] += $expense_approved;
			
			// CIERRE DE CÁLCULOS DEL RESUMEN GENERAL
			
			//-----------------------------------------------------------------------------------------------------------------------------
			
			// CÁLCULOS DEL RESUMEN POR PLATAFORMA
			
			$resumen_platform = array(
				'name' => 'PLATAFORMA',
				'alias' => 'PLATAFORMA',
				'username' => 'PLATAFORMA',
				'capital_invested' => 0,
				'returned_capital' => 0,
				'retirement_capital_available' => 0,
				'capital_in_project' => 0
			);  // Para el resultado final (Resumen de montos de plataforma)
			
			// Capital Invertido
			$deposit_approved = 0;
			
			foreach($fondos_details as $fondo){
					
				// Conversión de cada monto a dólares
				$currency = $fondo->coin_avr;  // Tipo de moneda de la transacción
				
				// Si la moneda de la transacción difiere de la del proyecto
				if($currency != $data_project[0]->coin_avr){
					
					// Si el tipo de moneda de la transacción es alguna cryptomoneda (BTC, LTC, BCH, ect.) o Bolívares (VEF) hacemos la conversión usando una api más acorde
					if (in_array($currency, $rates)) {
						
						// Primero convertimos el valor de la cryptodivisa
						$valor1anycoin = 0;
						$i = 0;
						$rate = $currency;
						foreach($exchangeRates2 as $divisa){
							if ($divisa['symbol'] == $rate){
								$i+=1;
								
								// Obtenemos el valor de la cryptomoneda de la transacción en dólares
								$valor1anycoin = $divisa['price_usd'];
							}
						}
						
						// Si el campo de tasa 'rate' es mayor a cero
						if((float)$fondo->rate > 0){
							$trans_usd = (float)$fondo->amount*(float)$fondo->rate;
							//~ $trans_usd *= (float)$valor1anycoin;
						}else{
							$trans_usd = (float)$fondo->amount*(float)$valor1anycoin;
						}
						
					}else if($currency == 'VEF'){
						
						// Si la moneda de la transacción es el bolívar y la transacción es anterior al 20-08-2018, se hace una reconversión
						if(strtotime($fondo->date) < strtotime("2018-08-20 00:00:00")){
							// Si el campo de tasa 'rate' es mayor a cero
							if((float)$fondo->rate > 0){
								$trans_usd = (float)($fondo->amount/100000)*(float)$fondo->rate;
								//~ $trans_usd /= (float)$valor1vef;
							}else{
								$trans_usd = (float)($fondo->amount/100000)/(float)$valor1vef;
							}
						}else{
							// Si el campo de tasa 'rate' es mayor a cero
							if((float)$fondo->rate > 0){
								$trans_usd = (float)$fondo->amount*(float)$fondo->rate;
								//~ $trans_usd /= (float)$valor1vef;
							}else{
								$trans_usd = (float)$fondo->amount/(float)$valor1vef;
							}
						}
						
					}else{
						
						// Si el campo de tasa 'rate' es mayor a cero
						if((float)$fondo->rate > 0){
							$trans_usd = (float)$fondo->amount*(float)$fondo->rate;
							//~ $trans_usd /= (float)$exchangeRates['rates'][$currency];
						}else{
							$trans_usd = (float)$fondo->amount/$exchangeRates['rates'][$currency];
						}
						
					}
					
				}else{
					
					// Si el tipo de moneda de la transacción es alguna cryptomoneda (BTC, LTC, BCH, ect.) o Bolívares (VEF) hacemos la conversión usando una api más acorde
					if (in_array($currency, $rates)) {
						
						// Primero convertimos el valor de la cryptodivisa
						$valor1anycoin = 0;
						$i = 0;
						$rate = $currency;
						foreach($exchangeRates2 as $divisa){
							if ($divisa['symbol'] == $rate){
								$i+=1;
								
								// Obtenemos el valor de la cryptomoneda de la transacción en dólares
								$valor1anycoin = $divisa['price_usd'];
							}
						}
						
						$trans_usd = (float)$fondo->amount*(float)$valor1anycoin;
						
					}else if($currency == 'VEF'){
						
						// Si la moneda de la transacción es el bolívar y la transacción es anterior al 20-08-2018, se hace una reconversión
						if(strtotime($fondo->date) < strtotime("2018-08-20 00:00:00")){
							$trans_usd = (float)($fondo->amount/100000)/(float)$valor1vef;
						}else{
							$trans_usd = (float)$fondo->amount/(float)$valor1vef;
						}
						
					}else{
						
						$trans_usd = (float)$fondo->amount/$exchangeRates['rates'][$currency];
						
					}
					
				}
				
				// Si no tiene usuario asociado en user_id lo tratamos como transacción de PLATAFORMA
				if($fondo->status == 'approved' && $fondo->user_id == 0 && $fondo->project_id == $project_id){
					// Suma de depósitos
					if($fondo->type == 'invest'){
						$deposit_approved += $trans_usd;
					}
				}
				
			}  // Cierre del for each de transacciones para capital invertido
			
			$resumen_platform['capital_invested'] += $deposit_approved;
			
			// Dividendo
			$profit_approved = 0;
			
			foreach($fondos_details as $fondo){
					
				// Conversión de cada monto a dólares
				$currency = $fondo->coin_avr;  // Tipo de moneda de la transacción
				
				// Si el tipo de moneda de la transacción es alguna cryptomoneda (BTC, LTC, BCH, ect.) o Bolívares (VEF) hacemos la conversión usando una api más acorde
				if (in_array($currency, $rates)) {
					
					// Primero convertimos el valor de la cryptodivisa
					$valor1anycoin = 0;
					$i = 0;
					$rate = $currency;
					foreach($exchangeRates2 as $divisa){
						if ($divisa['symbol'] == $rate){
							$i+=1;
							
							// Obtenemos el valor de la cryptomoneda de la transacción en dólares
							$valor1anycoin = $divisa['price_usd'];
						}
					}
					
					$trans_usd = (float)$fondo->amount*(float)$valor1anycoin;
					
				}else if($currency == 'VEF'){
					
					// Si la moneda de la transacción es el bolívar y la transacción es anterior al 20-08-2018, se hace una reconversión
					if(strtotime($fondo->date) < strtotime("2018-08-20 00:00:00")){
						$trans_usd = (float)($fondo->amount/100000)/(float)$valor1vef;
					}else{
						$trans_usd = (float)$fondo->amount/(float)$valor1vef;
					}
					
				}else{
					
					$trans_usd = (float)$fondo->amount/$exchangeRates['rates'][$currency];
					
				}
				
				// Si no tiene usuario asociado en user_id lo tratamos como transacción de PLATAFORMA
				if($fondo->status == 'approved' && $fondo->user_id == 0 && $fondo->project_id == $project_id){
					// Suma de ganancias
					if($fondo->type == 'profit'){
						$profit_approved += $trans_usd;
					}
				}
				
			}  // Cierre del for each de transacciones para el dividendo
			
			$resumen_platform['returned_capital'] += $profit_approved;
			
			// Capital en Proyecto
			$deposit_waiting = 0;
			$expense_waiting = 0;
			$profit_waiting = 0;
			$withdraw_waiting = 0;
			$invest_waiting = 0;
			$sell_waiting = 0;
			$deposit_approved = 0;
			$expense_approved = 0;
			$profit_approved = 0;
			$withdraw_approved = 0;
			$invest_approved = 0;
			$sell_approved = 0;
			
			foreach($fondos_details as $fondo){
					
				// Conversión de cada monto a dólares
				$currency = $fondo->coin_avr;  // Tipo de moneda de la transacción
				
				// Si el tipo de moneda de la transacción es alguna cryptomoneda (BTC, LTC, BCH, ect.) o Bolívares (VEF) hacemos la conversión usando una api más acorde
				if (in_array($currency, $rates)) {
					
					// Primero convertimos el valor de la cryptodivisa
					$valor1anycoin = 0;
					$i = 0;
					$rate = $currency;
					foreach($exchangeRates2 as $divisa){
						if ($divisa['symbol'] == $rate){
							$i+=1;
							
							// Obtenemos el valor de la cryptomoneda de la transacción en dólares
							$valor1anycoin = $divisa['price_usd'];
						}
					}
					
					$trans_usd = (float)$fondo->amount*(float)$valor1anycoin;
					
				}else if($currency == 'VEF'){
					
					// Si la moneda de la transacción es el bolívar y la transacción es anterior al 20-08-2018, se hace una reconversión
					if(strtotime($fondo->date) < strtotime("2018-08-20 00:00:00")){
						$trans_usd = (float)($fondo->amount/100000)/(float)$valor1vef;
					}else{
						$trans_usd = (float)$fondo->amount/(float)$valor1vef;
					}
					
				}else{
					
					$trans_usd = (float)$fondo->amount/$exchangeRates['rates'][$currency];
					
				}
				
				// Si tiene proyecto asociado en project_id lo sumamos
				if($fondo->status == 'approved' && $fondo->user_id == 0 && $fondo->project_id == $project_id){
					// Suma de depósitos
					if($fondo->type == 'deposit'){
						$deposit_approved += $trans_usd;
					}
					// Suma de gastos
					if($fondo->type == 'expense'){
						$expense_approved += $trans_usd;
					}
					// Suma de ganancias
					if($fondo->type == 'profit'){
						$profit_approved += $trans_usd;
					}
					// Suma de retiros
					if($fondo->type == 'withdraw'){
						$withdraw_approved += $trans_usd;
					}
					// Suma de inversiones
					if($fondo->type == 'invest'){
						$invest_approved += $trans_usd;
					}
					// Suma de ventas
					if($fondo->type == 'sell'){
						$sell_approved += $trans_usd;
					}
				}
				
			}  // Cierre del for each de transacciones para el capital en proyecto
			
			$resumen_platform['capital_in_project'] = $deposit_approved + $expense_approved + $profit_approved + $withdraw_approved + $invest_approved + $sell_approved;
			
			$decimals = 2;
			if($this->session->userdata('logged_in')['coin_decimals'] != ""){
				$decimals = $this->session->userdata('logged_in')['coin_decimals'];
			}
			$symbol = $this->session->userdata('logged_in')['coin_symbol'];
			
			// Conversión de los montos a la divisa del usuario
			$resumen_platform['capital_invested'] *= $currency_user; 
			$resumen_platform['capital_invested'] = round($resumen_platform['capital_invested'], $decimals);
			$resumen_platform['capital_invested'] = $resumen_platform['capital_invested']." ".$symbol;
			
			$resumen_platform['returned_capital'] *= $currency_user; 
			$resumen_platform['returned_capital'] = round($resumen_platform['returned_capital'], $decimals);
			$resumen_platform['returned_capital'] = $resumen_platform['returned_capital']." ".$symbol;
			
			$resumen_platform['capital_in_project'] *= $currency_user; 
			$resumen_platform['capital_in_project'] = round($resumen_platform['capital_in_project'], $decimals);
			$resumen_platform['capital_in_project'] = $resumen_platform['capital_in_project']." ".$symbol;
			
			// CIERRE DE CÁLCULOS DEL RESUMEN POR PLATAFORMA
			
			//-----------------------------------------------------------------------------------------------------------------------------
			
			// CÁLCULOS DEL RESUMEN POR USUARIO
			
			$resumen_users = array();  // Para el resultado final (Listado de usuarios con sus respectivos resúmenes)
			
			$ids_users = array();  // Para almacenar los ids de los usuarios que han registrado fondos
			
			// Colectamos los ids de los usuarios de las transacciones del proyecto
			foreach($fondos_details as $fondo){
				
				if(!in_array($fondo->user_id, $ids_users)){
					if($fondo->user_id > 0){
						$ids_users[] = $fondo->user_id;
					}
				}
				
			}
			
			// Armamos una lista de fondos por usuario y lo almacenamos en el arreglo '$resumen_users'
			foreach($ids_users as $id_user){
				
				$resumen_user = array(
					'name' => '',
					'alias' => '',
					'username' => '',
					'capital_payback' => 0,
					'capital_invested' => 0,
					'returned_capital' => 0,
					'retirement_capital_available' => 0,
					'pending_entry' => 0,
					'pending_exit' => 0
				);
				
				// Capital Invertido
				$deposit_approved = 0;
				
				foreach($fondos_details as $fondo){
					
					if($fondo->user_id == $id_user){
						
						// Conversión de cada monto a dólares
						$currency = $fondo->coin_avr;  // Tipo de moneda de la transacción
						
						// Si la moneda de la transacción difiere de la del proyecto
						if($currency != $data_project[0]->coin_avr){
							
							// Si el tipo de moneda de la transacción es alguna cryptomoneda (BTC, LTC, BCH, ect.) o Bolívares (VEF) hacemos la conversión usando una api más acorde
							if (in_array($currency, $rates)) {
								
								// Primero convertimos el valor de la cryptodivisa
								$valor1anycoin = 0;
								$i = 0;
								$rate = $currency;
								foreach($exchangeRates2 as $divisa){
									if ($divisa['symbol'] == $rate){
										$i+=1;
										
										// Obtenemos el valor de la cryptomoneda de la transacción en dólares
										$valor1anycoin = $divisa['price_usd'];
									}
								}
								
								// Si el campo de tasa 'rate' es mayor a cero
								if((float)$fondo->rate > 0){
									$trans_usd = (float)$fondo->amount*(float)$fondo->rate;
									//~ $trans_usd *= (float)$valor1anycoin;
								}else{
									$trans_usd = (float)$fondo->amount*(float)$valor1anycoin;
								}
								
							}else if($currency == 'VEF'){
								
								// Si la moneda de la transacción es el bolívar y la transacción es anterior al 20-08-2018, se hace una reconversión
								if(strtotime($fondo->date) < strtotime("2018-08-20 00:00:00")){
									// Si el campo de tasa 'rate' es mayor a cero
									if((float)$fondo->rate > 0){
										$trans_usd = (float)($fondo->amount/100000)*(float)$fondo->rate;
										//~ $trans_usd /= (float)$valor1vef;
									}else{
										$trans_usd = (float)($fondo->amount/100000)/(float)$valor1vef;
									}
								}else{
									// Si el campo de tasa 'rate' es mayor a cero
									if((float)$fondo->rate > 0){
										$trans_usd = (float)$fondo->amount*(float)$fondo->rate;
										//~ $trans_usd /= (float)$valor1vef;
									}else{
										$trans_usd = (float)$fondo->amount/(float)$valor1vef;
									}
								}
								
							}else{
								
								// Si el campo de tasa 'rate' es mayor a cero
								if((float)$fondo->rate > 0){
									$trans_usd = (float)$fondo->amount*(float)$fondo->rate;
									//~ $trans_usd /= (float)$exchangeRates['rates'][$currency];
								}else{
									$trans_usd = (float)$fondo->amount/$exchangeRates['rates'][$currency];
								}
								
							}
							
						}else{
							
							// Si el tipo de moneda de la transacción es alguna cryptomoneda (BTC, LTC, BCH, ect.) o Bolívares (VEF) hacemos la conversión usando una api más acorde
							if (in_array($currency, $rates)) {
								
								// Primero convertimos el valor de la cryptodivisa
								$valor1anycoin = 0;
								$i = 0;
								$rate = $currency;
								foreach($exchangeRates2 as $divisa){
									if ($divisa['symbol'] == $rate){
										$i+=1;
										
										// Obtenemos el valor de la cryptomoneda de la transacción en dólares
										$valor1anycoin = $divisa['price_usd'];
									}
								}
								
								$trans_usd = (float)$fondo->amount*(float)$valor1anycoin;
								
							}else if($currency == 'VEF'){
								
								// Si la moneda de la transacción es el bolívar y la transacción es anterior al 20-08-2018, se hace una reconversión
								if(strtotime($fondo->date) < strtotime("2018-08-20 00:00:00")){
									$trans_usd = (float)($fondo->amount/100000)/(float)$valor1vef;
								}else{
									$trans_usd = (float)$fondo->amount/(float)$valor1vef;
								}
								
							}else{
								
								$trans_usd = (float)$fondo->amount/$exchangeRates['rates'][$currency];
								
							}
							
						}
						
						$resumen_user['name'] = $fondo->name;
						$resumen_user['alias'] = $fondo->alias;
						$resumen_user['username'] = $fondo->username;
						
						// Si tiene proyecto asociado en project_id lo sumamos
						if($fondo->status == 'approved'){
							// Suma de depósitos
							if($fondo->type == 'invest'){
								$deposit_approved += $trans_usd;
							}
						}
					}
					
				}  // Cierre del for each de transacciones para capital invertido
				
				$resumen_user['capital_invested'] += $deposit_approved;
				
				// Dividendo
				$profit_approved = 0;
				
				foreach($fondos_details as $fondo){
					
					if($fondo->user_id == $id_user){
						
						// Conversión de cada monto a dólares
						$currency = $fondo->coin_avr;  // Tipo de moneda de la transacción
						
						// Si el tipo de moneda de la transacción es alguna cryptomoneda (BTC, LTC, BCH, ect.) o Bolívares (VEF) hacemos la conversión usando una api más acorde
						if (in_array($currency, $rates)) {
							
							// Primero convertimos el valor de la cryptodivisa
							$valor1anycoin = 0;
							$i = 0;
							$rate = $currency;
							foreach($exchangeRates2 as $divisa){
								if ($divisa['symbol'] == $rate){
									$i+=1;
									
									// Obtenemos el valor de la cryptomoneda de la transacción en dólares
									$valor1anycoin = $divisa['price_usd'];
								}
							}
							
							$trans_usd = (float)$fondo->amount*(float)$valor1anycoin;
							
						}else if($currency == 'VEF'){
							
							// Si la moneda de la transacción es el bolívar y la transacción es anterior al 20-08-2018, se hace una reconversión
							if(strtotime($fondo->date) < strtotime("2018-08-20 00:00:00")){
								$trans_usd = (float)($fondo->amount/100000)/(float)$valor1vef;
							}else{
								$trans_usd = (float)$fondo->amount/(float)$valor1vef;
							}
							
						}else{
							
							$trans_usd = (float)$fondo->amount/$exchangeRates['rates'][$currency];
							
						}
						
						// Si tiene proyecto asociado en project_id lo sumamos
						if($fondo->status == 'approved'){
							// Suma de depósitos
							if($fondo->type == 'profit'){
								$profit_approved += $trans_usd;
							}
						}
					}
					
				}  // Cierre del for each de transacciones para el dividendo
				
				$resumen_user['returned_capital'] += $profit_approved;
				
				// Capital en Proyecto
				$deposit_waiting = 0;
				$expense_waiting = 0;
				$profit_waiting = 0;
				$withdraw_waiting = 0;
				$invest_waiting = 0;
				$sell_waiting = 0;
				$deposit_approved = 0;
				$expense_approved = 0;
				$profit_approved = 0;
				$withdraw_approved = 0;
				$invest_approved = 0;
				$sell_approved = 0;
				
				foreach($fondos_details as $fondo){
					
					if($fondo->user_id == $id_user){
						
						// Conversión de cada monto a dólares
						$currency = $fondo->coin_avr;  // Tipo de moneda de la transacción
						
						// Si el tipo de moneda de la transacción es alguna cryptomoneda (BTC, LTC, BCH, ect.) o Bolívares (VEF) hacemos la conversión usando una api más acorde
						if (in_array($currency, $rates)) {
							
							// Primero convertimos el valor de la cryptodivisa
							$valor1anycoin = 0;
							$i = 0;
							$rate = $currency;
							foreach($exchangeRates2 as $divisa){
								if ($divisa['symbol'] == $rate){
									$i+=1;
									
									// Obtenemos el valor de la cryptomoneda de la transacción en dólares
									$valor1anycoin = $divisa['price_usd'];
								}
							}
							
							$trans_usd = (float)$fondo->amount*(float)$valor1anycoin;
							
						}else if($currency == 'VEF'){
							
							// Si la moneda de la transacción es el bolívar y la transacción es anterior al 20-08-2018, se hace una reconversión
							if(strtotime($fondo->date) < strtotime("2018-08-20 00:00:00")){
								$trans_usd = (float)($fondo->amount/100000)/(float)$valor1vef;
							}else{
								$trans_usd = (float)$fondo->amount/(float)$valor1vef;
							}
							
						}else{
							
							$trans_usd = (float)$fondo->amount/$exchangeRates['rates'][$currency];
							
						}
						
						// Si tiene proyecto asociado en project_id lo sumamos
						if($fondo->status == 'approved'){
							// Suma de depósitos
							if($fondo->type == 'deposit'){
								$deposit_approved += $trans_usd;
							}
							// Suma de gastos
							if($fondo->type == 'expense'){
								$expense_approved += $trans_usd;
							}
							// Suma de ganancias
							if($fondo->type == 'profit'){
								$profit_approved += $trans_usd;
							}
							// Suma de retiros
							if($fondo->type == 'withdraw'){
								$withdraw_approved += $trans_usd;
							}
							// Suma de inversiones
							if($fondo->type == 'invest'){
								/*$variable1 = "projects.type"; $condicional = "="; $variable2 = $data_project[0]->type; $segmento = "invest";
								$reglas = $this->MProjects->buscar_rules($variable1, $condicional, $variable2, $segmento);  // Listado de reglas
								if(count($reglas) > 0){
									if($reglas[0]->result == "true"){*/
										$invest_approved += $trans_usd;
									/*}
								}*/
							}
							// Suma de ventas
							if($fondo->type == 'sell'){
								$sell_approved += $trans_usd;
							}
						}
					}
					
				}  // Cierre del for each de transacciones para el capital en cuenta
				
				$resumen_user['retirement_capital_available'] = $deposit_approved + $expense_approved + $profit_approved + $withdraw_approved + $invest_approved + $sell_approved;
				
				// Inversión Pendiente
				$deposit_waiting = 0;
				
				foreach($fondos_details as $fondo){
					
					if($fondo->user_id == $id_user){
						
						// Conversión de cada monto a dólares
						$currency = $fondo->coin_avr;  // Tipo de moneda de la transacción
						
						// Si el tipo de moneda de la transacción es alguna cryptomoneda (BTC, LTC, BCH, ect.) o Bolívares (VEF) hacemos la conversión usando una api más acorde
						if (in_array($currency, $rates)) {
							
							// Primero convertimos el valor de la cryptodivisa
							$valor1anycoin = 0;
							$i = 0;
							$rate = $currency;
							foreach($exchangeRates2 as $divisa){
								if ($divisa['symbol'] == $rate){
									$i+=1;
									
									// Obtenemos el valor de la cryptomoneda de la transacción en dólares
									$valor1anycoin = $divisa['price_usd'];
								}
							}
							
							$trans_usd = (float)$fondo->amount*(float)$valor1anycoin;
							
						}else if($currency == 'VEF'){
							
							// Si la moneda de la transacción es el bolívar y la transacción es anterior al 20-08-2018, se hace una reconversión
							if(strtotime($fondo->date) < strtotime("2018-08-20 00:00:00")){
								$trans_usd = (float)($fondo->amount/100000)/(float)$valor1vef;
							}else{
								$trans_usd = (float)$fondo->amount/(float)$valor1vef;
							}
							
						}else{
							
							$trans_usd = (float)$fondo->amount/$exchangeRates['rates'][$currency];
							
						}
						
						// Si tiene depósito pendiente
						if($fondo->status == 'waiting'){
							// Suma de depósitos
							if($fondo->type == 'deposit'){
								$deposit_waiting += $trans_usd;
							}
						}
					}
					
				}  // Cierre del for each de transacciones para la inversión pendiente
				
				$resumen_user['pending_entry'] += $deposit_waiting;
				
				// Retiro Pendiente
				$withdraw_waiting = 0;
				
				foreach($fondos_details as $fondo){
					
					if($fondo->user_id == $id_user){
						
						// Conversión de cada monto a dólares
						$currency = $fondo->coin_avr;  // Tipo de moneda de la transacción
						
						// Si el tipo de moneda de la transacción es alguna cryptomoneda (BTC, LTC, BCH, ect.) o Bolívares (VEF) hacemos la conversión usando una api más acorde
						if (in_array($currency, $rates)) {
							
							// Primero convertimos el valor de la cryptodivisa
							$valor1anycoin = 0;
							$i = 0;
							$rate = $currency;
							foreach($exchangeRates2 as $divisa){
								if ($divisa['symbol'] == $rate){
									$i+=1;
									
									// Obtenemos el valor de la cryptomoneda de la transacción en dólares
									$valor1anycoin = $divisa['price_usd'];
								}
							}
							
							$trans_usd = (float)$fondo->amount*(float)$valor1anycoin;
							
						}else if($currency == 'VEF'){
							
							// Si la moneda de la transacción es el bolívar y la transacción es anterior al 20-08-2018, se hace una reconversión
							if(strtotime($fondo->date) < strtotime("2018-08-20 00:00:00")){
								$trans_usd = (float)($fondo->amount/100000)/(float)$valor1vef;
							}else{
								$trans_usd = (float)$fondo->amount/(float)$valor1vef;
							}
							
						}else{
							
							$trans_usd = (float)$fondo->amount/$exchangeRates['rates'][$currency];
							
						}
						
						// Si tiene retiro pendiente
						if($fondo->status == 'waiting'){
							// Suma de depósitos
							if($fondo->type == 'withdraw'){
								$withdraw_waiting += $trans_usd;
							}
						}
					}
					
				}  // Cierre del for each de transacciones para la retiro pendiente
				
				$resumen_user['pending_exit'] += $withdraw_waiting;
				
				$decimals = 2;
				if($this->session->userdata('logged_in')['coin_decimals'] != ""){
					$decimals = $this->session->userdata('logged_in')['coin_decimals'];
				}
				$symbol = $this->session->userdata('logged_in')['coin_symbol'];
				
				// Conversión de los montos a la divisa del usuario
				$resumen_user['capital_invested'] *= $currency_user; 
				$resumen_user['capital_invested'] = round($resumen_user['capital_invested'], $decimals);
				$resumen_user['capital_invested'] = $resumen_user['capital_invested']." ".$symbol;
				
				$resumen_user['returned_capital'] *= $currency_user; 
				$resumen_user['returned_capital'] = round($resumen_user['returned_capital'], $decimals);
				$resumen_user['returned_capital'] = $resumen_user['returned_capital']." ".$symbol;
				
				$resumen_user['retirement_capital_available'] *= $currency_user; 
				$resumen_user['retirement_capital_available'] = round($resumen_user['retirement_capital_available'], $decimals);
				$resumen_user['retirement_capital_available'] = $resumen_user['retirement_capital_available']." ".$symbol;
				
				$resumen_user['pending_entry'] *= $currency_user; 
				$resumen_user['pending_entry'] = round($resumen_user['pending_entry'], $decimals);
				$resumen_user['pending_entry'] = $resumen_user['pending_entry']." ".$symbol;
				
				$resumen_user['pending_exit'] *= $currency_user; 
				$resumen_user['pending_exit'] = round($resumen_user['pending_exit'], $decimals);
				$resumen_user['pending_exit'] = $resumen_user['pending_exit']." ".$symbol;
				
				$resumen_users[] = $resumen_user;
				
			}
			
			// CIERRE DE CÁLCULOS DEL RESUMEN POR USUARIO
			
			
		}else{
			
			// CÁLCULOS DEL RESUMEN GENERAL
			
			// Capital en Proyecto
			$deposit_waiting = 0;
			$expense_waiting = 0;
			$profit_waiting = 0;
			$withdraw_waiting = 0;
			$invest_waiting = 0;
			$sell_waiting = 0;
			$deposit_approved = 0;
			$expense_approved = 0;
			$profit_approved = 0;
			$withdraw_approved = 0;
			$invest_approved = 0;
			$sell_approved = 0;
			
			foreach($fondos_details as $fondo){
					
				// Conversión de cada monto a dólares
				$currency = $fondo->coin_avr;  // Tipo de moneda de la transacción
				
				// Si el tipo de moneda de la transacción es alguna cryptomoneda (BTC, LTC, BCH, ect.) o Bolívares (VEF) hacemos la conversión usando una api más acorde
				if (in_array($currency, $rates)) {
					
					// Primero convertimos el valor de la cryptodivisa
					$valor1anycoin = 0;
					$i = 0;
					$rate = $currency;
					foreach($exchangeRates2 as $divisa){
						if ($divisa['symbol'] == $rate){
							$i+=1;
							
							// Obtenemos el valor de la cryptomoneda de la transacción en dólares
							$valor1anycoin = $divisa['price_usd'];
						}
					}
					
					$trans_usd = (float)$fondo->amount*(float)$valor1anycoin;
					
				}else if($currency == 'VEF'){
					
					// Si la moneda de la transacción es el bolívar y la transacción es anterior al 20-08-2018, se hace una reconversión
					if(strtotime($fondo->date) < strtotime("2018-08-20 00:00:00")){
						$trans_usd = (float)($fondo->amount/100000)/(float)$valor1vef;
					}else{
						$trans_usd = (float)$fondo->amount/(float)$valor1vef;
					}
					
				}else{
					
					$trans_usd = (float)$fondo->amount/$exchangeRates['rates'][$currency];
					
				}
				
				// Si el usuario es de perfil GESTOR tomamos en cuenta sólo las transacciones realizadas por él (user_create_id).
				// Si el usuario no es de perfil GESTOR tomamos en cuenta sólo las transacciones asociadas a él (user_id).
				if($this->session->userdata('logged_in')['profile_id'] == 5){
					if($fondo->status == 'approved' && $fondo->user_create_id == $this->session->userdata('logged_in')['id']){
						// Suma de depósitos
						if($fondo->type == 'deposit'){
							$deposit_approved += $trans_usd;
						}
						// Suma de gastos
						if($fondo->type == 'expense'){
							$expense_approved += $trans_usd;
						}
						// Suma de ganancias
						if($fondo->type == 'profit'){
							$profit_approved += $trans_usd;
						}
						// Suma de retiros
						if($fondo->type == 'withdraw'){
							$withdraw_approved += $trans_usd;
						}
						// Suma de inversiones
						if($fondo->type == 'invest'){
							$invest_approved += $trans_usd;
						}
						// Suma de ventas
						if($fondo->type == 'sell'){
							$sell_approved += $trans_usd;
						}
					}
				}else{
					if($fondo->status == 'approved' && $fondo->user_id == $this->session->userdata('logged_in')['id']){
						// Suma de depósitos
						if($fondo->type == 'deposit'){
							$deposit_approved += $trans_usd;
						}
						// Suma de gastos
						if($fondo->type == 'expense'){
							$expense_approved += $trans_usd;
						}
						// Suma de ganancias
						if($fondo->type == 'profit'){
							$profit_approved += $trans_usd;
						}
						// Suma de retiros
						if($fondo->type == 'withdraw'){
							$withdraw_approved += $trans_usd;
						}
						// Suma de inversiones
						if($fondo->type == 'invest'){
							$invest_approved += $trans_usd;
						}
						// Suma de ventas
						if($fondo->type == 'sell'){
							$sell_approved += $trans_usd;
						}
					}
				}
				
			}  // Cierre del for each de transacciones para capital en cuenta
			
			$resumen['retirement_capital_available'] += $deposit_approved + $expense_approved + $profit_approved + $withdraw_approved + $invest_approved + $sell_approved;
			$resumen['retirement_capital_available_user'] += $deposit_approved + $expense_approved + $profit_approved + $withdraw_approved + $invest_approved + $sell_approved;
			
			// Capital Invertido
			$deposit_approved = 0;
			
			foreach($fondos_details as $fondo){
					
				// Conversión de cada monto a dólares
				$currency = $fondo->coin_avr;  // Tipo de moneda de la transacción
				
				// Si la moneda de la transacción difiere de la del proyecto
				if($currency != $data_project[0]->coin_avr){
					
					// Si el tipo de moneda de la transacción es alguna cryptomoneda (BTC, LTC, BCH, ect.) o Bolívares (VEF) hacemos la conversión usando una api más acorde
					if (in_array($currency, $rates)) {
						
						// Primero convertimos el valor de la cryptodivisa
						$valor1anycoin = 0;
						$i = 0;
						$rate = $currency;
						foreach($exchangeRates2 as $divisa){
							if ($divisa['symbol'] == $rate){
								$i+=1;
								
								// Obtenemos el valor de la cryptomoneda de la transacción en dólares
								$valor1anycoin = $divisa['price_usd'];
							}
						}
						
						// Si el campo de tasa 'rate' es mayor a cero
						if((float)$fondo->rate > 0){
							$trans_usd = (float)$fondo->amount*(float)$fondo->rate;
							//~ $trans_usd *= (float)$valor1anycoin;
						}else{
							$trans_usd = (float)$fondo->amount*(float)$valor1anycoin;
						}
						
					}else if($currency == 'VEF'){
						
						// Si la moneda de la transacción es el bolívar y la transacción es anterior al 20-08-2018, se hace una reconversión
						if(strtotime($fondo->date) < strtotime("2018-08-20 00:00:00")){
							// Si el campo de tasa 'rate' es mayor a cero
							if((float)$fondo->rate > 0){
								$trans_usd = (float)($fondo->amount/100000)*(float)$fondo->rate;
								//~ $trans_usd /= (float)$valor1vef;
							}else{
								$trans_usd = (float)($fondo->amount/100000)/(float)$valor1vef;
							}
						}else{
							// Si el campo de tasa 'rate' es mayor a cero
							if((float)$fondo->rate > 0){
								$trans_usd = (float)$fondo->amount*(float)$fondo->rate;
								//~ $trans_usd /= (float)$valor1vef;
							}else{
								$trans_usd = (float)$fondo->amount/(float)$valor1vef;
							}
						}
						
					}else{
						
						// Si el campo de tasa 'rate' es mayor a cero
						if((float)$fondo->rate > 0){
							$trans_usd = (float)$fondo->amount*(float)$fondo->rate;
							//~ $trans_usd /= (float)$exchangeRates['rates'][$currency];
						}else{
							$trans_usd = (float)$fondo->amount/$exchangeRates['rates'][$currency];
						}
						
					}
					
				}else{
					
					// Si el tipo de moneda de la transacción es alguna cryptomoneda (BTC, LTC, BCH, ect.) o Bolívares (VEF) hacemos la conversión usando una api más acorde
					if (in_array($currency, $rates)) {
						
						// Primero convertimos el valor de la cryptodivisa
						$valor1anycoin = 0;
						$i = 0;
						$rate = $currency;
						foreach($exchangeRates2 as $divisa){
							if ($divisa['symbol'] == $rate){
								$i+=1;
								
								// Obtenemos el valor de la cryptomoneda de la transacción en dólares
								$valor1anycoin = $divisa['price_usd'];
							}
						}
						
						$trans_usd = (float)$fondo->amount*(float)$valor1anycoin;
						
					}else if($currency == 'VEF'){
						
						// Si la moneda de la transacción es el bolívar y la transacción es anterior al 20-08-2018, se hace una reconversión
						if(strtotime($fondo->date) < strtotime("2018-08-20 00:00:00")){
							$trans_usd = (float)($fondo->amount/100000)/(float)$valor1vef;
						}else{
							$trans_usd = (float)$fondo->amount/(float)$valor1vef;
						}
						
					}else{
						
						$trans_usd = (float)$fondo->amount/$exchangeRates['rates'][$currency];
						
					}
					
				}
				
				// Si el usuario es de perfil GESTOR tomamos en cuenta sólo las transacciones realizadas por él (user_create_id).
				// Si el usuario no es de perfil GESTOR tomamos en cuenta sólo las transacciones asociadas a él (user_id).
				if($this->session->userdata('logged_in')['profile_id'] == 5){
					if($fondo->status == 'approved' && $fondo->user_create_id == $this->session->userdata('logged_in')['id']){
						// Suma de depósitos
						if($fondo->type == 'invest'){
							$deposit_approved += $trans_usd;
						}
					}
				}else{
					if($fondo->status == 'approved' && $fondo->user_id == $this->session->userdata('logged_in')['id']){
						// Suma de depósitos
						if($fondo->type == 'invest'){
							$deposit_approved += $trans_usd;
						}
					}
				}
				
			}  // Cierre del for each de transacciones para capital invertido
			
			$resumen['capital_invested'] += $deposit_approved;
			$resumen['capital_invested_user'] += $deposit_approved;
			
			// Dividendo
			$profit_approved = 0;
			
			foreach($fondos_details as $fondo){
					
				// Conversión de cada monto a dólares
				$currency = $fondo->coin_avr;  // Tipo de moneda de la transacción
				
				// Si el tipo de moneda de la transacción es alguna cryptomoneda (BTC, LTC, BCH, ect.) o Bolívares (VEF) hacemos la conversión usando una api más acorde
				if (in_array($currency, $rates)) {
					
					// Primero convertimos el valor de la cryptodivisa
					$valor1anycoin = 0;
					$i = 0;
					$rate = $currency;
					foreach($exchangeRates2 as $divisa){
						if ($divisa['symbol'] == $rate){
							$i+=1;
							
							// Obtenemos el valor de la cryptomoneda de la transacción en dólares
							$valor1anycoin = $divisa['price_usd'];
						}
					}
					
					$trans_usd = (float)$fondo->amount*(float)$valor1anycoin;
					
				}else if($currency == 'VEF'){
					
					// Si la moneda de la transacción es el bolívar y la transacción es anterior al 20-08-2018, se hace una reconversión
					if(strtotime($fondo->date) < strtotime("2018-08-20 00:00:00")){
						$trans_usd = (float)($fondo->amount/100000)/(float)$valor1vef;
					}else{
						$trans_usd = (float)$fondo->amount/(float)$valor1vef;
					}
					
				}else{
					
					$trans_usd = (float)$fondo->amount/$exchangeRates['rates'][$currency];
					
				}
				
				// Si el usuario es de perfil GESTOR tomamos en cuenta sólo las transacciones realizadas por él (user_create_id).
				// Si el usuario no es de perfil GESTOR tomamos en cuenta sólo las transacciones asociadas a él (user_id).
				if($this->session->userdata('logged_in')['profile_id'] == 5){
					if($fondo->status == 'approved' && $fondo->user_create_id == $this->session->userdata('logged_in')['id']){
						// Suma de ganancias
						if($fondo->type == 'profit'){
							$profit_approved += $trans_usd;
						}
					}
				}else{
					if($fondo->status == 'approved' && $fondo->user_id == $this->session->userdata('logged_in')['id']){
						// Suma de ganancias
						if($fondo->type == 'profit'){
							$profit_approved += $trans_usd;
						}
					}
				}
				
			}  // Cierre del for each de transacciones para el dividendo
			
			$resumen['returned_capital'] += $profit_approved;
			$resumen['returned_capital_user'] += $profit_approved;
			
			// Gastos
			$expense_approved = 0;
			
			foreach($fondos_details as $fondo){
					
				// Conversión de cada monto a dólares
				$currency = $fondo->coin_avr;  // Tipo de moneda de la transacción
				
				// Si la moneda de la transacción difiere de la del proyecto
				if($currency != $data_project[0]->coin_avr){
					
					// Si el tipo de moneda de la transacción es alguna cryptomoneda (BTC, LTC, BCH, ect.) o Bolívares (VEF) hacemos la conversión usando una api más acorde
					if (in_array($currency, $rates)) {
						
						// Primero convertimos el valor de la cryptodivisa
						$valor1anycoin = 0;
						$i = 0;
						$rate = $currency;
						foreach($exchangeRates2 as $divisa){
							if ($divisa['symbol'] == $rate){
								$i+=1;
								
								// Obtenemos el valor de la cryptomoneda de la transacción en dólares
								$valor1anycoin = $divisa['price_usd'];
							}
						}
						
						// Si el campo de tasa 'rate' es mayor a cero
						if((float)$fondo->rate > 0){
							$trans_usd = (float)$fondo->amount*(float)$fondo->rate;
							//~ $trans_usd *= (float)$valor1anycoin;
						}else{
							$trans_usd = (float)$fondo->amount*(float)$valor1anycoin;
						}
						
					}else if($currency == 'VEF'){
						
						// Si la moneda de la transacción es el bolívar y la transacción es anterior al 20-08-2018, se hace una reconversión
						if(strtotime($fondo->date) < strtotime("2018-08-20 00:00:00")){
							// Si el campo de tasa 'rate' es mayor a cero
							if((float)$fondo->rate > 0){
								$trans_usd = (float)($fondo->amount/100000)*(float)$fondo->rate;
								//~ $trans_usd /= (float)$valor1vef;
							}else{
								$trans_usd = (float)($fondo->amount/100000)/(float)$valor1vef;
							}
						}else{
							// Si el campo de tasa 'rate' es mayor a cero
							if((float)$fondo->rate > 0){
								$trans_usd = (float)$fondo->amount*(float)$fondo->rate;
								//~ $trans_usd /= (float)$valor1vef;
							}else{
								$trans_usd = (float)$fondo->amount/(float)$valor1vef;
							}
						}
						
					}else{
						
						// Si el campo de tasa 'rate' es mayor a cero
						if((float)$fondo->rate > 0){
							$trans_usd = (float)$fondo->amount*(float)$fondo->rate;
							//~ $trans_usd /= (float)$exchangeRates['rates'][$currency];
						}else{
							$trans_usd = (float)$fondo->amount/$exchangeRates['rates'][$currency];
						}
						
					}
					
				}else{
					
					// Si el tipo de moneda de la transacción es alguna cryptomoneda (BTC, LTC, BCH, ect.) o Bolívares (VEF) hacemos la conversión usando una api más acorde
					if (in_array($currency, $rates)) {
						
						// Primero convertimos el valor de la cryptodivisa
						$valor1anycoin = 0;
						$i = 0;
						$rate = $currency;
						foreach($exchangeRates2 as $divisa){
							if ($divisa['symbol'] == $rate){
								$i+=1;
								
								// Obtenemos el valor de la cryptomoneda de la transacción en dólares
								$valor1anycoin = $divisa['price_usd'];
							}
						}
						
						$trans_usd = (float)$fondo->amount*(float)$valor1anycoin;
						
					}else if($currency == 'VEF'){
						
						// Si la moneda de la transacción es el bolívar y la transacción es anterior al 20-08-2018, se hace una reconversión
						if(strtotime($fondo->date) < strtotime("2018-08-20 00:00:00")){
							$trans_usd = (float)($fondo->amount/100000)/(float)$valor1vef;
						}else{
							$trans_usd = (float)$fondo->amount/(float)$valor1vef;
						}
						
					}else{
						
						$trans_usd = (float)$fondo->amount/$exchangeRates['rates'][$currency];
						
					}
					
				}
				
				// Si el usuario es de perfil GESTOR tomamos en cuenta sólo las transacciones realizadas por él (user_create_id).
				// Si el usuario no es de perfil GESTOR tomamos en cuenta sólo las transacciones asociadas a él (user_id).
				if($this->session->userdata('logged_in')['profile_id'] == 5){
					if($fondo->status == 'approved' && $fondo->user_create_id == $this->session->userdata('logged_in')['id']){
						// Suma de ganancias
						if($fondo->type == 'expense'){
							$expense_approved += $trans_usd;
						}
					}
				}else{
					if($fondo->status == 'approved' && $fondo->user_id == $this->session->userdata('logged_in')['id']){
						// Suma de ganancias
						if($fondo->type == 'expense'){
							$expense_approved += $trans_usd;
						}
					}
				}
				
			}  // Cierre del for each de transacciones para el dividendo
			
			$resumen['expense_capital'] += $expense_approved;
			$resumen['expense_capital_user'] += $expense_approved;
			
			// CIERRE DE CÁLCULOS DEL RESUMEN GENERAL
			
			//-----------------------------------------------------------------------------------------------------------------------------
			
			// CÁLCULOS DEL RESUMEN POR USUARIO
			
			$resumen_users = array();  // Para el resultado final (Listado de usuarios con sus respectivos resúmenes)
			
			$ids_users = array();  // Para almacenar los ids de los usuarios que han registrado fondos
			
			// Colectamos los ids de los usuarios de las transacciones del proyecto
			foreach($fondos_details as $fondo){
				
				if(!in_array($fondo->user_id, $ids_users)){
					if($fondo->user_id > 0){
						$ids_users[] = $fondo->user_id;
					}
				}
				
			}
			
			// Armamos una lista de fondos por usuario y lo almacenamos en el arreglo '$resumen_users'
			foreach($ids_users as $id_user){
				
				$resumen_user = array(
					'name' => '',
					'alias' => '',
					'username' => '',
					'capital_payback' => 0,
					'capital_invested' => 0,
					'returned_capital' => 0,
					'retirement_capital_available' => 0,
					'pending_entry' => 0,
					'pending_exit' => 0
				);
				
				// Capital Invertido
				$deposit_approved = 0;
				
				foreach($fondos_details as $fondo){
					
					if($fondo->user_id == $id_user){
						
						// Conversión de cada monto a dólares
						$currency = $fondo->coin_avr;  // Tipo de moneda de la transacción
						
						// Si la moneda de la transacción difiere de la del proyecto
						if($currency != $data_project[0]->coin_avr){
							
							// Si el tipo de moneda de la transacción es alguna cryptomoneda (BTC, LTC, BCH, ect.) o Bolívares (VEF) hacemos la conversión usando una api más acorde
							if (in_array($currency, $rates)) {
								
								// Primero convertimos el valor de la cryptodivisa
								$valor1anycoin = 0;
								$i = 0;
								$rate = $currency;
								foreach($exchangeRates2 as $divisa){
									if ($divisa['symbol'] == $rate){
										$i+=1;
										
										// Obtenemos el valor de la cryptomoneda de la transacción en dólares
										$valor1anycoin = $divisa['price_usd'];
									}
								}
								
								// Si el campo de tasa 'rate' es mayor a cero
								if((float)$fondo->rate > 0){
									$trans_usd = (float)$fondo->amount*(float)$fondo->rate;
									//~ $trans_usd *= (float)$valor1anycoin;
								}else{
									$trans_usd = (float)$fondo->amount*(float)$valor1anycoin;
								}
								
							}else if($currency == 'VEF'){
								
								// Si la moneda de la transacción es el bolívar y la transacción es anterior al 20-08-2018, se hace una reconversión
								if(strtotime($fondo->date) < strtotime("2018-08-20 00:00:00")){
									// Si el campo de tasa 'rate' es mayor a cero
									if((float)$fondo->rate > 0){
										$trans_usd = (float)($fondo->amount/100000)*(float)$fondo->rate;
										//~ $trans_usd /= (float)$valor1vef;
									}else{
										$trans_usd = (float)($fondo->amount/100000)/(float)$valor1vef;
									}
								}else{
									// Si el campo de tasa 'rate' es mayor a cero
									if((float)$fondo->rate > 0){
										$trans_usd = (float)$fondo->amount*(float)$fondo->rate;
										//~ $trans_usd /= (float)$valor1vef;
									}else{
										$trans_usd = (float)$fondo->amount/(float)$valor1vef;
									}
								}
								
							}else{
								
								// Si el campo de tasa 'rate' es mayor a cero
								if((float)$fondo->rate > 0){
									$trans_usd = (float)$fondo->amount*(float)$fondo->rate;
									//~ $trans_usd /= (float)$exchangeRates['rates'][$currency];
								}else{
									$trans_usd = (float)$fondo->amount/$exchangeRates['rates'][$currency];
								}
								
							}
							
						}else{
							
							// Si el tipo de moneda de la transacción es alguna cryptomoneda (BTC, LTC, BCH, ect.) o Bolívares (VEF) hacemos la conversión usando una api más acorde
							if (in_array($currency, $rates)) {
								
								// Primero convertimos el valor de la cryptodivisa
								$valor1anycoin = 0;
								$i = 0;
								$rate = $currency;
								foreach($exchangeRates2 as $divisa){
									if ($divisa['symbol'] == $rate){
										$i+=1;
										
										// Obtenemos el valor de la cryptomoneda de la transacción en dólares
										$valor1anycoin = $divisa['price_usd'];
									}
								}
								
								$trans_usd = (float)$fondo->amount*(float)$valor1anycoin;
								
							}else if($currency == 'VEF'){
								
								// Si la moneda de la transacción es el bolívar y la transacción es anterior al 20-08-2018, se hace una reconversión
								if(strtotime($fondo->date) < strtotime("2018-08-20 00:00:00")){
									$trans_usd = (float)($fondo->amount/100000)/(float)$valor1vef;
								}else{
									$trans_usd = (float)$fondo->amount/(float)$valor1vef;
								}
								
							}else{
								
								$trans_usd = (float)$fondo->amount/$exchangeRates['rates'][$currency];
								
							}
							
						}
						
						$resumen_user['name'] = $fondo->name;
						$resumen_user['alias'] = $fondo->alias;
						$resumen_user['username'] = $fondo->username;
						
						// Si tiene proyecto asociado en project_id lo sumamos
						if($fondo->status == 'approved'){
							// Suma de depósitos
							if($fondo->type == 'invest'){
								$deposit_approved += $trans_usd;
							}
						}
					}
					
				}  // Cierre del for each de transacciones para capital invertido
				
				$resumen_user['capital_invested'] += $deposit_approved;
				
				// Dividendo
				$profit_approved = 0;
				
				foreach($fondos_details as $fondo){
					
					if($fondo->user_id == $id_user){
						
						// Conversión de cada monto a dólares
						$currency = $fondo->coin_avr;  // Tipo de moneda de la transacción
						
						// Si el tipo de moneda de la transacción es alguna cryptomoneda (BTC, LTC, BCH, ect.) o Bolívares (VEF) hacemos la conversión usando una api más acorde
						if (in_array($currency, $rates)) {
							
							// Primero convertimos el valor de la cryptodivisa
							$valor1anycoin = 0;
							$i = 0;
							$rate = $currency;
							foreach($exchangeRates2 as $divisa){
								if ($divisa['symbol'] == $rate){
									$i+=1;
									
									// Obtenemos el valor de la cryptomoneda de la transacción en dólares
									$valor1anycoin = $divisa['price_usd'];
								}
							}
							
							$trans_usd = (float)$fondo->amount*(float)$valor1anycoin;
							
						}else if($currency == 'VEF'){
							
							// Si la moneda de la transacción es el bolívar y la transacción es anterior al 20-08-2018, se hace una reconversión
							if(strtotime($fondo->date) < strtotime("2018-08-20 00:00:00")){
								$trans_usd = (float)($fondo->amount/100000)/(float)$valor1vef;
							}else{
								$trans_usd = (float)$fondo->amount/(float)$valor1vef;
							}
							
						}else{
							
							$trans_usd = (float)$fondo->amount/$exchangeRates['rates'][$currency];
							
						}
						
						// Si tiene proyecto asociado en project_id lo sumamos
						if($fondo->status == 'approved'){
							// Suma de depósitos
							if($fondo->type == 'profit'){
								$profit_approved += $trans_usd;
							}
						}
					}
					
				}  // Cierre del for each de transacciones para el dividendo
				
				$resumen_user['returned_capital'] += $profit_approved;
				
				// Capital en Proyecto
				$deposit_waiting = 0;
				$expense_waiting = 0;
				$profit_waiting = 0;
				$withdraw_waiting = 0;
				$invest_waiting = 0;
				$sell_waiting = 0;
				$deposit_approved = 0;
				$expense_approved = 0;
				$profit_approved = 0;
				$withdraw_approved = 0;
				$invest_approved = 0;
				$sell_approved = 0;
				
				foreach($fondos_details as $fondo){
					
					if($fondo->user_id == $id_user){
						
						// Conversión de cada monto a dólares
						$currency = $fondo->coin_avr;  // Tipo de moneda de la transacción
						
						// Si el tipo de moneda de la transacción es alguna cryptomoneda (BTC, LTC, BCH, ect.) o Bolívares (VEF) hacemos la conversión usando una api más acorde
						if (in_array($currency, $rates)) {
							
							// Primero convertimos el valor de la cryptodivisa
							$valor1anycoin = 0;
							$i = 0;
							$rate = $currency;
							foreach($exchangeRates2 as $divisa){
								if ($divisa['symbol'] == $rate){
									$i+=1;
									
									// Obtenemos el valor de la cryptomoneda de la transacción en dólares
									$valor1anycoin = $divisa['price_usd'];
								}
							}
							
							$trans_usd = (float)$fondo->amount*(float)$valor1anycoin;
							
						}else if($currency == 'VEF'){
							
							// Si la moneda de la transacción es el bolívar y la transacción es anterior al 20-08-2018, se hace una reconversión
							if(strtotime($fondo->date) < strtotime("2018-08-20 00:00:00")){
								$trans_usd = (float)($fondo->amount/100000)/(float)$valor1vef;
							}else{
								$trans_usd = (float)$fondo->amount/(float)$valor1vef;
							}
							
						}else{
							
							$trans_usd = (float)$fondo->amount/$exchangeRates['rates'][$currency];
							
						}
						
						// Si tiene proyecto asociado en project_id lo sumamos
						if($fondo->status == 'approved'){
							// Suma de depósitos
							if($fondo->type == 'deposit'){
								$deposit_approved += $trans_usd;
							}
							// Suma de gastos
							if($fondo->type == 'expense'){
								$expense_approved += $trans_usd;
							}
							// Suma de ganancias
							if($fondo->type == 'profit'){
								$profit_approved += $trans_usd;
							}
							// Suma de retiros
							if($fondo->type == 'withdraw'){
								$withdraw_approved += $trans_usd;
							}
							// Suma de inversiones
							if($fondo->type == 'invest'){
								/*$variable1 = "projects.type"; $condicional = "="; $variable2 = $data_project[0]->type; $segmento = "invest";
								$reglas = $this->MProjects->buscar_rules($variable1, $condicional, $variable2, $segmento);  // Listado de reglas
								if(count($reglas) > 0){
									if($reglas[0]->result == "true"){*/
										$invest_approved += $trans_usd;
									/*}
								}*/
							}
							// Suma de ventas
							if($fondo->type == 'sell'){
								$sell_approved += $trans_usd;
							}
						}
					}
					
				}  // Cierre del for each de transacciones para el capital en cuenta
				
				$resumen_user['retirement_capital_available'] = $deposit_approved + $expense_approved + $profit_approved + $withdraw_approved + $invest_approved + $sell_approved;
				
				// Inversión Pendiente
				$deposit_waiting = 0;
				
				foreach($fondos_details as $fondo){
					
					if($fondo->user_id == $id_user){
						
						// Conversión de cada monto a dólares
						$currency = $fondo->coin_avr;  // Tipo de moneda de la transacción
						
						// Si el tipo de moneda de la transacción es alguna cryptomoneda (BTC, LTC, BCH, ect.) o Bolívares (VEF) hacemos la conversión usando una api más acorde
						if (in_array($currency, $rates)) {
							
							// Primero convertimos el valor de la cryptodivisa
							$valor1anycoin = 0;
							$i = 0;
							$rate = $currency;
							foreach($exchangeRates2 as $divisa){
								if ($divisa['symbol'] == $rate){
									$i+=1;
									
									// Obtenemos el valor de la cryptomoneda de la transacción en dólares
									$valor1anycoin = $divisa['price_usd'];
								}
							}
							
							$trans_usd = (float)$fondo->amount*(float)$valor1anycoin;
							
						}else if($currency == 'VEF'){
							
							// Si la moneda de la transacción es el bolívar y la transacción es anterior al 20-08-2018, se hace una reconversión
							if(strtotime($fondo->date) < strtotime("2018-08-20 00:00:00")){
								$trans_usd = (float)($fondo->amount/100000)/(float)$valor1vef;
							}else{
								$trans_usd = (float)$fondo->amount/(float)$valor1vef;
							}
							
						}else{
							
							$trans_usd = (float)$fondo->amount/$exchangeRates['rates'][$currency];
							
						}
						
						// Si tiene depósito pendiente
						if($fondo->status == 'waiting'){
							// Suma de depósitos
							if($fondo->type == 'deposit'){
								$deposit_waiting += $trans_usd;
							}
						}
					}
					
				}  // Cierre del for each de transacciones para la inversión pendiente
				
				$resumen_user['pending_entry'] += $deposit_waiting;
				
				// Retiro Pendiente
				$withdraw_waiting = 0;
				
				foreach($fondos_details as $fondo){
					
					if($fondo->user_id == $id_user){
						
						// Conversión de cada monto a dólares
						$currency = $fondo->coin_avr;  // Tipo de moneda de la transacción
						
						// Si el tipo de moneda de la transacción es alguna cryptomoneda (BTC, LTC, BCH, ect.) o Bolívares (VEF) hacemos la conversión usando una api más acorde
						if (in_array($currency, $rates)) {
							
							// Primero convertimos el valor de la cryptodivisa
							$valor1anycoin = 0;
							$i = 0;
							$rate = $currency;
							foreach($exchangeRates2 as $divisa){
								if ($divisa['symbol'] == $rate){
									$i+=1;
									
									// Obtenemos el valor de la cryptomoneda de la transacción en dólares
									$valor1anycoin = $divisa['price_usd'];
								}
							}
							
							$trans_usd = (float)$fondo->amount*(float)$valor1anycoin;
							
						}else if($currency == 'VEF'){
							
							// Si la moneda de la transacción es el bolívar y la transacción es anterior al 20-08-2018, se hace una reconversión
							if(strtotime($fondo->date) < strtotime("2018-08-20 00:00:00")){
								$trans_usd = (float)($fondo->amount/100000)/(float)$valor1vef;
							}else{
								$trans_usd = (float)$fondo->amount/(float)$valor1vef;
							}
							
						}else{
							
							$trans_usd = (float)$fondo->amount/$exchangeRates['rates'][$currency];
							
						}
						
						// Si tiene retiro pendiente
						if($fondo->status == 'waiting'){
							// Suma de depósitos
							if($fondo->type == 'withdraw'){
								$withdraw_waiting += $trans_usd;
							}
						}
					}
					
				}  // Cierre del for each de transacciones para la retiro pendiente
				
				$resumen_user['pending_exit'] += $withdraw_waiting;
				
				$decimals = 2;
				if($this->session->userdata('logged_in')['coin_decimals'] != ""){
					$decimals = $this->session->userdata('logged_in')['coin_decimals'];
				}
				$symbol = $this->session->userdata('logged_in')['coin_symbol'];
				
				// Conversión de los montos a la divisa del usuario
				$resumen_user['capital_invested'] *= $currency_user; 
				$resumen_user['capital_invested'] = round($resumen_user['capital_invested'], $decimals);
				$resumen_user['capital_invested'] = $resumen_user['capital_invested']." ".$symbol;
				
				$resumen_user['returned_capital'] *= $currency_user; 
				$resumen_user['returned_capital'] = round($resumen_user['returned_capital'], $decimals);
				$resumen_user['returned_capital'] = $resumen_user['returned_capital']." ".$symbol;
				
				$resumen_user['retirement_capital_available'] *= $currency_user; 
				$resumen_user['retirement_capital_available'] = round($resumen_user['retirement_capital_available'], $decimals);
				$resumen_user['retirement_capital_available'] = $resumen_user['retirement_capital_available']." ".$symbol;
				
				$resumen_user['pending_entry'] *= $currency_user; 
				$resumen_user['pending_entry'] = round($resumen_user['pending_entry'], $decimals);
				$resumen_user['pending_entry'] = $resumen_user['pending_entry']." ".$symbol;
				
				$resumen_user['pending_exit'] *= $currency_user; 
				$resumen_user['pending_exit'] = round($resumen_user['pending_exit'], $decimals);
				$resumen_user['pending_exit'] = $resumen_user['pending_exit']." ".$symbol;
				
				$resumen_users[] = $resumen_user;
				
			}
			
			// CIERRE DE CÁLCULOS DEL RESUMEN POR USUARIO
		
		}  // Cierre de validación de perfil
			
		$decimals = 2;
		$decimals_user = 2;
		if($data_project[0]->coin_decimals != ""){
			$decimals = $data_project[0]->coin_decimals;
			$decimals_user = $this->session->userdata('logged_in')['coin_decimals'];
		}
		if($this->session->userdata('logged_in')['coin_decimals'] != ""){
			$decimals_user = $this->session->userdata('logged_in')['coin_decimals'];
		}
		$symbol = $data_project[0]->coin_symbol;
		$symbol_user = $this->session->userdata('logged_in')['coin_symbol'];
		
		// Cálculo del capital payback (Porcentaje del capital de retorno con respecto al capital invertido)
		if($resumen['capital_invested'] > 0){
			$resumen['capital_payback'] = $resumen['returned_capital']*100/$resumen['capital_invested'];
		}else{
			$resumen['capital_payback'] = 100;
		}
		
		// Conversión de los montos a la divisa del proyecto
		$resumen['capital_payback'] = round($resumen['capital_payback'], $decimals);
		
		$resumen['capital_invested'] *= $currency_project; 
		$resumen['capital_invested'] = round($resumen['capital_invested'], $decimals);
		$resumen['capital_invested'] = $resumen['capital_invested']." ".$symbol;
		
		$resumen['returned_capital'] *= $currency_project; 
		$resumen['returned_capital'] = round($resumen['returned_capital'], $decimals);
		$resumen['returned_capital'] = $resumen['returned_capital']." ".$symbol;
		
		$resumen['expense_capital'] *= $currency_project; 
		$resumen['expense_capital'] = round($resumen['expense_capital'], $decimals);
		$resumen['expense_capital'] = $resumen['expense_capital']." ".$symbol;
		
		$resumen['retirement_capital_available'] *= $currency_project; 
		$resumen['retirement_capital_available'] = round($resumen['retirement_capital_available'], $decimals);
		$resumen['retirement_capital_available'] = $resumen['retirement_capital_available']." ".$symbol;
		
		// Conversión de los montos a la divisa del usuario
		$resumen['capital_invested_user'] *= $currency_user; 
		$resumen['capital_invested_user'] = round($resumen['capital_invested_user'], $decimals_user);
		$resumen['capital_invested_user'] = $resumen['capital_invested_user']." ".$symbol_user;
		
		$resumen['returned_capital_user'] *= $currency_user; 
		$resumen['returned_capital_user'] = round($resumen['returned_capital_user'], $decimals_user);
		$resumen['returned_capital_user'] = $resumen['returned_capital_user']." ".$symbol_user;
		
		$resumen['expense_capital_user'] *= $currency_user; 
		$resumen['expense_capital_user'] = round($resumen['expense_capital_user'], $decimals_user);
		$resumen['expense_capital_user'] = $resumen['expense_capital_user']." ".$symbol_user;
		
		$resumen['retirement_capital_available_user'] *= $currency_user; 
		$resumen['retirement_capital_available_user'] = round($resumen['retirement_capital_available_user'], $decimals_user);
		$resumen['retirement_capital_available_user'] = $resumen['retirement_capital_available_user']." ".$symbol_user;
		
		//~ return json_decode(json_encode($resumen), false);  // Esto retorna un arreglo de objetos
		
		if($this->session->userdata('logged_in')['profile_id'] == 1 || $this->session->userdata('logged_in')['profile_id'] == 2){
			// Retorno de todos los montos calculados
			return array(
				'resumen_general' => json_decode(json_encode($resumen), false),
				'resumen_plataforma' => json_decode(json_encode($resumen_platform), false),
				'resumen_usuarios' => json_decode(json_encode($resumen_users), false)
			);  // Esto retorna un arreglo de objetos
		}else{
			// Retorno de todos los montos calculados
			return array(
				'resumen_general' => json_decode(json_encode($resumen), false),
				'resumen_usuarios' => json_decode(json_encode($resumen_users), false)
			);  // Esto retorna un arreglo de objetos
		}
		
    }
    
    // Método que retorna una lista de transacciones agrupadas por la moneda
    public function fondos_json_coins($project_id)
    {
		$data_project = $this->MProjects->obtenerProyecto($project_id);  // Datos del proyecto
		
		// Obtenemos el valor en dólares de las distintas divisas
		$exchangeRates = $this->coin_openexchangerates;
		
		// Colectando los symbolos de todas las cryptomonedas soportadas por la plataforma de coinmarketcap
		$exchangeRates2 = $this->coin_coinmarketcap;
		$valor1anycoin = 0;
		$i = 0;
		$rate = $data_project[0]->coin_avr;
		$rates = array();
		foreach($exchangeRates2 as $divisa){
			if ($divisa['symbol'] == $rate){
				$i+=1;
				
				// Obtenemos el valor de la cryptomoneda del usuario en dólares
				$valor1anycoin = $divisa['price_usd'];
			}
			$rates[] = $divisa['symbol'];  // Colectamos los símbolos de todas las cryptomonedas
		}
		
		// Valor de 1 dólar en bolívares
		$valor1vef = $this->coin_rate;
		
		if (in_array($data_project[0]->coin_avr, $rates)) {
		
			$currency_project = 1/(float)$valor1anycoin;  // Tipo de moneda del proyecto
			
		} else if($data_project[0]->coin_avr == 'VEF') {
		
			$currency_project = $valor1vef;  // Tipo de moneda del proyecto
		
		} else {
			
			$currency_project = $exchangeRates['rates'][$data_project[0]->coin_avr];  // Tipo de moneda del proyecto
			
		}
        
        $fondos_details = $this->MProjects->obtenerTransaccionesPorMoneda($project_id);  // Listado de fondos detallados
        
        $avr_coins = array();  // Para almacenar los códigos ISO de las monedas asociadas a los fondos
        $avr_coins_decimals = array();  // Para almacenar los códigos ISO y decimales de las monedas asociadas a los fondos
        
        // Colectamos los códigos ISO y los decimales de las monedas de las transacciones resultantes
        foreach($fondos_details as $fondo){
			
			if(!in_array($fondo->coin_avr, $avr_coins)){
				$avr_coins[] = $fondo->coin_avr;  // Sólo los códigos iso
				$avr_coins_decimals[] = array($fondo->coin_avr => $fondo->coin_decimals);  // Códigos iso y los decimales soportados
			}
			
		}
		
		$summary_coins = array();  // Donde almacenaremos la nueva lista de transacciones
		
		// Recorrido de las transacciones para agruparlas por moneda 
		if(count($fondos_details) > 0){
			
			// Armamos una lista de fondos por usuario y lo almacenamos en el arreglo '$resumen_users'
			foreach($avr_coins_decimals as $avr_coin){
				
				$summary_coin = array(
					'coin' => "",
					'amount' => 0,
					'amount_project' => 0,
				);
			
				foreach($fondos_details as $fondo){
					
					// Si el usuario es de perfil 'ADMINISTRADOR' o 'PLATAFORMA'
					if($this->session->userdata('logged_in')['profile_id'] == 1 || $this->session->userdata('logged_in')['profile_id'] == 2){
						// Si es una transacción de la modela iterada la procesamos y sumamos
						if($fondo->coin_avr == key($avr_coin) && $fondo->status == 'approved'){
						
							// Asignamos el nombre de la moneda
							$summary_coin['coin'] = $fondo->coin;
							
							// Asignamos el monto total de la moneda
							// Si la moneda de la transacción es el bolívar y la transacción es anterior al 20-08-2018, se hace una reconversión
							if($fondo->coin_avr == 'VEF' && strtotime($fondo->date) < strtotime("2018-08-20 00:00:00")){
								$summary_coin['amount'] += ($fondo->amount/100000);
							}else{
								$summary_coin['amount'] += $fondo->amount;
							}
							
							// Formateamos el monto total de la moneda con sus decimales correspondientes
							$summary_coin['amount'] = round($summary_coin['amount'], $fondo->coin_decimals);
							
						}
					}else{
						// Si el usuario es de perfil GESTOR tomamos en cuenta sólo las transacciones realizadas por él (user_create_id).
						// Si el usuario no es de perfil GESTOR tomamos en cuenta sólo las transacciones asociadas a él (user_id).
						if($this->session->userdata('logged_in')['profile_id'] == 5){
							// Si es una transacción de la moneda iterada y del usuario logueado la procesamos y sumamos
							if($fondo->coin_avr == key($avr_coin) && $fondo->status == 'approved' && $fondo->user_create_id == $this->session->userdata('logged_in')['id']){
							
								// Asignamos el nombre de la moneda
								$summary_coin['coin'] = $fondo->coin;
								
								// Asignamos el monto total de la moneda
								// Si la moneda de la transacción es el bolívar y la transacción es anterior al 20-08-2018, se hace una reconversión
								if($fondo->coin_avr == 'VEF' && strtotime($fondo->date) < strtotime("2018-08-20 00:00:00")){
									$summary_coin['amount'] += ($fondo->amount/100000);
								}else{
									$summary_coin['amount'] += $fondo->amount;
								}
								
								// Formateamos el monto total de la moneda con sus decimales correspondientes
								$summary_coin['amount'] = round($summary_coin['amount'], $fondo->coin_decimals);
								
							}
						}else{
							// Si es una transacción de la moneda iterada y del usuario logueado la procesamos y sumamos
							if($fondo->coin_avr == key($avr_coin) && $fondo->status == 'approved' && $fondo->user_id == $this->session->userdata('logged_in')['id']){
							
								// Asignamos el nombre de la moneda
								$summary_coin['coin'] = $fondo->coin;
								
								// Asignamos el monto total de la moneda
								// Si la moneda de la transacción es el bolívar y la transacción es anterior al 20-08-2018, se hace una reconversión
								if($fondo->coin_avr == 'VEF' && strtotime($fondo->date) < strtotime("2018-08-20 00:00:00")){
									$summary_coin['amount'] += ($fondo->amount/100000);
								}else{
									$summary_coin['amount'] += $fondo->amount;
								}
								
								// Formateamos el monto total de la moneda con sus decimales correspondientes
								$summary_coin['amount'] = round($summary_coin['amount'], $fondo->coin_decimals);
								
							}
						}
					}
					
				}
				
				// Configuraciones de moneda del proyecto
				$decimals = 2;
				if($data_project[0]->coin_decimals > 0){
					$decimals = $data_project[0]->coin_decimals;
				}
				$symbol = $data_project[0]->coin_avr;
				
				// Conversión de cada monto a dólares
				$currency_fund = key($avr_coin);  // Tipo de moneda del fondo
				$currency_decimals = $avr_coin[$currency_fund];  // Cantidad de decimales de la moneda
				
				// Si el tipo de moneda del fondo es alguna cryptomoneda (BTC, LTC, BCH, ect.) o Bolívares (VEF) hacemos la conversión usando una api más acorde
				if (in_array($currency_fund, $rates)) {
					
					// Primero convertimos el valor de la cryptodivisa
					$valor1anycoin = 0;
					$i = 0;
					$rate = $currency_fund;
					foreach($exchangeRates2 as $divisa){
						if ($divisa['symbol'] == $rate){
							$i+=1;
							
							// Obtenemos el valor de la cryptomoneda en dólares
							$valor1anycoin = $divisa['price_usd'];
						}
					}
					
					$fund_usd = (float)$summary_coin['amount']*(float)$valor1anycoin;
					
				}else if($currency_fund == 'VEF'){
					
					$fund_usd = (float)$summary_coin['amount']/(float)$valor1vef;
					
				}else{
					
					$fund_usd = (float)$summary_coin['amount']/$exchangeRates['rates'][$currency_fund];
					
				}
				
				// Formateamos el monto total de la moneda con su símbolo correspondiente
				$summary_coin['amount'] = number_format($summary_coin['amount'], $currency_decimals, '.', '')." ".key($avr_coin);
				
				// Conversión de cada fondo a la divisa del proyecto
				$summary_coin['amount_project'] = $fund_usd * $currency_project;
				$summary_coin['amount_project'] = number_format($summary_coin['amount_project'], $decimals, '.', '');
				$summary_coin['amount_project'] = $summary_coin['amount_project']." ".$symbol;
				
				$summary_coins[] = $summary_coin;
			
			}
			
		}
		
		return json_decode(json_encode($summary_coins), false);  // Esto retorna un arreglo de objetos
		
	}
	
	public function ajax_projects()
    {
        $result = $this->MProjects->obtener();
        echo json_encode($result);
    }
    
    
    // Método para actualizar el precio del dólar tomando como referencia la api de dolartoday
    public function load_rate(){
		
		$coin = 'USD';  // Moneda a convertir
		
		// Valor de 1 dólar en bolívares
		// Con el uso de @ evitamos la impresión forzosa de errores que hace file_get_contents()
		$ct = @file_get_contents("https://s3.amazonaws.com/dolartoday/data.json");
		
		if($ct){
			
			// Valor de 1 dólar en bolívares
			$get3 = file_get_contents("https://s3.amazonaws.com/dolartoday/data.json");
			$exchangeRates3 = json_decode($get3, true);
			$valor1vef = $exchangeRates3[$coin]['transferencia'];
			
			// Verificamos el valor del dólar
			if($valor1vef != 0 && $valor1vef != null && $valor1vef != ''){
			
				$data_reg = array(
					'coin' => $coin,
					'rate' => $valor1vef,
					'd_create' => date('Y-m-d')
				);
				
				$reg = $this->MCoinRate->insert($data_reg);
				
			}else{
			
				// Cargamos un mensaje de error
				$this->coin_rate_message['type'] = 'error';
				$this->coin_rate_message['message'] = '1';
			
			}
			
		} else {
			
			$this->coin_rate_message['type'] = 'error';
			$this->coin_rate_message['message'] = '2';
		
		}
		
	}
	
	
	// Método para retornar el precio del dólar más actualizado en la base de datos
    public function show_rate(){
		
		// Consultamos los registros de las tasas
		$tasas = $this->MCoinRate->obtener();
		
		$valor_actual = 1;
		
		if(count($tasas) > 0){
		
			foreach($tasas as $tasa){
					
				$valor_actual = $tasa->rate;
				
			}
		
		}
			
		return number_format($valor_actual, 2, '.', '');
		
	}
	
    
    // Método para obtener el valor del dólar en las distintas divisas tomando como referencia la api de openexchangerates
    public function load_openexchangerates(){
		
		$exchangeRates = array();
		
		// Con el uso de @ evitamos la impresión forzosa de errores que hace file_get_contents()
		$ct = @file_get_contents("https://openexchangerates.org/api/latest.json?app_id=65148900f9c2443ab8918accd8c51664");
		
		if($ct){
			
			$get = file_get_contents("https://openexchangerates.org/api/latest.json?app_id=65148900f9c2443ab8918accd8c51664");
			//~ // Se decodifica la respuesta JSON
			$exchangeRates = json_decode($get, true);
			
			$this->openexchangerates_message['type'] = 'message1';
			$this->openexchangerates_message['message'] = '1';
			
		} else {
			
			// Si ha fallado la carga de la api con la key primaria intentamos con la key secundaria
			$ct2 = @file_get_contents("https://openexchangerates.org/api/latest.json?app_id=1d8edbe4f5d54857b1686c15befc4a85");
			
			if($ct2){
				
				$get = file_get_contents("https://openexchangerates.org/api/latest.json?app_id=1d8edbe4f5d54857b1686c15befc4a85");
				//~ // Se decodifica la respuesta JSON
				$exchangeRates = json_decode($get, true);
				
				$this->openexchangerates_message['type'] = 'message2';
				$this->openexchangerates_message['message'] = '2';
				
			}else{
				
				$this->openexchangerates_message['type'] = 'error';
				$this->openexchangerates_message['message'] = '3';
				
			}
		}
		
		return $exchangeRates;
		
	}
	
    
    // Colectando los symbolos de todas las cryptomonedas soportadas por la plataforma de coinmarketcap
    public function load_rates_coinmarketcap(){
		
		$exchangeRates2 = array();
		
		// Con el uso de @ evitamos la impresión forzosa de errores que hace file_get_contents()
		$ct = @file_get_contents("https://api.coinmarketcap.com/v1/ticker/");
		
		if($ct){
			
			$get = file_get_contents("https://api.coinmarketcap.com/v1/ticker/");
			//~ // Se decodifica la respuesta JSON
			$exchangeRates2 = json_decode($get, true);
			
			$this->coinmarketcap_message['type'] = 'message1';
			$this->coinmarketcap_message['message'] = '1';
			
		} else {
			
			$this->coinmarketcap_message['type'] = 'error';
			$this->coinmarketcap_message['message'] = '2';
			
		}
		
		return $exchangeRates2;
		
	}
	
}
