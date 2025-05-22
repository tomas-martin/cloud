<?php
include ('../../app/config.php');
include ('../../admin/layout/parte1.php');

$id_producto = $_GET['id_producto'];
include ('../../app/controllers/productos/datos_del_producto.php');


?>


<br>
<div class="container-fluid">

    <h1>Actualización del producto</h1>

    <div class="row">
        <div class="col-md-12">
            <div class="card card-outline card-success">
                <div class="card-header">
                    <h3 class="card-title"><b>Datos del producto</b></h3>
                </div>
                <div class="card-body">
                    <form action="../../app/controllers/productos/update.php" method="post" enctype="multipart/form-data">
                        <div class="row">
                            <div class="col-md-9">
                                <div class="row">
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="">Código</label><b> *</b>
                                            <input type="text" class="form-control" value="<?= $codigo; ?>" disabled>
                                            <input type="text" name="codigo" class="form-control" value="<?= $codigo; ?>" hidden>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="">Nombre del producto</label><b> *</b>
                                            <input type="text" name="nombre_producto" value="<?= $nombre_producto; ?>" class="form-control" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="">Descripción</label>
                                            <input type="text" name="descripcion" value="<?= $descripcion; ?>" class="form-control">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="">Stock</label><b> *</b>
                                            <input type="number" name="stock" value="<?= $stock; ?>" class="form-control" required>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="">Stock mínimo</label><b> *</b>
                                            <input type="number" name="stock_minimo" value="<?=$stock_minimo;?>" class="form-control" required>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="">Stock máximo</label><b> *</b>
                                            <input type="number" name="stock_maximo" value="<?=$stock_maximo;?>" class="form-control" required>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="">Precio compra</label><b> *</b>
                                            <input type="number" name="precio_compra" value="<?=$precio_compra;?>" class="form-control" required>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="">Precio venta</label><b> *</b>
                                            <input type="number" name="precio_venta" value="<?=$precio_venta;?>" class="form-control" required>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="">Fecha de ingreso</label><b> *</b>
                                            <input type="date" name="fecha_de_ingreso" value="<?=$fecha_de_ingreso;?>" class="form-control" required>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="">Imagen</label>
                                    <input type="file" class="form-control" name="file" id="file">
                                    <br>
                                    <center>
                                        <output id="list">
                                            <img src="<?=$URL."/public/images/productos/".$imagen;?>" width="80%" alt="">
                                        </output>
                                    </center>
                                </div>
                            </div>
                        </div>
                        <input type="text" name="id_usuario" value="<?= $id_usuario_sesion; ?>" hidden>
                        <input type="text" value="<?= $id_producto;?>" name="id_producto" hidden>
                        <input type="text" value="<?= $imagen;?>" name="imagen" hidden >
                        <hr>
                        <div class="row">
                            <div class="col-md-12">
                                <a href="index.php" class="btn btn-secondary">Cancelar</a>
                                <input type="submit" class="btn btn-success" value="Actualizar producto">
                            </div>
                        </div>
                    </form>
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
    function archivo(evt) {
        var files = evt.target.files; // FileList object
        // Obtenemos la imagen del campo "file".
        for (var i = 0, f; f = files[i]; i++) {
            //Solo admitimos imágenes.
            if (!f.type.match('image.*')) {
                continue;
            }
            var reader = new FileReader();
            reader.onload = (function (theFile) {
                return function (e) {
                    // Insertamos la imagen
                    document.getElementById("list").innerHTML = ['<img class="thumb thumbnail" src="',e.target.result, '" width="200px" title="', escape(theFile.name), '"/>'].join('');
                };
            })(f);
            reader.readAsDataURL(f);
        }
    }
    document.getElementById('file').addEventListener('change', archivo, false);
</script>

