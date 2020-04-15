<?php
$error = false;
if($logged) {
    //check if owner is file owner
    $sql = false;
    if($_POST['targetType']=='file') {
        $fileData = $db->prepare('SELECT id FROM files WHERE uid = ? AND ownerID = ?');
        $fileData->execute([$_POST['targetID'], $logged['id']]);
        if($fileData->rowCount() > 0) {
            $sql = 'UPDATE files SET folderID = ? WHERE uid = ?';
        }
    } else if($_POST['targetType']=='folder') {
        $folderData = $db->prepare('SELECT id FROM folders WHERE uid = ? AND ownerID = ?');
        $folderData->execute([$_POST['targetID'], $logged['id']]);
        if($folderData->rowCount() > 0) {
            $sql = 'UPDATE folders SET folderID = ? WHERE uid = ?';
        }
    }



    if($sql) {
        $folder = null;
        if($_POST['targetFolder']&&$_POST['targetFolder']!="null") {
            $check = $db->prepare('CALL checkFolder(?, ?)');
            $check->execute([$_POST['targetFolder'], $logged['id']]);
            if($check->rowCount()>0) {
                $folder = $check->fetch()['id'];
            } else {
                $error = 'Folderen blev ikke fundet';
            }
            $check->closeCursor();
        }
        if(!$error) {
            $update = $db->prepare($sql);
            $update->execute([$folder, $_POST['targetID']]);
            if(!$update) {
                $error = 'Der skete en database fejl';
            }
        }
    } else {
        $error = 'Fil / Folder der flyttes ikke fundet';
    }
} else {
    $error = 'Du er ikke logget ind';
}
if($error) {
    echo '{"success": false, "message": "'.$error.'"}';
} else {
    echo '{"success": true}';
}
?>