<?php
if($logged) {
$sql = false;
$values = [];
$error = false;
$parent = false;
if(isset($_GET['id'])) {
    $folderData = $db->prepare('CALL checkFolder(?, ?)');
    $folderData->execute([$_GET['id'], $logged['id']]);
    if($folderData->rowCount() > 0) {
        $folder = $folderData->fetch();
        $parent = true;
        $folderData->closeCursor();
        $sql = 'CALL folderFiles(?)';
        $values = [$folder['id']];
    } else {
        echo 'No access to folder';
        die();
    }
} else {
    $sql = 'CALL rootFiles(?)';
    $values = [$logged['id']];
}
if($sql) {
    $itemsData = $db->prepare($sql);
    $itemsData->execute($values);
    $items = $itemsData->fetchAll();
    $itemsData->closeCursor();
} else {
    $error = 'Du har ikke adgang hertil.';
}


if($error) errorBox($error);
if($parent) {
    ?>
    <span class="customLink" onclick="goToFolder('<?php safe($folder['parentUID']); ?>', event)">Tilbage</span>
    <br>
    <?php
}
if(count($items) == 0) {
    echo 'Der blev ikke fundet nogen filer';
}
?>
<div class="columns is-multiline is-mobile is-3">
<?php
foreach($items as $item) { ?>
    <div class="column is-one-third" draggable="true" ondragstart="drag(event)" data-id="<?php safe($item['uid']); ?>" data-type="<?php safe($item['type']); ?>" <?php if($item['type']=='folder') { ?>  ondragenter="dragOver(event)" ondragleave="dragLeave(event)" ondrop="drop(event, this)"<?php } ?>>
    <?php
    if($item['type'] == 'file') { ?>
        <div class="el file" onclick="downloadFile('<?php safe($item['uid']); ?>')">
            <span class="name"><?php safe($item['name']); ?></span>
            <span class="type"><?php safe($item['type']); ?></span>
            <span class="date"><?php safe(beautifyTimestamp($item['creation'], true)); ?></span>
        </div>
    <?php
    } else { ?>
        <div class="el folder" onclick="goToFolder('<?php safe($item['uid']); ?>', event)">
            <span class="name"><?php safe($item['name']); ?></span>
            <span class="type"><?php safe($item['type']); ?></span>
            <span class="date"><?php safe(beautifyTimestamp($item['creation'], true)); ?></span>
        </div>
    <?php
    } 
    ?>
    </div>

<?php
}
?>
</div>
<?php
}
?>