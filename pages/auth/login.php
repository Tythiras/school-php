<?php
$error = $info = false;
$email = $password = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = valid($_POST['email']);
    $password = valid($_POST['password']);
    if($email&&$password) {
        //validate email
        $userData = $db->prepare('SELECT * FROM users WHERE email = ?');
        $userData->execute([$email]);
        $failedMessage = "Brugernavn eller password er forkert!";
        if($userData->rowCount()>0) {
            $user = $userData->fetch();
            if(password_verify($password, $user['password'])) {
                $_SESSION['user'] = $user;
                header('Location: .'.$pagePath);
                die();
            } else {
                $error = $failedMessage;
            }
        } else {
            $error = $failedMessage;
        }
    } else {
        $error = "Du mangler at udfylde en af felterne!";
    }
}

if($_GET['a']=='created') {
    $info = 'Brugeren er blevet oprettet, du kan nu logge ind.';
}
?>

<h1>Login</h1>
<?php
if($error) errorBox($error);
if($info&&!$error) infoBox($info);
?>
<form action="" method="post">
    Email: <br>
    <input type="text" name="email" value="<?php echo $email; ?>"> <br> <br>
    Password: <br>
    <input type="password" name="password"> <br> <br>
    <input type="submit" value="Login">
    <br> <br>
    <a href="./register">Registrer</a>
</form>