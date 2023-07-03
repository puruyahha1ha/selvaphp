<?php
session_start();

if (!empty($_GET['confirm']) && $_GET['confirm'] === 'ログアウト') {
    unset($_SESSION['login']);
}
if (!empty($_GET['confirm']) && $_GET['confirm'] === '新規会員登録') {
    header('Location: member_regist.php', true, 307);
    exit;
}
if (!empty($_GET['confirm']) && $_GET['confirm'] === 'ログイン') {
    header('Location: login.php', true, 307);
    exit;
}
if (!empty($_GET['confirm']) && $_GET['confirm'] === 'スレッド一覧') {
    header('Location: thread.php', true, 307);
    exit;
}
if (!empty($_GET['confirm']) && $_GET['confirm'] === '新規スレッド作成') {
    $_SESSION['login'] = 'ログイン';
    header('Location: thread_regist.php', true, 307);
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
    <?php if ((!empty($_POST['confirm']) && $_POST['confirm'] === 'ログイン') || (!empty($_SESSION['login']) && $_SESSION['login'] === 'ログイン')) {
        require_once('header_login.php');
    } else {
        require_once('header.php');
    } ?>

</body>

</html>