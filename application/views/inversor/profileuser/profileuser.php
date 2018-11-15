<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2> <?php echo $this->lang->line('heading_title_profileuser'); ?>  </h2>
        <ol class="breadcrumb">
            <li>
                <a href="<?php echo base_url() ?>home"><?php echo $this->lang->line('heading_home_profileuser'); ?></a>
            </li>
            <li class="active">
                <strong><?php echo $this->lang->line('heading_subtitle_profileuser'); ?></strong>
            </li>
        </ol>
    </div>
</div>

<!-- Campos ocultos que almacenan los nombres del menú y el submenú de la vista actual -->
<input type="hidden" id="ident" value="<?php echo $ident; ?>">
<input type="hidden" id="ident_sub" value="<?php echo $ident_sub; ?>">

<div class="wrapper wrapper-content animated fadeInRight">
	
	<form id="profileuser" method="post" accept-charset="utf-8" class="form-horizontal">
	
	<!-- Sección de datos básicos -->
	<div class="row">
        <div class="col-lg-12">
			<div class="ibox float-e-margins">
				<div class="ibox-title">
					<h5><?php echo $this->lang->line('edit_title1_profileuser'); ?> <small></small></h5>
				</div>
				<div class="ibox-content">
						
					<div class="form-group">
						<label class="col-sm-2 control-label" ><?php echo $this->lang->line('edit_user_profileuser'); ?> *</label>
						<div class="col-sm-10">
							<input type="text" class="form-control"  placeholder="ejemplo@xmail.com" name="username" id="username" value="<?php echo $editar[0]->username ?>" readonly="true">
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label" ><?php echo $this->lang->line('edit_name_profileuser'); ?> *</label>
						<div class="col-sm-10">
							<input type="text" class="form-control"  placeholder="" name="name" id="name" value="<?php echo $editar[0]->name ?>">
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label" ><?php echo $this->lang->line('edit_alias_profileuser'); ?> *</label>
						<div class="col-sm-10">
							<input type="text" class="form-control"  placeholder="" name="alias" id="alias" value="<?php echo $editar[0]->alias ?>">
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label" ><?php echo $this->lang->line('edit_language_profileuser'); ?> *</label>
						<div class="col-sm-10">
							<select class="form-control m-b" name="lang_id" id="lang_id">
								<option value="0" selected="">Seleccione</option>
								<?php foreach($idiomas as $idioma){?>
								<option value="<?php echo $idioma->id; ?>"><?php echo ucfirst($idioma->name); ?></option>
								<?php }?>
							</select>
						</div>
					</div>				
					<div class="form-group">
						<label class="col-sm-2 control-label" ><?php echo $this->lang->line('edit_image_profileuser'); ?> *</label>
						<div class="col-sm-4">
							<input type="file" class="form-control image" name="image[]" id="image" onChange="valida_tipo($(this))">
						</div>
						<div class="col-sm-6">
							<img id="imgSalida" style="height:150px;width:150px;" class="img-circle" src="<?php echo base_url(); ?>assets/img/users/<?php echo $editar[0]->image; ?>">
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label" ><?php echo $this->lang->line('edit_password_profileuser'); ?> *</label>
						<div class="col-sm-10">
							<a href="<?php echo base_url() ?>users/change_passwd">
								<button class="btn btn-outline btn-primary dim" type="button">
									<i class="fa fa-plus"></i>
									<?php echo $this->lang->line('edit_buttonpassword_profileuser'); ?>
								</button>
							</a>
						</div>
					</div>
						
				</div>
			</div>
        </div>
    </div>
    <!-- Cierre de sección de datos básicos -->
    
    <!-- Sección de datos complementarios -->
	<div class="row">
        <div class="col-lg-12">
			<div class="ibox float-e-margins">
				<div class="ibox-title">
					<h5><?php echo $this->lang->line('edit_title2_profileuser'); ?> <small></small></h5>
				</div>
				<div class="ibox-content">
						
					<div class="form-group">
						<label class="col-sm-2 control-label" ><?php echo $this->lang->line('edit_dni_profileuser'); ?> *</label>
						<div class="col-sm-10">
							<input type="text" class="form-control" placeholder="V-12345678" onkeypress="return valida_cedula(event)" name="dni" id="dni" maxlength="11" value="<?php echo $editar_data[0]->dni ?>">
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label" ><?php echo $this->lang->line('edit_gender_profileuser'); ?> *</label>
						<div class="col-sm-10">
							<select class="form-control m-b" name="gender" id="gender">
								<option value="0" selected="">Seleccione</option>
								<option value="masculine"><?php echo $this->lang->line('edit_gender1_profileuser'); ?></option>
								<option value="femenine"><?php echo $this->lang->line('edit_gender2_profileuser'); ?></option>
							</select>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label" ><?php echo $this->lang->line('edit_birthday_profileuser'); ?> *</label>
						<div class="col-sm-10">
							<?php
							if($editar_data[0]->birthday != ''){
								$birthday = explode("-", $editar_data[0]->birthday);
								$birthday = $birthday[2] . "/" . $birthday[1] . "/" . $birthday[0];
							}else{
								$birthday = "";
							}
							?>
							<input type="text" class="form-control" placeholder="" name="birthday" id="birthday" value="<?php echo $birthday ?>">
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label" ><?php echo $this->lang->line('edit_phone_profileuser'); ?> *</label>
						<div class="col-sm-10">
							<input type="text" class="form-control" onkeypress="return valida_telefono(event)" maxlength="11" name="phone" id="phone" value="<?php echo $editar_data[0]->phone ?>">
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label" ><?php echo $this->lang->line('edit_emergency_contact_profileuser'); ?> *</label>
						<div class="col-sm-10">
							<input type="text" class="form-control" placeholder="" name="emergency_contact" id="emergency_contact" value="<?php echo $editar_data[0]->emergency_contact ?>">
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label" ><?php echo $this->lang->line('edit_emergency_phone_profileuser'); ?> *</label>
						<div class="col-sm-10">
							<input type="text" class="form-control" onkeypress="return valida_telefono(event)" maxlength="11" name="emergency_phone" id="emergency_phone" value="<?php echo $editar_data[0]->emergency_phone ?>">
						</div>
					</div>
					
					<div class="form-group">
						<div class="col-sm-4 col-sm-offset-2">
							<input id="base_url" type="hidden" value="<?php echo base_url(); ?>"/>
							<input id="id_gender" type="hidden" value="<?php echo $editar_data[0]->gender ?>"/>
							<input id="id_lang" type="hidden" value="<?php echo $editar[0]->lang_id ?>"/>
							<input id="id_image" type="hidden" value="<?php echo $editar[0]->image; ?>"/>
							<input class="form-control"  type='hidden' id="id" name="id" value="<?php echo $this->session->userdata('logged_in')['id'] ?>"/>
							<input type="hidden" name="admin" id="admin" value="<?php echo $editar[0]->admin ?>">
							<button class="btn btn-white" id="volver" type="button"><?php echo $this->lang->line('back_changepass'); ?></button>
							<button class="btn btn-primary" id="update" type="button"><?php echo $this->lang->line('save_changepass'); ?></button>
						</div>
					</div>
					
				</div>
			</div>
        </div>
    </div>
    <!-- Cierre sección de datos complementarios -->
    
    </form>
    
</div>
 <script src="<?php echo assets_url('script/profileuser.js'); ?>" type="text/javascript" charset="utf-8" ></script>
