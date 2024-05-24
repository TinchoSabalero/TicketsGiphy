<?php
include("conexion.php");
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Agregar ticket</title>

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
            <h2>Datos del ticket &raquo; Agregar datos</h2>
            <hr />

            <?php
            if (isset($_POST['add'])) {

                $name = mysqli_real_escape_string($con, (strip_tags($_POST["name"], ENT_QUOTES)));
                $description = mysqli_real_escape_string($con, (strip_tags($_POST["description"], ENT_QUOTES)));
                $level = mysqli_real_escape_string($con, (strip_tags($_POST["level"], ENT_QUOTES)));
                $date = new DateTime();
                $dateFormat = $date->format('Y-m-d');

                $insert = mysqli_query($con, "INSERT INTO tickets(name, description, level, state, created_at)
															VALUES('$name','$description', '$level', '0',  '$dateFormat')");
                if ($insert) {
                    echo '<div class="alert alert-success alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>Bien hecho! Los datos han sido guardados con éxito.</div>';
                } else {
                    echo '<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>Error. No se pudo guardar los datos !</div>';
                }
            }
            ?>

            <form class="form-horizontal" action="" method="post">
                <div class="form-group">
                    <label class="col-sm-3 control-label">Nombre</label>
                    <div class="col-sm-4">
                        <input type="text" name="name" class="form-control" placeholder="Nombre" required>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label">Descripción</label>
                    <div class="col-sm-3">
                        <input type="text" name="description" class="form-control" placeholder="Descripción"></textarea>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label">Nivel</label>
                    <div class="col-sm-3">
                        <select name="level" class="form-control">
                            <option value="0">Bajo</option>
                            <option value="1">Medio</option>
                            <option value="2">Alto</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-3 control-label">&nbsp;</label>
                    <div class="col-sm-6">
                        <input type="submit" name="add" class="btn btn-sm btn-primary" value="Guardar datos">
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