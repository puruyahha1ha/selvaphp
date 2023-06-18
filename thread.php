<?php
session_start();

if (!empty($_POST['confirm']) && $_POST['confirm'] === '新規スレッド作成') {
    $_SESSION['login'] = 'ログイン';
    header('Location: thread_regist.php', true, 307);
    exit;
}
if (!empty($_POST['confirm']) && $_POST['confirm'] === 'トップに戻る') {
    $_SESSION['login'] = 'ログイン';
    header('Location: top.php', true, 307);
    exit;
}
try {
    $dsn = 'mysql:dbname=mysql;host=localhost;charset=utf8;';
    $user = 'root';
    $password = 'kazuto060603';

    $pdo = new PDO($dsn, $user, $password);

    if (!empty($_POST['confirm']) && $_POST['confirm'] === 'スレッド検索') {

        $search = $_POST['search'];

        // SQL文をセット
        $prepare = $pdo->prepare('SELECT id, title, created_at FROM threads WHERE MATCH (title, content) AGAINST (:search IN BOOLEAN MODE) ORDER BY created_at DESC;');
        $prepare->bindValue(':search', $search, PDO::PARAM_STR);
        $prepare->execute();

        $records = $prepare->fetchAll();
        printf('search');
    } else {
        // SQL文をセット
        $prepare = $pdo->prepare('SELECT id, title, created_at FROM threads ORDER BY created_at DESC;');
        $prepare->execute();

        $records = $prepare->fetchAll();
        printf('first');
        var_dump($_POST);
    }
} catch (PDOException $e) {
    if (!empty($pdo)) {
        $db->rollback();
    }
    echo 'DB接続エラー:' . $e->getMessage();
    return;
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
    <form action="thread.php" method="post">
        <header>
            <div class="link">
                <input type="submit" name="confirm" value="新規スレッド作成" class="button_header">
            </div>
        </header>
        <main>
            <div class="search">
                <input type="text" name="search" class="form" value="">
                <input type="submit" name="confirm" value="スレッド検索">
            </div>
            <div class="list">
                <div>
                    <?php foreach ($records as $record) : ?>
                        <div class="record">
                            <p>ID:<?php echo $record['id'] ?></p>
                            <p><?php echo $record['title'] ?></p>
                            <p class="created_at"><?php echo $record['created_at'] ?></p>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <div class="submit">
                <input type="submit" name="confirm" value="トップに戻る" class="button_back">
            </div>
        </main>
    </form>
</body>

</html>