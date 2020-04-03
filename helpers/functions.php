<?php
function safe($message, $multiline = false) {
    if($multiline) {
        echo nl2br(htmlspecialchars($message));
    } else {
        echo htmlspecialchars($message);
    }
}

function beautifyDate($date = '2018-05-25') {
    $array = explode("-", $date);
    $months = ["Januar", "Februar", "Marts", "April", "Maj", "Juni", "Juli", "August", "September", "Oktober", "November", "December"];
    $day = $array[2];
    $month = $months[$array[1]-1];
    $year = $array[0];
    $beautified = $day.". ".$month." ".$year;
    return $beautified;
}

function beautifyTimestamp($time = '2018-05-25 21:20:10', $compact = false) {
    $timeObj = new DateTime($time);
    $nowObj = new DateTime();
    $array = explode(' ', $time);
    $date = $array[0];
    $time = $array[1];

    $dateArray = explode("-", $date);
    $months = ["Januar", "Februar", "Marts", "April", "Maj", "Juni", "Juli", "August", "September", "Oktober", "November", "December"];
    $day = (int) $dateArray[2];
    $month = $months[$dateArray[1]-1];
    $year = $dateArray[0];

    $timeArray = explode(":", $time);
    $hours = $timeArray[0];
    $minutes = $timeArray[1];
    $seconds = $timeArray[2];

    if($compact) {
        if($timeObj->format('Y-m-d')==$nowObj->format('Y-m-d')) {
            $beautified = "kl. ".$hours.":".$minutes;
        } else {
            $beautified = $day.". ".$month." ".$year;
        }
    } else {
        $beautified = $hours.":".$minutes." ".$day.". ".$month." ".$year;
    }
    return $beautified;

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