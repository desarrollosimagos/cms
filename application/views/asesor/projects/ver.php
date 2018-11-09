<style>
.ibox-content {
    background-color: #ffffff;
    color: inherit;
    padding: 15px 20px 20px 20px;
    border-color: #e7eaec;
    border-image: none;
    border-style: solid solid solid;
    border-width: 1px 1px 1px 1px;
}

/* Forzamos que la alineación de los títulos de las tablas sea siempre centrados */
th {
    text-align: center !important;
}
</style>

<!-- FooTable -->
<!--<link href="<?php echo assets_url('css/plugins/footable/footable.bootstrap.css');?>" rel="stylesheet">-->
<link href="<?php echo assets_url('css/plugins/footable/footable.core.css');?>" rel="stylesheet">

<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2><?php echo $ver[0]->name; ?></h2>
        <ol class="breadcrumb">
            <li>
                <a href="<?php echo base_url() ?>home"><?php echo $this->lang->line('heading_home_projects_view'); ?></a>
            </li>
            
            <li>
                <a href="<?php echo base_url() ?>projects"><?php echo $this->lang->line('heading_subtitle_projects_view'); ?></a>
            </li>
           
            <li class="active">
                <strong><?php echo $ver[0]->name; ?></strong>
            </li>
        </ol>
    </div>
</div>

<!-- Campos ocultos que almacenan los nombres del menú y el submenú de la vista actual -->
<input type="hidden" id="ident" value="<?php echo $ident; ?>">
<input type="hidden" id="ident_sub" value="<?php echo $ident_sub; ?>">

