<?php
session_start();
$_SESSION['confirm'] = '登録';

// エラーメッセージの初期化
$errors = [];
if ($_GET['confirm'] === '一覧へ戻る') {
    header('Location: member.php', true, 307);
    exit;
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // ポストデータの取得
    $posts = filter_input_array(INPUT_POST, $_POST);

    // 性別の配列 (男性の場合は1,女性の場合は2)
    $gender_flg = ['1', '2'];

    // 都道府県の配列
    $pref_names = [
        "北海道", "青森県", "岩手県", "宮城県", "秋田県", "山形県", "福島県",
        "茨城県", "栃木県", "群馬県", "埼玉県", "千葉県", "東京都", "神奈川県", "新潟県", "富山県",
        "石川県", "福井県", "山梨県", "長野県", "岐阜県", "静岡県", "愛知県", "三重県", "滋賀県", "京都府",
        "大阪府", "兵庫県", "奈良県", "和歌山県", "鳥取県", "島根県", "岡山県", "広島県", "山口県", "徳島県",
        "香川県", "愛媛県", "高知県", "福岡県", "佐賀県", "長崎県", "熊本県", "大分県", "宮崎県", "鹿児島県", "沖縄県"
    ];

    // 姓のバリデーション
    if ($posts['name_sei'] === '') {
        $errors['name_sei'] = '※氏名（姓）は必須入力です';
    } elseif (mb_strlen($posts['name_sei']) > 20) {
        $errors['name_sei'] = '※氏名(姓)は２０字以内で入力してください';
    }

    // 名のバリデーション
    if ($posts['name_mei'] === '') {
        $errors['name_mei'] = '※氏名（名）は必須入力です';
    } elseif (mb_strlen($posts['name_mei']) > 20) {
        $errors['name_mei'] = '※氏名(名)は２０字以内で入力してください';
    }

    // 性別のバリデーション
    if (!in_array($posts['gender'], $gender_flg)) {
        $errors['gender'] = '※性別を選択してください';
    }

    // 都道府県のバリデーション
    if (!in_array($posts['pref_name'], $pref_names)) {
        $errors['pref_name'] = '※都道府県を選択してください';
    }

    // それ以降の住所のバリデーション
    if (mb_strlen($posts['address']) > 100) {
        $errors['address'] = '※それ以降の住所は１００字以内で入力してください';
    }

    // パスワードのバリデーション
    if (empty($posts['password'])) {
        $errors['password'] = '※パスワードは必須入力です';
    } elseif (!preg_match("/^[a-zA-Z0-9]+$/", $posts['password'])) {
        $errors['password'] = '※パスワードは半角英数字のみを入力してください';
    }
    if ((mb_strlen($posts['password']) < 8 || mb_strlen($posts['password']) > 20) && $errors['password'] !== "※パスワードは必須入力です") {
        $errors['password_length'] = '※パスワードは８〜２０字以内で入力してください';
    }

    // パスワード確認のバリデーション
    if (empty($posts['password_check'])) {
        $errors['password_check'] = '※パスワード確認は必須入力です';
    } elseif (!preg_match("/^[a-zA-Z0-9]+$/", $posts['password_check'])) {
        $errors['password_check'] = '※パスワード確認は半角英数字のみを入力してください';
    }
    if ((mb_strlen($posts['password_check']) < 8 || mb_strlen($posts['password_check']) > 20) && $errors['password_check'] !== "※パスワード確認は必須入力です") {
        $errors['password_check_length'] = '※パスワード確認は８〜２０字以内で入力してください';
    }

    // パスワードの一致チェック
    if ($posts['password'] !== $posts['password_check']) {
        $errors['password_check_match'] = '※パスワードとパスワード確認が異なっています';
    }

    // メールアドレスのバリデーション
    if (empty($posts['email'])) {
        $errors['email'] = '※メールアドレスは必須入力です';
    } elseif (mb_strlen($posts['email']) > 200) {
        $errors['email'] = '※メールアドレスは２００字以内で入力してください';
    }
    if (!filter_var($posts['email'], FILTER_VALIDATE_EMAIL)) {
        $errors['email_filter'] = '※有効なメールアドレスを入力してください';
    }

    if ($posts['confirm'] === '前に戻る') {
        // member_confirm.phpからの遷移時は画面を維持
    } elseif (empty($errors)) {
        // エラーの有無チェック
        header('Location: member_confirm.php', true, 307);
        exit;
    }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>会員登録ページ</title>
    <link rel="stylesheet" href="https://unpkg.com/modern-css-reset/dist/reset.min.css" />
    <link rel="stylesheet" href="index.css">
</head>

<?php require_once("templete.php"); ?>

</html>