<?php
include '../db.php';
$title = $_POST["title"];
$id = $_POST["id"];
echo GetItem($title, $id);