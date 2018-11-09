<!-- FooTable -->
<link href="<?php echo assets_url('css/plugins/fileinput/fileinput.min.css');?>" rel="stylesheet">

<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2><?php echo $this->lang->line('heading_title_users_registry'); ?></h2>
        <ol class="breadcrumb">
            <li>
                <a href="<?php echo base_url() ?>home"><?php echo $this->lang->line('heading_home_users_registry'); ?></a>
            </li>
            <li>
                <a href="<?php echo base_url() ?>users"><?php echo $this->lang->line('heading_subtitle_users_registry'); ?></a>
            </li>
            <li class="active">
                <strong><?php echo $this->lang->line('heading_info_users_registry'); ?></strong>
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
					<h5><?php echo $this->lang->line('heading_info_users_registry'); ?><small></small></h5>
				</div>
				<div class="ibox-content">
					<form id="form_users" method="post" accept-charset="utf-8" class="form-horizontal" enctype="multipart/form-data">
						<div class="form-group">
							<label class="col-sm-2 control-label" ><?php echo $this->lang->line('registry_image_users'); ?> *</label>
							<div class="col-sm-4">
								<input type="file" class="form-control image" placeholder="" name="image[]" id="image" onChange="valida_tipo($(this))">
							</div>
							<div class="col-sm-6">
								<img id="imgSalida" style="height:150px;width:150px;" class="img-circle" src="<?php echo base_url(); ?>assets/img/users/usuario.jpg">
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-2 control-label" ><?php echo $this->lang->line('registry_name_users'); ?> *</label>
							<div class="col-sm-10">
								<input type="text" class="form-control"  placeholder="" name="name" id="name">
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-2 control-label" ><?php echo $this->lang->line('registry_alias_users'); ?> *</label>
							<div class="col-sm-10">
								<input type="text" class="form-control"  placeholder="" name="alias" id="alias">
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-2 control-label" ><?php echo $this->lang->line('registry_user_users'); ?> *</label>
							<div class="col-sm-10">
								<input type="text" class="form-control"  placeholder="ejemplo@xmail.com" name="username" id="username">
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-2 control-label" ><?php echo $this->lang->line('registry_passwd_users'); ?> *</label>
							<div class="col-sm-10">
								<input type="password" class="form-control required"  placeholder="" name="password" id="password">
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-2 control-label" ><?php echo $this->lang->line('registry_repasswd_users'); ?> *</label>
							<div class="col-sm-10">
								<input type="password" class="form-control "  placeholder="" name="passw1" id="passw1">
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-2 control-label" ><?php echo $this->lang->line('registry_profile_users'); ?> *</label>
							<div class="col-sm-10">
								<select class="form-control m-b" name="profile_id" id="profile">
									<option value="0" selected="">Seleccione</option>
									<?php foreach ($list_perfil as $perfil) { ?>
										<option value="<?php echo $perfil->id ?>"><?php echo $perfil->name ?></option>
									<?php } ?>
								</select>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-2 control-label" ><?php echo $this->lang->line('registry_currency_users'); ?> *</label>
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
							<label class="col-sm-2 control-label" ><?php echo $this->lang->line('registry_language_users'); ?> *</label>
							<div class="col-sm-10">
								<select class="form-control m-b" name="lang_id" id="lang_id">
									<option value="0" selected="">Seleccione</option>
									<?php foreach($idiomas as $idioma){?>
									<option value="<?php echo $idioma->id; ?>"><?php echo ucfirst($idioma->name); ?></option>
									<?php }?>
								</select>
							</div>
						</div>
						<?php if($this->session->userdata('logged_in')['profile_id'] == 1){ ?>
						<div class="form-group"><label class="col-sm-2 control-label" ><?php echo $this->lang->line('registry_actions_users'); ?></label>
							<div class="col-sm-10">
								<select id="actions_ids" name="actions_ids[]" class="form-control" multiple="multiple">
									
								</select>
							</div>
						</div>
						<?php } ?>
						<div class="form-group">
							<label class="col-sm-2 control-label" ><?php echo $this->lang->line('registry_status_users'); ?> *</label>
							<div class="col-sm-10">
								<select class="form-control m-b" name="status" id="status">
									<option value="1" selected=""><?php echo $this->lang->line('registry_status1_users'); ?></option>
									<option value="0"><?php echo $this->lang->line('registry_status2_users'); ?></option>

								</select>
							</div>
						</div>
						<div class="form-group">
							<div class="col-sm-4 col-sm-offset-2">
								<input id="base_url" type="hidden" value="<?php echo base_url(); ?>"/>
								<input type='hidden' id="id" value=""/>
								<input type="hidden" name="admin" id="admin">
								<button class="btn btn-white" id="volver" type="button"><?php echo $this->lang->line('registry_back_users'); ?></button>
								<button class="btn btn-primary" id="registrar" type="submit"><?php echo $this->lang->line('registry_save_users'); ?></button>
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

<script src="<?php echo assets_url('script/users.js'); ?>" type="text/javascript" charset="utf-8" ></script>
