<?php
session_start();

// ログイン状態ではない場合は、トップ画面に遷移
if (empty($_SESSION['login'])) {
    header('Location: top.php', true, 307);
    exit;
}

// エラーメッセージの初期化
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($_POST['confirm'] === 'トップに戻る') {
        header('Location: top.php', true, 307);
        exit;
    }

    // スレッドタイトルのバリデーション
    if ($_POST['title'] === '') {
        $errors['title'] = '※スレッドタイトルは必須入力です';
    } elseif (mb_strlen($_POST['title']) > 100) {
        $errors['title'] = '※スレッドタイトルは１００字以内で入力してください';
    }
    // コメントのバリデーション
    if ($_POST['content'] === '') {
        $errors['content'] = '※コメントは必須入力です';
    } elseif (mb_strlen($_POST['content']) > 500) {
        $errors['content'] = '※コメントは５００字以内で入力してください';
    }

    if (empty($errors) && $_POST['confirm'] !== '前に戻る') {
        header('Location: thread_confirm.php', true, 307);
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>スレッド作成フォーム</title>
    <link rel="stylesheet" href="https://unpkg.com/modern-css-reset/dist/reset.min.css" />
    <link rel="stylesheet" href="index.css">
</head>

<body>
    <header>
        <h1>スレッド作成フォーム</h1>
    </header>
    <form action="thread_regist.php" method="post">
        <div class="title">
            <p>スレッドタイトル</p>
            <input type="text" name="title" value="<?php if (!empty($_POST['title'])) {echo htmlspecialchars($_POST['title']);} ?>">
        </div>
        <div class="error">
            <?php 
                if (!empty($errors['title'])) {
                    echo $errors['title'];
                }
            ?>
        </div>
        <div class="content">
            <p>コメント</p>
            <textarea name="content" id="" cols="30" rows="10"><?php if (!empty($_POST['content'])) {echo htmlspecialchars($_POST['content']);} ?></textarea>
        </div>
        <div class="error">
            <?php 
                if (!empty($errors['content'])) {
                    echo $errors['content'];
                }
            ?>
        </div>
        <div class="submit">
            <input type="submit" name="confirm" value="確認画面へ" class="button">
            <input type="submit" name="confirm" value="トップに戻る" class="button_back">
        </div>
    </form>
</body>

</html>
