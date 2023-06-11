<?php 
    session_start();

    // フォームデータの取得
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $gender = $_POST['gender'];
    $prefecture = $_POST['prefecture'];
    $other_prefecture = $_POST['other_prefecture'];
    $password = $_POST['password'];
    $password_check = $_POST['password_check'];
    $mail = $_POST['mail'];

    // 入力データのバリデーション
    $errors = [];
    // 性別の配列
    $genders = ["男性","女性"];
    // 都道府県の配列
    $prefectures = ["北海道","青森県","岩手県","宮城県","秋田県","山形県","福島県",
    "茨城県","栃木県","群馬県","埼玉県","千葉県","東京都","神奈川県","新潟県","富山県",
    "石川県","福井県","山梨県","長野県","岐阜県","静岡県","愛知県","三重県","滋賀県","京都府",
    "大阪府","兵庫県","奈良県","和歌山県","鳥取県","島根県","岡山県","広島県","山口県","徳島県",
    "香川県","愛媛県","高知県","福岡県","佐賀県","長崎県","熊本県","大分県","宮崎県","鹿児島県","沖縄県"
    ];

    // 姓のバリデーション
    if (empty($first_name) || mb_strlen(trim($first_name)) === 0) {
        $errors['first_name'] = '※氏名（姓）は必須入力です';
    } elseif (mb_strlen($first_name) > 20) {
        $errors['first_name'] = '※氏名(姓)は２０字以内で入力してください';
    }
    if (!preg_match("/^[ぁ-んァ-ヶー一-龠]+$/", $first_name)) {
        $errors['first_name_character'] = '※氏名（姓）は記号以外の全角文字を入力してください';
    }

    // 名のバリデーション
    if (empty($last_name) || mb_strlen(trim($last_name)) === 0) {
        $errors['last_name'] = '※氏名（名）は必須入力です';
    } elseif (mb_strlen($last_name) > 20) {
        $errors['last_name'] = '※氏名(名)は２０字以内で入力してください';
    }
    if (!preg_match("/^[ぁ-んァ-ヶー一-龠]+$/", $last_name)) {
        $errors['last_name_character'] = '※氏名（名）は記号以外の全角文字を入力してください';
    }

    // 性別のバリデーション
    if (!in_array($gender, $genders)) {
        $errors['gender'] = '※性別を選択してください';
    }

    // 都道府県のバリデーション
    if (empty($prefecture) || !in_array($prefecture, $prefectures)) {
        $errors['prefecture'] = '※都道府県を選択してください';
    }

    // それ以降の住所のバリデーション
    if (mb_strlen($other_prefecture) > 100) {
        $errors['other_prefecture'] = '※それ以降の住所は１００字以内で入力してください';
    }

    // パスワードのバリデーション
    if (empty($password)) {
        $errors['password'] = '※パスワードは必須入力です';
    } elseif (!preg_match("/^[a-zA-Z0-9]+$/", $password)) {
        $errors['password'] = '※パスワードは半角英数字のみを入力してください';
    }
    if (mb_strlen($password) < 8 || mb_strlen($password) > 20) {
        $errors['password_length'] = '※パスワードは８〜２０字以内で入力してください';
    }

    // パスワード確認のバリデーション
    if (empty($password_check)) {
        $errors['password_check'] = '※パスワード確認は必須入力です';
    } elseif (!preg_match("/^[a-zA-Z0-9]+$/", $password_check)) {
        $errors['password_check'] = '※パスワード確認は半角英数字のみを入力してください';
    }
    if (mb_strlen($password_check) < 8 || mb_strlen($password_check) > 20) {
        $errors['password_check_length'] = '※パスワード確認は８〜２０字以内で入力してください';
    }

    // パスワードの一致チェック
    if ($password !== $password_check) {
        $errors['password_check_match'] = '※パスワードとパスワード確認が異なっています';
    }
    
    // メールアドレスのバリデーション
    if (empty($mail)) {
        $errors['mail'] = '※メールアドレスは必須入力です';
    } elseif (mb_strlen($mail) > 200) {
        $errors['mail'] = '※メールアドレスは２００字以内で入力してください';
    }
    if (!filter_var($mail, FILTER_VALIDATE_EMAIL)) {
        $errors['mail_filter'] = '※有効なメールアドレスを入力してください。';
    }
    

    // エラーの有無チェック
    if (!empty($errors)) {
        $_SESSION['errors'] = $errors;
        $_SESSION['back'] = 'back';
        $_SESSION['first_name'] = $first_name;
        $_SESSION['last_name'] = $last_name;
        $_SESSION['gender'] = $gender;
        $_SESSION['prefecture'] = $prefecture;
        $_SESSION['other_prefecture'] = $other_prefecture;
        $_SESSION['password'] = $password;
        $_SESSION['password_check'] = $password_check;
        $_SESSION['mail'] = $mail;


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
