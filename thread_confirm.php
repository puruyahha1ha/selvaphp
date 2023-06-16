<?php

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>スレッド作成確認画面</title>
    <link rel="stylesheet" href="https://unpkg.com/modern-css-reset/dist/reset.min.css" />
    <link rel="stylesheet" href="index.css">
</head>

<body>
    <header>
        <h1>スレッド作成確認画面</h1>
    </header>
    <form action="thread_confirm.php" method="post">
        <div class="title">
            <p>スレッドタイトル</p>
            <span><?php echo htmlspecialchars($_POST["title"]); ?></span>
            <input type="hidden" name="title" value="<?php echo $_POST["title"]; ?>">
        </div>
        <div class="content">
            <p>コメント</p>
            <textarea name="content" id="" cols="30" rows="10">
                <?php echo htmlspecialchars($_POST["content"]); ?>
            </textarea>
            <input type="hidden" name="content" value="<?php echo $_POST["content"]; ?>">
        </div>
        <div class="submit">
            <input type="submit" name="confirm" value="スレッドを作成する" class="button" onclick="<?php if ($_POST['confirm'] === 'スレッドを作成する') {echo "disabled = true;";} ?>">
            <input type="submit" name="confirm" value="前に戻る" class="button_back">
        </div>
    </form>

</body>

</html>