<div class="wrapper wrapper-content animated fadeInUp">
	
	<!-- Alerta para el mensaje de la api de openexchangerates -->
	<?php if($openexchangerates_message['type'] == 'error'){ ?>
	<div class="col-lg-12 alert alert-danger alert-dismissable">
		<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
		<?php 
		echo $this->lang->line('project_openexchangerates_message_error');
		?>
	</div>
	<?php }else if($openexchangerates_message['type'] == 'message1'){ ?>
	<div class="col-lg-12 alert alert-success alert-dismissable">
		<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
		<?php 
		echo $this->lang->line('project_openexchangerates_message');
		?>
	</div>
	<?php }else{ ?>
		<div class="col-lg-12 alert alert-success alert-dismissable">
		<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
		<?php 
		echo $this->lang->line('project_openexchangerates_message2');
		?>
	</div>
	<?php } ?>
	
	<!-- Alerta para cuando el mensaje de la api de coinmarketcap es un error -->
	<?php if($coinmarketcap_message['type'] == 'error'){ ?>
	<div class="col-lg-12 alert alert-danger alert-dismissable">
		<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
		<?php 
		echo $this->lang->line('project_coinmarketcap_message_error');
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
	
	<!-- Inicio de sección de resumen general -->
	<div class="row">
		
		<div class="col-lg-12">
			<div class="wrapper wrapper-content animated fadeInUp">
				<div class="ibox">
					<div class="ibox-content">
						
						<div class="row">
							<div class="col-lg-12">
								<div class="m-b-md">
									<a href="<?php echo base_url() ?>projects/edit/<?= $ver[0]->id; ?>" class="btn btn-white btn-xs pull-right"><?php echo $this->lang->line('btn_edit_projects_view'); ?></a>
									<!--<h2><?php echo $ver[0]->name; ?></h2>-->
								</div>
								<dl class="dl-horizontal">
									<dt><?php echo $this->lang->line('view_description_projects'); ?>:</dt> 
									<dd>
									<?php echo $ver[0]->description; ?>
									</dd>
								</dl>
								<dl class="dl-horizontal">
									<dt><?php echo $this->lang->line('view_status_projects'); ?>:</dt> 
									<dd>
									<?php if($ver[0]->status == 1) { ?>
									<span class="label label-primary"><?php echo $this->lang->line('view_status1_projects'); ?></span>
									<?php }else{ ?>
									<span class="label label-default"><?php echo $this->lang->line('view_status2_projects'); ?></span>
									<?php } ?>
									</dd>
								</dl>
							</div>
						</div>
						
						<!-- Sección de documentos y lecturas -->
						<?php if(count($documentos_asociados) > 0 || count($lecturas_asociadas) > 0){ ?>
						<div class="row">
							<div class="col-lg-6">
								<?php if(count($documentos_asociados) > 0){ ?>
								<dl class="dl-horizontal">
									<dt><?php echo $this->lang->line('view_documents_projects'); ?>:</dt>
									<dd>
										<ul class="list-unstyled project-files">
											<?php foreach($documentos_asociados as $doc){ ?>
											<li>
												<a target="_blank" href="<?php echo base_url(); ?>assets/documents/<?php echo $doc->description; ?>">
													<i class="fa fa-file"></i> <?php echo $doc->description; ?>
												</a>
											</li>
											<?php } ?>
										</ul>
									</dd>
								</dl>
								<?php } ?>
							</div>
							<div class="col-lg-6">
								<?php if(count($lecturas_asociadas) > 0){ ?>
								<dl class="dl-horizontal" >
									<dt><?php echo $this->lang->line('view_readings_projects'); ?>:</dt>
									<dd>
										<ul class="list-unstyled project-files">
											<?php foreach($lecturas_asociadas as $reading){ ?>
											<li>
												<a target="_blank" href="<?php echo base_url(); ?>assets/readings/<?php echo $reading->description; ?>">
													<i class="fa fa-file"></i> <?php echo $reading->description; ?>
												</a>
											</li>
											<?php } ?>
										</ul>
									</dd>
								</dl>
								<?php } ?>
							</div>
						</div>
						<?php } ?>
						<!-- Cierre de sección de documentos y lecturas -->
						
						<div class="row">
							<div class="col-lg-5">
								<dl class="dl-horizontal">
									<dt><?php echo $this->lang->line('view_created_projects'); ?>:</dt> <dd><?php //echo $ver[0]->username; ?></dd>
									<dt><?php echo $this->lang->line('view_investors_projects'); ?>:</dt> <dd>  <?php echo count($investors); ?></dd>
								</dl>
							</div>
							<div class="col-lg-7" id="cluster_info">
								<dl class="dl-horizontal" >
									<dt><?php echo $this->lang->line('view_last_updated_count'); ?>:</dt> <dd><?php echo $ver[0]->d_update; ?></dd>
									<dt><?php echo $this->lang->line('view_date_created_project'); ?>:</dt> <dd> 	<?php echo $ver[0]->d_create; ?> </dd>
								</dl>
							</div>
						</div>
						
						<div class="row">
							<div class="col-lg-12">
								<dl class="dl-horizontal" >
									<dt></dt>
									<dd class="project-people">
									<?php foreach($data_investors as $investor){?>
										<?php if($investor['image'] != '' && $investor['image'] != null){ ?>
										<a href=""><img class="img-circle" src="<?php echo base_url(); ?>assets/img/users/<?php echo $investor['image']; ?>" title="<?php echo $investor['username']?>"></a>
										<?php }else{ ?>
										<a href=""><img class="img-circle" src="<?php echo base_url(); ?>assets/img/users/usuario.jpg" title="<?php echo $investor['username']?>"></a>
										<?php } ?>
									<?php } ?>
									</dd>
								</dl>
							</div>
						</div>
						
						<div class="row">
							<div class="col-lg-12">
								<dl class="dl-horizontal">
									<dt><?php echo $this->lang->line('view_completed_projects'); ?>:</dt>
									<dd>
										<?php 
										if($ver[0]->amount_r == null){
											echo "&infin;";
											$percentage = 0;
										}else{
											if($porcentaje_r > 0){
												echo round($porcentaje_r, 2)."%";
												$percentage = round($porcentaje_r, 2);
											}else{
												echo "0%";
												$percentage = 0;
											}
										}
										?>
										<div class="progress progress-striped active m-b-sm">
											<div style="width: <?php echo $percentage; ?>%;" class="progress-bar"></div>
										</div>
										
										<small><?php echo $this->lang->line('view_detail_completed_projects'); ?> <strong><?php echo $percentage; ?>%</strong>. <?php echo $this->lang->line('view_detail_completed2_projects'); ?></small>
									</dd>
								</dl>
							</div>
						</div>
						
						<div class="row m-t-sm">
							
							<div class="col-lg-12">
								
								<div class="col-lg-3">
									<div class="ibox">
										<div class="ibox-content">
											<h5><?php echo $this->lang->line('view_payback'); ?></h5>
											<!--<h1 class="no-margins">
												<?php 
												//~ $payback = explode(" ", $project_transactions_gen->capital_payback);
												//~ $invested = explode(" ", $project_transactions_gen->capital_invested);
												//~ $result = (string)$payback[0]."/".(string)$invested[0];
												?>
												<span class="pie"><?php //echo $result; ?></span>
											</h1>-->
											<h2><?php echo $project_transactions_gen['resumen_general']->capital_payback; ?>%</h2>
											<div class="progress progress-mini">
												<div style="width: <?php echo $project_transactions_gen['resumen_general']->capital_payback; ?>%;" class="progress-bar"></div>
											</div>
											<!--<div class="stat-percent font-bold text-danger">24% <i class="fa fa-level-down"></i></div>-->
											<!--<small><?php echo $result; ?></small>-->
										</div>
									</div>
								</div>
								<div class="col-lg-2">
									<div class="ibox">
										<div class="ibox-content">
											<h5><?php echo $this->lang->line('view_invested_capital'); ?></h5>
											<h1 class="no-margins" style="font-size:25px;">
											<?php echo $project_transactions_gen['resumen_general']->capital_invested; ?>
											</h1>
											<!--<div class="stat-percent font-bold text-navy">98% <i class="fa fa-bolt"></i></div>-->
											<small>
											<?php
											if($ver[0]->coin_avr != $this->session->userdata('logged_in')['coin_iso']){
												echo $project_transactions_gen['resumen_general']->capital_invested_user; 
											}
											?>
											</small>
										</div>
									</div>
								</div>
								<div class="col-lg-2">
									<div class="ibox">
										<div class="ibox-content">
											<h5><?php echo $this->lang->line('view_dividend'); ?></h5>
											<h1 class="no-margins" style="font-size:25px;">
											<?php echo $project_transactions_gen['resumen_general']->returned_capital; ?>
											</h1>
											<!--<div class="stat-percent font-bold text-danger">12% <i class="fa fa-level-down"></i></div>-->
											<small>
											<?php
											if($ver[0]->coin_avr != $this->session->userdata('logged_in')['coin_iso']){
												echo $project_transactions_gen['resumen_general']->returned_capital_user;
											}
											?>
											</small>
										</div>
									</div>
								</div>
								<div class="col-lg-2">
									<div class="ibox">
										<div class="ibox-content">
											<h5><?php echo $this->lang->line('view_expenses'); ?></h5>
											<h1 class="no-margins" style="font-size:25px;">
											<?php echo $project_transactions_gen['resumen_general']->expense_capital; ?>
											</h1>
											<!--<div class="stat-percent font-bold text-danger">12% <i class="fa fa-level-down"></i></div>-->
											<small>
											<?php
											if($ver[0]->coin_avr != $this->session->userdata('logged_in')['coin_iso']){
												echo $project_transactions_gen['resumen_general']->expense_capital_user;
											}
											?>
											</small>
										</div>
									</div>
								</div>
								<div class="col-lg-3">
									<div class="ibox">
										<div class="ibox-content">
											<h5><?php echo $this->lang->line('view_capital_in_project'); ?></h5>
											<h1 class="no-margins" style="font-size:25px;">
											<?php echo $project_transactions_gen['resumen_general']->retirement_capital_available; ?>
											</h1>
											<!--<div class="stat-percent font-bold text-danger">24% <i class="fa fa-level-down"></i></div>-->
											<small>
											<?php
											if($ver[0]->coin_avr != $this->session->userdata('logged_in')['coin_iso']){
												echo $project_transactions_gen['resumen_general']->retirement_capital_available_user;
											}
											?>
											</small>
										</div>
									</div>
								</div>
								
							</div>
							
						</div>
						
					</div>
					
				</div>
				
			</div>
			
		</div>
		
		<!--<div class="col-lg-3">
			<div class="wrapper wrapper-content project-manager">
				<h4>Project description</h4>
				<p class="small">
					<?php echo $ver[0]->description; ?>
				</p>
				<h5>Project documents</h5>
				<ul class="list-unstyled project-files">
					<?php foreach($documentos_asociados as $doc){ ?>
					<li>
						<a target="_blank" href="<?php echo base_url(); ?>assets/documents/<?php echo $doc->description; ?>">
							<i class="fa fa-file"></i> <?php echo $doc->description; ?>
						</a>
					</li>
					<?php } ?>
				</ul>
				<h5>Project readings</h5>
				<ul class="list-unstyled project-files">
					<?php foreach($lecturas_asociadas as $reading){ ?>
					<li>
						<a target="_blank" href="<?php echo base_url(); ?>assets/readings/<?php echo $reading->description; ?>">
							<i class="fa fa-file"></i> <?php echo $reading->description; ?>
						</a>
					</li>
					<?php } ?>
				</ul>
			</div>
		</div>-->
		
	</div>
	<!-- Cierre de sección de resumen general -->
	
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
					<?php foreach ($project_transactions_coins as $transact) { ?>
						<tr style="text-align: center">
							<td>
								<?php echo $transact->coin; ?>
							</td>
							<td>
								<?php echo $transact->amount; ?>
							</td>
							<td>
								<?php echo $transact->amount_project; ?>
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
	
	<?php 
	// Ids de los perfiles que tendrań permisos de visualización
	$global_profiles = array(1, 2);
	?>
	
	<?php if(in_array($this->session->userdata('logged_in')['profile_id'], $global_profiles)){?>
	<!-- Cuerpo de la sección de cuentas -->
	<div class="ibox">
		<div class="ibox-title">
			<h5><?php echo $this->lang->line('view_list_accounts_title_projects'); ?></h5>
			
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
			
			<div class="col-sm-4 col-md-offset-8">
				<div class="input-group">
					<input type="text" placeholder="Search in table" class="input-sm form-control" id="filter">
					<span class="input-group-btn">
						<button type="button" class="btn btn-sm btn-primary"> Go!</button>
					</span>
				</div>
			</div>

			<table id="tab_accounts" data-page-size="10" data-filter=#filter class="footable table table-stripped toggle-arrow-tiny">
				<thead>
					<tr>
						<th>#</th>
						<th data-hide="phone,tablet" ><?php echo $this->lang->line('view_list_accounts_account_projects'); ?></th>
						<th ><?php echo $this->lang->line('view_list_accounts_number_projects'); ?></th>
						<th data-hide="phone,tablet" ><?php echo $this->lang->line('view_list_accounts_type_projects'); ?></th>
						<th data-hide="phone,tablet"><?php echo $this->lang->line('view_list_accounts_description_projects'); ?></th>
						<th data-hide="phone,tablet" ><?php echo $this->lang->line('view_list_accounts_amount_projects'); ?></th>
						<th data-hide="phone,tablet" ><?php echo $this->lang->line('view_list_accounts_amount_projects'); ?> <?php echo $ver[0]->coin_avr;?></th>
						<th data-hide="phone,tablet" ><?php echo $this->lang->line('view_list_accounts_status_projects'); ?></th>
					</tr>
				</thead>
				<tbody>
					<?php $i = 1; ?>
					<?php foreach ($cuentas as $cuenta) { ?>
						<tr style="text-align: center">
							<td>
								<?php echo $i; ?>
							</td>
							<td>
								<?php echo $cuenta->alias; ?>
							</td>
							<td>
								<?php echo $cuenta->number; ?>
							</td>
							<td>
								<?php echo $cuenta->tipo_cuenta; ?>
							</td>
							<td>
								<?php echo $cuenta->description; ?>
							</td>
							<td>
								<?php $monto = number_format($cuenta->capital_disponible_moneda_cuenta, $cuenta->coin_decimals, '.', ''); ?>
								<?php echo $monto."  ".$cuenta->coin_symbol; ?>
							</td>
							<td>
								<?php echo $cuenta->capital_disponible_moneda_proyecto; ?>
							</td>
							<td>
								<?php
								if($cuenta->status == 1){
									echo "<span style='color:#337AB7;'>".$this->lang->line('view_list_accounts_status1_projects')."</span>";
								}else if($cuenta->status == 0){
									echo "<span style='color:#D33333;'>".$this->lang->line('view_list_accounts_status2_projects')."</span>";
								}else{
									echo "";
								}
								?>
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
	<!-- Cierre del cuerpo de la sección de cuentas -->
	<?php } ?>

	<!-- Cuerpo de la sección de transacciones -->
	<div class="ibox float-e-margins">
		<div class="ibox-title">
			<h5><?php echo $this->lang->line('view_list_transactions_title_projects'); ?></h5>

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
			
			<div class="col-sm-4 col-md-offset-8">
				<div class="input-group">
					<input type="text" placeholder="Search in table" class="input-sm form-control" id="filter1">
					<span class="input-group-btn">
						<button type="button" class="btn btn-sm btn-primary"> Go!</button>
					</span>
				</div>
			</div>
			
			<!--<input type="text" class="form-control input-sm m-b-xs"  placeholder="">-->
			
			<table class="footable table table-stripped" data-page-size="10" data-filter=#filter1>
				<thead>
					<tr>
						<th data-hide="phone,tablet">Id</th>
						<th data-hide="phone,tablet"><?php echo $this->lang->line('view_list_transactions_date_projects'); ?></th>
						<th><?php echo $this->lang->line('view_list_transactions_username_projects'); ?></th>
						<th data-hide="phone,tablet"><?php echo $this->lang->line('view_list_transactions_type_projects'); ?></th>
						<th data-hide="phone,tablet"><?php echo $this->lang->line('view_list_transactions_description_projects'); ?></th>
						<th><?php echo $this->lang->line('view_list_transactions_amount_projects'); ?></th>
						<?php if($this->session->userdata('logged_in')['profile_id'] == 1 || $this->session->userdata('logged_in')['profile_id'] == 2){ ?>
						<th data-hide="phone,tablet"><?php echo $this->lang->line('view_list_transactions_account_projects'); ?></th>
						<?php } ?>
						<th data-hide="phone,tablet"><?php echo $this->lang->line('view_list_transactions_status_projects'); ?></th>
					</tr>
				</thead>
				<tbody>
					<?php $i = 1; ?>
					<?php foreach ($project_transactions as $transact) { ?>
						<tr style="text-align: center">
							<td>
								<?php echo $transact->id; ?>
							</td>
							<td>
								<?php echo $transact->date; ?>
							</td>
							<td>
								<?php echo $transact->name; ?>
							</td>
							<td>
								<?php echo $transact->type; ?>
							</td>
							<td>
								<?php echo $transact->description; ?>
							</td>
							<td>
								<?php echo number_format($transact->amount, $transact->coin_decimals, '.', '')."  ".$transact->coin_avr; ?>
							</td>
							<?php if($this->session->userdata('logged_in')['profile_id'] == 1 || $this->session->userdata('logged_in')['profile_id'] == 2){ ?>
							<td>
								<?php echo $transact->alias; ?>
							</td>
							<?php } ?>
							<td>
								<?php
								if($transact->status == "approved"){
									echo "<i class='fa fa-check text-navy'></i>";
								}else if($transact->status == "waiting"){
									echo "<i class='fa fa-check text-warning'></i>";
								}else if($transact->status == "denied"){
									echo "<i class='fa fa-times text-danger'></i>";
								}else{
									echo "";
								}
								?>
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
	<!-- Cierre del cuerpo de la sección de transacciones -->
	
	<?php 
	// Ids de los perfiles que tendrań permisos de visualización
	$global_profiles = array(1, 2);
	?>
	
	<?php if(in_array($this->session->userdata('logged_in')['profile_id'], $global_profiles)){?>
	<!-- Cuerpo de la sección de transacciones por usuario-->
	<div class="ibox float-e-margins">
		<div class="ibox-title">
			<h5><?php echo $this->lang->line('view_list_summary_users_title_projects'); ?></h5>
			
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
			
			<div class="col-sm-4 col-md-offset-8">
				<div class="input-group">
					<input type="text" placeholder="Search in table" class="input-sm form-control" id="filter2">
					<span class="input-group-btn">
						<button type="button" class="btn btn-sm btn-primary"> Go!</button>
					</span>
				</div>
			</div>
			
			<table class="footable table table-stripped toggle-arrow-tiny" data-page-size="10" data-filter=#filter2>
				<thead>
					<tr>
						<th><?php echo $this->lang->line('view_list_users_username_projects'); ?></th>
						<th data-hide="phone,tablet"><?php echo $this->lang->line('view_list_users_payback_projects'); ?></th>
						<th data-hide="phone,tablet"><?php echo $this->lang->line('view_list_users_invested_capital_projects'); ?></th>
						<th data-hide="phone,tablet"><?php echo $this->lang->line('view_list_users_dividend_projects'); ?></th>
						<th data-hide="phone,tablet"><?php echo $this->lang->line('view_list_users_capital_in_project_projects'); ?></th>
						<th data-hide="phone,tablet"><?php echo $this->lang->line('view_list_users_pending_invest_projects'); ?></th>
						<th data-hide="phone,tablet"><?php echo $this->lang->line('view_list_users_pending_withdraw_projects'); ?></th>
					</tr>
				</thead>
				<tbody>
					<?php $i = 1; ?>
					<?php foreach ($project_transactions_gen['resumen_usuarios'] as $transact) { ?>
						<tr style="text-align: center">
							<td>
								<?php echo $transact->name; ?>
							</td>
							<?php
							$returned_capital = explode(" ", $transact->returned_capital);
							$returned_capital = $returned_capital[0];
							$capital_invested = explode(" ", $transact->capital_invested);
							$capital_invested = $capital_invested[0];
							if($capital_invested > 0){
								$payback = $returned_capital*100/$capital_invested;
							}else{
								$payback = 100;
							}
							?>
							<td title="<?php echo round($payback, 2); ?>%">
								<span class="pie"><?php echo (string)$returned_capital."/".(string)$capital_invested; ?></span>
							</td>
							<td>
								<?php echo $transact->capital_invested; ?>
							</td>
							<td>
								<?php echo $transact->returned_capital; ?>
							</td>
							<td>
								<?php echo $transact->retirement_capital_available; ?>
							</td>
							<td>
								<?php echo $transact->pending_entry; ?>
							</td>
							<td>
								<?php echo $transact->pending_exit; ?>
							</td>
						</tr>
						<?php $i++ ?>
					<?php } ?>
				</tbody>
				<tfoot>
					<tr>
						<td class='text-center' colspan='7'>
							<ul class='pagination'></ul>
						</td>
					</tr>
				</tfoot>
			</table>
			
		</div>
		
	</div>
	<!-- Cierre del cuerpo de la sección de transacciones por usuario -->
	<?php } ?>

</div>

<!-- FooTable -->
<!--<script src="<?php echo assets_url('js/plugins/footable/footable.js');?>"></script>-->
<script src="<?php echo assets_url('js/plugins/footable/footable.all.min.js');?>"></script>

<!-- Peity -->
<script src="<?php echo assets_url('js/plugins/peity/jquery.peity.min.js');?>"></script>
<script src="<?php echo assets_url('js/demo/peity-demo.js');?>"></script>

<!-- Flot -->
<script src="<?php echo assets_url('js/plugins/flot/jquery.flot.js');?>"></script>
<script src="<?php echo assets_url('js/plugins/flot/jquery.flot.pie.js');?>"></script>

<script>
$(document).ready(function(){
	
	$('.footable').footable();  // Aplicamos el plugin footable
	
});
</script>
