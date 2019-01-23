<?php
/**@var $cdr \Greenter\Model\Response\CdrResponse*/

?>
<div class="bs-callout <?php echo $cdr->isAccepted() ? 'bs-callout-success' : 'bs-callout-danger' ?>">
    <h4>Respuesta</h4><br>
    <table class="table">
        <tbody>
            <tr>
                <td>ID</td>
                <td><?=$cdr->getId()?></td>
            </tr>
            <tr>
                <td>Código:</td>
                <td><?=$cdr->getCode()?></td>
            </tr>
            <tr>
                <td>Descripción:</td>
                <td><?=$cdr->getDescription()?></td>
            </tr>
        </tbody>
    </table>
    <br>
    <?php if (count($cdr->getNotes()) > 0) :?>
        <b>Notas</b><br>
        <ul class="list-group">
            <?php foreach ($cdr->getNotes() as $note): ?>
                <li class="list-group-item"><?=$note?></li>
            <?php endforeach; ?>
        </ul><br>
    <?php endif;?>
    <b>Adjuntos</b><br>
    <ul class="list-group">
        <li class="list-group-item"><a href="files/<?=$filename?>.xml"><i class="fa fa-file-code"></i>&nbsp;<?=$filename?>.xml</a></li>
        <li class="list-group-item"><a href="files/R-<?=$filename?>.zip"><i class="fa fa-file-archive"></i>&nbsp;R-<?=$filename?>.zip</a></li>
    </ul>
</div>
