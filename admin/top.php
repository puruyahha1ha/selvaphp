<?php
session_start();
var_dump($_SESSION);
if (!empty($_GET['confirm']) && $_GET['confirm'] === 'ログアウト') {
    $_SESSION = [];
    header('Location: login.php', true, 307);
    exit;
}

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
    <header>
        <h2>掲示板管理画面メインメニュー</h2>
        <span><?php echo "ようこそ{$_SESSION['name']}さん";?></span>
        <form action="top.php" action="get">
            <input type="submit" name="confirm" value="ログアウト" class="button_header">
        </form>
    </header>

</body>

</html>