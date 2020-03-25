<?php
if($logged) {
$sql = false;
$values = [];
$error = false;
if(isset($_GET['id'])) {
    $folderData = $db->prepare('SELECT
        fo.id
        FROM folders fo
        WHERE fo.uid = ? AND (
        fo.ownerID = ? OR fo.id IN (
            SELECT itemID from permissions WHERE receiverID = ?
            )
        )
    ');
    $folderData->execute([$_GET['id'], $logged['id'], $logged['id']]);
    $folder = $folderData->fetch();
    $sql = 'SELECT name, uid, creation, ownerID FROM files WHERE folderID = ? OR id IN (
        SELECT fileID from shortcuts WHERE targetFolder = ?
    )';
    $values = [$folder['id'], $folder['id']];
} else {
    $sql = 'SELECT name, uid, creation, ownerID FROM files WHERE ownerID = ? OR id IN (
        SELECT fileID from shortcuts WHERE targetFolder = NULL AND ownerID = ?
    )';
    $values = [$logged['id'], $logged['id']];
}
if($sql) {
    $filesData = $db->prepare($sql);
    $filesData->execute($values);
    $files = $filesData->fetchAll();
} else {
    $error = 'Du har ikke adgang hertil.';
}


if($error) errorBox($error);
if(count($files) == 0) {
    echo 'Der blev ikke fundet nogen filer';
}
foreach($files as $file) { ?>
    <div class="file">
        <span class="name"><?php safe($file['name']); ?></span>
        <span class="date"><?php safe($file['creation']); ?><</span>
    </div>
<?php
}
}
?>