<?php

session_start();

// 初期化
$errors = [];

if (!empty($_SESSION['back'])) {
    $back = $_SESSION['back'];

    // 氏名（姓）のエラーチェック
    if (!empty($_SESSION['errors']['first_name'])) {
        $errors['first_name'] = $_SESSION['errors']['first_name'];
        $first_name = $_SESSION['first_name'];
    } else {
        $first_name = $_SESSION['first_name'];
    }
    if (!empty($_SESSION['errors']['first_name_character']) && $errors['first_name'] !== "※氏名（姓）は必須入力です") {
        $errors['first_name_character'] = $_SESSION['errors']['first_name_character'];
        $first_name = $_SESSION['first_name'];
    } else {
        $first_name = $_SESSION['first_name'];
    }

    // 氏名（名）のエラーチェック
    if (!empty($_SESSION['errors']['last_name'])) {
        $errors['last_name'] = $_SESSION['errors']['last_name'];
        $last_name = $_SESSION['last_name'];
    } else {
        $last_name = $_SESSION['last_name'];
    }
    if (!empty($_SESSION['errors']['last_name_character']) && $errors['last_name'] !== "※氏名（名）は必須入力です") {
        $errors['last_name_character'] = $_SESSION['errors']['last_name_character'];
        $last_name = $_SESSION['last_name'];
    } else {
        $last_name = $_SESSION['last_name'];
    }

    // 性別のエラーチェック
    if (!empty($_SESSION['errors']['gender'])) {
        $errors['gender'] = $_SESSION['errors']['gender'];
    } else {
        $gender = $_SESSION['gender'];
    }

    // 都道府県のエラーチェック
    if (!empty($_SESSION['errors']['prefecture'])) {
        $errors['prefecture'] = $_SESSION['errors']['prefecture'];
    } else {
        $prefecture = $_SESSION['prefecture'];
    }

    // それ以降の住所のエラーチェック
    if (!empty($_SESSION['errors']['other_prefecture'])) {
        $errors['other_prefecture'] = $_SESSION['errors']['other_prefecture'];
        $other_prefecture = $_SESSION['other_prefecture'];
    } else {
        $other_prefecture = $_SESSION['other_prefecture'];
    }

    // パスワードのエラーチェック
    if (!empty($_SESSION['errors']['password'])) {
        $errors['password'] = $_SESSION['errors']['password'];
        $password = $_SESSION['password'];
    } else {
        $password = $_SESSION['password'];
    }
    if (!empty($_SESSION['errors']['password_length']) && $errors['password'] !== "※パスワードは必須入力です") {
        $errors['password_length'] = $_SESSION['errors']['password_length'];
        $password = $_SESSION['password'];
    } else {
        $password = $_SESSION['password'];
    }

    // パスワード確認のエラーチェック
    if (!empty($_SESSION['errors']['password_check'])) {
        $errors['password_check'] = $_SESSION['errors']['password_check'];
        $password_check = $_SESSION['password_check'];
    } else {
        $password_check = $_SESSION['password_check'];
    }
    if (!empty($_SESSION['errors']['password_check_length']) && $errors['password_check'] !== "※パスワード確認は必須入力です") {
        $errors['password_check_length'] = $_SESSION['errors']['password_check_length'];
        $password_check = $_SESSION['password_check'];
    } else {
        $password_check = $_SESSION['password_check'];
    }
    if (!empty($_SESSION['errors']['password_check_match']) && empty($errors['password']) && empty($errors['password_check'])) {
        $errors['password_check_match'] = $_SESSION['errors']['password_check_match'];
        $password = $_SESSION['password'];
        $password_check = $_SESSION['password_check'];
    } else {
        $password = $_SESSION['password'];
        $password_check = $_SESSION['password_check'];
    }

    // メールアドレスのエラーチェック
    if (!empty($_SESSION['errors']['mail'])) {
        $errors['mail'] = $_SESSION['errors']['mail'];
        $mail = $_SESSION['mail'];
    } else {
        $mail = $_SESSION['mail'];
    }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>会員登録フォーム</title>
    <link rel="stylesheet" href="https://unpkg.com/modern-css-reset/dist/reset.min.css" />
    <link rel="stylesheet" href="index.css">
</head>

<body>
    <h1>会員情報登録フォーム</h1>
    <form action="confirm.php" method="post">
        <div class="name">
            <p>氏名</p>
            <label for="first_name">姓</label>
            <input 
                type="text"
                name="first_name"
                value="<?php if (!empty($first_name)) {echo htmlspecialchars($first_name);} ?>"
            >
            <label for="last_name">名</label>
            <input 
                type="text"
                name="last_name"
                value="<?php if (!empty($last_name)) {echo htmlspecialchars($last_name);} ?>"
            >
        </div>
        <div class="error" style="color: red";>
            <?php 
                if (!empty($errors['first_name'])) {
                    echo $errors['first_name']."<br>";
                }
                if (!empty($errors['first_name_character'])) {
                    echo $errors['first_name_character']."<br>";
                }
                if (!empty($errors['last_name'])) {
                    echo $errors['last_name']."<br>";
                }
                if (!empty($errors['last_name_character'])) {
                    echo $errors['last_name_character'];
                }
            ?>
        </div>
        <div class="gender">
            <p>性別</p>
            <input 
                type="hidden"
                name="gender" 
                value="<?php if (!empty($gender)) {echo htmlspecialchars($gender);} ?>"
            >
            <input type="radio" name="gender" value="男性" 
                <?php if (!empty($gender) && $gender === '男性') {echo 'checked';} ?>
            >
            <label>男性</label>
            <input type="radio" name="gender" value="女性"
                <?php if (!empty($gender) && $gender === '女性') {echo 'checked';} ?>
            >
            <label for="女性">女性</label>
        </div>
        <div class="error">
            <?php 
                if (!empty($errors['gender'])) {
                    echo $errors['gender'];
                }
            ?>
        </div>
        <div class="address">
            <p>住所</p>
            <div class="address_input">
                <div class="prefecture">
                    <label for="prefecture">都道府県</label>
                    <select name="prefecture">
                        <option value="" selected>選択してください</option>
                        <option value="北海道" <?php if (!empty($prefecture) && $prefecture === '北海道') {echo 'selected';} ?>>北海道</option>
                        <option value="青森県" <?php if (!empty($prefecture) && $prefecture === '青森県') {echo 'selected';} ?>>青森県</option>
                        <option value="岩手県" <?php if (!empty($prefecture) && $prefecture === '岩手県') {echo 'selected';} ?>>岩手県</option>
                        <option value="宮城県" <?php if (!empty($prefecture) && $prefecture === '宮城県') {echo 'selected';} ?>>宮城県</option>
                        <option value="秋田県" <?php if (!empty($prefecture) && $prefecture === '秋田県') {echo 'selected';} ?>>秋田県</option>
                        <option value="山形県" <?php if (!empty($prefecture) && $prefecture === '山形県') {echo 'selected';} ?>>山形県</option>
                        <option value="福島県" <?php if (!empty($prefecture) && $prefecture === '福島県') {echo 'selected';} ?>>福島県</option>
                        <option value="茨城県" <?php if (!empty($prefecture) && $prefecture === '茨城県') {echo 'selected';} ?>>茨城県</option>
                        <option value="栃木県" <?php if (!empty($prefecture) && $prefecture === '栃木県') {echo 'selected';} ?>>栃木県</option>
                        <option value="群馬県" <?php if (!empty($prefecture) && $prefecture === '群馬県') {echo 'selected';} ?>>群馬県</option>
                        <option value="埼玉県" <?php if (!empty($prefecture) && $prefecture === '埼玉県') {echo 'selected';} ?>>埼玉県</option>
                        <option value="千葉県" <?php if (!empty($prefecture) && $prefecture === '千葉県') {echo 'selected';} ?>>千葉県</option>
                        <option value="東京都" <?php if (!empty($prefecture) && $prefecture === '東京都') {echo 'selected';} ?>>東京都</option>
                        <option value="神奈川県" <?php if (!empty($prefecture) && $prefecture === '神奈川県') {echo 'selected';} ?>>神奈川県</option>
                        <option value="新潟県" <?php if (!empty($prefecture) && $prefecture === '新潟県') {echo 'selected';} ?>>新潟県</option>
                        <option value="富山県" <?php if (!empty($prefecture) && $prefecture === '富山県') {echo 'selected';} ?>>富山県</option>
                        <option value="石川県" <?php if (!empty($prefecture) && $prefecture === '石川県') {echo 'selected';} ?>>石川県</option>
                        <option value="福井県" <?php if (!empty($prefecture) && $prefecture === '福井県') {echo 'selected';} ?>>福井県</option>
                        <option value="山梨県" <?php if (!empty($prefecture) && $prefecture === '山梨県') {echo 'selected';} ?>>山梨県</option>
                        <option value="長野県" <?php if (!empty($prefecture) && $prefecture === '長野県') {echo 'selected';} ?>>長野県</option>
                        <option value="岐阜県" <?php if (!empty($prefecture) && $prefecture === '岐阜県') {echo 'selected';} ?>>岐阜県</option>
                        <option value="静岡県" <?php if (!empty($prefecture) && $prefecture === '静岡県') {echo 'selected';} ?>>静岡県</option>
                        <option value="愛知県" <?php if (!empty($prefecture) && $prefecture === '愛知県') {echo 'selected';} ?>>愛知県</option>
                        <option value="三重県" <?php if (!empty($prefecture) && $prefecture === '三重県') {echo 'selected';} ?>>三重県</option>
                        <option value="滋賀県" <?php if (!empty($prefecture) && $prefecture === '滋賀県') {echo 'selected';} ?>>滋賀県</option>
                        <option value="京都府" <?php if (!empty($prefecture) && $prefecture === '京都府') {echo 'selected';} ?>>京都府</option>
                        <option value="大阪府" <?php if (!empty($prefecture) && $prefecture === '大阪府') {echo 'selected';} ?>>大阪府</option>
                        <option value="兵庫県" <?php if (!empty($prefecture) && $prefecture === '兵庫県') {echo 'selected';} ?>>兵庫県</option>
                        <option value="奈良県" <?php if (!empty($prefecture) && $prefecture === '奈良県') {echo 'selected';} ?>>奈良県</option>
                        <option value="和歌山県" <?php if (!empty($prefecture) && $prefecture === '和歌山県') {echo 'selected';} ?>>和歌山県</option>
                        <option value="鳥取県" <?php if (!empty($prefecture) && $prefecture === '鳥取県') {echo 'selected';} ?>>鳥取県</option>
                        <option value="島根県" <?php if (!empty($prefecture) && $prefecture === '島根県') {echo 'selected';} ?>>島根県</option>
                        <option value="岡山県" <?php if (!empty($prefecture) && $prefecture === '岡山県') {echo 'selected';} ?>>岡山県</option>
                        <option value="広島県" <?php if (!empty($prefecture) && $prefecture === '広島県') {echo 'selected';} ?>>広島県</option>
                        <option value="山口県" <?php if (!empty($prefecture) && $prefecture === '山口県') {echo 'selected';} ?>>山口県</option>
                        <option value="徳島県" <?php if (!empty($prefecture) && $prefecture === '徳島県') {echo 'selected';} ?>>徳島県</option>
                        <option value="香川県" <?php if (!empty($prefecture) && $prefecture === '香川県') {echo 'selected';} ?>>香川県</option>
                        <option value="愛媛県" <?php if (!empty($prefecture) && $prefecture === '愛媛県') {echo 'selected';} ?>>愛媛県</option>
                        <option value="高知県" <?php if (!empty($prefecture) && $prefecture === '高知県') {echo 'selected';} ?>>高知県</option>
                        <option value="福岡県" <?php if (!empty($prefecture) && $prefecture === '福岡県') {echo 'selected';} ?>>福岡県</option>
                        <option value="佐賀県" <?php if (!empty($prefecture) && $prefecture === '佐賀県') {echo 'selected';} ?>>佐賀県</option>
                        <option value="長崎県" <?php if (!empty($prefecture) && $prefecture === '長崎県') {echo 'selected';} ?>>長崎県</option>
                        <option value="熊本県" <?php if (!empty($prefecture) && $prefecture === '熊本県') {echo 'selected';} ?>>熊本県</option>
                        <option value="大分県" <?php if (!empty($prefecture) && $prefecture === '大分県') {echo 'selected';} ?>>大分県</option>
                        <option value="宮崎県" <?php if (!empty($prefecture) && $prefecture === '宮崎県') {echo 'selected';} ?>>宮崎県</option>
                        <option value="鹿児島県" <?php if (!empty($prefecture) && $prefecture === '鹿児島県') {echo 'selected';} ?>>鹿児島県</option>
                        <option value="沖縄県" <?php if (!empty($prefecture) && $prefecture === '沖縄県') {echo 'selected';} ?>>沖縄県</option>
                    </select>
                </div>
                <div class="other_prefecture">
                    <label for="other_prefecture">それ以降の住所</label>
                    <input 
                        type="text" 
                        name="other_prefecture" 
                        value="<?php if (!empty($other_prefecture)) {echo htmlspecialchars($other_prefecture);} ?>"
                    >
                </div>
            </div>
        </div>
        <div class="error">
            <?php 
                if (!empty($errors['prefecture'])) {
                    echo $errors['prefecture']."<br>";
                }
                if (!empty($errors['other_prefecture'])) {
                    echo $errors['other_prefecture'];
                }
            ?>
        </div>
        <div class="password">
            <p>パスワード</p>
            <input 
                type="password" 
                name="password"
                value="<?php if (!empty($password)) {echo htmlspecialchars($password);} ?>"
            >
        </div>
        <div class="error">
            <?php 
                if (!empty($errors['password'])) {
                    echo $errors['password']."<br>";
                }
                if (!empty($errors['password_length'])) {
                    echo $errors['password_length'];
                }
            ?>
        </div>
        <div class="password_check">
            <p>パスワード確認</p>
            <input 
                type="password" 
                name="password_check"
                value="<?php if (!empty($password_check)) {echo htmlspecialchars($password_check);} ?>"
            >
        </div>
        <div class="error">
            <?php 
                if (!empty($errors['password_check'])) {
                    echo $errors['password_check']."<br>";
                }
                if (!empty($errors['password_check_length'])) {
                    echo $errors['password_check_length']."<br>";
                }
                if (!empty($errors['password_check_match'])) {
                    echo $errors['password_check_match'];
                }
            ?>
        </div>
        <div class="mail">
            <p>メールアドレス</p>
            <input 
                type="mail" 
                name="mail"
                value="<?php if (!empty($mail)) {echo htmlspecialchars($mail);} ?>"
            >
        </div>
        <div class="error">
            <?php 
                if (!empty($errors['mail'])) {
                    echo $errors['mail'];
                }
            ?>
        </div>
        <div class="submit">
            <input type="submit" name="confirm" value="確認画面へ" class="button">
        </div>
    </form>
</body>

</html>
