<?php
session_start();

if (!empty($_GET['confirm']) && $_GET['confirm'] === '新規スレッド作成') {
    header('Location: thread_regist.php', true, 307);
    exit;
}
if (!empty($_POST['confirm']) && $_POST['confirm'] === 'トップに戻る') {
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
        $prepare = $pdo->prepare("SELECT id, title, created_at FROM threads WHERE title LIKE :search OR content LIKE :search ORDER BY created_at DESC;");
        $prepare->bindValue(':search', '%' . $search . '%', PDO::PARAM_STR);
        $prepare->execute();

        $records = $prepare->fetchAll();
    } else {
        // SQL文をセット
        $prepare = $pdo->prepare('SELECT id, title, created_at FROM threads ORDER BY created_at DESC;');
        $prepare->execute();

        $records = $prepare->fetchAll();
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
    <form action="thread.php" method="get">
        <header>
            <div class="link">
                <?php if (($_SESSION['login']) && $_SESSION['login'] === 'ログイン') {
                    echo "<input type='submit' name='confirm' value='新規スレッド作成' class='button_header'>";
                } ?>
            </div>
        </header>
    </form>

    <main>
        <form action="thread.php" method="post">
            <div class="search">
                <input type="text" name="search" class="form" value="">
                <input type="submit" name="confirm" value="スレッド検索">
            </div>
        </form>

        <div class="list">
            <div>
                <?php foreach ($records as $record) : ?>
                    <form action="thread_detail.php" method="get">
                        <div class="record">
                            ID: <input type="text" name="id" value="<?php echo htmlspecialchars($record['id']) ?>" readonly>
                            <input type="submit" value="<?php echo htmlspecialchars($record['title']) ?>">
                            <p class="created_at"><?php echo htmlspecialchars($record['created_at']) ?></p>
                        </div>
                    </form>
                <?php endforeach; ?>
            </div>
        </div>
        <form action="thread.php" method="post">
            <div class="submit">
                <input type="submit" name="confirm" value="トップに戻る" class="button_back">
            </div>
        </form>

    </main>
</body>

</html>
