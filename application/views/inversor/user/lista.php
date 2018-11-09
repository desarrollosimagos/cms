<input id="base_url" type="hidden" value="<?php echo base_url(); ?>"/>
<script src="<?php echo assets_url('script/users.js'); ?>" type="text/javascript" charset="utf-8" ></script>
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2><?php echo $this->lang->line('heading_title_users'); ?></h2>
        <ol class="breadcrumb">
            <li>
                <a href="<?php echo base_url() ?>home"><?php echo $this->lang->line('heading_home_users'); ?></a>
            </li>
            <li class="active">
                <strong><?php echo $this->lang->line('heading_subtitle_users'); ?></strong>
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
            <a href="<?php echo base_url() ?>users/register">
            <button class="btn btn-outline btn-primary dim" type="button"><i class="fa fa-plus"></i> <?php echo $this->lang->line('btn_registry_users'); ?></button></a>
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5><?php echo $this->lang->line('list_title_users'); ?></h5>
                </div>
                <div class="ibox-content">
                    <div class="table-responsive">
                        <table id="tab_users" class="table table-striped table-bordered dt-responsive table-hover dataTables-example" >
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th><?php echo $this->lang->line('list_name_users'); ?></th>
                                    <th><?php echo $this->lang->line('list_alias_users'); ?></th>
                                    <th><?php echo $this->lang->line('list_user_users'); ?></th>
                                    <th><?php echo $this->lang->line('list_currency_users'); ?></th>
                                    <th><?php echo $this->lang->line('list_profile_users'); ?></th>
                                    <th><?php echo $this->lang->line('list_permissions_users'); ?></th>
                                    <th><?php echo $this->lang->line('list_language_users'); ?></th>
                                    <th><?php echo $this->lang->line('list_edit_users'); ?></th>
                                    <th><?php echo $this->lang->line('list_active_users'); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $i = 1; ?>
                                <?php foreach ($listar as $usuario) { ?>
                                    <tr style="text-align: center">
                                        <td>
                                            <?php echo $i; ?>
                                        </td>
                                        <td>
                                            <?php echo $usuario->name; ?>
                                        </td>
                                        <td>
                                            <?php echo $usuario->alias; ?>
                                        </td>
                                        <td>
                                            <?php echo $usuario->username; ?>
                                        </td>
                                        <td>
                                            <?php echo $usuario->coin_avr." (".$usuario->coin.")"; ?>
                                        </td>
                                        <td>
                                            <?php echo $usuario->perfil; ?>
                                        </td>
                                        <td>
                                            <?php
                                            echo "<br>";
                                            // Validamos qué acciones están asociadas a cada usuario
                                            foreach($permisos as $permiso){
												if($usuario->id == $permiso->user_id){
													foreach ($acciones as $accion){
														if($permiso->action_id == $accion->id){
															echo $accion->name."<br>";
														}else{
															echo "";
														}
													}
												}
											}
											?>
                                        </td>
                                        <td>
                                            <?php echo $usuario->lang_name; ?>
                                        </td>
                                        <td style='text-align: center'>
                                            <a href="<?php echo base_url() ?>users/edit/<?= $usuario->id; ?>"  title="<?php echo $this->lang->line('list_edit_users'); ?>"><i class="fa fa-edit fa-2x"></i></a>
                                        </td>
                                        <td style='text-align: center'>
											<?php if ($usuario->status == 1) {?>
											<input class='activar_desactivar' id='<?php echo $usuario->id; ?>' type="checkbox" title='<?php echo $this->lang->line('list_active1_users'); ?> <?php echo $usuario->id;?>' checked="checked"/>
											<?php }else if ($usuario->status == 0){ ?>
											<input class='activar_desactivar' id='<?php echo $usuario->id; ?>' type="checkbox" title='<?php echo $this->lang->line('list_active2_users'); ?> <?php echo $usuario->id;?>'/>
											<?php } ?>
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
    </div>
</div>

