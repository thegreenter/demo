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
</head>
<body>
<?php include 'views/top.php'; ?>
<div class="container">
    <div class="row">
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
                            <a href="examples/pages/status-cdr.php">Consulta CDR <i class="fa fa-external-link"></i></a>
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
                            <div id="result">De click en algun elemento de la lista.</div>
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
                            <li class="list-group-item"><a href="<?= $file['path']?>" target="_blank"><span class="fa fa-external-link"></span>&nbsp;<?=$file['name']?></a></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include 'views/footer.php'; ?>
<script>
    var	clsStopwatch = function() {
        // Private vars
        var	startAt	= 0;	// Time of last start / resume. (0 if not running)
        var	lapTime	= 0;	// Time on the clock when last stopped in milliseconds

        var	now	= function() {
            return (new Date()).getTime();
        };

        // Public methods
        // Start or resume
        this.start = function() {
            startAt	= startAt ? startAt : now();
        };

        // Stop or pause
        this.stop = function() {
            // If running, update elapsed time otherwise keep it
            lapTime	= startAt ? lapTime + now() - startAt : lapTime;
            startAt	= 0; // Paused
        };

        // Reset
        this.reset = function() {
            lapTime = startAt = 0;
        };

        // Duration
        this.time = function() {
            return lapTime + (startAt ? now() - startAt : 0);
        };
    };

    var x = new clsStopwatch();
    var $time;
    var clocktimer;

    function pad(num, size) {
        var s = "0000" + num;
        return s.substr(s.length - size);
    }

    function formatTime(time) {
        var h = m = s = ms = 0;

        h = Math.floor( time / (60 * 60 * 1000) );
        time = time % (60 * 60 * 1000);
        m = Math.floor( time / (60 * 1000) );
        time = time % (60 * 1000);
        s = Math.floor( time / 1000 );
        ms = time % 1000;

        return pad(h, 2) + ':' + pad(m, 2) + ':' + pad(s, 2) + ':' + pad(ms, 3);
    }

    function show() {
        $time = document.getElementById('time');
        update();
    }

    function update() {
        $time.innerHTML = formatTime(x.time());
    }

    function start() {
        clocktimer = setInterval("update()", 1);
        x.start();
    }

    function stop() {
        x.stop();
        clearInterval(clocktimer);
    }

    function reset() {
        stop();
        x.reset();
        update();
    }

    var active = null;
    show();
    function iconLoad() {
        $('#result').html('<i class="fa fa-spinner fa-pulse fa-3x fa-fw"></i>');
    }

    function loadUrl(element, url) {
        if (active) {
            $(active).removeClass('active');
        }
        $(element).addClass('active');
        active = element;

        reset();
        start();
        iconLoad();
        $.get(url, function(data) {
            $("#result").html(data);
        }).fail(function () {
            $("#result").html('<span class="text-danger">Ocurrío un error invocando el script</span>');
        }).always(function () {
            stop();
        });
    }
</script>
<script>!function(e,a,t,n,o,c,i){e.GoogleAnalyticsObject=o,e.ga=e.ga||function(){(e.ga.q=e.ga.q||[]).push(arguments)},e.ga.l=1*new Date,c=a.createElement(t),i=a.getElementsByTagName(t)[0],c.async=1,c.src="https://www.google-analytics.com/analytics.js",i.parentNode.insertBefore(c,i)}(window,document,"script",0,"ga"),ga("create","UA-90097417-4","auto"),ga("set","anonymizeIp",!0),ga("send","pageview");var links=document.getElementsByTagName("a");Array.prototype.map.call(links,function(e){e.host!=document.location.host&&e.addEventListener("click",function(){var a=e.getAttribute("data-md-action")||"follow";ga("send","event","outbound",a,e.href)})});var query=document.forms.search.query;query.addEventListener("blur",function(){if(this.value){var e=document.location.pathname;ga("send","pageview",e+"?q="+this.value)}})</script>
</body>
</html>
