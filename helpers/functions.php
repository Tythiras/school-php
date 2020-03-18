<?php
function safe($message, $multiline = false) {
    if($multiline) {
        echo nl2br(htmlspecialchars($message));
    } else {
        echo htmlspecialchars($message);
    }
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