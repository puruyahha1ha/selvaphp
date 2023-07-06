<?php
session_start();
if ($_GET['confirm'] == '編集') {
    $id = $_GET['id'];
    header("Location: member_edit.php?id={$id}", true, 307);
    exit;
}
if ($_GET['confirm'] == '削除') {
    try {
        $dsn = 'mysql:dbname=mysql;host=localhost;charset=utf8;';
        $user = 'root';
        $password = 'kazuto060603';
    
        $pdo = new PDO($dsn, $user, $password);

        $id = $_GET['id'];
    
        // SQL文をセット
        $prepare = $pdo->prepare('UPDATE members SET deleted_at=now() WHERE id = :id');
        $prepare->bindValue(':id', $id, PDO::PARAM_INT);
        $prepare->execute();
    
    } catch (PDOException $e) {
        if (!empty($pdo)) {
            $db->rollback();
        }
        echo 'DB接続エラー:' . $e->getMessage();
        return;
    }
    $id = $_GET['id'];
    header("Location: member.php?id={$id}", true, 307);
    exit;
}
try {
    $dsn = 'mysql:dbname=mysql;host=localhost;charset=utf8;';
    $user = 'root';
    $password = 'kazuto060603';

    $pdo = new PDO($dsn, $user, $password);

    $id = $_GET['id'];

    // SQL文をセット
    $prepare = $pdo->prepare('SELECT * FROM members WHERE id = :id');
    $prepare->bindValue(':id', $id, PDO::PARAM_INT);
    $prepare->execute();

    $record = $prepare->fetch(PDO::FETCH_ASSOC);
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
    <title>会員詳細画面</title>
    <link rel="stylesheet" href="https://unpkg.com/modern-css-reset/dist/reset.min.css" />
    <link rel="stylesheet" href="index.css">
</head>

<body>
    <header>
        <h2>会員詳細</h2>
        <form action="member.php" action="get" class="header_top">
            <input type="submit" name="confirm" value="一覧へ戻る" class="button_header">
        </form>
    </header>
    <div class="confirm_form">
        <div class="id">
            <p>ID</p>
            <span><?php echo $record['id']; ?></span>
        </div>
        <div class="name">
            <p>氏名</p>
            <span><?php echo htmlspecialchars($record["name_sei"] . "　" . $record["name_mei"]); ?></span>
        </div>
        <div class="gender">
            <p>性別</p>
            <span><?php if ($record["gender"] === '1') {
                        echo '男性';
                    } elseif ($record["gender"] === '2') {
                        echo '女性';
                    } ?></span>
        </div>
        <div class="address">
            <p>住所</p>
            <span><?php echo htmlspecialchars($record["pref_name"] . $record["address"]); ?></span>
        </div>
        <div class="password_regist">
            <p>パスワード</p>
            <span>セキュリティのため非表示</span>
        </div>
        <div class="email">
            <p>メールアドレス</p>
            <span class="email_color"><?php echo htmlspecialchars($record["email"]); ?></span>
        </div>
        <div class="submit_detail">
            <a href="member_detail.php?confirm=編集&id=<?php echo $record['id']; ?>">
                <div class="edit_button">編集</div>
            </a>
            <a href="member_detail.php?confirm=削除&id=<?php echo $record['id']; ?>">
                <div class="edit_button">削除</div>
            </a>
        </div>
    </div>
</body>

</html>