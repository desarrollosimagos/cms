<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2><?php echo $this->lang->line('heading_title_projects'); ?></h2>
        <ol class="breadcrumb">
            <li>
                <a href="<?php echo base_url() ?>home"><?php echo $this->lang->line('heading_home_projects'); ?></a>
            </li>
            <li class="active">
                <strong><?php echo $this->lang->line('heading_subtitle_projects'); ?></strong>
            </li>
        </ol>
    </div>
</div>

<!-- Campos ocultos que almacenan los nombres del menú y el submenú de la vista actual -->
<input type="hidden" id="ident" value="<?php echo $ident; ?>">
<input type="hidden" id="ident_sub" value="<?php echo $ident_sub; ?>">

<div class="wrapper wrapper-content animated fadeInRight">
    
    <!--<div class="row">
        <div class="col-lg-12">
            <a href="<?php echo base_url() ?>projects/register" id="agregar">
            <button class="btn btn-outline btn-primary dim" type="button"><i class="fa fa-plus"></i> Agregar</button>
            </a>
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>Listado de Projectos </h5>
                    <input type="hidden" id="precio_dolar">
                </div>
                <div class="ibox-content">
                    <div class="table-responsive">
						
						<br>
						
                        <table id="tab_projects" class="table table-striped table-bordered dt-responsive table-hover dataTables-example" >
                            
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Nombre</th>
                                    <th>Descripción</th>
                                    <th>Tipo</th>
                                    <th>Valor</th>
                                    <th>Monto a Recaudar</th>
                                    <th>Monto Mínimo</th>
                                    <th>Monto Máximo</th>
                                    <th>Fecha</th>
                                    <th>Fecha de Retorno</th>
                                    <th>Fecha de Validez</th>
                                    <th>Número de Fotos</th>
                                    <th>Número de Notificaciones</th>
                                    <th>Número de Documentos</th>
                                    <th>Número de Lecturas</th>
                                    <th>Editar</th>
                                    <th>Eliminar</th>
                                </tr>
                            </thead>
                            
                            <tbody>
                                <?php $i = 1; ?>
                                <?php foreach ($listar as $proyecto) { ?>
                                    <tr style="text-align: center">
                                        <td>
                                            <?php echo $i; ?>
                                        </td>
                                        <td>
                                            <?php echo $proyecto->name; ?>
                                        </td>
                                        <td>
                                            <?php echo $proyecto->description; ?>
                                        </td>
                                        <td>
                                            <?php echo $proyecto->type; ?>
                                        </td>
                                        <td>
                                            <?php echo $proyecto->valor; ?>
                                        </td>
                                        <td>
                                            <?php echo $proyecto->amount_r; ?>
                                        </td>
                                        <td>
                                            <?php echo $proyecto->amount_min; ?>
                                        </td>
                                        <td>
                                            <?php echo $proyecto->amount_max; ?>
                                        </td>
                                        <td>
                                            <?php echo $proyecto->date; ?>
                                        </td>
                                        <td>
                                            <?php echo $proyecto->date_r; ?>
                                        </td>
                                        <td>
                                            <?php echo $proyecto->date_v; ?>
                                        </td>
                                        <td>
                                            <?php echo $proyecto->num_fotos; ?>
                                        </td>
                                        <td>
                                            <?php echo $proyecto->num_news; ?>
                                        </td>
                                        <td>
                                            <?php echo $proyecto->num_docs; ?>
                                        </td>
                                        <td>
                                            <?php echo $proyecto->num_readings; ?>
                                        </td>
                                        <td style='text-align: center'>
                                            <a href="<?php echo base_url() ?>projects/edit/<?= $proyecto->id; ?>" title="Editar"><i class="fa fa-edit fa-2x"></i></a>
                                        </td>
                                        <td style='text-align: center'>
                                            <a class='borrar' id='<?php echo $proyecto->id; ?>' title='Eliminar'><i class="fa fa-trash-o fa-2x"></i></a>
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
			<h5><?php echo $this->lang->line('list_title_projects'); ?></h5>
			<div class="ibox-tools">
				<a href="<?php echo base_url() ?>projects/register" id="agregar" class="btn btn-primary btn-xs"><?php echo $this->lang->line('btn_registry_projects'); ?></a>
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

				<table class="table table-hover" id="tab_projects" >
					<tbody>
					<?php $i = 1; ?>
					<?php foreach ($listar as $proyecto) { ?>
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

<!-- jScroll -->
<script src="<?php echo assets_url('js/plugins/jscroll/jquery.jscroll.js');?>"></script>

<script>
	//~ $('.scroll').jscroll();
</script>

 <!-- Page-Level Scripts -->
<script src="<?php echo assets_url(); ?>script/projects.js"></script>
