<?php
set_time_limit(0);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <?php include 'views/head.php'; ?>
    <script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.0.1/dist/alpine.js" defer></script>
    <link rel="stylesheet" href="assets/style.css?v=2">
</head>
<body>
<?php include 'views/top.php'; ?>
<div class="container"
     x-data="app()"
     x-init="docs()"
    >
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card bg-primary">
                <div class="card-header text-white">Comprobantes</div>
                <div class="card-block">
                    <ul class="list-group">
                        <template x-for="item in examples.invoices" :key="item.file">
                            <li @click="loadUrl($event.currentTarget, item.file)" class="list-group-item">
                                <i class="fa fa-angle-right"></i>&nbsp;<span x-html="item.title"></span>
                                <span class="badge bg-secondary" x-text="item.tag"></span>
                                <br>
                                <sub x-html="item.description" x-show="item.description"></sub>
                            </li>
                        </template>
                        <li class="list-group-item">
                            <a href="examples/pages/status-cdr.php">Consulta CDR <i class="fa fa-external-link-alt"></i></a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-primary text-white">Resultado</div>
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
                <div class="card-header text-white">PDF</div>
                <div class="card-block">
                    <ul class="list-group">
                        <template x-for="item in examples.reports" :key="item.path">
                            <li class="list-group-item">
                                <a :href="item.path" target="_blank">
                                    <span class="fa fa-external-link-alt"></span> <span x-text="item.name"></span>
                                </a>
                            </li>
                        </template>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include 'views/footer.php'; ?>
<script src="assets/demo.js?v2"></script>
</body>
</html>
