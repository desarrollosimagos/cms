<?php
defined('BASEPATH') OR exit('No direct script access allowed');
//~ $datos_sesion = array();
?><!DOCTYPE html>
<html lang="en">
<head>
	<!-- Metadata -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title><?php echo $this->config->item('title_app'); ?></title>
	<!-- CSS Files -->
    <link href="<?php echo assets_url('css/bootstrap.min.css');?>" rel="stylesheet">
    <link href="<?php echo assets_url('font-awesome/css/font-awesome.css');?>" rel="stylesheet">
	<link href="<?php echo assets_url('css/plugins/iCheck/custom.css');?>" rel="stylesheet">
    <link href="<?php echo assets_url('css/plugins/steps/jquery.steps.css');?>" rel="stylesheet">
	<link href="<?php echo assets_url('css/plugins/dataTables/datatables.min.css');?>" rel="stylesheet">
	<link href="<?php echo assets_url('css/plugins/select2/select2.min.css');?>" rel="stylesheet">
	<link href="<?php echo assets_url('js/datatables.net-bs/css/dataTables.bootstrap.css'); ?>" rel="stylesheet" />
    <link rel="stylesheet" href="<?php echo assets_url('css/dataTables.responsive.css'); ?>">
    <link href="<?php echo assets_url('js/datatables.net-responsive-bs/css/responsive.bootstrap.min.css'); ?>" rel="stylesheet">
    <!-- Toastr style -->
    <link href="<?php echo assets_url('css/plugins/toastr/toastr.min.css');?>" rel="stylesheet">
	<link href="<?php echo assets_url('css/animate.css');?>" rel="stylesheet">
    <link href="<?php echo assets_url('css/style.css');?>" rel="stylesheet">
	<link href="<?php echo assets_url('css/plugins/datapicker/datepicker3.css');?>" rel="stylesheet">
	<link href="<?php echo assets_url('css/plugins/datetimepicker/jquery.datetimepicker.min.css');?>" rel="stylesheet">
	<!-- Sweet Alert -->
    <link href="<?php echo assets_url('css/plugins/sweetalert/sweetalert.css');?>" rel="stylesheet">
	
	<!-- Custom and plugin javascript -->
	<script src="<?php echo assets_url('js/jquery-3.1.1.min.js');?>"></script>
	<script src="<?php echo assets_url('js/bootstrap.min.js');?>"></script>
	<script src="<?php echo assets_url('js/plugins/metisMenu/jquery.metisMenu.js');?>"></script>
	<script src="<?php echo assets_url('js/plugins/slimscroll/jquery.slimscroll.min.js');?>"></script>
	<script src="<?php echo assets_url('js/plugins/dataTables/datatables.min.js');?>"></script>
	<script src="<?php echo assets_url('js/plugins/select2/select2.full.min.js');?>"></script>
	<script src="<?php echo assets_url('js/datatables.net/js/jquery.dataTables.min.js'); ?>"></script>
	<script src="<?php echo assets_url('js/datatables.net-bs/js/dataTables.bootstrap.min.js'); ?>"></script>
	<script src="<?php echo assets_url('js/datatables.net-responsive/js/dataTables.responsive.min.js'); ?>"></script>
	<script src="<?php echo assets_url('js/inspinia.js');?>"></script>
	<script src="<?php echo assets_url('js/plugins/pace/pace.min.js');?>"></script>
	<!--<script src="<?php echo assets_url('js/plugins/slimscroll/jquery.slimscroll.min.js');?>"></script>-->
	<!-- Sweet alert -->
    <script src="<?php echo assets_url('js/plugins/sweetalert/sweetalert.min.js');?>"></script>
	<!-- Custom and plugin javascript -->
    <script src="<?php echo assets_url('js/inspinia.js');?>"></script>
    <script src="<?php echo assets_url('js/plugins/pace/pace.min.js');?>"></script>
    <!-- Steps -->
    <script src="<?php echo assets_url('js/plugins/steps/jquery.steps.min.js');?>"></script>
	<!-- Jquery Validate -->
    <script src="<?php echo assets_url('js/plugins/validate/jquery.validate.min.js');?>"></script>
	<script src="<?php echo assets_url('js/jquery.numeric.js');?>"></script>
	<!-- Data picker -->
	<script src="<?php echo assets_url('js/plugins/datapicker/bootstrap-datepicker.js');?>"></script>
	<!-- Date range picker -->
	<!--<script src="<?php echo assets_url('js/plugins/daterangepicker/daterangepicker.js');?>"></script>-->
	<!-- Date time picker -->
	<script src="<?php echo assets_url('js/plugins/datetimepicker/jquery.datetimepicker.full.js');?>"></script>
	<!-- Typehead -->
    <script src="<?php echo assets_url('js/plugins/typehead/bootstrap3-typeahead.min.js');?>"></script>
    
	<!-- iCheck -->
	<script src="<?php echo assets_url('js/plugins/iCheck/icheck.min.js');?>"></script>
	
	<!-- Toastr script -->
    <script src="<?php echo assets_url('js/plugins/toastr/toastr.min.js');?>"></script>
    
	<style>
	.page-scroll {
		color: #ffffff !important;
		background-color: transparent !important;
		padding: 20px 10px !important;
	}
	</style>
	
