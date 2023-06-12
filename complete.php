<?php
//直リンクされた場合リダイレクト
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: member_regist.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>会員登録完了</title>
    <link rel="stylesheet" href="https://unpkg.com/modern-css-reset/dist/reset.min.css" />
    <link rel="stylesheet" href="index.css">
</head>
<body>
    <h1>会員登録完了</h1>
    <p class="complete">会員登録が完了しました。</label>
</body>
</html>

