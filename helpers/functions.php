<?php
function safe($message, $multiline = false) {
    if($multiline) {
        echo nl2br(htmlspecialchars($message));
    } else {
        echo htmlspecialchars($message);
    }
}
function getToken($length = 16){
    $token = "";
    $codeAlphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
    $codeAlphabet.= "abcdefghijklmnopqrstuvwxyz";
    $codeAlphabet.= "0123456789";
    $max = strlen($codeAlphabet);

    for ($i=0; $i < $length; $i++) {
        $token .= $codeAlphabet[random_int(0, $max-1)];
    }

    return $token;
}

function valid($input) {
    return !empty($input)&&ltrim($input)!='' ? ltrim($input) : false;
}
function errorBox($error) { ?>
    <div class="error">
        <span><?php safe($error); ?></span>
    </div>
<?php
}
function infoBox($info) { ?>
    <div class="info">
        <span><?php safe($info); ?></span>
    </div>
<?php
}

?>