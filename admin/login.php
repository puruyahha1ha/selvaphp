<?php
session_start();
if (!empty($_SESSION)) {
    header('Location: top.php', true, 307);
    exit;
}
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($_POST['confirm'] === 'ログイン') {

        // ログインIDのバリデーション
        if (empty($_POST['login_id']) || (mb_strlen($_POST['login_id']) > 10 || mb_strlen($_POST['login_id']) < 7) || !preg_match("/^[a-zA-Z0-9]+$/", $_POST['login_id'])) {
            $errors['no_record'] = 'IDもしくはパスワードが間違っています';
        }
        // パスワードのバリデーション
        if (empty($_POST['password']) || !preg_match("/^[a-zA-Z0-9]+$/", $_POST['password']) || (mb_strlen($_POST['password']) < 8 || mb_strlen($_POST['password']) > 20)) {
            $errors['no_record'] = 'IDもしくはパスワードが間違っています';
        }

        if (empty($errors)) {
            try {
                $dsn = 'mysql:dbname=mysql;host=localhost;charset=utf8;';
                $user = 'root';
                $password = 'kazuto060603';

                $pdo = new PDO($dsn, $user, $password);

                $login_id = $_POST['login_id'];
                $password = $_POST['password'];

                // SQL文をセット
                $prepare = $pdo->prepare('SELECT * FROM administers WHERE login_id = :login_id AND password = :password');
                $prepare->bindValue(':login_id', $login_id, PDO::PARAM_STR);
                $prepare->bindValue(':password', $password, PDO::PARAM_STR);
                $prepare->execute();

                $record = $prepare->fetch();

                if (isset($record['deleted_at'])) {
                    $errors['no_record'] = 'ログインできません';
                } elseif ($record) {
                    $_SESSION['id'] = $record['id'];
                    $_SESSION['name'] = $record['name'];
                    $_SESSION['login_id'] = $record['login_id'];
                    header('Location: top.php', true, 307);
                    exit;
                } else {
                    $errors['no_record'] = 'IDもしくはパスワードが間違っています';
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
    </header>
    <main>
        <h1>管理画面</h1>

        <form action="login.php" method="post">
            <div class="login_id">
                <p>ログインID</p>
                <input 
                    type="text" 
                    name="login_id" 
                    value="<?php if (!empty($_POST['login_id'])) { echo htmlspecialchars($_POST['login_id']);} ?>"
                >
            </div>

            <div class="password">
                <p>パスワード</p>
                <input type="password" name="password" value="">
            </div>
            <div class="error">
                <?php
                if (!empty($errors['no_record'])) {
                    echo $errors['no_record'];
                }
                ?>
            </div>

            <div class="submit">
                <input type="submit" name="confirm" value="ログイン" class="button">
            </div>

        </form>
    </main>
    <footer></footer>
</body>

</html>