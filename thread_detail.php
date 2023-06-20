<?php
session_start();
if (!empty($_GET['confirm']) && $_GET['confirm'] === 'スレッド一覧に戻る') {
    header('Location: thread.php', true, 307);
    exit;
}

if (!empty($_GET['id'])) {
    try {
        $dsn = 'mysql:dbname=mysql;host=localhost;charset=utf8;';
        $user = 'root';
        $password = 'kazuto060603';

        $pdo = new PDO($dsn, $user, $password);

        $id = $_GET['id'];

        // SQL文をセット
        $prepare = $pdo->prepare("SELECT threads.member_id, threads.title,threads.content,threads.created_at, members.name_sei, members.name_mei FROM threads LEFT JOIN members ON members.id = threads.member_id WHERE threads.id = :id;");
        $prepare->bindValue(':id', $id, PDO::PARAM_INT);
        $prepare->execute();

        $record = $prepare->fetch();

    } catch (PDOException $e) {
        if (!empty($pdo)) {
            $db->rollback();
        }
        echo 'DB接続エラー:' . $e->getMessage();
        return;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>スレッド詳細</title>
    <link rel="stylesheet" href="https://unpkg.com/modern-css-reset/dist/reset.min.css" />
    <link rel="stylesheet" href="index.css">
</head>

<body>
    <header>
        <div class="link">
            <form action="top.php" method="get">
                <input type="submit" name="confirm" value="スレッド一覧に戻る" class="button_header">
            </form>
        </div>
        <main>
            <div class="title_thread">
                <h1><?php if (!empty($record)) {echo $record['title'];}?></h1>
                <span><?php if (!empty($record)) {echo $record['created_at'];}?></span>
            </div>
            <div class="gray"></div>
            <div class="content_thread">
                <p>投稿者：<?php if (!empty($record)) {echo $record['name_sei'].'　'.$record['name_mei'].'　'.$record['created_at'];}?></p>
                <p><?php if (!empty($record)) {echo $record['content'];}?></p>
            </div>
            <div class="gray"></div>
            <?php if (!empty($_SESSION) && $_SESSION['login'] === 'ログイン') :?>
                <form action="" method="post">
                <textarea name="comment" id="" cols="30" rows="10"></textarea>
                <input type="submit" name="confirm" value="コメントする" class="button">
                </form>
            <?php endif ?>    
        </main>
    </header>
</body>

</html>
