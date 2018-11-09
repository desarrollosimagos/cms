<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2> <?php echo $this->lang->line('heading_title_changepass'); ?>  </h2>
        <ol class="breadcrumb">
            <li>
                <a href="<?php echo base_url() ?>home"><?php echo $this->lang->line('heading_home_changepass'); ?></a>
            </li>
            <li class="active">
                <strong><?php echo $this->lang->line('heading_subtitle_changepass'); ?></strong>
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
					<h5><?php echo $this->lang->line('heading_subtitle_changepass'); ?> <small></small></h5>
				</div>
				<div class="ibox-content">
					<form id="change_passwd" method="post" accept-charset="utf-8" class="form-horizontal">
						
						<div class="form-group">
							<label class="col-sm-2 control-label" ><?php echo $this->lang->line('passwd_actual_changepass'); ?> *</label>
							<div class="col-sm-10">
								<input type="password" class="form-control required"  placeholder="" name="passwd_actual" id="passwd_actual">
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-2 control-label" ><?php echo $this->lang->line('passwd_new_changepass'); ?> *</label>
							<div class="col-sm-10">
								<input type="password" class="form-control required"  placeholder="" name="new_passwd" id="new_passwd">
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-2 control-label" ><?php echo $this->lang->line('confirm_new_passwd_changepass'); ?> *</label>
							<div class="col-sm-10">
								<input type="password" class="form-control "  placeholder="" name="confirm_new_passwd" id="confirm_new_passwd">
							</div>
						</div>
						<div class="form-group">
							<div class="col-sm-4 col-sm-offset-2">
								<input id="base_url" type="hidden" value="<?php echo base_url(); ?>"/>
								<input type='hidden' id="id" value=""/>
								<input type="hidden" name="admin" id="admin">
								<button class="btn btn-white" id="volver" type="button"><?php echo $this->lang->line('back_changepass'); ?></button>
								<button class="btn btn-primary" id="cambiar" type="button"><?php echo $this->lang->line('save_changepass'); ?></button>
							</div>
						</div>
					</form>
				</div>
			</div>
        </div>
    </div>
</div>
 <script src="<?php echo assets_url('script/change_passwd.js'); ?>" type="text/javascript" charset="utf-8" ></script>
