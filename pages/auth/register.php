<?php
$error = false;
$name = $email = $password = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = valid($_POST['name']);
    $email = valid($_POST['email']);
    $password = valid($_POST['password']);
    if($name&&$email&&$password) {
        //validate email
        $userData = $db->prepare('SELECT 1 FROM users WHERE email = ?');
        $userData->execute([$email]);
        if($userData->rowCount()==0) {
            $passwordHash = password_hash($password, PASSWORD_DEFAULT);
            $insertQuery = $db->prepare('INSERT INTO users(name, email, password) VALUES (?, ?, ?)');
            $insertQuery->execute([$name, $email, $passwordHash]);
            if($insertQuery) {
                header('Location: ./?a=created');
                die();
            } else {
                $error = "Der skete en fejl ved indsættelse i databasen";
            }
        } else {
            $error = "Der er allerede en bruger oprettet med denne email!";
        }
    } else {
        $error = "Du mangler at udfylde en af felterne!";
    }
}
?>

<h1>Register</h1>
<?php
if($error) errorBox($error);
?>
<form action="" method="post">
    Navn: <br>
    <input type="text" name="name" value="<?php echo $name; ?>"> <br> <br>
    Email: <br>
    <input type="text" name="email" value="<?php echo $email; ?>"> <br> <br>
    Password: <br>
    <input type="password" name="password" value="<?php echo $password; ?>"> <br> <br>
    <input type="submit" value="Opret mig">
    <br> <br>
    <a href="./">Login</a>
</form>