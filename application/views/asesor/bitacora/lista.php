<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2>Bitácora</h2>
        <ol class="breadcrumb">
            <li>
                <a href="<?php echo base_url() ?>home">Inicio</a>
            </li>
            <li class="active">
                <strong>Bitácora</strong>
            </li>
        </ol>
    </div>
</div>
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>Historial de acciones</h5>
                </div>
                <div class="ibox-content">
                    <div class="table-responsive">
                        <table id="tab_bitacora" class="table table-striped table-bordered dt-responsive table-hover dataTables-example" >
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Fecha</th>
                                    <th>IP</th>
                                    <th>Usuario</th>
                                    <th>Detalle</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $i = 1; ?>
                                <?php foreach ($listar as $action) { ?>
                                    <tr style="text-align: center">
                                        <td>
                                            <?php echo $i; ?>
                                        </td>
                                        <td>
                                            <?php echo $action->date; ?>
                                        </td>
                                        <td>
                                            <?php echo $action->ip; ?>
                                        </td>
                                        <td>
                                            <?php echo $action->username; ?>
                                        </td>
                                        <td>
                                            <?php echo $action->detail; ?>
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


 <!-- Page-Level Scripts -->
<script>
$(document).ready(function(){
     $('#tab_bitacora').DataTable({
        "paging": true,
        "lengthChange": true,
        "autoWidth": false,
        "searching": true,
        "ordering": true,
        "info": true,
        "iDisplayLength": 50,
        "iDisplayStart": 0,
        "sPaginationType": "full_numbers",
        "aLengthMenu": [10, 50, 100, 150],
        "oLanguage": {"sUrl": "<?= assets_url() ?>js/es.txt"},
        "aoColumns": [
            {"sClass": "registro center", "sWidth": "5%"},
            {"sClass": "registro center", "sWidth": "10%"},
            {"sClass": "registro center", "sWidth": "10%"},
            {"sClass": "registro center", "sWidth": "10%"},
            {"sClass": "registro center", "sWidth": "10%"},
        ]
    });
             
    // Validacion para borrar
    $("table#tab_bitacora").on('click', 'a.borrar', function (e) {
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
             
                $.post('<?php echo base_url(); ?>bitacora/delete/' + id + '', function (response) {

                    if (response['response'] == "existe") {
                       
                         swal({ 
                           title: "Disculpe,",
                            text: "No se puede eliminar, se encuentra asociado a una transacción",
                             type: "warning" 
                           },
                           function(){
                             
                         });
                    } else if (response['response'] == "existe2") {
                       
                         swal({ 
                           title: "Disculpe,",
                            text: "No se puede eliminar, se encuentra asociado a un grupo de inversionistas.",
                             type: "warning" 
                           },
                           function(){
                             
                         });
                    } else if (response['response'] == "error") {
                       
                         swal({ 
                           title: "Disculpe,",
                            text: "No se puede eliminar, ha ocurrido un falo en el sistema, por favor consulte con su administrador.",
                             type: "warning" 
                           },
                           function(){
                             
                         });
                    } else {
                         swal({ 
                           title: "Eliminar",
                            text: "Registro eliminado con exito.",
                             type: "success" 
                           },
                           function(){
                             window.location.href = '<?php echo base_url(); ?>bitacora';
                         });
                    }
                }, 'json');
            } 
        });
    });       
});
        
</script>
