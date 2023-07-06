<?php
session_start();
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
    <title>会員登録確認画面</title>
    <link rel="stylesheet" href="https://unpkg.com/modern-css-reset/dist/reset.min.css" />
    <link rel="stylesheet" href="index.css">
</head>

<body>
    <header>
        <h2><?php if ($_SESSION['confirm'] == "登録") {
                echo "会員登録";
            } else {
                echo "会員編集";
            } ?></h2>
        <form action="member.php" action="get" class="header_top">
            <input type="submit" name="confirm" value="一覧へ戻る" class="button_header">
        </form>
    </header>
    <form action="member_confirm.php" method="post">
        <div class="confirm_form">
            <div class="id">
                <p>ID</p>
                <span><?php if ($_SESSION['confirm'] == "登録") {
                            echo "登録後に自動採番";
                        } else {
                            echo $_POST['id'];
                        } ?></span>
                <input type="hidden" name="id" value="<?php echo $_POST["id"]; ?>">

            </div>
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
            <div class="password_regist">
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
                <input type="submit" name="confirm" value="<?php if ($_SESSION['confirm'] == "登録") {
                                                                echo "登録完了";
                                                            } else {
                                                                echo "編集完了";
                                                            } ?>" class="button_re" onclick="<?php if ($_POST['confirm'] === '登録完了' || $_POST['confirm'] === '編集完了') {
                                                                                                    echo "disabled = true;";
                                                                                                } ?>">
            </div>
        </div>
    </form>
</body>

</html>
