<?php
include '../db.php';
$title = $_POST["title"];
$id = $_POST["id"];
echo json_encode(UseItem($id, $title));