<?php

$items = [
    [
        'file' => 'examples/factura.php',
        'title' => 'Factura',
        'tag' => '2.1',
    ],
    [
        'file' => 'examples/factura-contingencia.php',
        'title' => 'Factura',
        'tag' => 'CONTINGENCIA',
    ],
    [
        'file' => 'examples/boleta.php',
        'title' => 'Boleta de Venta',
        'tag' => '2.1',
    ],
    [
        'file' => 'examples/factura-inafecta.php',
        'title' => 'Factura Inafecta',
        'tag' => '2.1',
    ],
    [
        'file' => 'examples/factura-gratuita.php',
        'title' => 'Factura Gratuita',
        'tag' => '2.1',
    ],
    [
        'file' => 'examples/factura-percepcion.php',
        'title' => 'Factura con Percepción',
        'tag' => '2.1',
    ],
    [
        'file' => 'examples/factura-exportacion.php',
        'title' => 'Factura Exportación',
        'tag' => '2.1',
    ],
    [
        'file' => 'examples/factura-anticipo.php',
        'title' => 'Factura con Anticipos',
        'tag' => '2.1',
    ],
    [
        'file' => 'examples/factura-icbper.php',
        'title' => 'Factura ICBPER',
        'tag' => '2.1',
    ],
    [
        'file' => 'examples/factura-descuento-global.php',
        'title' => 'Factura con Descuento Global',
        'tag' => '2.1',
    ],
    [
        'file' => 'examples/nota-credito.php',
        'title' => 'Nota de Crédito',
        'tag' => '2.1',
    ],
    [
        'file' => 'examples/nota-debito.php',
        'title' => 'Nota de Débito',
        'tag' => '2.1',
    ],
    [
        'file' => 'examples/resumen.php',
        'title' => 'Resumen Diario',
        'tag' => '2.0',
    ],
    [
        'file' => 'examples/resumen-contingencia.php',
        'title' => 'Resumen Diario',
        'tag' => 'CONTINGENCIA',
    ],
    [
        'file' => 'examples/resumen-usd.php',
        'title' => 'Resumen Diario (en Dólares)',
        'tag' => '2.0',
    ],
    [
        'file' => 'examples/comunicacion-baja.php',
        'title' => 'Comunicación de Baja',
        'tag' => '2.0',
    ],
    [
        'file' => 'examples/guia-remision.php',
        'title' => 'Guía de Remisión',
        'tag' => '2.1',
    ],
    [
        'file' => 'examples/percepcion.php',
        'title' => 'C. de Percepción',
        'tag' => '2.0',
    ],
    [
        'file' => 'examples/retencion.php',
        'title' => 'C. de Retención',
        'tag' => '2.0',
    ],
    [
        'file' => 'examples/reversion.php',
        'title' => 'Resumen de Reversiones',
        'tag' => '2.0',
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