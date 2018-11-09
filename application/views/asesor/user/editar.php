<!-- FooTable -->
<link href="<?php echo assets_url('css/plugins/fileinput/fileinput.min.css');?>" rel="stylesheet">

<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2><?php echo $this->lang->line('heading_title_users_edit'); ?> </h2>
        <ol class="breadcrumb">
            <li>
                <a href="<?php echo base_url() ?>home"><?php echo $this->lang->line('heading_home_users_edit'); ?></a>
            </li>
            <li>
                <a href="<?php echo base_url() ?>users"><?php echo $this->lang->line('heading_subtitle_users_edit'); ?></a>
            </li>
            <li class="active">
                <strong><?php echo $this->lang->line('heading_info_users_edit'); ?></strong>
            </li>
        </ol>
    </div>
</div>

<!-- Campos ocultos que almacenan los nombres del menú y el submenú de la vista actual -->
<input type="hidden" id="ident" value="<?php echo $ident; ?>">
<input type="hidden" id="ident_sub" value="<?php echo $ident_sub; ?>">

<div class="wrapper wrapper-content animated fadeInRight">
	<div class="row">
        <div class="col-lg-12">
			<div class="ibox float-e-margins">
				<div class="ibox-title">
					<h5><?php echo $this->lang->line('heading_info_users_edit'); ?><small></small></h5>
				</div>
				<div class="ibox-content">
					<form id="form_users" method="post" accept-charset="utf-8" class="form-horizontal" enctype="multipart/form-data">
						<div class="form-group">
							<label class="col-sm-2 control-label" ><?php echo $this->lang->line('edit_image_users'); ?> *</label>
							<div class="col-sm-4">
								<input type="file" class="form-control image" name="image[]" id="image" onChange="valida_tipo($(this))">
							</div>
							<div class="col-sm-6">
								<img id="imgSalida" style="height:150px;width:150px;" class="img-circle" src="<?php echo base_url(); ?>assets/img/users/<?php echo $editar[0]->image; ?>">
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-2 control-label" ><?php echo $this->lang->line('edit_name_users'); ?> *</label>
							<div class="col-sm-10">
								<input type="text" class="form-control"  placeholder="" name="name" id="name" value="<?php echo $editar[0]->name ?>">
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-2 control-label" ><?php echo $this->lang->line('edit_alias_users'); ?> *</label>
							<div class="col-sm-10">
								<input type="text" class="form-control"  placeholder="" name="alias" id="alias" value="<?php echo $editar[0]->alias ?>">
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-2 control-label" ><?php echo $this->lang->line('edit_user_users'); ?> *</label>
							<div class="col-sm-10">
								<input type="text" class="form-control"  placeholder="ejemplo@xmail.com" name="username" id="username" value="<?php echo $editar[0]->username ?>">
							</div>
						</div>
						<!--<div class="form-group">
							<label class="col-sm-2 control-label" >Contraseña *</label>
							<div class="col-sm-10">
								<input type="password" class="form-control required"  placeholder="" name="password" id="password">
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-2 control-label" >Confirme Contraseña *</label>
							<div class="col-sm-10">
								<input type="password" class="form-control "  placeholder="" name="passw1" id="passw1">
							</div>
						</div>-->
						<div class="form-group">
							<label class="col-sm-2 control-label" ><?php echo $this->lang->line('edit_profile_users'); ?> *</label>
							<div class="col-sm-10">
								<select class="form-control m-b" name="profile_id" id="profile" >
									<option value="0" selected="">Seleccione</option>
									<?php foreach ($list_perfil as $perfil) { ?>
										<option value="<?php echo $perfil->id ?>"><?php echo $perfil->name ?></option>
									<?php } ?>
								</select>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-2 control-label" ><?php echo $this->lang->line('edit_currency_users'); ?> *</label>
							<div class="col-sm-10">
								<select class="form-control m-b" name="coin_id" id="coin_id">
									<option value="0" selected="">Seleccione</option>
									<?php foreach($monedas as $moneda){?>
									<option value="<?php echo $moneda->id; ?>"><?php echo $moneda->abbreviation." (".$moneda->description.")"; ?></option>
									<?php }?>
								</select>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-2 control-label" ><?php echo $this->lang->line('edit_language_users'); ?> *</label>
							<div class="col-sm-10">
								<select class="form-control m-b" name="lang_id" id="lang_id">
									<option value="0" selected="">Seleccione</option>
									<?php foreach($idiomas as $idioma){?>
									<option value="<?php echo $idioma->id; ?>"><?php echo ucfirst($idioma->name); ?></option>
									<?php }?>
								</select>
							</div>
						</div>
						<?php if($this->session->userdata('logged_in')['id'] == 1){ ?>
						<div class="form-group"><label class="col-sm-2 control-label" ><?php echo $this->lang->line('edit_actions_users'); ?></label>
							<div class="col-sm-10">
								<select id="actions_ids" name="actions_ids[]" class="form-control" multiple="multiple">
									<?php
									// Primero creamos un arreglo con la lista de ids de acciones proveniente del controlador
									$acciones_ids = explode(",",$ids_actions);
									?>
									<?php foreach ($acciones as $accion) { ?>
										<?php if(in_array($accion->id, $acciones_ids)) { ?>
										<option selected="selected" value="<?php echo $accion->id ?>"><?php echo $accion->name ?></option>
										<?php }else{ ?>
										<option value="<?php echo $accion->id ?>"><?php echo $accion->name ?></option>
										<?php } ?>
									<?php } ?>
								</select>
							</div>
						</div>
						<?php } ?>
						<div class="form-group">
							<label class="col-sm-2 control-label" ><?php echo $this->lang->line('edit_status_users'); ?> *</label>
							<div class="col-sm-10">
								<select class="form-control m-b" name="status" id="status">
									<option value="1" selected=""><?php echo $this->lang->line('edit_status1_users'); ?></option>
									<option value="0"><?php echo $this->lang->line('edit_status2_users'); ?></option>

								</select>
							</div>
						</div>
						
						<?php if($this->session->userdata('logged_in')['profile_id'] == 1){ ?>
						<div class="form-group">
							<label class="col-sm-2 control-label" ></label>
							<div class="col-sm-10">
								<!--Tab de acciones-->
								<div class="tabs-container">
									<ul class="nav nav-tabs">
										<li class="active"><a data-toggle="tab" href="#tab-1"><?php echo $this->lang->line('edit_tab_permissions_title_users'); ?></a></li>
										<!--<li class=""><a data-toggle="tab" href="#tab-2">Productos</a></li>-->
									</ul>
									<div class="tab-content">
										<div id="tab-1" class="tab-pane active">
											<div class="panel-body">
											  <!--<button  class="btn btn-w-m btn-primary" id="i_new_line"><i class="fa fa-plus"></i>&nbsp;Agregar Acción</button>-->
												 <div class="table-responsive">
													<table style="width: 100%" class="table dataTable table-striped table-bordered dt-responsive" id="tab_acciones">
														<thead>
														<tr>
															<th><?php echo $this->lang->line('edit_tab_permissions_item_users'); ?></th>
															<th><?php echo $this->lang->line('edit_tab_permissions_action_users'); ?></th>
															<th><?php echo $this->lang->line('edit_tab_permissions_create_users'); ?></th>
															<th><?php echo $this->lang->line('edit_tab_permissions_edit_users'); ?></th>
															<th><?php echo $this->lang->line('edit_tab_permissions_delete_users'); ?></th>
															<th><?php echo $this->lang->line('edit_tab_permissions_validate_users'); ?></th>
														</tr>
														</thead>
														<tbody>
															<?php 
															foreach ($permissions as $permission) {
																foreach ($acciones as $accion) { 
																	// Imprimimos sólo las acciones asociadas
																	if($accion->id == $permission->action_id){ 
																		$parameter1 = $permission->parameter_permit[0];
																		$parameter2 = $permission->parameter_permit[1];
																		$parameter3 = $permission->parameter_permit[2];
																		$parameter4 = $permission->parameter_permit[3];
																		?>
																		<tr id="<?php echo $id;?>">
																			<td><?php echo $accion->id; ?></td>
																			<td><?php echo $accion->name; ?></td>
																			<?php if($parameter1 == '0'){?>
																				<td><input type="checkbox" id=""></td>
																			<?php }else{ ?>
																				<td><input type="checkbox" id="" checked="checked"></td>
																			<?php } ?>
																			<?php if($parameter2 == '0'){?>
																				<td><input type="checkbox" id=""></td>
																			<?php }else{ ?>
																				<td><input type="checkbox" id="" checked="checked"></td>
																			<?php } ?>
																			<?php if($parameter3 == '0'){?>
																				<td><input type="checkbox" id=""></td>
																			<?php }else{ ?>
																				<td><input type="checkbox" id="" checked="checked"></td>
																			<?php } ?>
																			<?php if($parameter4 == '0'){?>
																				<td><input type="checkbox" id=""></td>
																			<?php }else{ ?>
																				<td><input type="checkbox" id="" checked="checked"></td>
																			<?php } ?>
																		</tr>
																<?php }
																}
															} ?>
														</tbody>
													</table>
												</div>
											</div>
										</div>
									</div>
								</div>
								<!--Tab de acciones-->
							</div>
						</div>
						<?php } ?>
						
						<div class="form-group">
							<div class="col-sm-4 col-sm-offset-2">
								<input id="base_url" type="hidden" value="<?php echo base_url(); ?>"/>
								<input id="id_profile" type="hidden" value="<?php echo $editar[0]->profile_id ?>"/>
								<input id="id_coin" type="hidden" value="<?php echo $editar[0]->coin_id ?>"/>
								<input id="id_lang" type="hidden" value="<?php echo $editar[0]->lang_id ?>"/>
                                <input id="id_status" type="hidden" value="<?php echo $editar[0]->status ?>"/>
                                <input id="ids_actions" type="hidden" value="<?php echo $ids_actions; ?>"/>
                                <input id="id_image" type="hidden" value="<?php echo $editar[0]->image; ?>"/>
                                <input id="data" name="data" type="hidden" value=""/>
								<input class="form-control"  type='hidden' id="id" name="id" value="<?php echo $id ?>"/>
								<input type="hidden" name="admin" id="admin" value="<?php echo $editar[0]->admin ?>">
								<button class="btn btn-white" id="volver2" type="button"><?php echo $this->lang->line('edit_back_users'); ?></button>
								<button class="btn btn-primary" id="edit" type="submit"><?php echo $this->lang->line('edit_save_users'); ?></button>
							</div>
						</div>
					</form>
				</div>
			</div>
        </div>
    </div>
</div>

<!-- FooTable -->
<script src="<?php echo assets_url('js/plugins/fileinput/fileinput.min.js');?>"></script>

<script src="<?php echo assets_url('script/users_alternative.js'); ?>" type="text/javascript" charset="utf-8" ></script>
