<?php
include ('../../app/config.php');
include ('../../admin/layout/parte1.php');

$id_usuario = $_GET['id_usuario'];
include ('../../app/controllers/usuarios/datos_del_usuario.php');
?>

    <br>
    <div class="container-fluid">

        <h1>Datos del usuario</h1>

        <div class="row">
            <div class="col-md-6">
                <div class="card card-outline card-primary">
                    <div class="card-header">

                        <h3 class="card-title"><b>Datos registrados</b></h3>
                    </div>
                    <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="">Nombre completo</label>
                                        <input type="text" value="<?php echo $nombre_completo; ?>" name="nombre_completo" class="form-control" disabled>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="">Correo electronico</label>
                                        <input type="email" value="<?php echo $email; ?>" name="email" class="form-control" disabled>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="">Cargo</label>
                                        <input type="text" class="form-control" value="<?php echo $cargo; ?>" disabled>
                                    </div>
                                </div>
                            </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

<?php
include ('../../admin/layout/parte2.php');
include ('../../admin/layout/mensaje.php');
?>