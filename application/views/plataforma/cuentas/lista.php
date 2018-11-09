<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2><?php echo $this->lang->line('heading_title_accounts'); ?></h2>
        <ol class="breadcrumb">
            <li>
                <a href="<?php echo base_url() ?>home"><?php echo $this->lang->line('heading_home_accounts'); ?></a>
            </li>
            <li class="active">
                <strong><?php echo $this->lang->line('heading_subtitle_accounts'); ?></strong>
            </li>
        </ol>
    </div>
</div>

<!-- Campos ocultos que almacenan los nombres del menú y el submenú de la vista actual -->
<input type="hidden" id="ident" value="<?php echo $ident; ?>">
<input type="hidden" id="ident_sub" value="<?php echo $ident_sub; ?>">

<div class="wrapper wrapper-content animated fadeInRight">
	
	<!-- Viejo estilo de listado -->
    <!--<div class="row">
        <div class="col-lg-12">
            <a href="<?php echo base_url() ?>accounts/register">
            <button class="btn btn-outline btn-primary dim" type="button"><i class="fa fa-plus"></i> Agregar</button></a>
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>Listado de Cuentas</h5>
                </div>
                <div class="ibox-content">
                    <div class="table-responsive">
                        <table id="tab_cuentas" class="table table-striped table-bordered dt-responsive table-hover dataTables-example" >
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Persona</th>
                                    <th>Nombre</th>
                                    <th>Número</th>
                                    <th>Usuario</th>
                                    <th>Tipo</th>
                                    <th>Monto</th>
                                    <th>Moneda</th>
                                    <th>Estatus</th>
                                    <th>Observaciones</th>
                                    <th>Editar</th>
                                    <th>Eliminar</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $i = 1; ?>
                                <?php foreach ($listar as $fondo) { ?>
                                    <tr style="text-align: center">
                                        <td>
                                            <?php echo $i; ?>
                                        </td>
                                        <td>
                                            <?php echo $fondo->owner; ?>
                                        </td>
                                        <td>
                                            <?php echo $fondo->alias; ?>
                                        </td>
                                        <td>
                                            <?php echo $fondo->number; ?>
                                        </td>
                                        <td>
                                            <?php echo $fondo->usuario; ?>
                                        </td>
                                        <td>
                                            <?php echo $fondo->tipo_cuenta; ?>
                                        </td>
                                        <td>
                                            <?php echo $fondo->amount; ?>
                                        </td>
                                        <td>
                                            <?php echo $fondo->coin_avr." (".$fondo->coin.")"; ?>
                                        </td>
                                        <td>
                                            <?php echo $fondo->status; ?>
                                        </td>
                                        <td>
                                            <?php echo $fondo->description; ?>
                                        </td>
                                        <td style='text-align: center'>
                                            <a href="<?php echo base_url() ?>accounts/edit/<?= $fondo->id; ?>" title="Editar"><i class="fa fa-edit fa-2x"></i></a>
                                        </td>
                                        <td style='text-align: center'>
                                            
                                            <a class='borrar' id='<?php echo $fondo->id; ?>' title='Eliminar'><i class="fa fa-trash-o fa-2x"></i></a>
                                        </td>
                                    </tr>
                                    <?php $i++ ?>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>-->
    
    <div class="line"></div>
    
    <!-- Nuevo estilo de listado -->
    <div class="ibox">
		<div class="ibox-title">
			<h5><?php echo $this->lang->line('list_title_accounts'); ?></h5>
			<div class="ibox-tools">
				<a href="<?php echo base_url() ?>accounts/register" id="agregar" class="btn btn-primary btn-xs"><?php echo $this->lang->line('btn_registry_accounts'); ?></a>
			</div>
		</div>
		<div class="ibox-content">
			<div class="row m-b-sm m-t-sm">
				<div class="col-md-6">
					<!--<button type="button" id="loading-example-btn" class="btn btn-white btn-sm" ><i class="fa fa-refresh"></i> Refresh</button>-->
				</div>
				<div class="col-md-6">
					<div class="input-group">
						<input type="text" id="search" name="search" placeholder="Search" class="input-sm form-control">
						<span class="input-group-btn">
							<button type="button" id="go-search" class="btn btn-sm btn-primary"> Go!</button> 
						</span>
					</div>
				</div>
			</div>
			
			<!-- Capa para mostrar la busqueda realizada -->
			<div class="row m-b-sm m-t-sm">
				<div class="col-md-6">
					
				</div>
				<div class="col-md-6">
					<div class="input-group">
						<div class="col-sm-6">
							<span class="info">
								
							</span>
						</div>
						<div class="col-sm-6">
							<!-- Spinner de carga -->
							<div class="sk-spinner sk-spinner-circle">
								<div class="sk-circle1 sk-circle"></div>
								<div class="sk-circle2 sk-circle"></div>
								<div class="sk-circle3 sk-circle"></div>
								<div class="sk-circle4 sk-circle"></div>
								<div class="sk-circle5 sk-circle"></div>
								<div class="sk-circle6 sk-circle"></div>
								<div class="sk-circle7 sk-circle"></div>
								<div class="sk-circle8 sk-circle"></div>
								<div class="sk-circle9 sk-circle"></div>
								<div class="sk-circle10 sk-circle"></div>
								<div class="sk-circle11 sk-circle"></div>
								<div class="sk-circle12 sk-circle"></div>
							</div>
							<!-- Cierre de spinner de carga -->
						</div>
					</div>
				</div>
			</div>
			<!-- Cierre de la capa para mostrar la busqueda realizada -->

			<div class="project-list">

				<table class="table table-hover" id="tab_cuentas" >
					<tbody class="results">
					<?php $i = 1; ?>
					<?php foreach ($listar as $fondo) { ?>
					<tr class="scroll">
						<td class="project-status">
							<?php if($fondo->status == 1) { ?>
							<span class="label label-primary"><?php echo $this->lang->line('list_status1_accounts'); ?></span>
							<?php }else{ ?>
							<span class="label label-default"><?php echo $this->lang->line('list_status2_accounts'); ?></span>
							<?php } ?>
						</td>
						<td class="project-title">
							<a href="<?php echo base_url() ?>accounts/view/<?= $fondo->id; ?>"><?php echo $fondo->alias; ?></a>
							<br/>
							<small>Created <?php echo $fondo->d_create; ?></small>
							<br>
							<?php if($this->session->userdata('logged_in')['profile_id'] == 1 || $this->session->userdata('logged_in')['profile_id'] == 2) { ?>
							<small><?php echo $fondo->groups_names; ?></small>
							<?php } ?>
						</td>
						<td class="project-completion">
								<small>
									<!--Completion with:-->
									<?php echo $this->lang->line('list_capital_available_accounts'); ?>: <?php echo $fondo->capital_disponible_total; ?>
								</small>
								<!--<div class="progress progress-mini">
									<div style="width: <?php echo $percentage; ?>%;" class="progress-bar"></div>
								</div>-->
						</td>
						<td class="project-title">
							<?php echo $fondo->coin; ?>
						</td>
						<!--<td class="project-people">
							<a href=""><img alt="image" class="img-circle" src="img/a3.jpg"></a>
							<a href=""><img alt="image" class="img-circle" src="img/a1.jpg"></a>
							<a href=""><img alt="image" class="img-circle" src="img/a2.jpg"></a>
							<a href=""><img alt="image" class="img-circle" src="img/a4.jpg"></a>
							<a href=""><img alt="image" class="img-circle" src="img/a5.jpg"></a>
						</td>-->
						<td class="project-actions">
							<a href="<?php echo base_url() ?>accounts/view/<?= $fondo->id; ?>" title="<?php echo $this->lang->line('list_view_accounts'); ?>" class="btn btn-white btn-sm"><i class="fa fa-folder"></i> <?php echo $this->lang->line('list_view_accounts'); ?> </a>
							<a href="<?php echo base_url() ?>accounts/edit/<?= $fondo->id; ?>" title="<?php echo $this->lang->line('list_edit_accounts'); ?>" class="btn btn-white btn-sm"><i class="fa fa-pencil"></i> <?php echo $this->lang->line('list_edit_accounts'); ?> </a>
							<a id='<?php echo $fondo->id; ?>' title='<?php echo $this->lang->line('list_delete_accounts'); ?>' class="btn btn-danger btn-sm borrar"><i class="fa fa-trash"></i> <?php echo $this->lang->line('list_delete_accounts'); ?> </a>
						</td>
					</tr>
					<?php $i++ ?>
					<?php } ?>
					</tbody>
				</table>
                        
				<!-- Campo oculto de base_url -->
				<input type="hidden" name="base_url" id="base_url" value="<?php echo base_url() ?>"/>
				
			</div>
		</div>
	</div>
	
</div>


 <!-- Page-Level Scripts -->
<script src="<?php echo assets_url(); ?>script/accounts.js"></script>
