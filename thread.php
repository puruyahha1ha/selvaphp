<?php
if (!empty($_GET['confirm']) && $_GET['confirm'] === '新規スレッド作成') {
    $_SESSION['login'] = 'ログイン';
    header('Location: thread_regist.php', true, 307);
    exit;
}
try {
    $dsn = 'mysql:dbname=mysql;host=localhost;charset=utf8;';
    $user = 'root';
    $password = 'kazuto060603';

    $pdo = new PDO($dsn, $user, $password);

    // SQL文をセット
    $prepare = $pdo->prepare('SELECT id, title, created_at FROM threads;');
    $prepare->execute();
    $prepare->debugDumpParams();

    $records = $prepare->fetch();


} catch (PDOException $e) {
    if (!empty($pdo)) {
        $db->rollback();
    }
    echo 'DB接続エラー:' . $e->getMessage();
    return;
}
if (!empty($_POST['confirm']) && $_GET['confirm'] === 'スレッド検索') {
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>スレッド一覧</title>
    <link rel="stylesheet" href="https://unpkg.com/modern-css-reset/dist/reset.min.css" />
    <link rel="stylesheet" href="index.css">
</head>

<body>
    <header>
        <div class="link">
            <form action="thread.php" method="get">
                <input type="submit" name="confirm" value="新規スレッド作成" class="button_header">
            </form>
        </div>
    </header>
    <main>
        <form action="thread.php" method="post">
            <div class="search">
                <input type="text" name="search" class="form" value="">
                <input type="button" name="confirm" value="スレッド検索">
            </div>
            <div class="list">
                <div>
                    <?php foreach ($records as $record) : ?>
                        <div class="record">
                            <p>ID:<?php echo $record['id'] ?></p>
                            <p><?php echo $record['title'] ?></p>
                            <p><?php echo $record['created_at'] ?></p>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <div class="submit">
                <input type="submit" name="confirm" value="トップに戻る" class="button_back">
            </div>
        </form>
    </main>
</body>

</html>
