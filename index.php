<?php
require_once 'db.php';
require_once 'pages/header.php';

if ( isset ($_SESSION['logged_user'])){
    require_once 'map.php';
} else {
    require_once 'login.php';
}