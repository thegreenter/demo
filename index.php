<?php
set_time_limit(0);

$docsList = require __DIR__.'/docs.php';

$pdfPaths = glob(__DIR__.'/examples/report/*.php');
$pdfPaths = array_map(function ($file) {
    $name = basename($file);
    $path = 'examples/report/' . $name;

    return ['name' => $name, 'path' => $path];
}, $pdfPaths);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <?php include 'views/head.php'; ?>
    <style>
        ul.list-group li {
            cursor: pointer;
        }
    </style>
    <link rel="stylesheet" href="assets/style.css?v=1">
</head>
<body>
<?php include 'views/top.php'; ?>
<div class="container">
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card bg-primary">
                <div class="card-header text-white">Comprobantes <span class="badge badge-secondary"><?php echo count($docsList); ?></span></div>
                <div class="card-block">
                    <ul class="list-group">
                        <?php foreach ($docsList as $file): ?>
                            <li onclick="loadUrl(this, 'examples/<?= $file['file']?>')" class="list-group-item">
                                <i class="fa fa-angle-right"></i>&nbsp;<?=$file['title']?>
                                <?php foreach ($file['tags'] as $tag): ?>
                                    <span class="badge badge-secondary"><?=$tag?></span>
                                <?php endforeach; ?>
                            </li>
                        <?php endforeach; ?>
                        <li class="list-group-item">
                            <a href="examples/pages/status-cdr.php">Consulta CDR <i class="fa fa-external-link-alt"></i></a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-success text-white">Resultado</div>
                <div class="card-block">
                    <div class="card bg-light text-dark">
                        <div class="card-body">
                            <div id="result">De click en algún elemento de la lista.</div>
                            <div>Time: <span id="time"></span></div>
                        </div>
                    </div>
                </div>
            </div>
            <br>
            <div class="card bg-primary">
                <div class="card-header text-white">PDF <span class="badge badge-secondary"><?php echo count($pdfPaths); ?></span></div>
                <div class="card-block">
                    <ul class="list-group">
                        <?php foreach ($pdfPaths as $file): ?>
                            <li class="list-group-item"><a href="<?= $file['path']?>" target="_blank"><span class="fa fa-external-link-alt"></span>&nbsp;<?=$file['name']?></a></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include 'views/footer.php'; ?>
<script src="assets/demo.js"></script>
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-90097417-4"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());
  gtag('config', 'UA-90097417-4');
</script>
</body>
</html>
