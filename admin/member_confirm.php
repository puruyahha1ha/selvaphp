<?php
session_start();
//直リンクされた場合リダイレクト
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: member_regist.php");
    exit;
}

if ($_POST['confirm'] === '登録完了') {

    try {
        $dsn = 'mysql:dbname=mysql;host=localhost;charset=utf8;';
        $user = 'root';
        $password = 'kazuto060603';

        $pdo = new PDO($dsn, $user, $password);

        if (!empty($_POST['update'])) {
            // 変更時の処理

        } else {
            $name_sei = $_POST['name_sei'];
            $name_mei = $_POST['name_mei'];
            $gender = $_POST['gender'];
            $pref_name = $_POST['pref_name'];
            $address = $_POST['address'];
            $password = $_POST['password'];
            $email = $_POST['email'];

            // SQL文をセット
            $prepare = $pdo->prepare('SELECT * FROM members WHERE email = :email');
            $prepare->bindValue(':email', $email, PDO::PARAM_STR);
            $prepare->execute();

            $record = $prepare->fetch();

            if (!$record) {
                // DBにメールアドレスがない場合
                $prepare = $pdo->prepare('INSERT into members (name_sei, name_mei, gender, pref_name, address, password, email, created_at) VALUES (:name_sei, :name_mei, :gender, :pref_name, :address, :password, :email, now());');
                $pdo->query('SET NAMES utf8');
                // 値をセット
                $prepare->bindValue(':name_sei', $name_sei, PDO::PARAM_STR);
                $prepare->bindValue(':name_mei', $name_mei, PDO::PARAM_STR);
                $prepare->bindValue(':gender', $gender, PDO::PARAM_INT);
                $prepare->bindValue(':pref_name', $pref_name, PDO::PARAM_STR);
                $prepare->bindValue(':address', $address, PDO::PARAM_STR);
                $prepare->bindValue(':password', $password, PDO::PARAM_STR);
                $prepare->bindValue(':email', $email, PDO::PARAM_STR);

                $prepare->execute();
                header('Location: complete.php', true, 307);
                exit;
            } else {
                // DBにメールアドレスがある場合
                $error = '※このメールアドレスはすでに使用されています';
            }
        }

        $prepare = null;
    } catch (PDOException $e) {
        if (!empty($pdo)) {
            $db->rollback();
        }
        echo 'DB接続エラー:' . $e->getMessage();
        return;
    }
}
if ($_POST['confirm'] === '前に戻る') {
    header('Location: member_regist.php', true, 307);
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>会員登録確認画面</title>
    <link rel="stylesheet" href="https://unpkg.com/modern-css-reset/dist/reset.min.css" />
    <link rel="stylesheet" href="index.css">
</head>

<body>
    <h1>会員情報確認画面</h1>
    <form action="member_confirm.php" method="post">
        <div class="name">
            <p>氏名</p>
            <span><?php echo htmlspecialchars($_POST["name_sei"] . "　" . $_POST["name_mei"]); ?></span>
            <input type="hidden" name="name_sei" value="<?php echo $_POST["name_sei"]; ?>">
            <input type="hidden" name="name_mei" value="<?php echo $_POST["name_mei"]; ?>">
        </div>
        <div class="gender">
            <p>性別</p>
            <span><?php if ($_POST["gender"] === '1') {
                        echo '男性';
                    } elseif ($_POST["gender"] === '2') {
                        echo '女性';
                    } ?></span>
            <input type="hidden" name="gender" value="<?php echo $_POST["gender"]; ?>">
        </div>
        <div class="address">
            <p>住所</p>
            <span><?php echo htmlspecialchars($_POST["pref_name"] . $_POST["address"]); ?></span>
            <input type="hidden" name="pref_name" value="<?php echo $_POST["pref_name"]; ?>">
            <input type="hidden" name="address" value="<?php echo $_POST["address"]; ?>">
        </div>
        <div class="password">
            <p>パスワード</p>
            <span>セキュリティのため非表示</span>
            <input type="hidden" name="password" value="<?php echo $_POST["password"]; ?>">
            <input type="hidden" name="password_check" value="<?php echo $_POST["password_check"]; ?>">
        </div>
        <div class="email">
            <p>メールアドレス</p>
            <span class="email_color"><?php echo htmlspecialchars($_POST["email"]); ?></span>
            <input type="hidden" name="email" value="<?php echo $_POST["email"]; ?>">
        </div>
        <div class="error">
            <?php if (!empty($error)) {
                echo $error;
            } ?>
        </div>
        <div class="submit">
            <input type="submit" name="confirm" value="登録完了" class="button" onclick="<?php if ($_POST['confirm'] === '登録完了') {echo "disabled = true;";} ?>">
            <input type="submit" name="confirm" value="前に戻る" class="button_back">
        </div>
    </form>
</body>

</html>