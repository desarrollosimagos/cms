<?php

defined('BASEPATH') OR exit('No direct script access allowed');


class MMessages extends CI_Model {
	
	//configuracion para mailtrap
	private $config = Array(
	  'protocol' => 'smtp',
	  'smtp_host' => 'smtp.mailtrap.io',
	  'smtp_port' => 2525,
	  'smtp_user' => '7070f0ddfd21e6',
	  'smtp_pass' => '0d07237bfd1f66',
	  'mailtype' => 'html',
	  'crlf' => "\r\n",
	  'newline' => "\r\n"
	);

    public function __construct() {
       
        parent::__construct();
        $this->load->database();
        
        //cargamos la librería email de ci
		$this->load->library("email");
    }

    // Método público, forma de insertar los datos
    public function insert($datos) {
		$result = $this->db->insert("messages", $datos);
		//return $result;
		$id = $this->db->insert_id();
		return $id;
    }
    
    // Método público para enviar un correo con los datos del mensaje
    public function enviarMailMessage($datos) {
        // Varios destinatarios
		//~ $para = 'aidan@example.com' . ', '; // atención a la coma
		$para = 'solorzano282017@gmail.com';

		// título
		$título = 'Lubricar Delibery: Nuevo mensaje';

		// mensaje
		$mensaje = '
		<!DOCTYPE html>
		<html>
		  <head>
			<meta name="viewport" content="width=device-width">
			<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
			<title>Simple Transactional Email</title>
			<style type="text/css">
			/* -------------------------------------
				INLINED WITH https://putsmail.com/inliner
			------------------------------------- */
			/* -------------------------------------
				RESPONSIVE AND MOBILE FRIENDLY STYLES
			------------------------------------- */
			@media only screen and (max-width: 620px) {
			  table[class=body] h1 {
				font-size: 28px !important;
				margin-bottom: 10px !important; }
			  table[class=body] p,
			  table[class=body] ul,
			  table[class=body] ol,
			  table[class=body] td,
			  table[class=body] span,
			  table[class=body] a {
				font-size: 16px !important; }
			  table[class=body] .wrapper,
			  table[class=body] .article {
				padding: 10px !important; }
			  table[class=body] .content {
				padding: 0 !important; }
			  table[class=body] .container {
				padding: 0 !important;
				width: 100% !important; }
			  table[class=body] .main {
				border-left-width: 0 !important;
				border-radius: 0 !important;
				border-right-width: 0 !important; }
			  table[class=body] .btn table {
				width: 100% !important; }
			  table[class=body] .btn a {
				width: 100% !important; }
			  table[class=body] .img-responsive {
				height: auto !important;
				max-width: 100% !important;
				width: auto !important; }}
			/* -------------------------------------
				PRESERVE THESE STYLES IN THE HEAD
			------------------------------------- */
			@media all {
			  .ExternalClass {
				width: 100%; }
			  .ExternalClass,
			  .ExternalClass p,
			  .ExternalClass span,
			  .ExternalClass font,
			  .ExternalClass td,
			  .ExternalClass div {
				line-height: 100%; }
			  .apple-link a {
				color: inherit !important;
				font-family: inherit !important;
				font-size: inherit !important;
				font-weight: inherit !important;
				line-height: inherit !important;
				text-decoration: none !important; }
			  .btn-primary table td:hover {
				background-color: #34495e !important; }
			  .btn-primary a:hover {
				background-color: #34495e !important;
				border-color: #34495e !important; } }
			</style>
		  </head>
		  <body class="" style="background-color:#f6f6f6;font-family:sans-serif;-webkit-font-smoothing:antialiased;font-size:14px;line-height:1.4;margin:0;padding:0;-ms-text-size-adjust:100%;-webkit-text-size-adjust:100%;">
			<table border="0" cellpadding="0" cellspacing="0" class="body" style="border-collapse:separate;mso-table-lspace:0pt;mso-table-rspace:0pt;background-color:#f6f6f6;width:100%;">
			  <tr>
				<td style="font-family:sans-serif;font-size:14px;vertical-align:top;">&nbsp;</td>
				<td class="container" style="font-family:sans-serif;font-size:14px;vertical-align:top;display:block;max-width:580px;padding:10px;width:580px;Margin:0 auto !important;">
				  <div class="content" style="box-sizing:border-box;display:block;Margin:0 auto;max-width:580px;padding:10px;">
					<!-- START CENTERED WHITE CONTAINER -->
					<span class="preheader" style="color:transparent;display:none;height:0;max-height:0;max-width:0;opacity:0;overflow:hidden;mso-hide:all;visibility:hidden;width:0;">This is preheader text. Some clients will show this text as a preview.</span>
					<table class="main" style="border-collapse:separate;mso-table-lspace:0pt;mso-table-rspace:0pt;background:#fff;border-radius:3px;width:100%;">
					  <!-- START MAIN CONTENT AREA -->
					  <tr>
						<td class="wrapper" style="font-family:sans-serif;font-size:14px;vertical-align:top;box-sizing:border-box;padding:20px;">
						  <table border="0" cellpadding="0" cellspacing="0" style="border-collapse:separate;mso-table-lspace:0pt;mso-table-rspace:0pt;width:100%;">
							<tr>
							  <td>
								<img src="'.assets_url().'public/img/demos/medical/logo-medical.png" alt="No se pudo mostrar" width="80px;" height="50px;">
							  </td>
							</tr>
							<tr>
							  <td style="font-family:sans-serif;font-size:14px;vertical-align:top;">
								<h2 style="font-size:15px;line-height:28px;margin:0 0 12px 0">Ha recibido un nuevo mensaje</h2>
								<p style="font-family:sans-serif;font-size:14px;font-weight:normal;margin:0;Margin-bottom:15px;">A continuación los detalles...</p>
								
								<ul class="m_392633976507179222profile-list" style="display:block;margin:15px 20px;padding:0;list-style:none;border-top:1px solid #eee">
								  <li style="display:block;margin:0;padding:5px 0;border-bottom:1px solid #eee">
									<strong>Nombre:</strong>
									'.$datos['name'].'
								  </li>
								  <li style="display:block;margin:0;padding:5px 0;border-bottom:1px solid #eee">
									<strong>Email:</strong>
									'.$datos['email'].'
								  </li>
								  <li style="display:block;margin:0;padding:5px 0;border-bottom:1px solid #eee">
									<strong>Asunto:</strong>
									'.$datos['subject'].'
								  </li>
								  <li style="display:block;margin:0;padding:5px 0;border-bottom:1px solid #eee">
									<strong>Mensaje:</strong>
									'.$datos['message'].'
								  </li>
								</ul>
							  </td>
							</tr>
						  </table>
						</td>
					  </tr>
					  <!-- END MAIN CONTENT AREA -->
					</table>
					<!-- START FOOTER -->
					<div class="footer" style="clear:both;padding-top:10px;text-align:center;width:100%;">
					  <table border="0" cellpadding="0" cellspacing="0" style="border-collapse:separate;mso-table-lspace:0pt;mso-table-rspace:0pt;width:100%;">
						<tr>
						  <td class="content-block" style="font-family:sans-serif;font-size:14px;vertical-align:top;color:#999999;font-size:12px;text-align:center;">
							<span class="apple-link" style="color:#999999;font-size:12px;text-align:center;">Lubricar Delibery, 3 Abbey Road, San Francisco CA 94102</span>
							<br>
							 <!--Don\'t like these emails? <a href="http://i.imgur.com/CScmqnj.gif" style="color:#3498db;text-decoration:underline;color:#999999;font-size:12px;text-align:center;">Unsubscribe</a>.-->
						  </td>
						</tr>
						<tr>
						  <td class="content-block powered-by" style="font-family:sans-serif;font-size:14px;vertical-align:top;color:#999999;font-size:12px;text-align:center;">
							Powered by <a href="http://htmlemail.io" style="color:#3498db;text-decoration:underline;color:#999999;font-size:12px;text-align:center;text-decoration:none;">HTMLemail</a>.
						  </td>
						</tr>
					  </table>
					</div>
					<!-- END FOOTER -->
					<!-- END CENTERED WHITE CONTAINER -->
				  </div>
				</td>
				<td style="font-family:sans-serif;font-size:14px;vertical-align:top;">&nbsp;</td>
			  </tr>
			</table>
		  </body>
		</html>
		';

		// Cargamos la configuración para enviar con mailtrap (config)
		$this->email->initialize($this->config);

		$this->email->from('contacto@lubricardelivery.com');
		$this->email->to($para);
		$this->email->subject($título);
		$this->email->message($mensaje);
		$this->email->send();
		// con esto podemos ver el resultado
		//~ var_dump($this->email->print_debugger());
	}
    

}
?>
