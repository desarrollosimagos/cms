<!-- FooTable -->
<!--<link href="<?php echo assets_url('css/plugins/footable/footable.bootstrap.css');?>" rel="stylesheet">-->
<link href="<?php echo assets_url('css/plugins/footable/footable.core.css');?>" rel="stylesheet">
<style>
.views-number {
    font-size: 18px !important;
}
</style>

<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2><?php echo $this->lang->line('page_heading_title'); ?></h2>
        <ol class="breadcrumb">
            <li>
                <a href="<?php echo base_url() ?>home"><?php echo $this->lang->line('page_heading_home'); ?></a>
            </li>
            <li class="active">
                <strong><?php echo $this->lang->line('page_heading_subtitle'); ?></strong>
            </li>
        </ol>
    </div>
</div>

<!-- Campo oculto que almacena el url base del proyecto -->
<input type="hidden" id="base_url" value="<?php echo base_url(); ?>">
<!-- Campos ocultos que almacenan el tipo de moneda de la cuenta del usuario logueado -->
<input type="hidden" id="iso_currency_user" value="<?php echo $this->session->userdata('logged_in')['coin_iso']; ?>">
<input type="hidden" id="symbol_currency_user" value="<?php echo $this->session->userdata('logged_in')['coin_symbol']; ?>">
<input type="hidden" id="decimals_currency_user" value="<?php echo $this->session->userdata('logged_in')['coin_decimals']; ?>">

<!-- Campos ocultos que almacenan los nombres del menú y el submenú de la vista actual -->
<input type="hidden" id="ident" value="<?php echo $ident; ?>">
<input type="hidden" id="ident_sub" value="<?php echo $ident_sub; ?>">

