<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($_POST['confirm'] === 'ログイン') {

        // メールアドレス（ID）のバリデーション
        if (empty($_POST['email'])) {
            $errors['email'] = '※メールアドレス（ID）は必須入力です';
        } elseif (mb_strlen($_POST['email']) > 200) {
            $errors['email'] = '※メールアドレス（ID）は２００字以内で入力してください';
        }
        if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
            $errors['email_filter'] = '※有効なメールアドレス（ID）を入力してください';
        }
        // パスワードのバリデーション
        if (empty($_POST['password'])) {
            $errors['password'] = '※パスワードは必須入力です';
        } elseif (!preg_match("/^[a-zA-Z0-9]+$/", $_POST['password'])) {
            $errors['password'] = '※パスワードは半角英数字のみを入力してください';
        }
        if ((mb_strlen($_POST['password']) < 8 || mb_strlen($_POST['password']) > 20) && $errors['password'] !== "※パスワードは必須入力です") {
            $errors['password_length'] = '※パスワードは８〜２０字以内で入力してください';
        }
        if (empty($errors)) {
            try {
                $dsn = 'mysql:dbname=mysql;host=localhost';
                $user = 'root';
                $password = 'kazuto060603';

                $pdo = new PDO($dsn, $user, $password);

                $email = $_POST['email'];
                $password = $_POST['password'];

                // SQL文をセット
                $prepare = $pdo->prepare('SELECT * FROM members WHERE email = :email and password = :password');
                $prepare->bindValue(':email', $email, PDO::PARAM_STR);
                $prepare->bindValue(':password', $password, PDO::PARAM_STR);
                $prepare->execute();

                $record = $prepare->fetch();
                var_dump($record);
                if ($record) {
                    $_SESSION['name_sei'] = $record['name_sei'];
                    $_SESSION['name_mei'] = $record['name_mei'];
                    header('Location: top.php', true, 307);
                    exit;
                }
            } catch (PDOException $e) {
                if (!empty($pdo)) {
                    $db->rollback();
                }
                echo 'DB接続エラー:' . $e->getMessage();
                return;
            }
        }
    }
    if ($_POST['confirm'] === 'トップに戻る') {
        header('Location: top.php', true, 307);
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
    <link rel="stylesheet" href="https://unpkg.com/modern-css-reset/dist/reset.min.css" />
    <link rel="stylesheet" href="index.css">
    <title>ログイン画面</title>
</head>

<body>
    <header>
        <h1>ログイン</h1>
    </header>
    <main>
        <form action="login.php" method="post">
            <div class="email">
                <p>メールアドレス（ID）</p>
                <input type="text" name="email" value="<?php if (!empty($_POST['email'])) {echo htmlspecialchars($_POST['email']);} ?>">
            </div>
            <div class="error">
                <?php
                if (!empty($errors['email'])) {
                    echo $errors['email'] . "<br>";
                }
                if (!empty($errors['email_filter']) && $errors['email'] !== '※メールアドレス（ID）は必須入力です') {
                    echo $errors['email_filter'];
                }
                ?>
            </div>
            <div class="password">
                <p>パスワード</p>
                <input type="password" name="password" value="">
            </div>
            <div class="error">
                <?php
                if (!empty($errors['password'])) {
                    echo $errors['password'] . "<br>";
                }
                if (!empty($errors['password_length'])) {
                    echo $errors['password_length'];
                }
                ?>
            </div>
            <div class="submit">
                <input type="submit" name="confirm" value="ログイン" class="button">
                <input type="submit" name="confirm" value="トップに戻る" class="button_back">
            </div>
        </form>
    </main>
</body>

</html>
