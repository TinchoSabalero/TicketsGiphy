<?php
include("conexion.php");

$api_key = 'oFmxwMtykNb0JUSixtzH1XpmLvyeD5qS';
$base_url = "https://api.giphy.com/v1/gifs/search";

$search_query = [
    0 => 'art-typography-alphabet-hgBrc2DZ7d1TM60Lun',
    1 => 'aminalstickers-ok-okay-purple-Qu6WPG7U9zkXNyiqAP',
    2 => 'NeighborlyNotaryNYC-level-high-2sMspqFaTy4syGc59M',
];


?>
<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Datos de tickets</title>

    <!-- Bootstrap -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/style_nav.css" rel="stylesheet">

    <style>
        .content {
            margin-top: 80px;
        }
    </style>

</head>

<body>
    <nav class="navbar navbar-default navbar-fixed-top">
        <?php include('nav.php'); ?>
    </nav>
    <div class="container">
        <div class="content">
            <h2>Lista de tickets</h2>
            <hr />

            <?php
            if (isset($_GET['aksi']) == 'delete') {
                $nik = mysqli_real_escape_string($con, (strip_tags($_GET["nik"], ENT_QUOTES)));
                $cek = mysqli_query($con, "SELECT * FROM tickets WHERE id_ticket='$nik'");
                if (mysqli_num_rows($cek) == 0) {
                    echo '<div class="alert alert-info alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button> No se encontraron datos.</div>';
                } else {
                    $delete = mysqli_query($con, "UPDATE tickets SET state = 2 WHERE id_ticket='$nik'");
                    if ($delete) {
                        echo '<div class="alert alert-success alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button> Datos eliminado correctamente.</div>';
                    } else {
                        echo '<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button> Error, no se pudo eliminar los datos.</div>';
                    }
                }
            }
            ?>

            <form class="form-inline" method="get">
                <div class="form-group">
                    <select name="filterState" class="form-control" onchange="form.submit()">
                        <option value="">Estado</option>
                        <?php $filterState = (isset($_GET['filterState']) ? strtolower($_GET['filterState']) : NULL);  ?>
                        <option value="0" <?php if ($filterState == 0) {
                                                echo 'selected';
                                            } ?>>Incompleto</option>
                        <option value="1" <?php if ($filterState == 1) {
                                                echo 'selected';
                                            } ?>>Completo</option>
                    </select>
                </div>

                <div class="form-group">
                    <select name="filterLevel" class="form-control" onchange="form.submit()">
                        <option value="">Nivel</option>
                        <?php $filterLevel = (isset($_GET['filterLevel']) ? strtolower($_GET['filterLevel']) : NULL);  ?>
                        <option value="0" <?php if ($filterLevel == 0) {
                                                echo 'selected';
                                            } ?>>Bajo</option>
                        <option value="1" <?php if ($filterLevel == 1) {
                                                echo 'selected';
                                            } ?>>Medio</option>
                        <option value="2" <?php if ($filterLevel == 3) {
                                                echo 'selected';
                                            } ?>>Alto</option>
                    </select>
                </div>

                <div class="form-group">
                    <div class="col-md-6">
                        <input type="date" value="<?= $createdAt = (isset($_GET['created_at']) ? strtolower($_GET['created_at']) : NULL);  ?>" class="form-control" id="created_at" name="created_at" onchange="form.submit()">
                    </div>
                </div>
            </form>
            <br />
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Descripción</th>
                        <th>Nivel de Dificultad</th>
                        <th>GIF</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                    <?php

                    $addWhere = '';

                    if ($filterLevel != '') {
                        $addWhere .= ' && level = ' . $filterLevel;
                    }
                    if ($filterState != '') {
                        $addWhere .= ' && state = ' . $filterState;
                    }
                    if ($createdAt != '') {
                        $addWhere .= ' && created_at = "' . $createdAt . ' 00:00:00" ';
                    }

                    $sql = mysqli_query($con, "SELECT * FROM tickets WHERE state != 2 " . $addWhere . " ORDER BY id_ticket ASC");

                    if (mysqli_num_rows($sql) == 0) {
                        echo '<tr><td colspan="8">No hay datos.</td></tr>';
                    } else {
                        $no = 1;
                        while ($row = mysqli_fetch_assoc($sql)) {
                            echo '
						<tr>
							<td>' . $row['id_ticket'] . '</td>
                            <td>' . $row['name'] . '</td>
                            <td>' . $row['description'] . '</td>
                            <td>';
                            if ($row['level'] == '0') {
                                echo '<span class="label label-info">Bajo</span>';
                            } else if ($row['level'] == '1') {
                                echo '<span class="label label-warning">Medio</span>';
                            } else if ($row['level'] == '2') {
                                echo '<span class="label label-danger">Alto</span>';
                            }
                            echo '
							</td>';

                            $i = $row['level'];
                            $url = "$base_url?q=" . urlencode($search_query[$i]) . "&api_key=$api_key";
                            $response = file_get_contents($url);
                            $gif_data = json_decode($response);

                            if ($gif_data && $gif_data->data && count($gif_data->data) > 0) {
                                $gif_url = $gif_data->data[0]->images->fixed_height->url;
                                $imgGiphy = "<img src='$gif_url' alt='GIF'>";
                            } else {
                                echo "No se encontraron GIFs para tu búsqueda.";
                            }

                            echo '<td>' . $imgGiphy . '</td>
							<td>';
                            if ($row['state'] == '0') {
                                echo '<span class="label label-success">Incompleto</span>';
                            } else if ($row['state'] == '1') {
                                echo '<span class="label label-info">Completo</span>';
                            }
                            echo '
							</td>
							<td>
								<a href="edit.php?nik=' . $row['id_ticket'] . '" title="Editar datos" class="btn btn-primary btn-sm"><span class="glyphicon glyphicon-edit" aria-hidden="true"></span></a>
								<a href="index.php?aksi=delete&nik=' . $row['id_ticket'] . '" title="Eliminar" onclick="return confirm(\'Esta seguro de borrar los datos ' . $row['name'] . '?\')" class="btn btn-danger btn-sm"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></a>
							</td>
						</tr>
						';
                            $no++;
                        }
                    }
                    ?>
                </table>
            </div>
        </div>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>

</body>

</html>