<div class="wrapper wrapper-content animated fadeInUp">
	
	<!-- Alerta para el mensaje de la api de openexchangerates -->
	<?php if($openexchangerates_message['type'] == 'error'){ ?>
	<div class="col-lg-12 alert alert-danger alert-dismissable">
		<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
		<?php 
		echo $this->lang->line('openexchangerates_message_error');
		?>
	</div>
	<?php }else if($openexchangerates_message['type'] == 'message1'){ ?>
	<div class="col-lg-12 alert alert-success alert-dismissable">
		<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
		<?php 
		echo $this->lang->line('openexchangerates_message');
		?>
	</div>
	<?php }else{ ?>
		<div class="col-lg-12 alert alert-success alert-dismissable">
		<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
		<?php 
		echo $this->lang->line('openexchangerates_message2');
		?>
	</div>
	<?php } ?>
	
	<!-- Alerta para cuando el mensaje de la api de coinmarketcap es un error -->
	<?php if($coinmarketcap_message['type'] == 'error'){ ?>
	<div class="col-lg-12 alert alert-danger alert-dismissable">
		<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
		<?php 
		echo $this->lang->line('coinmarketcap_message_error');
		?>
	</div>
	<?php } ?>
	
	<!-- Alerta para cuando el mensaje de la api de dolartoday es un error -->
	<?php if($coin_rate_message['type'] == 'error'){ ?>
	<div class="col-lg-12 alert alert-danger alert-dismissable">
		<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
		<?php 
		if($coin_rate_message['message'] == '1' || $coin_rate_message['message'] == '2'){
			echo $this->lang->line('coin_rate_message');
		}
		?>
	</div>
	<?php } ?>
	
	<div class="row">
		<div class="col-lg-6">
			<div class="ibox">
				<div class="ibox-title">
					<h5><?php echo $this->lang->line('financial_summary'); ?></h5>
				</div>
				<div class="ibox-content">
					<div class="text-center">
						<canvas id="finances" height="140"></canvas>
					</div>
				</div>
			</div>
		</div>
		<div class="col-lg-6">
			<div class="ibox float-e-margins">
				<div class="ibox-title">
					<h5><?php echo $this->lang->line('investment_summary'); ?></h5>
				</div>
				<div class="ibox-content">
					<div class="text-center">
						<canvas id="investments" height="140"></canvas>
					</div>
				</div>
			</div>
		</div>
		
		<!-- Campos ocultos para almacenamiento de los montos para los gráficos -->
		<input id="graph_invertido" type="hidden" value="<?php echo $fondo_resumen['resumen_general']->capital_invested; ?>">
		<input id="graph_retornado" type="hidden" value="<?php echo $fondo_resumen['resumen_general']->returned_capital; ?>">
		<input id="graph_disponible" type="hidden" value="<?php echo $fondo_resumen['resumen_general']->retirement_capital_available; ?>">
		<input id="graph_aprobado" type="hidden" value="<?php echo $fondo_resumen['resumen_general']->approved_capital; ?>">
		<input id="graph_ingreso_pendiente" type="hidden" value="<?php echo $fondo_resumen['resumen_general']->pending_entry; ?>">
		<input id="graph_egreso_pendiente" type="hidden" value="<?php echo $fondo_resumen['resumen_general']->pending_exit; ?>">
		<!-- Campos ocultos para almacenamiento del idioma en sesión -->
		<input id="lang_session" type="hidden" value="<?php echo $this->session->userdata('site_lang'); ?>">
		<!-- Campos ocultos para almacenamiento del perfil del usuario -->
		<input id="profile_id" type="hidden" value="<?php echo $this->session->userdata('logged_in')['profile_id']; ?>">
		
	</div>
	
	<!-- Cuerpo de la sección de cintillo de montos -->
	<div class="row">
		<div class="col-lg-6">
			<div class="contact-box">
				
				<div class="contact-box-footer" style="border-top:0px;">
					<div>
						<!--<div class="col-md-4 forum-info">
							<span class="views-number" id="span_aprobado">
								<?php echo $fondo_resumen['resumen_general']->approved_capital; ?>
							</span>
							<div>
								<small>Capital aprobado</small>
							</div>
						</div>-->
						<div class="col-md-4 forum-info">
							<span class="views-number" id="span_retornado">
								<?php echo $fondo_resumen['resumen_general']->retirement_capital_available; ?>
							</span>
							<div>
								<small><?php echo $this->lang->line('capital_account'); ?></small>
							</div>
						</div>
						<div class="col-md-4 forum-info">
							<span class="views-number" id="span_ingreso_pendiente">
								<?php echo $fondo_resumen['resumen_general']->pending_entry; ?>
							</span>
							<div>
								<small><?php echo $this->lang->line('pending_deposit'); ?></small>
							</div>
						</div>
						<div class="col-md-4 forum-info">
							<span class="views-number" id="span_egreso_pendiente">
								<?php echo $fondo_resumen['resumen_general']->pending_exit; ?>
							</span>
							<div>
								<small><?php echo $this->lang->line('pending_retirement'); ?></small>
							</div>
						</div>
					</div>
					<br>
					<br>
				</div>
			</div>
		</div>
		<div class="col-lg-6">
			<div class="contact-box">
				
				<div class="contact-box-footer" style="border-top:0px;">
					<div>
						<div class="col-md-4 forum-info">
							<span class="views-number">
								<?php echo $fondo_resumen['resumen_general']->capital_invested; ?>
							</span>
							<div>
								<small><?php echo $this->lang->line('capital_invested'); ?></small>
							</div>
						</div>
						<div class="col-md-4 forum-info">
							<span class="views-number" id="span_invertido">
								<?php echo $fondo_resumen['resumen_general']->returned_capital; ?>
							</span>
							<div>
								<small><?php echo $this->lang->line('dividend'); ?></small>
							</div>
						</div>
						<div class="col-md-4 forum-info">
							<span class="views-number" id="span_inversion_pendiente">
								<?php echo $fondo_resumen['resumen_general']->pending_invest; ?>
							</span>
							<div>
								<small><?php echo $this->lang->line('pending_investment'); ?></small>
							</div>
						</div>
					</div>
					<br>
					<br>
				</div>
				
			</div>
		</div>
		
	</div>
	<!-- Cierre del cuerpo de la sección de cintillo de montos -->
	
	<!-- Cuerpo de la sección de montos agrupados por moneda -->
	<div class="ibox float-e-margins">
		<div class="ibox-title">
			<h5><?php echo $this->lang->line('view_list_summary_currency_title_projects'); ?></h5>

			<div class="ibox-tools">
				<a class="collapse-link">
					<i class="fa fa-chevron-up"></i>
				</a>
				<a class="close-link">
					<i class="fa fa-times"></i>
				</a>
			</div>
		</div>
		<div class="ibox-content">
			
			<div class="col-sm-4 col-md-offset-8">
				<div class="input-group">
					<input type="text" placeholder="Search in table" class="input-sm form-control" id="filter_coin">
					<span class="input-group-btn">
						<button type="button" class="btn btn-sm btn-primary"> Go!</button>
					</span>
				</div>
			</div>
			
			<table class="footable table table-stripped toggle-arrow-tiny" data-page-size="10" data-filter=#filter_coin>
				<thead>
					<tr style="text-align: center">
						<th><?php echo $this->lang->line('view_list_currency_projects'); ?></th>
						<th><?php echo $this->lang->line('view_list_currency_amount_projects'); ?></th>
						<th data-hide="phone,tablet"><?php echo $this->lang->line('view_list_currency_amountproject_projects'); ?></th>
					</tr>
				</thead>
				<tbody>
					<?php $i = 1; ?>
					<?php foreach ($transactions_coins as $transact) { ?>
						<tr style="text-align: center">
							<td>
								<?php echo $transact->coin; ?>
							</td>
							<td>
								<?php echo $transact->amount; ?>
							</td>
							<td>
								<?php echo $transact->amount_user; ?>
							</td>
						</tr>
						<?php $i++ ?>
					<?php } ?>
				</tbody>
				<tfoot>
					<tr>
						<td class='text-center' colspan='3'>
							<ul class='pagination'></ul>
						</td>
					</tr>
				</tfoot>
			</table>
			
		</div>
		
	</div>
	<!-- Cierre del cuerpo de la sección de montos agrupados por moneda -->
	
	<!-- Cuerpo de la sección de resumen por proyecto -->
	<div class="ibox">
		<div class="ibox-title">
			<h5><?php echo $this->lang->line('project_summary_title'); ?></h5>
			
			<div class="ibox-tools">
				<a class="collapse-link">
					<i class="fa fa-chevron-up"></i>
				</a>
				<!--<a class="dropdown-toggle" data-toggle="dropdown" href="#">
					<i class="fa fa-wrench"></i>
				</a>
				<ul class="dropdown-menu dropdown-user">
					<li><a href="#">Config option 1</a>
					</li>
					<li><a href="#">Config option 2</a>
					</li>
				</ul>-->
				<a class="close-link">
					<i class="fa fa-times"></i>
				</a>
			</div>
		</div>
		<div class="ibox-content">
			
			<?php $filter_profile = array(1, 2); ?>
			<?php if(in_array($this->session->userdata('logged_in')['profile_id'], $filter_profile)){ ?> 
			<div class="col-sm-4 col-md-offset-8">
				<div class="input-group">
					<input type="text" placeholder="Search in table" class="input-sm form-control" id="filter_by_project">
					<span class="input-group-btn">
						<button type="button" class="btn btn-sm btn-primary"> Go!</button>
					</span>
				</div>
			</div>
			<?php } ?>

			<table id="tab_transactions_user" data-page-size="10" data-filter=#filter_by_project class="footable table table-stripped toggle-arrow-tiny">
				<thead>
					<tr>
						<th>#</th>
						<th data-hide="phone,tablet" ><?php echo $this->lang->line('project_summary_name'); ?></th>
						<th data-hide="phone,tablet" ><?php echo $this->lang->line('project_summary_type'); ?></th>
						<th data-hide="phone,tablet" ><?php echo $this->lang->line('capital_invested'); ?></th>
						<th data-hide="phone,tablet" ><?php echo $this->lang->line('dividend'); ?></th>
						<th data-hide="phone,tablet" ><?php echo $this->lang->line('capital_project'); ?></th>
					</tr>
				</thead>
				<tbody>
					<?php $i = 1; ?>
					<?php foreach ($fondo_resumen['resumen_projects'] as $fondo) { ?>
						<tr style="text-align: center">
							<td>
								<?php echo $i; ?>
							</td>
							<td>
								<?php echo $fondo->name; ?>
							</td>
							<td>
								<?php echo $fondo->type; ?>
							</td>
							<td>
								<?php echo $fondo->capital_invested; ?>
							</td>
							<td>
								<?php echo $fondo->returned_capital; ?>
							</td>
							<td>
								<?php echo $fondo->retirement_capital_available; ?>
							</td>
						</tr>
						<?php $i++ ?>
					<?php } ?>
				</tbody>
				<tfoot>
					<tr>
						<td class='text-center' colspan='8'>
							<ul class='pagination'></ul>
						</td>
					</tr>
				</tfoot>
			</table>
		</div>
	</div>
	<!-- Cierre del cuerpo de la sección de resume por proyecto -->
	
	<!-- Cuerpo de la sección de transacciones asociadas -->
	<div class="ibox">
		<div class="ibox-title">
			<h5><?php echo $this->lang->line('transactions_title'); ?></h5>
			<div class="ibox-tools">
				<a class="collapse-link">
					<i class="fa fa-chevron-up"></i>
				</a>
				<!--<a class="dropdown-toggle" data-toggle="dropdown" href="#">
					<i class="fa fa-wrench"></i>
				</a>
				<ul class="dropdown-menu dropdown-user">
					<li><a href="#">Config option 1</a>
					</li>
					<li><a href="#">Config option 2</a>
					</li>
				</ul>-->
				<a class="close-link">
					<i class="fa fa-times"></i>
				</a>
			</div>
		</div>
		<div class="ibox-content">
			<?php $filter_profile = array(1, 2, 4); ?>
			<?php if(in_array($this->session->userdata('logged_in')['profile_id'], $filter_profile)){ ?> 
			<div class="col-sm-4 col-md-offset-8">
				<div class="input-group">
					<input type="text" placeholder="Search in table" class="input-sm form-control" id="filter3">
					<span class="input-group-btn">
						<button type="button" class="btn btn-sm btn-primary"> Go!</button>
					</span>
				</div>
			</div>
			<?php } ?>

			<table id="tab_transactions" data-paging="true" class="table table-striped dt-responsive table-hover">
				<thead>
					<tr>
						<th data-hide="phone,tablet" >Id</th>
						<th data-hide="phone,tablet" ><?php echo $this->lang->line('transactions_date'); ?></th>
						<!--<th ><?php echo $this->lang->line('transactions_user'); ?></th>-->
						<th data-hide="phone,tablet" ><?php echo $this->lang->line('transactions_type'); ?></th>
						<th data-hide="phone,tablet" ><?php echo $this->lang->line('transactions_amount'); ?></th>
						<?php if($this->session->userdata('logged_in')['profile_id'] == 1 || $this->session->userdata('logged_in')['profile_id'] == 2){ ?>
						<th data-hide="phone,tablet" ><?php echo $this->lang->line('transactions_account'); ?></th>
						<?php } ?>
						<th data-hide="phone,tablet" ><?php echo $this->lang->line('transactions_description'); ?></th>
						<th data-hide="phone,tablet" ><?php echo $this->lang->line('transactions_reference'); ?></th>
						<th data-hide="phone,tablet" ><?php echo $this->lang->line('transactions_observations'); ?></th>
						<th data-hide="phone,tablet" ><?php echo $this->lang->line('transactions_status'); ?></th>
					</tr>
				</thead>
				<!--<tbody>
					<?php $i = 1; ?>
					<?php foreach ($listar as $fondo) { ?>
						<tr style="text-align: center">
							<td>
								<?php echo $fondo->id; ?>
							</td>
							<td>
								<?php echo $fondo->date; ?>
							</td>
							<td>
								<?php echo $fondo->user_name; ?>
							</td>
							<td>
								<?php
								echo $this->lang->line('transactions_type_'.$fondo->type);
								?>
							</td>
							<td>
								<?php echo number_format($fondo->amount, $fondo->coin_decimals, '.', '')."  ".$fondo->coin_symbol; ?>
							</td>
							<?php if($this->session->userdata('logged_in')['profile_id'] == 1 || $this->session->userdata('logged_in')['profile_id'] == 2){ ?>
							<td>
								<?php echo $fondo->alias." - ".$fondo->number; ?>
							</td>
							<?php } ?>
							<td>
								<?php echo $fondo->description; ?>
							</td>
							<td>
								<?php echo $fondo->reference; ?>
							</td>
							<td>
								<?php echo $fondo->observation; ?>
							</td>
							<td>
								<?php
								if($fondo->status == 'approved'){
									echo "<span style='color:#337AB7;'>".$this->lang->line('transactions_status_approved')."</span>";
								}else if($fondo->status == 'waiting'){
									echo "<span style='color:#A5D353;'>".$this->lang->line('transactions_status_waiting')."</span>";
								}else{
									echo "<span style='color:#D33333;'>".$this->lang->line('transactions_status_denied')."</span>";
								}
								?>
							</td>
						</tr>
						<?php $i++ ?>
					<?php } ?>
				</tbody>
				<tfoot>
					<tr>
						<?php if($this->session->userdata('logged_in')['profile_id'] == 1 || $this->session->userdata('logged_in')['profile_id'] == 2){ ?>
						<td class='text-center' colspan='10'>
							<ul class='pagination'></ul>
						</td>
						<?php }else{ ?>
						<td class='text-center' colspan='9'>
							<ul class='pagination'></ul>
						</td>
						<?php } ?>
					</tr>
				</tfoot>-->
			</table>
					
		</div>
	</div>
	<!-- Cierre del cuerpo de la sección de transacciones asociadas -->
	
</div>

<!-- FooTable -->
<!--<script src="<?php echo assets_url('js/plugins/footable/footable.js');?>"></script>-->
<script src="<?php echo assets_url('js/plugins/footable/footable.all.min.js');?>"></script>

<!-- ChartJS-->
<script src="<?php echo assets_url('js/plugins/chartJs/Chart.min.js');?>"></script>

<!-- Page-Level Scripts -->
<script src="<?php echo assets_url(); ?>script/resumen.js"></script>
