<?php
$this->load->model('MCuentas');
?>
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
</style>

<!-- FooTable -->
<!--<link href="<?php echo assets_url('css/plugins/footable/footable.bootstrap.css');?>" rel="stylesheet">-->
<link href="<?php echo assets_url('css/plugins/footable/footable.core.css');?>" rel="stylesheet">

<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2><?php echo $ver[0]->alias; ?></h2>
        <ol class="breadcrumb">
            <li>
                <a href="<?php echo base_url() ?>home"><?php echo $this->lang->line('heading_home_accounts_view'); ?></a>
            </li>
            
            <li>
                <a href="<?php echo base_url() ?>accounts"><?php echo $this->lang->line('heading_subtitle_accounts_view'); ?></a>
            </li>
           
            <li class="active">
                <strong><?php echo $ver[0]->alias; ?></strong>
            </li>
        </ol>
    </div>
</div>

<!-- Campos ocultos que almacenan los nombres del menú y el submenú de la vista actual -->
<input type="hidden" id="ident" value="<?php echo $ident; ?>">
<input type="hidden" id="ident_sub" value="<?php echo $ident_sub; ?>">

<!-- Cálculo de los valores del cintillo de totales -->
<?php 
$capital_disponible_total = 0;
$capital_disponible_real = 0;
$capital_cuenta = 0;
$capital_proyectos = 0;
$capital_disponible_parcial = 0;
$depósito_pendiente = 0;
$capital_diferido = 0;

// Cálculo de los capitales disponibles
foreach($find_transactions as $transact) {
	if($transact->status == 'approved'){
		// Si la moneda de la cuenta es el bolívar y la transacción es anterior al 20-08-2018, se hace una reconversión
		if($ver[0]->coin_avr == 'VEF' && strtotime($transact->date) < strtotime("2018-08-20 00:00:00")){
			$capital_disponible_total += ($transact->amount/100000);
			// Capital real
			if($transact->real == 1){
				$capital_disponible_real += ($transact->amount/100000);
			}
		}else{
			$capital_disponible_total += $transact->amount;
			// Capital real
			if($transact->real == 1){
				$capital_disponible_real += $transact->amount;
			}
		}
		if($transact->user_id > 0 && $transact->project_id == 0){
			// Si la moneda de la cuenta es el bolívar y la transacción es anterior al 20-08-2018, se hace una reconversión
			if($ver[0]->coin_avr == 'VEF' && strtotime($transact->date) < strtotime("2018-08-20 00:00:00")){
				$capital_cuenta += ($transact->amount/100000);
			}else{
				$capital_cuenta += $transact->amount;  // En cuenta
			}
		}
		if($transact->user_id > 0 && $transact->project_id > 0){
			// Si la moneda de la cuenta es el bolívar y la transacción es anterior al 20-08-2018, se hace una reconversión
			if($ver[0]->coin_avr == 'VEF' && strtotime($transact->date) < strtotime("2018-08-20 00:00:00")){
				$capital_proyectos += ($transact->amount/100000);
			}else{
				$capital_proyectos += $transact->amount;  // En proyectos
			}
		}
	}
	$relations = $this->MCuentas->buscar_transaction_relation($transact->id);
	if($transact->type != "invest" && $transact->type != "sell" && $transact->status == 'approved'){
		if(count($relations) == 0){
			// Si la moneda de la cuenta es el bolívar y la transacción es anterior al 20-08-2018, se hace una reconversión
			if($ver[0]->coin_avr == 'VEF' && strtotime($transact->date) < strtotime("2018-08-20 00:00:00")){
				$capital_disponible_parcial += ($transact->amount/100000);
			}else{
				$capital_disponible_parcial += $transact->amount;  // Disponible parcial
			}
		}
		if(count($relations) > 0 && $relations[0]->type != "distribute"){
			// Si la moneda de la cuenta es el bolívar y la transacción es anterior al 20-08-2018, se hace una reconversión
			if($ver[0]->coin_avr == 'VEF' && strtotime($transact->date) < strtotime("2018-08-20 00:00:00")){
				$capital_disponible_parcial += ($transact->amount/100000);
			}else{
				$capital_disponible_parcial += $transact->amount;  // Disponible parcial
			}
		}
	}
}
//~ foreach($find_transactions_project as $transact_project) {
	//~ if($transact_project->status == 'approved'){ $capital_disponible_total += $transact_project->monto; }
	//~ $relations = $this->MCuentas->buscar_project_transaction_relation($transact_project->id);
	//~ if(count($relations) == 0){
		//~ if($transact_project->type == "profit" || $transact_project->type == "expense"){
			//~ if($transact_project->status == 'approved'){
				//~ $capital_disponible_parcial += $transact_project->monto;
			//~ }
		//~ }
	//~ }
