<?php
set_time_limit(0);

$files = glob(__DIR__.'/examples/*.php');
$files = array_map(function ($file) {
    $name = basename($file);
    $path = 'examples/' . $name;

    return ['name' => $name, 'path' => $path];
}, $files);

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
                <?php foreach ($pdfPaths as $file): ?>
                    <li class="list-group-item"><a href="<?= $file['path']?>" target="_blank"><span class="glyphicon glyphicon-file"></span>&nbsp;<?=$file['name']?></a></li>
                <?php endforeach; ?>
                <?php foreach ($files as $file): ?>
                    <li onclick="loadUrl(this, '<?= $file['path']?>')" class="list-group-item"><span class="glyphicon glyphicon-menu-right"></span>&nbsp;<?=$file['name']?></li>
                <?php endforeach; ?>
            </ul>
        </div>
        <div class="col-md-8">
            <div id="result" class="well">De click en algun elemento de la lista.</div>
            <div>Time: <span id="time"></span></div>
        </div>
    </div>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
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
        var newTime = '';

        h = Math.floor( time / (60 * 60 * 1000) );
        time = time % (60 * 60 * 1000);
        m = Math.floor( time / (60 * 1000) );
        time = time % (60 * 1000);
        s = Math.floor( time / 1000 );
        ms = time % 1000;

        newTime = pad(h, 2) + ':' + pad(m, 2) + ':' + pad(s, 2) + ':' + pad(ms, 3);
        return newTime;
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
            stop();
        });
    }
</script>
</body>
</html>
