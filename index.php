<?php
set_time_limit(0);

$files = glob(__DIR__.'/examples/*.php');
$files = array_map(function ($file) {
    $name = basename($file);
    $path = 'examples/' . $name;

    return ['name' => $name, 'path' => $path];
}, $files);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <title>Bootstrap Example</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</head>
<body>

<div class="jumbotron text-center">
    <h1>Greenter Samples</h1>
    <p>Ejemplos de envio de comprobantes electronicos a SUNAT</p>
</div>

<div class="container">
    <div class="row">
        <div class="col-md-4">
            <div class="list-group">
                <?php foreach ($files as $file): ?>
                    <a target="_blank" href="<?=$file['path']?>" class="list-group-item"><span class="glyphicon glyphicon-menu-right"></span>&nbsp;<?=$file['name']?></a>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>

</body>
</html>
