<?php
function safe($message, $multiline = false) {
    if($multiline) {
        echo nl2br(htmlspecialchars($message));
    } else {
        echo htmlspecialchars($message);
    }
}
?>