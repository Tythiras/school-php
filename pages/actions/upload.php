<?php
$error = $info = false;
if($logged) {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $count = isset($_FILES['file']) ? count($_FILES['file']['name']) : 0;
        if($count > 0) {
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
                for($i=0; $i<$count; $i++){
                    $name = $_FILES['file']['name'][$i];

                    //generate random stuff
                    $destName = $uid = false;
                    while(!$destName) {
                        $tmpDest = getToken();
                        $tmpUid = getToken();
                        $check = $db->prepare('SELECT 1 FROM files WHERE uid = ? OR path = ?');
                        $check->execute([$tmpUid, $tmpDest]);
                        if($check->rowCount() == 0) {
                            $destName = $tmpDest;
                            $uid = $tmpUid;
                        }
                    }
                    $path = $config['path'].'/'.$destName;

                    $success = move_uploaded_file($_FILES['file']['tmp_name'][$i], $path);
                    if($success) {
                        $insert = $db->prepare('INSERT INTO files(uid, path, name, ownerID, folderID) VALUES(?, ?, ?, ?, ?)');
                        $insert->execute([$uid, $destName, $name, $logged['id'], $folder]);
                        if(!$insert) {
                            $error = "Skete fejl ved indsættelse i databasen ved en af filerne";
                        }
                    } else {
                        $error = "Skete fejl ved upload af en af filerne";
                    }
                }
                if(!$error) {
                    $info = 'Filerne blev uploadet';
                }
            }

        } else {
            $error = "no files";
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