<?php
    session_start();



?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://unpkg.com/modern-css-reset/dist/reset.min.css" />
    <link rel="stylesheet" href="index.css">
    <title>トップ画面</title>
</head>

<body>
    <?php if (!empty($_POST['confirm']) && $_POST['confirm'] === 'ログイン') {require_once('header_login.php'); } else {require_once('header.php');}?>

</body>

</html>
