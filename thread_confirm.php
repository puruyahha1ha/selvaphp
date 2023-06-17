<?php

if ($_POST['confirm'] === '前に戻る') {
    header('Location: thread_regist.php', true, 307);
    exit;
}

if ($_POST['confirm'] === 'スレッドを作成する') {
    try {
        $dsn = 'mysql:dbname=mysql;host=localhost;charset=utf8;';
        $user = 'root';
        $password = 'kazuto060603';

        $pdo = new PDO($dsn, $user, $password);

        $member_id = $_SESSION['id'];
        $title = $_POST['title'];
        $content = $_POST['content'];

        // SQL文をセット
        $prepare = $pdo->prepare('INSERT into threads (member_id, title, content, created_at) VALUES (:member_id, :title, :content, now());');
        $prepare->bindValue(':member_id', $member_id, PDO::PARAM_INT);
        $prepare->bindValue(':title', $title, PDO::PARAM_STR);
        $prepare->bindValue(':content', $content, PDO::PARAM_STR);
        $prepare->execute();

        header('Location: top.php', true, 307);
        exit;
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
    <title>スレッド作成確認画面</title>
    <link rel="stylesheet" href="https://unpkg.com/modern-css-reset/dist/reset.min.css" />
    <link rel="stylesheet" href="index.css">
</head>

<body>
    <header>
        <h1>スレッド作成確認画面</h1>
    </header>
    <form action="thread_confirm.php" method="post">
        <div class="title">
            <p>スレッドタイトル</p>
            <span><?php echo htmlspecialchars($_POST["title"]); ?></span>
            <input type="hidden" name="title" value="<?php echo $_POST["title"]; ?>">
        </div>
        <div class="content">
            <p>コメント</p>
            <span><?php echo nl2br(htmlspecialchars($_POST['content']));?></span>
            <input type="hidden" name="content" value="<?php echo $_POST["content"]; ?>">
        </div>
        <div class="submit">
            <input type="submit" name="confirm" value="スレッドを作成する" class="button" onclick="<?php if ($_POST['confirm'] === 'スレッドを作成する') {echo "disabled = true;";} ?>">
            <input type="submit" name="confirm" value="前に戻る" class="button_back">
        </div>
    </form>

</body>

</html>
