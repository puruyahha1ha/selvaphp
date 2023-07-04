<?php
session_start();
// エラーメッセージの初期化
$errors = [];
if (!isset($_GET['page_id'])) {
    $now = 1;
} else {
    $now = $_GET['page_id'];
}

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
            $errors['comment'] = '※コメントは５００字以内で入力してください';
        }
        // コメントの登録
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

    // いいねの登録
    if ($_GET['like'] === '0') {
        if (empty($_SESSION['login'])) {
            header('Location: member_regist.php', true, 307);
            exit;
        }

        // SQL文をセット
        $prepare = $pdo->prepare("SELECT * FROM likes  WHERE member_id = :member_id AND comment_id = :comment_id");
        $prepare->bindValue(':member_id', $member_id, PDO::PARAM_INT);
        $prepare->bindValue(':comment_id', $_GET['comment_id'], PDO::PARAM_INT);
        $prepare->execute();
        $result = $prepare->fetch();
        // 重複チェック
        if (empty($result)) {
            // SQL文をセット
            $prepare = $pdo->prepare("INSERT INTO likes (member_id, comment_id) VALUES (:member_id, :comment_id)");
            $prepare->bindValue(':member_id', $member_id, PDO::PARAM_INT);
            $prepare->bindValue(':comment_id', $_GET['comment_id'], PDO::PARAM_INT);
            $prepare->execute();
        }
    }
    if ($_GET['like'] === '1') {
        // SQL文をセット
        $prepare = $pdo->prepare("DELETE FROM likes WHERE member_id = :member_id AND comment_id = :comment_id");
        $prepare->bindValue(':member_id', $member_id, PDO::PARAM_INT);
        $prepare->bindValue(':comment_id', $_GET['comment_id'], PDO::PARAM_INT);
        $prepare->execute();
    }

    // 初期表示の情報を取得
    // SQL文をセット
    $prepare = $pdo->prepare("SELECT threads.member_id, threads.title, threads.content, threads.created_at, members.name_sei, members.name_mei, COUNT(comments.comment) AS comment_num FROM threads LEFT JOIN members ON members.id = threads.member_id LEFT JOIN comments ON comments.thread_id = threads.id WHERE threads.id = :id;");
    $prepare->bindValue(':id', $id, PDO::PARAM_INT);
    $prepare->execute();

    $record = $prepare->fetch();
    $max_page = (string)ceil($record['comment_num'] / 5);

    // ページに応じてコメントを取得
    $limit = 5;
    $offset = ((int)$now - 1) * 5;
    $prepare_comment = $pdo->prepare("SELECT comments.*, members.name_sei, members.name_mei FROM comments LEFT JOIN members ON members.id = comments.member_id WHERE thread_id = :id ORDER BY comments.id ASC LIMIT :limit OFFSET :offset;");
    $prepare_comment->bindValue(':id', $id, PDO::PARAM_INT);
    $prepare_comment->bindValue(':limit', $limit, PDO::PARAM_INT);
    $prepare_comment->bindValue(':offset', $offset, PDO::PARAM_INT);
    $prepare_comment->execute();

    $comments = $prepare_comment->fetchAll();
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
    <script src="animation.js" async></script>
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
            <div class="gray">
                <form action="thread_detail.php" method="post" class="page">
                    <?php if ($now > 1) {
                        $page = $now - 1;
                        echo "<a href='thread_detail.php?page_id={$page}&id={$id}'>前へ＞</a>";
                    } else {
                        echo '<span>前へ＞</span>';
                    } ?>
                    <?php if ($now === $max_page) {
                        echo '<span>次へ＞</span>';
                    } else {
                        $page = $now + 1;
                        echo "<a href='thread_detail.php?page_id={$page}&id={$id}'>次へ＞</a>";
                    } ?>
                </form>
            </div>
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
                    if ($now === '1') {
                        $number = 0;
                    } else {
                        $number = ($now - 1) * 5;
                    }
                    foreach ($comments as $val) {
                        $number += 1;
                        $comment_id = $val['id'];
                        // いいね数を取得
                        $prepare_like = $pdo->prepare("SELECT COUNT(*) AS cnt FROM likes WHERE comment_id = :comment_id;");
                        $prepare_like->bindValue(':comment_id', $comment_id, PDO::PARAM_INT);
                        $prepare_like->execute();

                        $like = $prepare_like->fetch();
                        // いいねをしているかのチェック
                        $prepare_check = $pdo->prepare("SELECT COUNT(1) FROM likes WHERE member_id = :member_id AND comment_id = :comment_id;");
                        $prepare_check->bindValue(':member_id', $member_id, PDO::PARAM_INT);
                        $prepare_check->bindValue(':comment_id', $comment_id, PDO::PARAM_INT);
                        $prepare_check->execute();
                        $check = $prepare_check->fetch();

                        echo "<div class='comment'>" . $number . ".　" . $val['name_sei'] . '　' . $val['name_mei'] . '　' . $val['created_at'] . '<br>';
                        echo nl2br(htmlspecialchars($val['comment'])) . "<br>";
                        if ($check['COUNT(1)'] === '1') {
                            echo "   <div class='like'><a href='thread_detail.php?page_id={$now}&id={$id}&like=1&comment_id={$comment_id}'><img src='img\like.png'>";
                        } else {
                            echo " <div class='like'><a href='thread_detail.php?page_id={$now}&id={$id}&like=0&comment_id={$comment_id}'><img src='img\unlike.png'>";
                        }
                        echo "  </a><span>{$like['cnt']}</span></div>";
                        echo "</div>";
                    }
                }
                ?>
            </div>
            <div class="gray">
                <form action="thread_detail.php" method="post" class="page">
                    <?php if ($now > 1) {
                        $page = $now - 1;
                        echo "<a href='thread_detail.php?page_id={$page}&id={$id}'>前へ＞</a>";
                    } else {
                        echo '<span>前へ＞</span>';
                    } ?>
                    <?php if ($now === $max_page) {
                        echo '<span>次へ＞</span>';
                    } else {
                        $page = $now + 1;
                        echo "<a href='thread_detail.php?page_id={$page}&id={$id}'>次へ＞</a>";
                    } ?>
                </form>
            </div>
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