<?php
session_start();
// エラーメッセージの初期化
$errors = [];
if ($_GET['confirm'] === '一覧へ戻る') {
    header('Location: member.php', true, 307);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // ポストデータの取得
    $record = filter_input_array(INPUT_POST, $_POST);

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
    if ($record['name_sei'] === '') {
        $errors['name_sei'] = '※氏名（姓）は必須入力です';
    } elseif (mb_strlen($record['name_sei']) > 20) {
        $errors['name_sei'] = '※氏名(姓)は２０字以内で入力してください';
    }

    // 名のバリデーション
    if ($record['name_mei'] === '') {
        $errors['name_mei'] = '※氏名（名）は必須入力です';
    } elseif (mb_strlen($record['name_mei']) > 20) {
        $errors['name_mei'] = '※氏名(名)は２０字以内で入力してください';
    }

    // 性別のバリデーション
    if (!in_array($record['gender'], $gender_flg)) {
        $errors['gender'] = '※性別を選択してください';
    }

    // 都道府県のバリデーション
    if (!in_array($record['pref_name'], $pref_names)) {
        $errors['pref_name'] = '※都道府県を選択してください';
    }

    // それ以降の住所のバリデーション
    if (mb_strlen($record['address']) > 100) {
        $errors['address'] = '※それ以降の住所は１００字以内で入力してください';
    }

    // パスワードのバリデーション
    if (empty($record['password'])) {
        $errors['password'] = '※パスワードは必須入力です';
    } elseif (!preg_match("/^[a-zA-Z0-9]+$/", $record['password'])) {
        $errors['password'] = '※パスワードは半角英数字のみを入力してください';
    }
    if ((mb_strlen($record['password']) < 8 || mb_strlen($record['password']) > 20) && $errors['password'] !== "※パスワードは必須入力です") {
        $errors['password_length'] = '※パスワードは８〜２０字以内で入力してください';
    }

    // パスワード確認のバリデーション
    if (empty($record['password_check'])) {
        $errors['password_check'] = '※パスワード確認は必須入力です';
    } elseif (!preg_match("/^[a-zA-Z0-9]+$/", $record['password_check'])) {
        $errors['password_check'] = '※パスワード確認は半角英数字のみを入力してください';
    }
    if ((mb_strlen($record['password_check']) < 8 || mb_strlen($record['password_check']) > 20) && $errors['password_check'] !== "※パスワード確認は必須入力です") {
        $errors['password_check_length'] = '※パスワード確認は８〜２０字以内で入力してください';
    }

    // パスワードの一致チェック
    if ($record['password'] !== $record['password_check']) {
        $errors['password_check_match'] = '※パスワードとパスワード確認が異なっています';
    }

    // メールアドレスのバリデーション
    if (empty($record['email'])) {
        $errors['email'] = '※メールアドレスは必須入力です';
    } elseif (mb_strlen($record['email']) > 200) {
        $errors['email'] = '※メールアドレスは２００字以内で入力してください';
    }
    if (!filter_var($record['email'], FILTER_VALIDATE_EMAIL)) {
        $errors['email_filter'] = '※有効なメールアドレスを入力してください';
    }

    if ($record['confirm'] === '前に戻る') {
        // member_confirm.phpからの遷移時は画面を維持
    } elseif (empty($errors)) {
        // エラーの有無チェック
        $_SESSION['confirm'] = '編集';
        header('Location: member_confirm.php', true, 307);
        exit;
    }
}

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
    <title>会員編集ページ</title>
    <link rel="stylesheet" href="https://unpkg.com/modern-css-reset/dist/reset.min.css" />
    <link rel="stylesheet" href="index.css">
</head>

