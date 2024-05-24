<?php
include("conexion.php");
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Datos del ticket</title>

    <!-- Bootstrap -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/bootstrap-datepicker.css" rel="stylesheet">
    <link href="css/style_nav.css" rel="stylesheet">
    <style>
        .content {
            margin-top: 80px;
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-default navbar-fixed-top">
        <?php include("nav.php"); ?>
    </nav>
    <div class="container">
        <div class="content">
            <h2>Datos del ticket &raquo; Editar datos</h2>
            <hr />

            <?php
            $nik = mysqli_real_escape_string($con, (strip_tags($_GET["nik"], ENT_QUOTES)));
            $sql = mysqli_query($con, "SELECT * FROM tickets WHERE id_ticket='$nik'");
            if (mysqli_num_rows($sql) == 0) {
                header("Location: index.php");
            } else {
                $row = mysqli_fetch_assoc($sql);
            }
            if (isset($_POST['save'])) {

                $name = mysqli_real_escape_string($con, (strip_tags($_POST["name"], ENT_QUOTES)));
                $description = mysqli_real_escape_string($con, (strip_tags($_POST["description"], ENT_QUOTES)));
                $level = mysqli_real_escape_string($con, (strip_tags($_POST["level"], ENT_QUOTES)));
                $state = mysqli_real_escape_string($con, (strip_tags($_POST["state"], ENT_QUOTES)));

                $update = mysqli_query($con, "UPDATE tickets SET name='$name', description='$description', level='$level', state='$state' WHERE id_ticket='$nik'");
                if ($update) {
                    header("Location: edit.php?nik=" . $nik . "&pesan=sukses");
                } else {
                    echo '<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>Error, no se pudo guardar los datos.</div>';
                }
            }

            if (isset($_GET['pesan']) == 'sukses') {
                echo '<div class="alert alert-success alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>Los datos han sido guardados con éxito.</div>';
            }
            ?>
            <form class="form-horizontal" action="" method="post">

                <div class="form-group">
                    <label class="col-sm-3 control-label">Nombre</label>
                    <div class="col-sm-4">
                        <input type="text" name="name" value="<?php echo $row['name']; ?>" class="form-control" placeholder="Nombre" required>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label">Descripción</label>
                    <div class="col-sm-4">
                        <input type="text" name="description" value="<?php echo $row['description']; ?>" class="form-control" placeholder="Descripción">
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-3 control-label">Nivel</label>
                    <div class="col-sm-3">
                        <select name="level" class="form-control">
                            <option value="">- Selecciona nivel -</option>
                            <option value="0" <?php if ($row['level'] == 0) {
                                                    echo "selected";
                                                } ?>>Bajo</option>
                            <option value="1" <?php if ($row['level'] == 1) {
                                                    echo "selected";
                                                } ?>>Medio</option>
                            <option value="2" <?php if ($row['level'] == 2) {
                                                    echo "selected";
                                                } ?>>Alto</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-3 control-label">Estado</label>
                    <div class="col-sm-3">
                        <select name="state" class="form-control">
                            <option value="">- Selecciona estado -</option>
                            <option value="0" <?php if ($row['state'] == 0) {
                                                    echo "selected";
                                                } ?>>Incompleto</option>
                            <option value="1" <?php if ($row['state'] == 1) {
                                                    echo "selected";
                                                } ?>>Completo</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-3 control-label">&nbsp;</label>
                    <div class="col-sm-6">
                        <input type="submit" name="save" class="btn btn-sm btn-primary" value="Guardar datos">
                        <a href="index.php" class="btn btn-sm btn-danger">Cancelar</a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/bootstrap-datepicker.js"></script>
</body>

</html>