//~ }
?>
<!-- Fin del cálculo de los valores del cintillo de totales -->

<div class="wrapper wrapper-content animated fadeInUp">
	
	<!-- Alerta para cuando el mensaje de la api de coinmarketcap es un error -->
	<?php if(($capital_disponible_total - $capital_disponible_real) != 0){ ?>
	<div class="col-lg-12 alert alert-danger alert-dismissable">
		<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
		<?php 
		echo $this->lang->line('view_alert_message');
		?>
	</div>
	<?php } ?>
	
	<!-- Cuerpo de la sección de cintillo de montos -->
	<div class="row">
		<div class="col-lg-12">
			<div class="contact-box">
				
				<div class="contact-box-footer" style="border-top:0px;">
					<div>
						<div class="col-md-4 forum-info">
							<span class="views-number" id="span_retornado">
								<?php echo $capital_disponible_total; ?>
							</span>
							<div>
								<small><?php echo $this->lang->line('view_capital_available_accounts'); ?></small>
							</div>
						</div>
						<!--<div class="col-md-4 forum-info">
							<span class="views-number" id="span_retornado">
								<?php echo $capital_cuenta; ?>
							</span>
							<div>
								<small><?php echo $this->lang->line('view_capital_in_count'); ?></small>
							</div>
						</div>
						<div class="col-md-4 forum-info">
							<span class="views-number" id="span_retornado">
								<?php echo $capital_proyectos; ?>
							</span>
							<div>
								<small><?php echo $this->lang->line('view_capital_in_project'); ?></small>
							</div>
						</div>
						<div class="col-md-6 forum-info">
							<span class="views-number" id="span_aprobado">
								<?php echo $capital_disponible_parcial; ?>
							</span>
							<div>
								<small>Capital disponible parcial</small>
							</div>
						</div>
						<div class="col-md-3 forum-info">
							<span class="views-number" id="span_ingreso_pendiente">
								<?php echo $depósito_pendiente; ?>
							</span>
							<div>
								<small>Depósito Pendiente</small>
							</div>
						</div>
						<div class="col-md-3 forum-info">
							<span class="views-number" id="span_egreso_pendiente">
								<?php echo $capital_diferido; ?>
							</span>
							<div>
								<small>Capital Diferido</small>
							</div>
						</div>-->
					</div>
					<br>
					<br>
				</div>
			</div>
		</div>
	</div>
	<!-- Cierre del cuerpo de la sección de cintillo de montos -->

	<!-- Cuerpo de la sección de transacciones -->
	<div class="ibox float-e-margins">
		<div class="ibox-title">
			<h5><?php echo $this->lang->line('view_list_title_accounts'); ?></h5>

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
			
			<table class="footable table table-stripped" data-page-size="50" data-filter=#filter1>
				<thead>
					<tr>
						<th data-hide="phone,tablet">Id</th>
						<th data-hide="phone,tablet"><?php echo $this->lang->line('view_list_date_accounts'); ?></th>
						<th><?php echo $this->lang->line('view_list_username_accounts'); ?></th>
						<th data-hide="phone,tablet"><?php echo $this->lang->line('view_list_type_accounts'); ?></th>
						<th data-hide="phone,tablet"><?php echo $this->lang->line('view_list_description_accounts'); ?></th>
						<th><?php echo $this->lang->line('view_list_amount_accounts'); ?></th>
						<th data-hide="phone,tablet"><?php echo $this->lang->line('view_list_projectname_accounts'); ?></th>
						<th data-hide="phone,tablet"><?php echo $this->lang->line('view_list_status_accounts'); ?></th>
						<th data-hide="phone,tablet">Real</th>
					</tr>
				</thead>
				<tbody>
					<?php $i = 1; ?>
					<?php $total = 0; ?>
					<!-- Transacciones de la tabla 'transactions' -->
					<?php foreach ($find_transactions as $transact) { ?>
						<?php $background_color = "";?>
						<!-- Anteriormente se marcaba la fila si la transacción tenía relaciones en la tabla 'transaction_relations' -->
						<?php //$relations = $this->MCuentas->buscar_transaction_relation($transact->id); ?>
						<?php //if($transact->type != "invest" && $transact->type != "sell"){ ?>
							<?php //if(count($relations) == 0){ ?>
							<?php //$background_color = "background-color: #0DD9E9;";?>
							<?php //} ?>
							<?php //if(count($relations) > 0 && $relations[0]->type != "distribute"){ ?>
							<?php //$background_color = "background-color: #0DD9E9;";?>
							<?php //} ?>
						<?php //} ?>
						<!-- Ahora se marca la fila si la transacción es real, es decir, si tiene el campo real == 1 -->
						<?php 
						if($transact->real == 1){
							$background_color = "background-color: #DDDDDD;";
						}
						?>
						<tr style="text-align: center;<?php echo $background_color; ?>">
							<td>
								<?php echo $transact->id; ?>
							</td>
							<td>
								<?php echo $transact->date; echo count($relations); ?>
							</td>
							<td>
								<?php echo $transact->name_user; ?>
							</td>
							<td>
								<?php echo $transact->type; ?>
							</td>
							<td>
								<?php echo $transact->description; ?>
							</td>
							<td>
								<?php echo $transact->amount."  ".$transact->coin_avr; ?>
							</td>
							<td>
								<?php echo $transact->name_project; ?>
							</td>
							<td>
								<?php
								if($transact->status == "approved"){
									echo "<i class='fa fa-check text-navy' title='".$this->lang->line('view_list_status1_accounts')."'></i>";
								}else if($transact->status == "waiting"){
									echo "<i class='fa fa-check text-warning' title='".$this->lang->line('view_list_status2_accounts')."'></i>";
								}else if($transact->status == "denied"){
									echo "<i class='fa fa-times text-danger' title='".$this->lang->line('view_list_status3_accounts')."'></i>";
								}else{
									echo "";
								}
								?>
							</td>
							<td>
								<?php
								if($transact->real == 1){
									echo "<span class='text-navy'><strong>Real</strong></span>";
								}else{
									echo "";
								}
								?>
							</td>
						</tr>
						<?php $i++ ?>
						<?php if($transact->status == 'approved'){ $total += $transact->amount; } ?>
					<?php } ?>
					<!-- Fin de Transacciones de la tabla 'transactions' -->
					
					<!-- Transacciones de la tabla 'project_transactions' -->
					<?php //foreach ($find_transactions_project as $transact_project) { ?>
						<?php //$background_color = "";?>
						<?php //$relations = $this->MCuentas->buscar_project_transaction_relation($transact_project->id); ?>
						<?php //if(count($relations) == 0){ ?>
							<?php //if($transact_project->type == "profit" || $transact_project->type == "expense"){ ?>
							<?php //$background_color = "background-color: #0DD9E9;";?>
							<?php //} ?>
						<?php //} ?>
						<!--<tr style="text-align: center;<?php //echo $background_color; ?>">
							<td>
								<?php //echo $transact_project->date; ?>
							</td>
							<td>
								<?php //echo $transact_project->name_user; ?>
							</td>
							<td>
								<?php //echo $transact_project->type; ?>
							</td>
							<td>
								<?php //echo $transact_project->description; ?>
							</td>
							<td>
								<?php //echo $transact_project->monto."  ".$transact_project->coin_avr; ?>
							</td>
							<td>
								<?php //echo $transact_project->name_project; ?>
							</td>
							<td>
								<?php
								//~ if($transact_project->status == "approved"){
									//~ echo "<i class='fa fa-check text-navy'></i>";
								//~ }else if($transact_project->status == "waiting"){
									//~ echo "<i class='fa fa-check text-warning'></i>";
								//~ }else if($transact_project->status == "denied"){
									//~ echo "<i class='fa fa-times text-danger'></i>";
								//~ }else{
									//~ echo "";
								//~ }
								?>
							</td>
						</tr>-->
						<?php //$i++ ?>
						<?php //if($transact_project->status == 'approved'){ $total += $transact_project->monto; } ?>
					<?php //} ?>
					<!-- Fin de Transacciones de la tabla 'project_transactions' -->
					
				</tbody>
				<tfoot>
					<tr>
						<td class='text-center' colspan='9'>
							<ul class='pagination'></ul>
						</td>
						<!--<td class='text-right' colspan='1'>
							<span style="font-weight:bold;">Total: <?php echo $capital_disponible_total."  ".$ver[0]->coin_avr; ?></span>
						</td>-->
					</tr>
				</tfoot>
			</table>
			
		</div>
		
	</div>
	<!-- Cierre del cuerpo de la sección de transacciones -->

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