<body>
    <header>
        <h2>会員編集</h2>
        <form action="member.php" action="get" class="header_top">
            <input type="submit" name="confirm" value="一覧へ戻る" class="button_header">
        </form>
    </header>
    <form action="member_edit.php" method="post">
        <div class="regist_form">
            <!-- ID -->
            <div class="id">
                <p>ID</p>
                <input type="text" name="id" value="<?php if (!empty($record['id'])) {echo $record['id'];} ?>">
            </div>
            <!-- 氏名 -->
            <div class="name">
                <p>氏名</p>
                <label for="name_sei">姓</label>
                <input type="text" name="name_sei" value="<?php if (!empty($record['name_sei'])) {
                                                                echo htmlspecialchars($record['name_sei']);
                                                            } ?>">
                <label for="name_mei">名</label>
                <input type="text" name="name_mei" value="<?php if (!empty($record['name_mei'])) {
                                                                echo htmlspecialchars($record['name_mei']);
                                                            } ?>">
            </div>
            <!-- エラーメッセージ -->
            <div class="error">
                <?php
                if (!empty($errors['name_sei'])) {
                    echo $errors['name_sei'] . "<br>";
                }
                if (!empty($errors['name_mei'])) {
                    echo $errors['name_mei'];
                }
                ?>
            </div>
            <!-- 性別 -->
            <div class="gender">
                <p>性別</p>
                <input type="hidden" name="gender" value="<?php if (!empty($record['gender'])) {
                                                                echo htmlspecialchars($record['gender']);
                                                            } ?>">
                <input type="radio" name="gender" value="1" <?php if (!empty($record['gender']) && $record['gender'] === '1') {
                                                                echo 'checked';
                                                            } ?>>
                <label>男性</label>
                <input type="radio" name="gender" value="2" <?php if (!empty($record['gender']) && $record['gender'] === '2') {
                                                                echo 'checked';
                                                            } ?>>
                <label for="女性">女性</label>
            </div>
            <!-- エラーメッセージ -->
            <div class="error">
                <?php
                if (!empty($errors['gender'])) {
                    echo $errors['gender'];
                }
                ?>
            </div>
            <!-- 住所 -->
            <div class="address">
                <p>住所</p>
                <div class="address_input">
                    <div class="pref_name">
                        <label for="pref_name">都道府県</label>
                        <select name="pref_name">
                            <option value="" selected>選択してください</option>
                            <option value="北海道" <?php if (!empty($record['pref_name']) && $record['pref_name'] === '北海道') {
                                                    echo 'selected';
                                                } ?>>北海道</option>
                            <option value="青森県" <?php if (!empty($record['pref_name']) && $record['pref_name'] === '青森県') {
                                                    echo 'selected';
                                                } ?>>青森県</option>
                            <option value="岩手県" <?php if (!empty($record['pref_name']) && $record['pref_name'] === '岩手県') {
                                                    echo 'selected';
                                                } ?>>岩手県</option>
                            <option value="宮城県" <?php if (!empty($record['pref_name']) && $record['pref_name'] === '宮城県') {
                                                    echo 'selected';
                                                } ?>>宮城県</option>
                            <option value="秋田県" <?php if (!empty($record['pref_name']) && $record['pref_name'] === '秋田県') {
                                                    echo 'selected';
                                                } ?>>秋田県</option>
                            <option value="山形県" <?php if (!empty($record['pref_name']) && $record['pref_name'] === '山形県') {
                                                    echo 'selected';
                                                } ?>>山形県</option>
                            <option value="福島県" <?php if (!empty($record['pref_name']) && $record['pref_name'] === '福島県') {
                                                    echo 'selected';
                                                } ?>>福島県</option>
                            <option value="茨城県" <?php if (!empty($record['pref_name']) && $record['pref_name'] === '茨城県') {
                                                    echo 'selected';
                                                } ?>>茨城県</option>
                            <option value="栃木県" <?php if (!empty($record['pref_name']) && $record['pref_name'] === '栃木県') {
                                                    echo 'selected';
                                                } ?>>栃木県</option>
                            <option value="群馬県" <?php if (!empty($record['pref_name']) && $record['pref_name'] === '群馬県') {
                                                    echo 'selected';
                                                } ?>>群馬県</option>
                            <option value="埼玉県" <?php if (!empty($record['pref_name']) && $record['pref_name'] === '埼玉県') {
                                                    echo 'selected';
                                                } ?>>埼玉県</option>
                            <option value="千葉県" <?php if (!empty($record['pref_name']) && $record['pref_name'] === '千葉県') {
                                                    echo 'selected';
                                                } ?>>千葉県</option>
                            <option value="東京都" <?php if (!empty($record['pref_name']) && $record['pref_name'] === '東京都') {
                                                    echo 'selected';
                                                } ?>>東京都</option>
                            <option value="神奈川県" <?php if (!empty($record['pref_name']) && $record['pref_name'] === '神奈川県') {
                                                        echo 'selected';
                                                    } ?>>神奈川県</option>
                            <option value="新潟県" <?php if (!empty($record['pref_name']) && $record['pref_name'] === '新潟県') {
                                                    echo 'selected';
                                                } ?>>新潟県</option>
                            <option value="富山県" <?php if (!empty($record['pref_name']) && $record['pref_name'] === '富山県') {
                                                    echo 'selected';
                                                } ?>>富山県</option>
                            <option value="石川県" <?php if (!empty($record['pref_name']) && $record['pref_name'] === '石川県') {
                                                    echo 'selected';
                                                } ?>>石川県</option>
                            <option value="福井県" <?php if (!empty($record['pref_name']) && $record['pref_name'] === '福井県') {
                                                    echo 'selected';
                                                } ?>>福井県</option>
                            <option value="山梨県" <?php if (!empty($record['pref_name']) && $record['pref_name'] === '山梨県') {
                                                    echo 'selected';
                                                } ?>>山梨県</option>
                            <option value="長野県" <?php if (!empty($record['pref_name']) && $record['pref_name'] === '長野県') {
                                                    echo 'selected';
                                                } ?>>長野県</option>
                            <option value="岐阜県" <?php if (!empty($record['pref_name']) && $record['pref_name'] === '岐阜県') {
                                                    echo 'selected';
                                                } ?>>岐阜県</option>
                            <option value="静岡県" <?php if (!empty($record['pref_name']) && $record['pref_name'] === '静岡県') {
                                                    echo 'selected';
                                                } ?>>静岡県</option>
                            <option value="愛知県" <?php if (!empty($record['pref_name']) && $record['pref_name'] === '愛知県') {
                                                    echo 'selected';
                                                } ?>>愛知県</option>
                            <option value="三重県" <?php if (!empty($record['pref_name']) && $record['pref_name'] === '三重県') {
                                                    echo 'selected';
                                                } ?>>三重県</option>
                            <option value="滋賀県" <?php if (!empty($record['pref_name']) && $record['pref_name'] === '滋賀県') {
                                                    echo 'selected';
                                                } ?>>滋賀県</option>
                            <option value="京都府" <?php if (!empty($record['pref_name']) && $record['pref_name'] === '京都府') {
                                                    echo 'selected';
                                                } ?>>京都府</option>
                            <option value="大阪府" <?php if (!empty($record['pref_name']) && $record['pref_name'] === '大阪府') {
                                                    echo 'selected';
                                                } ?>>大阪府</option>
                            <option value="兵庫県" <?php if (!empty($record['pref_name']) && $record['pref_name'] === '兵庫県') {
                                                    echo 'selected';
                                                } ?>>兵庫県</option>
                            <option value="奈良県" <?php if (!empty($record['pref_name']) && $record['pref_name'] === '奈良県') {
                                                    echo 'selected';
                                                } ?>>奈良県</option>
                            <option value="和歌山県" <?php if (!empty($record['pref_name']) && $record['pref_name'] === '和歌山県') {
                                                        echo 'selected';
                                                    } ?>>和歌山県</option>
                            <option value="鳥取県" <?php if (!empty($record['pref_name']) && $record['pref_name'] === '鳥取県') {
                                                    echo 'selected';
                                                } ?>>鳥取県</option>
                            <option value="島根県" <?php if (!empty($record['pref_name']) && $record['pref_name'] === '島根県') {
                                                    echo 'selected';
                                                } ?>>島根県</option>
                            <option value="岡山県" <?php if (!empty($record['pref_name']) && $record['pref_name'] === '岡山県') {
                                                    echo 'selected';
                                                } ?>>岡山県</option>
                            <option value="広島県" <?php if (!empty($record['pref_name']) && $record['pref_name'] === '広島県') {
                                                    echo 'selected';
                                                } ?>>広島県</option>
                            <option value="山口県" <?php if (!empty($record['pref_name']) && $record['pref_name'] === '山口県') {
                                                    echo 'selected';
                                                } ?>>山口県</option>
                            <option value="徳島県" <?php if (!empty($record['pref_name']) && $record['pref_name'] === '徳島県') {
                                                    echo 'selected';
                                                } ?>>徳島県</option>
                            <option value="香川県" <?php if (!empty($record['pref_name']) && $record['pref_name'] === '香川県') {
                                                    echo 'selected';
                                                } ?>>香川県</option>
                            <option value="愛媛県" <?php if (!empty($record['pref_name']) && $record['pref_name'] === '愛媛県') {
                                                    echo 'selected';
                                                } ?>>愛媛県</option>
                            <option value="高知県" <?php if (!empty($record['pref_name']) && $record['pref_name'] === '高知県') {
                                                    echo 'selected';
                                                } ?>>高知県</option>
                            <option value="福岡県" <?php if (!empty($record['pref_name']) && $record['pref_name'] === '福岡県') {
                                                    echo 'selected';
                                                } ?>>福岡県</option>
                            <option value="佐賀県" <?php if (!empty($record['pref_name']) && $record['pref_name'] === '佐賀県') {
                                                    echo 'selected';
                                                } ?>>佐賀県</option>
                            <option value="長崎県" <?php if (!empty($record['pref_name']) && $record['pref_name'] === '長崎県') {
                                                    echo 'selected';
                                                } ?>>長崎県</option>
                            <option value="熊本県" <?php if (!empty($record['pref_name']) && $record['pref_name'] === '熊本県') {
                                                    echo 'selected';
                                                } ?>>熊本県</option>
                            <option value="大分県" <?php if (!empty($record['pref_name']) && $record['pref_name'] === '大分県') {
                                                    echo 'selected';
                                                } ?>>大分県</option>
                            <option value="宮崎県" <?php if (!empty($record['pref_name']) && $record['pref_name'] === '宮崎県') {
                                                    echo 'selected';
                                                } ?>>宮崎県</option>
                            <option value="鹿児島県" <?php if (!empty($record['pref_name']) && $record['pref_name'] === '鹿児島県') {
                                                        echo 'selected';
                                                    } ?>>鹿児島県</option>
                            <option value="沖縄県" <?php if (!empty($record['pref_name']) && $record['pref_name'] === '沖縄県') {
                                                    echo 'selected';
                                                } ?>>沖縄県</option>
                        </select>
                    </div>
                    <div class="address">
                        <label for="address">それ以降の住所</label>
                        <input type="text" name="address" value="<?php if (!empty($record['address'])) {
                                                                        echo htmlspecialchars($record['address']);
                                                                    } ?>">
                    </div>
                </div>
            </div>
            <!-- エラーメッセージ -->
            <div class="error">
                <?php
                if (!empty($errors['pref_name'])) {
                    echo $errors['pref_name'] . "<br>";
                }
                if (!empty($errors['address'])) {
                    echo $errors['address'];
                }
                ?>
            </div>
            <!-- パスワード -->
            <div class="password_regist">
                <p>パスワード</p>
                <input type="password" name="password" value="<?php if (!empty($record['password'])) {
                                                                    echo htmlspecialchars($record['password']);
                                                                } ?>">
            </div>
            <!-- エラーメッセージ -->
            <div class="error">
                <?php
                if (!empty($errors['password'])) {
                    echo $errors['password'] . "<br>";
                }
                if (!empty($errors['password_length'])) {
                    echo $errors['password_length'];
                }
                ?>
            </div>
            <!-- パスワード確認 -->
            <div class="password_check">
                <p>パスワード確認</p>
                <input type="password" name="password_check" value="<?php if (!empty($record['password'])) {
                                                                        echo htmlspecialchars($record['password']);
                                                                    } ?>">
            </div>
            <!-- エラーメッセージ -->
            <div class="error">
                <?php
                if (!empty($errors['password_check'])) {
                    echo $errors['password_check'] . "<br>";
                }
                if (!empty($errors['password_check_length'])) {
                    echo $errors['password_check_length'] . "<br>";
                }
                if (!empty($errors['password_check_match'])) {
                    echo $errors['password_check_match'];
                }
                ?>
            </div>
            <!-- メールアドレス -->
            <div class="email">
                <p>メールアドレス</p>
                <input type="text" name="email" value="<?php if (!empty($record['email'])) {
                                                            echo htmlspecialchars($record['email']);
                                                        } ?>">
            </div>
            <!-- エラーメッセージ -->
            <div class="error">
                <?php
                if (!empty($errors['email'])) {
                    echo $errors['email'] . "<br>";
                }
                if (!empty($errors['email_filter']) && $errors['email'] !== '※メールアドレスは必須入力です') {
                    echo $errors['email_filter'];
                }
                ?>
            </div>
            <!-- 確認ボタン -->
            <div class="submit">
                <input type="submit" name="confirm" value="確認画面へ" class="button_submit">
            </div>
        </div>
    </form>
</body>

</html>