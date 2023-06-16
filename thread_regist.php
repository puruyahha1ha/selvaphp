<?php

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
        <div class="content">
            <p>スレッドタイトル</p>
            <input type="content" name="content" value="<?php if (!empty($_POST['content'])) {echo htmlspecialchars($_POST['content']);} ?>">
        </div>
        <div class="submit">
            <input type="submit" name="confirm" value="確認画面へ" class="button">
            <input type="submit" name="confirm" value="トップに戻る" class="button_back">
        </div>
    </form>
</body>

</html>
