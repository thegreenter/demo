<?php

$items = [
    [
        'file' => 'examples/factura.php',
        'title' => 'Factura',
        'tags' => ['2.1'],
    ],
    [
        'file' => 'examples/factura-contingencia.php',
        'title' => 'Factura',
        'tags' => ['CONTINGENCIA'],
    ],
    [
        'file' => 'examples/boleta.php',
        'title' => 'Boleta de Venta',
        'tags' => ['2.1'],
    ],
    [
        'file' => 'examples/factura-inafecta.php',
        'title' => 'Factura Inafecta',
        'tags' => ['2.1'],
    ],
    [
        'file' => 'examples/factura-gratuita.php',
        'title' => 'Factura Gratuita',
        'tags' => ['2.1'],
    ],
    [
        'file' => 'examples/factura-percepcion.php',
        'title' => 'Factura con Percepción',
        'tags' => ['2.1'],
    ],
    [
        'file' => 'examples/factura-exportacion.php',
        'title' => 'Factura Exportación',
        'tags' => ['2.1'],
    ],
    [
        'file' => 'examples/factura-anticipo.php',
        'title' => 'Factura con Anticipos',
        'tags' => ['2.1'],
    ],
    [
        'file' => 'examples/factura-icbper.php',
        'title' => 'Factura ICBPER',
        'tags' => ['2.1'],
    ],
    [
        'file' => 'examples/factura-descuento-global.php',
        'title' => 'Factura con Descuento Global',
        'tags' => ['2.1'],
    ],
    [
        'file' => 'examples/nota-credito.php',
        'title' => 'Nota de Crédito',
        'tags' => ['2.1'],
    ],
    [
        'file' => 'examples/nota-debito.php',
        'title' => 'Nota de Débito',
        'tags' => ['2.1'],
    ],
    [
        'file' => 'examples/resumen.php',
        'title' => 'Resumen Diario',
        'tags' => ['2.0'],
    ],
    [
        'file' => 'examples/resumen-contingencia.php',
        'title' => 'Resumen Diario',
        'tags' => ['CONTINGENCIA'],
    ],
    [
        'file' => 'examples/resumen-usd.php',
        'title' => 'Resumen Diario (en Dólares)',
        'tags' => ['2.0'],
    ],
    [
        'file' => 'examples/comunicacion-baja.php',
        'title' => 'Comunicación de Baja',
        'tags' => ['2.0'],
    ],
    [
        'file' => 'examples/guia-remision.php',
        'title' => 'Guía de Remisión',
        'tags' => ['2.1'],
    ],
    [
        'file' => 'examples/percepcion.php',
        'title' => 'C. de Percepción',
        'tags' => ['2.0'],
    ],
    [
        'file' => 'examples/retencion.php',
        'title' => 'C. de Retención',
        'tags' => ['2.0'],
    ],
    [
        'file' => 'examples/reversion.php',
        'title' => 'Resumen de Reversiones',
        'tags' => ['2.0'],
    ],
];

$pdfPaths = glob(__DIR__.'/examples/report/*.php');
$pdfPaths = array_map(function ($file) {
    $name = basename($file);
    $path = 'examples/report/' . $name;

    return ['name' => $name, 'path' => $path];
}, $pdfPaths);

header('Content-Type: application/json');

echo json_encode([
    'invoices' => $items,
    'reports'  => $pdfPaths,
]);