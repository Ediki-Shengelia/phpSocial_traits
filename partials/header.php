<?php

require_once __DIR__ . '/../includes/init.php';

if ($session->is_signed_in()) {
    Redirect("admin/index.php");
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>