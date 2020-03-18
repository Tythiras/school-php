<?php
session_start();
ini_set('display_errors', 0);

include_once("helpers/db.php");
include_once("helpers/functions.php");

ini_set('session.cookie_httponly', 1);
ini_set('session.use_only_cookies', 1);
ini_set('session.cookie_secure', 1);
ini_set('session.use_strict_mode', 1);
ini_set('session.use_trans_sid', 0);

$page = $_SERVER['REQUEST_URI'];

$page = str_replace('/school-php', '', $page);
$page = strtok($page, '?');

$logged = $_SESSION['user'];


//routing
$file = '';
$title = 'Programming';
$template = true;
if($logged) {
  if($page=="/"){
    $file = 'dashboard.php';
  }
} else {
  if($page=="/register") {
    $file = 'auth/register.php';
  } else {
    $file = 'auth/login.php';
  }
}
if($file) {
  if($template) {
    include_once("template/head.php");
  }
  if($file!=''&&$file!==true) {
    include_once("pages/".$file);
  }
  if($template) {
    include_once("template/bottom.php");
  }
//404
} else if($template) {
  include_once("template/head.php");
  include_once("template/404.php");
  include_once("template/bottom.php");
}
?>