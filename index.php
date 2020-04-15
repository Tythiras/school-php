<?php
ini_set('display_errors', 1);

ini_set('session.cookie_httponly', 1);
ini_set('session.use_only_cookies', 1);
ini_set('session.cookie_secure', 1);
ini_set('session.use_strict_mode', 1);
ini_set('session.use_trans_sid', 0);
session_start();
$config = include('config.php');

include_once("helpers/db.php");
include_once("helpers/functions.php");


$page = $_SERVER['REQUEST_URI'];

$pagePath = str_replace('/school-php', '', $page);
$page = strtok($pagePath, '?');

$logged = $_SESSION['user'];


//routing
$file = '';
$title = 'Programming';
$template = true;
if($logged) {
  if($page=="/"){
    $file = 'dashboard.php';

  } else if($page=="/action.getFiles") {
    $template = false;
    $file = 'actions/getFiles.php';

  } else if($page=="/action.download") {
    $template = false;
    $file = 'actions/download.php';

  } else if($page=="/action.newFolder") {
    $template = false;
    $file = 'actions/newFolder.php';
    
  } else if($page=="/action.upload") {
    $template = false;
    $file = 'actions/upload.php';

  } else if($page=="/action.move") {
    $template = false;
    $file = 'actions/move.php';

  } else if($page=="/logout") {
    session_destroy();
    header("Location: ./");
    die();

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