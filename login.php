<?php






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
                    value="<?php if (!empty($posts['email'])) {echo htmlspecialchars($posts['email']);} ?>"
                >
            </div>
            <div class="password">
                <p>パスワード</p>
                <input 
                    type="password" 
                    name="password" 
                    value="<?php if (!empty($posts['password'])) {echo htmlspecialchars($posts['password']);} ?>"
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
