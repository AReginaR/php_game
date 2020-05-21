<?php
include '../db.php';
$id = $_POST["id"];
echo json_encode(GetImprovedWelfare($id));