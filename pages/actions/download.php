<?php
$error = $info = false;

if($logged) {
    $fileData = $db->prepare('SELECT id, folderID, ownerID, path, name FROM files WHERE uid = ?');
    $fileData->execute([$_GET['id']]);
    if($fileData->rowCount() > 0) {
        $file = $fileData->fetch();
        $access = false;
        if($file['ownerID'] == $logged['id']) {
            $access = true;
        } else {
            $permissions = $db->prepare('SELECT 1 FROM permissions WHERE receiverID = ? AND ((itemID = ? AND type = "folder") OR (itemID = ? AND type = "file"))');
            $permissions->execute([$logged['id'], $file['folderID'], $file['id']]);
            if($permissions->rowCount() > 0) {
                $access = true;
            }
        }
        
        if($access) {
            $path = $config['path'].'/'.$file['path'];

            if (file_exists($path)) {
                header('Content-Description: File Transfer');
                header('Content-Type: application/octet-stream');
                header('Content-Disposition: attachment; filename="'.basename($file['name']).'"');
                header('Expires: 0');
                header('Cache-Control: must-revalidate');
                header('Pragma: public');
                header('Content-Length: ' . filesize($path));
                readfile($path);
                exit;
            } else {
                $error = "Filen eksistere ikke længere";
            }
        } else {
            $error = "Du har ikke adgang til denne fil";
        }
    } else {
        $error = "Filen blev ikke fundet";
    }
}

if($error) {
    echo '{"success": false, "message": "'.$error.'"}';
}

?>