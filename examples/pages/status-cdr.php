<?php
/**
 * Created by PhpStorm.
 * User: Giansalex
 * Date: 09/09/2018
 * Time: 12:48
 */

use Greenter\Ws\Services\ConsultCdrService;
use Greenter\Ws\Services\SoapClient;
use Greenter\Ws\Services\SunatEndpoints;

require __DIR__ . '/../../vendor/autoload.php';

$errorMsg = null;
$filename = null;

function validateFields(array $items)
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

function getCdrStatusService($user, $password)
{
    $ws = new SoapClient(SunatEndpoints::FE_CONSULTA_CDR.'?wsdl');
    $ws->setCredentials($user, $password);

    $service = new ConsultCdrService();
    $service->setClient($ws);

    return $service;
}

function savedFile($filename, $content)
{
    $pathZip = __DIR__.'/../../files/'.$filename;
    file_put_contents($pathZip, $content);
}

function process($fields)
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
                                        <br>
                                        <strong>Observaciones: </strong> <?=implode('<br>', $result->getCdrResponse()->getNotes())?>
                                        <br>
                                        <?php if (!is_null($filename)): ?>
                                            <strong>CDR: </strong><br>
                                            <a href="/examples/pages/file-download.php?name=<?=$filename?>"><i class="fa fa-file-archive"></i>&nbsp;<?=$filename?></a>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <div class="alert alert-danger">
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
                            <?php if (isset($errorMsg)):?>
                                <div class="alert alert-danger">
                                    <?=$errorMsg?>
                                </div>
                            <?php endif; ?>
                            <form method="post">
                                <div class="row">
                                    <div class="col-md-6">
                                        <strong>Credenciales</strong>
                                        <div class="form-group">
                                            <label for="rucSol">Ruc:</label>
                                            <input type="text" class="form-control" name="rucSol" id="rucSol" maxlength="11"
                                                <?php if (isset($_POST['rucSol'])) {?> value="<?=$_POST['rucSol']; ?>" <?php }?>>
                                        </div>
                                        <div class="form-group">
                                            <label for="userSol">Usuario:</label>
                                            <input type="text" class="form-control" name="userSol" id="userSol"
                                                <?php if (isset($_POST['userSol'])) {?> value="<?=$_POST['userSol']; ?>" <?php }?>>
                                        </div>
                                        <div class="form-group">
                                            <label for="passSol">Contraseña:</label>
                                            <input type="password" class="form-control" name="passSol" id="passSol"
                                                <?php if (isset($_POST['passSol'])) {?> value="<?=$_POST['passSol']; ?>" <?php }?>>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <strong>Datos del Comprobante</strong>
                                        <div class="form-group">
                                            <label for="ruc">Ruc Emisor:</label>
                                            <input type="text" class="form-control" name="ruc" id="ruc"
                                                   maxlength="11"
                                                <?php if (isset($_POST['ruc'])) {?>
                                                    value="<?=$_POST['ruc']?>"
                                                <?php } else {?>
                                                    value="20000000001"
                                                <?php }?>>
                                        </div>
                                        <div class="form-group">
                                            <label for="tipo">Tipo:</label>
                                            <input type="text" class="form-control" name="tipo" id="tipo" value="01" maxlength="2">
                                        </div>
                                        <div class="form-group">
                                            <label for="serie">Serie:</label>
                                            <input type="text" class="form-control" name="serie" id="serie" value="F001" maxlength="4">
                                        </div>
                                        <div class="form-group">
                                            <label for="numero">Correlativo:</label>
                                            <input type="number" class="form-control" name="numero" id="numero" value="1" min="1">
                                        </div>
                                    </div>
                                </div>
                                <button class="btn btn-primary">Consultar Estado</button>
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

