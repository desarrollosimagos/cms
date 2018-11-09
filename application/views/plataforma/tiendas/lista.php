<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2>Tiendas</h2>
        <ol class="breadcrumb">
            <li>
                <a href="<?php echo base_url() ?>home">Inicio</a>
            </li>
            <li class="active">
                <strong>Tiendas</strong>
            </li>
        </ol>
    </div>
</div>
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">
            <a href="<?php echo base_url() ?>tiendas/register">
            <button class="btn btn-outline btn-primary dim" type="button"><i class="fa fa-plus"></i> Agregar</button>
            </a>
            <!--<button class="btn btn-outline btn-primary dim" id="referenciar" type="button"><i class="fa fa-refresh"></i> Referenciar</button>-->
            <label id="label_precio_dolar" style="color:red;"></label>
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>Listado de Tiendas</h5>
                    <input type="hidden" id="precio_dolar">
                </div>
                <div class="ibox-content">
                    <div class="table-responsive">
                        <table id="tab_tiendas" class="table table-striped table-bordered dt-responsive table-hover dataTables-example" >
                            <thead>
                                <tr style="text-align: center !important;">
                                    <th>#</th>
                                    <th>Rif</th>
                                    <th>Nombre</th>
                                    <th>status</th>
                                    <th>Dirección</th>
                                    <th>Referencia</th>
                                    <th>BD Externa</th>
                                    <th>Campo Externo</th>
                                    <th>Teléfono</th>
                                    <th>Editar</th>
                                    <th>Eliminar</th>
                                    <th>Update Precios</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $i = 1; ?>
                                <?php foreach ($listar as $tienda) { ?>
                                    <tr style="text-align: center">
                                        <td>
                                            <?php echo $i; ?>
                                        </td>
                                        <td>
                                            <?php echo $tienda->rif; ?>
                                        </td>
                                        <td>
                                            <?php echo $tienda->name; ?>
                                        </td>
                                        <td>
                                            <?php
												if($tienda->status == 1){
													echo "Activo"; 
												}else if($tienda->status == 0){
													echo "Inactivo"; 
												}else{
													echo "";
												}
                                            ?>
                                        </td>
                                        <td>
                                            <?php echo $tienda->address; ?>
                                        </td>
                                        <td>
                                            <?php echo $tienda->referencia; ?>
                                        </td>
                                        <td>
                                            <?php echo $tienda->bd_externa; ?>
                                        </td>
                                        <td>
                                            <?php echo $tienda->cp_externo; ?>
                                        </td>
                                        <td>
                                            <?php echo $tienda->phone; ?>
                                        </td>
                                        <td style='text-align: center'>
                                            <a href="<?php echo base_url() ?>tiendas/edit/<?= $tienda->id; ?>" title="Editar" style='color: #1ab394'><i class="fa fa-edit fa-2x"></i></a>
                                        </td>
                                        <td style='text-align: center'>
                                            <a class='borrar' id='<?php echo $tienda->id; ?>' style='color: #1ab394' title='Eliminar'><i class="fa fa-trash-o fa-2x"></i></a>
                                        </td>
                                        <td style='text-align: center'>
                                            <a class='actualizar' href="<?php echo base_url()."update_prices_m3?id=".$tienda->id; ?>"style='color: #1ab394' title='Actualizar precios'><i class="fa fa-upload fa-2x"></i></a>
                                        </td>
                                    </tr>
                                    <?php $i++ ?>
                                <?php } ?>
                            </tbody>
                        </table>
                        <input type="hidden" id="update_prices" value="<?php echo base_url(); ?>"/>
                        <!--<div class="text-right">
							<button class="btn btn-outline btn-primary dim" id="actualizar_montos" type="button"><i class="fa fa-floppy-o"></i> Actualizar</button>
						</div>-->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


 <!-- Page-Level Scripts -->
<script>
$(document).ready(function(){
	$('#tab_tiendas').DataTable({
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
        "iDisplayLength": 100,
        "iDisplayStart": 0,
        "sPaginationType": "full_numbers",
        "aLengthMenu": [100, 200, 300],
        "oLanguage": {"sUrl": "<?= assets_url() ?>js/es.txt"},
        "aoColumns": [
            {"sClass": "registro center", "sWidth": "5%"},
            {"sClass": "registro center", "sWidth": "30%"},
            {"sClass": "registro center", "sWidth": "30%"},
            {"sClass": "registro center", "sWidth": "10%"},
            {"sClass": "none", "sWidth": "10%"},
            {"sClass": "none", "sWidth": "10%"},
            {"sClass": "none", "sWidth": "10%"},
            {"sClass": "none", "sWidth": "10%"},
            {"sClass": "none", "sWidth": "10%"},
            {"sWidth": "3%", "bSortable": false, "sClass": "center sorting_false", "bSearchable": false},
            {"sWidth": "3%", "bSortable": false, "sClass": "center sorting_false", "bSearchable": false},
            {"sWidth": "3%", "bSortable": false, "sClass": "center sorting_false", "bSearchable": false}
        ]
    });
             
    // Validacion para borrar
    $("table#tab_tiendas").on('click', 'a.borrar', function (e) {
        e.preventDefault();
        var id = this.getAttribute('id');

        swal({
            title: "Borrar registro",
            text: "¿Está seguro de borrar el registro?",
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
             
                $.post('<?php echo base_url(); ?>tiendas/delete/' + id + '', function (response) {

                    if (response[0] == "e") {
                       
                         swal({ 
                           title: "Disculpe,",
                            text: "No se puede eliminar, tiene productos asociados",
                             type: "warning" 
                           },
                           function(){
                             
                         });
                    }else{
                         swal({ 
                           title: "Eliminar",
                            text: "Registro eliminado con exito",
                             type: "success" 
                           },
                           function(){
                             window.location.href = '<?php echo base_url(); ?>tiendas';
                         });
                    }
                });
            } 
        });
    });
    
    // Validacion para borrar
    //~ $("#update_prices").on('click', function (e) {
        //~ e.preventDefault();
	//~ 
		//~ $.post('<?php echo base_url(); ?>mercado/update', function (response) {
			//~ 
		//~ });
	//~ });
	
});
        
</script>
