<?php

declare(strict_types=1);

use Greenter\Model\Response\StatusCdrResult;
use Greenter\Ws\Services\ConsultCdrService;
use Greenter\Ws\Services\SoapClient;
use Greenter\Ws\Services\SunatEndpoints;

require __DIR__ . '/../../vendor/autoload.php';

$errorMsg = null;
$filename = null;

/**
 * @param array<string, string> $items
 * @return bool
 */
function validateFields(array $items): bool
{
    global $errorMsg;
    $validateFiels = ['rucSol', 'userSol', 'passSol', 'ruc', 'tipo', 'serie', 'numero'];
    foreach ($items as $key => $value) {
        if (in_array($key, $validateFiels) && empty($value)) {
            $errorMsg = 'El campo '.$key.', es requerido';
            return false;
        }
    }

    return true;
}

/**
 * @param string $user
 * @param string $password
 * @return ConsultCdrService
 */
function getCdrStatusService(?string $user, ?string $password): ConsultCdrService
{
    $ws = new SoapClient(SunatEndpoints::FE_CONSULTA_CDR.'?wsdl');
    $ws->setCredentials($user, $password);

    $service = new ConsultCdrService();
    $service->setClient($ws);

    return $service;
}

/**
 * @param string $filename
 * @param string $content
 */
function savedFile(?string $filename, ?string $content): void
{
    $fileDir = __DIR__.'/../../files';

    if (!file_exists($fileDir)) {
        mkdir($fileDir, 0777, true);
    }
    $pathZip = $fileDir.DIRECTORY_SEPARATOR.$filename;
    file_put_contents($pathZip, $content);
}

/**
 * @param array<string, string> $fields
 * @return StatusCdrResult|null
 */
function process(array $fields): ?StatusCdrResult
{
    global $filename;

    if (!isset($fields['rucSol'])) {
        return null;
    }

    if (!validateFields($fields)) {
        return null;
    }

    $service = getCdrStatusService($fields['rucSol'].$fields['userSol'], $fields['passSol']);

    $arguments = [
        $fields['ruc'],
        $fields['tipo'],
        $fields['serie'],
        intval($fields['numero'])
    ];
    if (isset($fields['cdr'])) {
        $result = $service->getStatusCdr(...$arguments);
        if ($result->getCdrZip()) {
            $filename = 'R-'.implode('-', $arguments).'.zip';
            savedFile($filename, $result->getCdrZip());
        }

        return $result;
    }

    return $service->getStatus(...$arguments);
}

$result = process($_POST);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <?php include '../../views/head.php'; ?>
    <style>
        .mb-100 {
            margin-bottom: 100px;
        }
        .mb-20 {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
<?php include '../../views/top.php'; ?>
<div class="container mb-100">
    <div class="row">
        <?php if (isset($result)): ?>
            <div class="col-md-12">
                <div class="card mb-20">
                    <div class="card-header bg-success text-white">Resultado</div>
                    <div class="card-block">
                        <div class="card bg-light text-dark">
                            <div class="card-body">
                                <?php if ($result->isSuccess()): ?>
                                    <strong>Codigo: </strong> <?=$result->getCode()?> <br>
                                    <strong>Mensaje: </strong> <?=$result->getMessage()?> <br>
                                    <?php if (!is_null($result->getCdrResponse())):?>
                                        <strong>Estado Comprobante: </strong> <?=$result->getCdrResponse()->getDescription()?>
                                        <?php if (!empty($result->getCdrResponse()->getNotes())): ?>
                                            <br>
                                            <strong>Observaciones: </strong>
                                            <ul>
                                            <?php foreach ($result->getCdrResponse()->getNotes() as $note): ?>
                                                <li><?=$note?></li>
                                            <?php endforeach; ?>
                                            </ul>
                                        <?php endif; ?>
                                        <?php if (!is_null($filename)): ?>
                                            <br>
                                            <strong>CDR: </strong><br>
                                            <a href="/files/<?=$filename?>"><i class="fa fa-file-archive"></i>&nbsp;<?=$filename?></a>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <div class="alert alert-danger">
                                        <?=$result->getCode()?><br>
                                        <?=$result->getMessage()?><br>
                                        <?=$result->getError()->getMessage()?>
                                    </div>
                                <?php endif;?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
        <div class="col-md-12">
            <div class="card bg-primary">
                <div class="card-header text-white">Consulta de CDR Status</div>
                <div class="card-block">
                    <div class="card bg-light text-dark">
                        <div class="card-body">
                            <?php if (!is_null($errorMsg)):?>
                                <div class="alert alert-danger">
                                    <?=$errorMsg?>
                                </div>
                            <?php endif; ?>
                            <form method="post">
                                <div class="row">
                                    <div class="col-md-6">
                                        <strong>Credenciales</strong>
                                        <div class="mb-3">
                                            <label for="rucSol" class="form-label">Ruc:</label>
                                            <input type="text" class="form-control" name="rucSol" id="rucSol" maxlength="11" value="<?=filter_input(INPUT_POST, 'rucSol') ?? ""?>">
                                        </div>
                                        <div class="mb-3">
                                            <label for="userSol" class="form-label">Usuario:</label>
                                            <input type="text" class="form-control" name="userSol" id="userSol" value="<?=filter_input(INPUT_POST, 'userSol') ?? ""?>">
                                        </div>
                                        <div class="mb-3">
                                            <label for="passSol" class="form-label">Contraseña:</label>
                                            <input type="password" class="form-control" name="passSol" id="passSol" value="">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <strong>Datos del Comprobante</strong>
                                        <div class="mb-3">
                                            <label for="ruc" class="form-label">Ruc Emisor:</label>
                                            <input type="text" class="form-control" name="ruc" id="ruc"
                                                   maxlength="11"
                                                   value="<?= filter_input(INPUT_POST, 'ruc') ?? '20000000001'?>">
                                        </div>
                                        <div class="mb-3">
                                            <label for="tipo" class="form-label">Tipo:</label>
                                            <input  type="text"
                                                    class="form-control"
                                                    name="tipo"
                                                    id="tipo"
                                                    maxlength="2"
                                                    value="<?= filter_input(INPUT_POST, 'tipo') ?? '01'?>">
                                        </div>
                                        <div class="mb-3">
                                            <label for="serie" class="form-label">Serie:</label>
                                            <input  type="text"
                                                    class="form-control"
                                                    name="serie"
                                                    id="serie"
                                                    maxlength="4"
                                                    value="<?= filter_input(INPUT_POST, 'serie') ?? 'F001'?>">
                                        </div>
                                        <div class="mb-3">
                                            <label for="numero" class="form-label">Correlativo:</label>
                                            <input  type="number"
                                                    class="form-control"
                                                    name="numero"
                                                    id="numero"
                                                    min="1"
                                                    value="<?= filter_input(INPUT_POST, 'numero') ?? '1'?>">
                                        </div>
                                    </div>
                                </div>
<!--                                <button class="btn btn-primary">Consultar Estado</button>-->
                                <button class="btn btn-primary" name="cdr">Consultar CDR</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include '../../views/footer.php'; ?>
</body>
</html>