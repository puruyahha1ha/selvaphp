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
    <form action="complete.php" method="post">
        <div class="name">
            <p>氏名</p>
            <span><?php echo $_POST["first_name"]."　".$_POST["last_name"] ?></span>
            <input type="hidden" name="first_name" value="<?php echo $_POST["first_name"] ?>">
            <input type="hidden" name="last_name" value="<?php echo $_POST["last_name"] ?>">
        </div>
        <div class="gender">
            <p>性別</p>
            <span><?php echo $_POST["gender"] ?></span>
            <input type="hidden" name="gender" value="<?php echo $_POST["gender"] ?>">
        </div>
        <div class="address">
            <p>住所</p>
            <span><?php echo $_POST["prefecture"].$_POST["other_prefecture"] ?></span>
            <input type="hidden" name="prefecture" value="<?php echo $_POST["prefecture"] ?>">
            <input type="hidden" name="other_prefecture" value="<?php echo $_POST["other_prefecture"] ?>">
        </div>
        <div class="password">
            <p>パスワード</p>
            <span>セキュリティのため非表示</span>
            <input type="hidden" name="password" value="<?php echo $_POST["password"] ?>">
        </div>
        <div class="mail">
            <p>メールアドレス</p>
            <span class="mail_color"><?php echo $_POST["mail"] ?></span>
            <input type="hidden" name="mail" value="<?php echo $_POST["mail"] ?>">
        </div>
        <div class="submit">
            <input type="submit" name="confirm" value="確認画面へ" class="button">
        </div>
        <div class="submit">
            <input type="button" onclick="history.back()" value="前に戻る" class="button_back">
        </div>
    </form>
</body>
</html>
