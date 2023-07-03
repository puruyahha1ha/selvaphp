<?php
session_start();
// エラーメッセージの初期化
$errors = [];
var_dump($_POST);

if (!empty($_GET['confirm']) && $_GET['confirm'] === 'スレッド一覧に戻る') {
    header('Location: thread.php', true, 307);
    exit;
}


try {
    $dsn = 'mysql:dbname=mysql;host=localhost;charset=utf8;';
    $user = 'root';
    $password = 'kazuto060603';

    $pdo = new PDO($dsn, $user, $password);
    // スレッドID
    $id = !empty($_GET) ? $_GET['id'] : $_POST['id'];
    // メンバーID
    $member_id = $_SESSION['id'];

    if (!empty($_POST) && $_POST['confirm'] === 'コメントする') {
        // 姓のバリデーション
        if ($_POST['comment'] === '') {
            $errors['comment'] = '※コメントは必須入力です';
        } elseif (mb_strlen($_POST['comment']) > 500) {
            $errors['comment'] = '※氏名(姓)は５００字以内で入力してください';
        }
        var_dump($errors);
        if (empty($errors)) {
            $comment = $_POST['comment'];
            // SQL文をセット
            $prepare = $pdo->prepare("INSERT INTO comments (member_id, thread_id, comment, created_at) VALUES (:member_id, :id, :comment, now())");
            $prepare->bindValue(':member_id', $member_id, PDO::PARAM_INT);
            $prepare->bindValue(':id', $id, PDO::PARAM_INT);
            $prepare->bindValue(':comment', $comment, PDO::PARAM_STR);
            $prepare->execute();
        }
    }

    // SQL文をセット
    $prepare = $pdo->prepare("SELECT threads.member_id, threads.title, threads.content, threads.created_at, members.name_sei, members.name_mei, COUNT(comments.comment) AS comment_num FROM threads LEFT JOIN members ON members.id = threads.member_id LEFT JOIN comments ON comments.thread_id = threads.id WHERE threads.id = :id;");
    $prepare->bindValue(':id', $id, PDO::PARAM_INT);
    $prepare->execute();

    $prepare_comment = $pdo->prepare("SELECT comments.*, members.name_sei, members.name_mei FROM comments LEFT JOIN members ON members.id = comments.member_id WHERE thread_id = 16 ORDER BY comments.id ASC;");
    $prepare_comment->bindValue(':id', $id, PDO::PARAM_INT);
    $prepare_comment->execute();

    $record = $prepare->fetch();
    $comments = $prepare_comment->fetchAll();
    var_dump($comments);
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
    <title>スレッド詳細</title>
    <link rel="stylesheet" href="https://unpkg.com/modern-css-reset/dist/reset.min.css" />
    <link rel="stylesheet" href="index.css">
</head>

<body>
    <header>
        <div class="link">
            <form action="thread_detail.php" method="get">
                <input type="submit" name="confirm" value="スレッド一覧に戻る" class="button_header">
            </form>
        </div>
        <main>
            <div class="title_thread">
                <h2><?php if (!empty($record)) {
                        echo $record['title'];
                    } ?></h2>
                <div class="under_title">
                    <span><?php if (!empty($record)) {
                                echo $record['comment_num'];
                            } ?>コメント</span>
                    <span><?php if (!empty($record)) {
                                echo $record['created_at'];
                            } ?></span>
                </div>
            </div>
            <div class="gray"></div>
            <div class="content_thread">
                <p>投稿者：<?php if (!empty($record)) {
                            echo $record['name_sei'] . '　' . $record['name_mei'] . '　' . $record['created_at'];
                        } ?></p>
                <p><?php if (!empty($record)) {
                        echo $record['content'];
                    } ?></p>
            </div>
            <div class="comments">
                <?php
                if (!empty($record)) {
                    $number = 0;
                    foreach ($comments as $val) {
                        $number += 1;
                        echo $number.".　".$val['name_sei'].'　'.$val['name_mei'].'　'.$val['created_at'].'<br>';
                        echo nl2br(htmlspecialchars($val['comment'])) . "<br>";
                    }
                }
                ?>
            </div>
            <div class="gray"></div>
            <?php if (!empty($_SESSION) && $_SESSION['login'] === 'ログイン') : ?>
                <form action="thread_detail.php" method="post" class="comment">
                    <textarea name="comment" id="" rows="10"></textarea>
                    <div class="error">
                        <?php
                        if (!empty($errors['comment'])) {
                            echo $errors['comment'];
                        }
                        ?>
                    </div>
                    <input type="submit" name="confirm" value="コメントする" class="button">
                    <input type="hidden" name="id" value="<?php echo !empty($_GET) ? $_GET['id'] : $_POST['id']; ?>">
                </form>
            <?php endif ?>
        </main>
    </header>
</body>

</html>