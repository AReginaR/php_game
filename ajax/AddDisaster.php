<?php
include '../db.php';
$title = $_POST["title"];
$id = $_POST["id"];
AddDisaster($id, $title);