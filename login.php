<?php
var_dump($_POST);
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($_POST['confirm'] === 'ログイン') {
        try{
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

            }



        } catch (PDOException $e) {
            if (!empty($pdo)) {
                $db->rollback();
            }
            echo 'DB接続エラー:' . $e->getMessage();
            return;    
        }
        // header('Location: top.php', true, 307);
        // exit;
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
                <input 
                    type="text" 
                    name="email" 
                    value="<?php if (!empty($_POST['email'])) {echo htmlspecialchars($_POST['email']);} ?>"
                >
            </div>
            <div class="password">
                <p>パスワード</p>
                <input 
                    type="password" 
                    name="password" 
                    value="<?php if (!empty($_POST['password'])) {echo htmlspecialchars($_POST['password']);} ?>"
                >
            </div>
            <div class="submit">
                <input type="submit" name="confirm" value="ログイン" class="button">
                <input type="submit" name="confirm" value="トップに戻る" class="button_back">
            </div>
        </form>
    </main>
</body>

</html>