</head>

<!-- Clases sin logueo -->
<?php
$rutas_publicas = array();
if(isset($this->session->userdata['logged_in']) && $this->router->class != 'Welcome'){
	$top_navigation = "";
	$fixed_nav = "fixed-nav";
	$md_skin = "";
	$no_skin_config = "";
}else{
	$top_navigation = "top-navigation";
	$fixed_nav = "";
	$md_skin = "md-skin";
	$no_skin_config = "no-skin-config";
}
?>
<!-- Clases sin logueo -->

<body class="<?php echo $md_skin; ?> <?php echo $fixed_nav; ?> <?php echo $no_skin_config; ?> <?php echo $top_navigation; ?>">
	<div id="wrapper">
		<?php if(isset($this->session->userdata['logged_in']) && $this->router->class != 'Welcome'){ ?>
		<input type="hidden" id="active_session" value="<?php echo $this->session->userdata['logged_in']['id']; ?>">
		<input type="hidden" id="time_session" value="<?php echo $this->session->userdata['logged_in']['time']; ?>">
		<nav class="navbar-default navbar-static-side" role="navigation">
			<div class="sidebar-collapse">
				<ul class="nav metismenu" id="side-menu">
					<li class="nav-header" style="background-color:#1b426c !important">
						<div class="dropdown profile-element">
							<!--<span>
								<img alt="image" class="img-circle" src="<?php echo assets_url('img/profile_small.jpg'); ?>" />
							</span>-->
							<a class="dropdown-toggle" href="<?php echo base_url();?>profileuser">
								<span class="clear">
									<span class="block m-t-xs">
										<strong class="font-bold"><?php echo $this->session->userdata('logged_in')['username'];?></strong>
									</span>
									<span class="text-muted text-xs block"><?php echo $this->session->userdata('logged_in')['profile_name'];?>
										<b class="caret"></b>
									</span>
								</span>
							</a>
							<!--<ul class="dropdown-menu animated fadeInRight m-t-xs">
								<li><a href="<?php echo base_url();?>home"><?php echo $this->lang->line('nav_static_home'); ?></a></li>
								<li><a href="">Perfil</a></li>
								<li><a href="contacts.html">Contactos</a></li>
								<li class="divider"></li>
								<li><a href="<?php echo base_url();?>logout"><?php echo $this->lang->line('top_bar_logout'); ?></a></li>
							</ul>-->
						</div>
						<div class="logo-element">
							<!--IN+-->
							<img src="<?php echo assets_url('img/logos/'.$this->config->item('logo_menu_admin')); ?>">
						</div>
					</li>
					
					<!-- Carga de menú lateral -->
					<?php echo menu(); ?>
					<!-- Carga de menú lateral -->

				</ul>

			</div>
		</nav>
		<?php } else { ?>
		<input type="hidden" id="active_session" value="">
		<?php } ?>

		<div id="page-wrapper" class="gray-bg">
			<div class="row border-bottom">
				<nav class="navbar navbar-fixed-top" role="navigation" style="margin-bottom: 0; background-color:#1b426c !important">
					<div class="navbar-header">
						<?php if(isset($this->session->userdata['logged_in']) && $this->router->class != 'Welcome'){ ?>
						<a class="navbar-minimalize minimalize-styl-2 btn btn-primary " href="#"><i class="fa fa-bars"></i> </a>
						<?php } ?>
						<img src="<?php echo assets_url('img/logos/'.$this->config->item('logo_menu_bar')); ?>" style="margin-top: 5px;">
						<!--<form role="search" class="navbar-form-custom" action="search_results.html">
							<div class="form-group">
								<input type="text" placeholder="Buscar..." class="form-control" name="top-search" id="top-search">
							</div>
						</form>-->
					</div>
					
					<ul class="nav navbar-top-links navbar-right">
						<!-- Construimos el menú superior combinando el arreglo de menús del config.php más las etiquetas de idiomas -->
						<?php $i = 1; ?>
						<?php foreach($this->config->item('public_menu') as $public_menu){ ?>
							<?php $public_menu[0] = $this->lang->line('menu_bar_menu4'); ?>
							<?php if(!isset($this->session->userdata['logged_in']) && $public_menu[2] == 2){ ?>
								<li style="display:none;"><a class="page-scroll" href="<?php echo $public_menu[1] ?>"><?php echo $public_menu[0] ?></a></li>
							<?php }else if(isset($this->session->userdata['logged_in']) && $this->session->userdata['logged_in']['profile_id'] != 4){ ?>
								<li><a class="page-scroll" href="<?php echo $public_menu[1] ?>"><?php echo $public_menu[0] ?></a></li>
							<?php }else{ ?>
								<li><a class="page-scroll" href="<?php echo base_url(); ?>home">Home</a></li>
							<?php } ?>
                        <?php $i++; } ?>
						<!--<li class="dropdown">
							<a class="dropdown-toggle count-info" data-toggle="dropdown" href="#">
								<i class="fa fa-envelope"></i>  <span class="label label-warning">16</span>
							</a>
							<ul class="dropdown-menu dropdown-messages">
								<li>
									<div class="dropdown-messages-box">
										<a href="profile.html" class="pull-left">
											<img alt="image" class="img-circle" src="<?php echo assets_url('img/a7.jpg'); ?>">
										</a>
										<div class="media-body">
											<small class="pull-right">46h ago</small>
											<strong>Mike Loreipsum</strong> started following <strong>Monica Smith</strong>. <br>
											<small class="text-muted">3 days ago at 7:58 pm - 10.06.2014</small>
										</div>
									</div>
								</li>
								<li class="divider"></li>
								<li>
									<div class="dropdown-messages-box">
										<a href="profile.html" class="pull-left">
											<img alt="image" class="img-circle" src="<?php echo assets_url('img/a4.jpg'); ?>">
										</a>
										<div class="media-body ">
											<small class="pull-right text-navy">5h ago</small>
											<strong>Chris Johnatan Overtunk</strong> started following <strong>Monica Smith</strong>. <br>
											<small class="text-muted">Yesterday 1:21 pm - 11.06.2014</small>
										</div>
									</div>
								</li>
								<li class="divider"></li>
								<li>
									<div class="dropdown-messages-box">
										<a href="profile.html" class="pull-left">
											<img alt="image" class="img-circle" src="<?php echo assets_url('img/profile.jpg');?>">
										</a>
										<div class="media-body ">
											<small class="pull-right">23h ago</small>
											<strong>Monica Smith</strong> love <strong>Kim Smith</strong>. <br>
											<small class="text-muted">2 days ago at 2:30 am - 11.06.2014</small>
										</div>
									</div>
								</li>
								<li class="divider"></li>
								<li>
									<div class="text-center link-block">
										<a href="mailbox.html">
											<i class="fa fa-envelope"></i> <strong>Read All Messages</strong>
										</a>
									</div>
								</li>
							</ul>
						</li>-->
						
						<li class="dropdown" id="li_language">
							<a class="dropdown-toggle count-info page-scroll" data-toggle="dropdown" href="#" title="Idioma">
								<i class="fa fa-language"></i> <span class="label label-warning" id="span_num_respuestas"></span>
							</a>
							<ul class="dropdown-menu">
								<?php $i = 1; ?>
								<?php foreach($this->session->userdata['languages'] as $language){ ?>
									<li>
										<a onclick="javascript:window.location.href='<?php echo base_url(); ?>LanguageSwitcher/switchLang/<?php echo $language->name; ?>';" href="#">
											<div>
												<img src="<?php echo assets_url('img/language/'.$language->name.'.png');?>" style="height:25px;width:25px;"> 
												<?php echo $this->lang->line('language_'.$language->name); ?>
											</div>
										</a>
									</li>
									<?php if($i < count($this->session->userdata['languages'])){ ?>
									<li class="divider"></li>
									<?php } ?>
									<?php $i++;?>
								<?php } ?>
							</ul>
						</li>
			
						<?php if(isset($this->session->userdata['logged_in'])){ ?>
						<li>
							<a class="page-scroll" href="<?php echo base_url();?>logout">
								<i class="fa fa-sign-out"></i> <?php echo $this->lang->line('top_bar_logout'); ?>
							</a>
						</li>
						
						<?php } else { ?>
						<li>
							<a class="page-scroll" href="<?php echo base_url();?>login">
								<i class="fa fa-sign-in"></i> <?php echo $this->lang->line('top_bar_login'); ?>
							</a>
						</li>
						<?php } ?>
						
					</ul>
			
				</nav>
			</div>
			
			<script>
			$(document).ready(function () {
				// Aplicamos select2() a todos los combos select
				$("select").select2();
				
				// Aplicamos iCheck() a todos los campos checkbox y radio buttoms
				$('.i-checks').iCheck({
					checkboxClass: 'icheckbox_square-green',
					radioClass: 'iradio_square-green',
				});
				
				// Función añadida manualmente para alternar entre mini-barra y barra de menú completa u ocultar en dispositivos móviles
				// .navbar-minimalize = clase del botón de acción
				// .md-skin = clase de la etiqueta body asignada automáticamente por los plugins de la plantilla
				$(".navbar-minimalize").on('click', function(){
					var cadena1 = "md-skin fixed-nav no-skin-config pace-done pace-done";
					var cadena1_small = "md-skin fixed-nav no-skin-config body-small pace-done pace-done";
					var cadena2 = "md-skin fixed-nav no-skin-config pace-done pace-done mini-navbar";
					var cadena2_small = "md-skin fixed-nav no-skin-config body-small pace-done pace-done mini-navbar";
					if($(".md-skin").attr("class") == cadena1 || $(".md-skin").attr("class") == cadena1_small){
						$(".md-skin").addClass("mini-navbar");
					}else if($(".md-skin").attr("class") == cadena2 || $(".md-skin").attr("class") == cadena2_small){
						$(".md-skin").removeClass("mini-navbar");
					}
				});
				
				//~ // Metodo de verificación de tiempo de sesión cada media hora
				//~ setInterval(function(){ if($("#active_session").val().trim() != ""){ 
					//~ $.post('<?php echo base_url(); ?>users/update_session', {'time_session':$("#time_session").val().trim()}, function (response) {
						//~ 
					//~ }, 'json').done(function(response2) {
						//~ 
						//~ if(response2['update'] == "ok"){
							//~ alert('El tiempo de su sesión a caducado, inicie sesión nuevamente...');
							//~ window.location.href = '<?php echo base_url(); ?>logout';
						//~ }
						//~ 
					//~ });
				//~ } }, 1800000);
				
			});
			
			</script>
			
		<!-- Validación de acciones -->
		<?php echo validar_acciones(); ?>
		<!-- Validación de acciones -->		
