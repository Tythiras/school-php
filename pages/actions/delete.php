<?php
ini_set('display_errors', 1);

function deleteFile($file) {
    global $db, $config;
    $error = false;
    $del = $db->prepare('DELETE FROM files WHERE id = ?');
    $del->execute([$file['id']]);
    $path = $config['path'].'/'.$file['path'];
    if(!unlink($path)) {
        $error = 'Der var problemer med at slette fil';
    }

}
function deleteFolder($id) {
    global $db;
    $error = false;
    $filesData = $db->prepare('CALL folderFilesBackend(?)');
    $filesData->execute([$id]);
    $files = $filesData->fetchAll();
    $filesData->closeCursor();
    foreach($files as $file) {
        if($file['type']=='file') {
            $errored = deleteFile($file);
        } else {
            $errored = deleteFolder($file['id']);
        }
        if($errored) {
            $error = true;
        }
    }
    if(!$error) {
        $del = $db->prepare('DELETE FROM folders WHERE id = ?');
        $del->execute([$id]);
        if(!$del) {
            $error = 'Der skete en database fejl ved slettelse af folderen';
        }
    }
    return $error;
}
$error = false;
if($logged) {
    $files = false;
    $access = false;
    $extraQuery = false;
    $extraValues = false;
    //check if owner is file owner and prepare files to be deleted
    if($_POST['targetType']=='file') {
        $fileData = $db->prepare('SELECT id, path FROM files WHERE uid = ? AND ownerID = ?');
        $fileData->execute([$_POST['targetID'], $logged['id']]);
        if($fileData->rowCount() > 0) {
            $error = deleteFile($fileData->fetch());
            $access = true;
        }
    } else if($_POST['targetType']=='folder') {
        $folderData = $db->prepare('SELECT id FROM folders WHERE uid = ? AND ownerID = ?');
        $folderData->execute([$_POST['targetID'], $logged['id']]);
        if($folderData->rowCount() > 0) {
            $folder = $folderData->fetch();
            $error = deleteFolder($folder['id']);
            $access = true;
        }
    }



    if(!$access) {
        $error = 'Fil / Folder der flyttes ikke fundet, eller du har ikke adgang til den';
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