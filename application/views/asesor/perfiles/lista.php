<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2><?php echo $this->lang->line('heading_title_profiles'); ?></h2>
        <ol class="breadcrumb">
            <li>
                <a href="<?php echo base_url() ?>home"><?php echo $this->lang->line('heading_home_profiles'); ?></a>
            </li>
            <li class="active">
                <strong><?php echo $this->lang->line('heading_subtitle_profiles'); ?></strong>
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
            <a href="<?php echo base_url() ?>profile/register">
            <button class="btn btn-outline btn-primary dim" type="button"><i class="fa fa-plus"></i> <?php echo $this->lang->line('btn_registry_profiles'); ?></button></a>
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5><?php echo $this->lang->line('list_title_profiles'); ?> </h5>
                </div>
                <div class="ibox-content">
                    <div class="table-responsive">
                        <table id="tab_perfiles" class="table table-striped table-bordered dt-responsive table-hover dataTables-example" >
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th><?php echo $this->lang->line('list_name_profiles'); ?></th>
                                    <th><?php echo $this->lang->line('list_actions_profiles'); ?></th>
                                    <th><?php echo $this->lang->line('list_edit_profiles'); ?></th>
                                    <th><?php echo $this->lang->line('list_delete_profiles'); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $i = 1; ?>
                                <?php foreach ($listar as $perfil) { 
									if($perfil->name != 'ADMINISTRADOR'){?>
                                    <tr style="text-align: center">
                                        <td>
                                            <?php echo $i; ?>
                                        </td>
                                        <td>
                                            <?php echo $perfil->name; ?>
                                        </td>
                                        <td>
                                            <?php
                                            echo "<br>";
                                            // Validamos qué acciones están asociadas a cada perfil
                                            foreach($profile_acciones as $profile_accion){
												if($perfil->id == $profile_accion->profile_id){
													foreach ($acciones as $accion){
														if($profile_accion->action_id == $accion->id){
															echo $accion->name."<br>";
														}else{
															echo "";
														}
													}
												}
											}
											?>
                                        </td>
                                        <td style='text-align: center'>
                                            <a href="<?php echo base_url() ?>profile/edit/<?= $perfil->id; ?>" title="<?php echo $this->lang->line('list_edit_profiles'); ?>"><i class="fa fa-edit fa-2x"></i></a>
                                        </td>
                                        <td style='text-align: center'>
                                            
                                            <a class='borrar' id='<?php echo $perfil->id; ?>' title='<?php echo $this->lang->line('list_delete_profiles'); ?>'><i class="fa fa-trash-o fa-2x"></i></a>
                                        </td>
                                    </tr>
                                    <?php $i++ ?>
                                <?php } 
                                }?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


 <!-- Page-Level Scripts -->
<script>
$(document).ready(function(){
    $('#tab_perfiles').DataTable({
       "paging": true,
       "lengthChange": false,
       "autoWidth": false,
       "searching": true,
       "ordering": true,
       "info": true,
       dom: '<"html5buttons"B>lTfgitp',
       buttons: [
           { extend: 'copy'},
           {extend: 'csv'},
           {extend: 'excel', title: 'ExampleFile'},
           {extend: 'pdf', title: 'ExampleFile'},

           {extend: 'print',
            customize: function (win){
                   $(win.document.body).addClass('white-bg');
                   $(win.document.body).css('font-size', '10px');

                   $(win.document.body).find('table')
                           .addClass('compact')
                           .css('font-size', 'inherit');
           }
           }
       ],
       "iDisplayLength": 5,
       "iDisplayStart": 0,
       "sPaginationType": "full_numbers",
       "aLengthMenu": [5, 10, 15],
       "oLanguage": {"sUrl": "<?= base_url() ?>assets/js/es.txt"},
       "aoColumns": [
           {"sClass": "registro center", "sWidth": "5%"},
           {"sClass": "registro center", "sWidth": "20%"},
           {"sClass": "none", "sWidth": "8%"},
           {"sWidth": "3%", "bSortable": false, "sClass": "center sorting_false", "bSearchable": false},
           {"sWidth": "3%", "bSortable": false, "sClass": "center sorting_false", "bSearchable": false}
       ]
   });
             
         // Validacion para borrar
    $("table#tab_perfiles").on('click', 'a.borrar', function (e) {
        e.preventDefault();
        var id = this.getAttribute('id');

        swal({
            title: "Borrar registro",
            text: "¿Está seguro de borrar el perfil?",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Eliminar",
            cancelButtonText: "Cancelar",
            closeOnConfirm: false,
            closeOnCancel: true
          },
          function(isConfirm){
            if (isConfirm) {
             
				$.post('<?php echo base_url(); ?>profile/delete/' + id + '', function (response) {
					
					if (response == 'existe') {
						
						swal("Disculpe,", "este perfil se encuentra asociado a un usuario");
						
					}else{
						
						swal({ 
						title: "Eliminar",
						 text: "Registro eliminado con exito",
						  type: "success" 
						},
						function(){
						  window.location.href = '<?php echo base_url(); ?>profile';
						});
						
					}
					
				});
            } 
          });
        
    });
            
});
        
</script>
