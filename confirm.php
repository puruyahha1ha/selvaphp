<?php 
session_start();
//直リンクされた場合リダイレクト
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: member_regist.php");
    exit;
}

if ($_POST['confirm'] === '登録完了') {
    
    try {
        $dsn = 'mysql:dbname=mysql;host=localhost';
        $user = 'root';
        $password = 'kazuto060603';

        $db = new PDO($dsn, $user, $password);
        if (!empty($_POST['update'])) {
            // 変更時の処理

        } else {
            $email = $_POST['mail'];
            $sql_select = "SELECT email from members";
        
            $res = $db->query($sql_select);
            printf($res);

            if (!$res) {
        //         error_log($mysqli->error);
        //         exit;
                printf('$resがない');
            }
        //     // 重複データの有無をチェック
        //     if (mysqli_num_rows($res) == 0) {
        //         // 重複するデータがない場合
        //         // 登録時の処理
        //         $sql = 'INSERT into members (name_sei, name_mei, gender, pref_name, address, password, email, created_at) VALUES (:name_sei, :name_mei, :gender, :pref_name, :address, :password, :email, now())';
            
        //         $statement = $db->prepare($sql);
            
        //         $db->beginTransaction();
            
        //         $params = [
        //             ':name_sei' => $_POST['first_name'],
        //             ':name_mei' => $_POST['last_name'],
        //             ':gender' => $_POST['gender'],
        //             ':pref_name' => $_POST['prefecture'],
        //             ':address' => $_POST['other_prefecture'],
        //             ':password' => $_POST['password'],
        //             ':email' => $_POST['mail'],
        //         ];
            
        //         $statement->execute($params);
        //         $db->commit();
        //         header('Location: complete.php', true, 307);
        //         exit;        
        //     }else{
        //         // 重複するデータがある場合
        //         $errors['mail'] = '※このメールアドレスはすでに使用されています';
            
        //         header("Location: member_regist.php");
        //         exit;
        //     }
        }

        // $statement = null;

    } catch (PDOException $e) {
        if (!empty($db)) {
            $db->rollback();
        }
        echo 'DB接続エラー:' . $e->getMessage();
        phpinfo();
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
    <form action="confirm.php" method="post">
        <div class="name">
            <p>氏名</p>
            <span><?php echo htmlspecialchars($_POST["name_sei"]."　".$_POST["name_mei"]); ?></span>
            <input type="hidden" name="name_sei" value="<?php echo htmlspecialchars($_POST["name_sei"]); ?>">
            <input type="hidden" name="name_mei" value="<?php echo htmlspecialchars($_POST["name_mei"]); ?>">
        </div>
        <div class="gender">
            <p>性別</p>
            <span><?php if ($_POST["gender"] === '1') {echo '男性'; } elseif ($_POST["gender"] === '2') {echo '女性'; }?></span>
            <input type="hidden" name="gender" value="<?php echo htmlspecialchars($_POST["gender"]); ?>">
        </div>
        <div class="address">
            <p>住所</p>
            <span><?php echo htmlspecialchars($_POST["pref_name"].$_POST["address"]); ?></span>
            <input type="hidden" name="pref_name" value="<?php echo htmlspecialchars($_POST["pref_name"]); ?>">
            <input type="hidden" name="address" value="<?php echo htmlspecialchars($_POST["address"]); ?>">
        </div>
        <div class="password">
            <p>パスワード</p>
            <span>セキュリティのため非表示</span>
            <input type="hidden" name="password" value="<?php echo htmlspecialchars($_POST["password"]); ?>">
            <input type="hidden" name="password_check" value="<?php echo htmlspecialchars($_POST["password_check"]); ?>">
        </div>
        <div class="email">
            <p>メールアドレス</p>
            <span class="email_color"><?php echo htmlspecialchars($_POST["email"]); ?></span>
            <input type="hidden" name="email" value="<?php echo htmlspecialchars($_POST["email"]); ?>">
        </div>
        <div class="submit">
            <input type="submit" name="confirm" value="登録完了" class="button">
            <input type="submit" name="confirm" value="前に戻る" class="button_back">
        </div>
    </form>
</body>
</html>
