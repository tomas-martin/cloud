<?php
include ('../../app/config.php');
include ('../../admin/layout/parte1.php');
include ('../../app/controllers/usuarios/listado_de_usuarios.php'); ?>

    <br>
    <div class="container-fluid">
        <h1>Listado de usuarios</h1>

        <div class="row">
            <div class="col-md-12">
                <div class="card card-outline card-primary">
                    <div class="card-header">
                        <h3 class="card-title"><b>Usuarios registrados</b></h3>
                    </div>
                    <div class="card-body">
                        <table id="example1" class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th style="text-align: center">Nro</th>
                                    <th style="text-align: center">Nombre completo</th>
                                    <th style="text-align: center">Email</th>
                                    <th style="text-align: center">Cargo</th>
                                    <th style="text-align: center">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php
                            $contador = 0;
                            foreach ($usuarios as $usuario){
                                $contador = $contador + 1;
                                $id_usuario = $usuario['id_usuario'];
                                ?>
                                <tr>
                                    <td><?php echo $contador; ?></td>
                                    <td><?php echo $usuario['nombre_completo']; ?></td>
                                    <td><?php echo $usuario['email']; ?></td>
                                    <td><?php echo $usuario['cargo']; ?></td>
                                    <td style="text-align: center">
                                        <div class="btn-group" role="group" aria-label="Basic example">
                                            <a href="show.php?id_usuario=<?php echo $id_usuario; ?>" class="btn btn-info"><i class="bi bi-eye-fill"></i> Ver</a>
                                            <a href="update.php?id_usuario=<?php echo $id_usuario;?>" type="button" class="btn btn-success"><i class="bi bi-pencil-square"></i> Editar</a>
                                            <a href="delete.php?id_usuario=<?php echo $id_usuario;?>" type="button" class="btn btn-danger"><i class="bi bi-trash3-fill"></i> Borrar</a>
                                        </div>
                                    </td>
                                </tr>
                            <?php
                            }
                            ?>
                            </tbody>
                        </table>

                        <br><br>


                    </div>
                </div>
            </div>
        </div>



    </div>

<?php
include ('../../admin/layout/parte2.php');
include ('../../admin/layout/mensaje.php');
?>

<script>
    $(function () {
        $("#example1").DataTable({
            "pageLength": 5,
            "language": {
                "emptyTable": "No hay información",
                "info": "Mostrando _START_ a _END_ de _TOTAL_ Usuarios",
                "infoEmpty": "Mostrando 0 a 0 de 0 Usuarios",
                "infoFiltered": "(Filtrado de _MAX_ total Usuarios)",
                "infoPostFix": "",
                "thousands": ",",
                "lengthMenu": "Mostrar _MENU_ Usuarios",
                "loadingRecords": "Cargando...",
                "processing": "Procesando...",
                "search": "Buscador:",
                "zeroRecords": "Sin resultados encontrados",
                "paginate": {
                    "first": "Primero",
                    "last": "Ultimo",
                    "next": "Siguiente",
                    "previous": "Anterior"
                }
            },
            "responsive": true, "lengthChange": true, "autoWidth": false,
            buttons: [{
                extend: 'collection',
                text: 'Reportes',
                orientation: 'landscape',
                buttons: [{
                    text: 'Copiar',
                    extend: 'copy',
                }, {
                    extend: 'pdf'
                },{
                    extend: 'csv'
                },{
                    extend: 'excel'
                },{
                    text: 'Imprimir',
                    extend: 'print'
                }
                ]
            },
                {
                    extend: 'colvis',
                    text: 'Visor de columnas',
                    collectionLayout: 'fixed three-column'
                }
            ],
        }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
    });
</script>
