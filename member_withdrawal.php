<?php
session_start();
var_dump($_SESSION);

if (!empty($_GET['confirm']) && $_GET['confirm'] === 'トップに戻る') {
    header('Location: top.php', true, 307);
    exit;
}

// try {
//     $dsn = 'mysql:dbname=mysql;host=localhost;charset=utf8;';
//     $user = 'root';
//     $password = 'kazuto060603';

//     $pdo = new PDO($dsn, $user, $password);

//     if (!empty($_POST['confirm']) && $_POST['confirm'] === '退会する') {

//         // SQL文をセット
//         $prepare = $pdo->prepare("SELECT id, title, created_at FROM threads WHERE title LIKE :search OR content LIKE :search ORDER BY created_at DESC;");
//         $prepare->bindValue(':search', '%' . $search . '%', PDO::PARAM_STR);
//         $prepare->execute();

//         $records = $prepare->fetchAll();
//     } else {
//         // SQL文をセット
//         $prepare = $pdo->prepare('SELECT id, title, created_at FROM threads ORDER BY created_at DESC;');
//         $prepare->execute();

//         $records = $prepare->fetchAll();
//     }
// } catch (PDOException $e) {
//     if (!empty($pdo)) {
//         $db->rollback();
//     }
//     echo 'DB接続エラー:' . $e->getMessage();
//     return;
// }

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>退会ページ</title>
    <link rel="stylesheet" href="https://unpkg.com/modern-css-reset/dist/reset.min.css" />
    <link rel="stylesheet" href="index.css">
</head>

<body>
    <header>
        <div class="link">
            <form action="top.php" method="get">
                <input type="submit" name="confirm" value="トップに戻る" class="button_header">
            </form>
        </div>
    </header>
    <h1>退会</h1>
    <p>退会しますか？</p>
    <form action="confirm.php" method="post">
        <div class="submit">
            <input type="submit" name="confirm" value="退会する" class="button">
        </div>
    </form>
</body>

</html>