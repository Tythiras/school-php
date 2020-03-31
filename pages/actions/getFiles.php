<?php
if($logged) {
$sql = false;
$values = [];
$error = false;
if(isset($_GET['id'])) {
    $folderData = $db->prepare('CALL checkFolder(?, ?)');
    $folderData->execute([$_GET['id'], $logged['id']]);
    $folder = $folderData->fetch();
    $sql = 'CALL folderFiles(?)';
    $values = [$folder['id']];
} else {
    $sql = 'CALL rootFiles(?)';
    $values = [$logged['id']];
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
        <span class="date"><?php safe($file['creation']); ?></span>
    </div>
<?php
}
}
?>