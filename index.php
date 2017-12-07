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
    <title>Greenter Examples</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
        ul.list-group li {
            cursor: pointer;
        }
    </style>
</head>
<body>

<div class="jumbotron text-center">
    <h1>Greenter Samples</h1>
    <p>Ejemplos de envio de comprobantes electronicos a SUNAT</p>
</div>

<div class="container">
    <div class="row">
        <div class="col-md-4">
            <ul class="list-group">
                <?php foreach ($files as $file): ?>
                    <li onclick="loadUrl(this, '<?= $file['path']?>')" class="list-group-item"><span class="glyphicon glyphicon-menu-right"></span>&nbsp;<?=$file['name']?></li>
                <?php endforeach; ?>
            </ul>
        </div>
        <div class="col-md-8">
            <div id="result" class="well">De click en algun elemento de la lista.</div>
        </div>
    </div>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<script>
    var active = null;
    function iconLoad() {
        $('#result').html('<i class="fa fa-spinner fa-pulse fa-3x fa-fw"></i>');
    }

    function loadUrl(element, url) {
        if (active) {
            $(active).removeClass('active');
        }
        $(element).addClass('active');
        active = element;

        iconLoad();
        $.get(url, function(data) {
            $("#result").html(data);
        });
    }
</script>
</body>
</html>
