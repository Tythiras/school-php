<?php
$error = $info = false;
if($logged) {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $folder = null;
        if($_POST['folder']&&$_POST['folder']!="null") {
            $check = $db->prepare('CALL checkFolder(?, ?)');
            $check->execute([$_POST['folder'], $logged['id']]);
            if($check->rowCount()>0) {
                $folder = $check->fetch()['id'];
            } else {
                $error = 'Folderen blev ikke fundet';
            }
        }
        if(!$error) {
            //generate random stuff
            $uid = false;
            while(!$uid) {
                $tmpUid = getToken();
                $check = $db->prepare('SELECT 1 FROM folders WHERE uid = ?');
                $check->execute([$tmpUid]);
                if($check->rowCount() == 0) {
                    $uid = $tmpUid;
                }
            }

            $insert = $db->prepare('INSERT INTO folders(uid, name, ownerID, folderID) VALUES(?, ?, ?, ?)');
            $insert->execute([$uid, $_POST['name'], $logged['id'], $folder]);
            if($insert) {
                $info = 'Folder oprettet';
            } else {
                $error = 'Der skete en fejl ved indsættelse i databasen';
            }
        }
    } else {
        $error = 404;
    }
}
if($error) {
    echo '{"success": false, "message": "'.$error.'"}';
} else {
    echo '{"success": true, "message": "'.$info.'"}';
}
?>