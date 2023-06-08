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
            <input type="text" name="first_name">
            <label for="last_name">名</label>
            <input type="text" name="last_name">
        </div>
        <div class="gender">
            <p>性別</p>
            <input type="radio" name="gender" value="男性">
            <label>男性</label>
            <input type="radio" name="gender" value="女性">
            <label for="女性">女性</label>
        </div>
        <div class="address">
            <p>住所</p>
            <div class="address_input">
                <div class="prefecture">
                    <label for="prefecture">都道府県</label>
                    <select name="prefecture">
                        <option value="" selected>選択してください</option>
                        <option value="北海道">北海道</option>
                        <option value="青森県">青森県</option>
                        <option value="岩手県">岩手県</option>
                        <option value="宮城県">宮城県</option>
                        <option value="秋田県">秋田県</option>
                        <option value="山形県">山形県</option>
                        <option value="福島県">福島県</option>
                        <option value="茨城県">茨城県</option>
                        <option value="栃木県">栃木県</option>
                        <option value="群馬県">群馬県</option>
                        <option value="埼玉県">埼玉県</option>
                        <option value="千葉県">千葉県</option>
                        <option value="東京都">東京都</option>
                        <option value="神奈川県">神奈川県</option>
                        <option value="新潟県">新潟県</option>
                        <option value="富山県">富山県</option>
                        <option value="石川県">石川県</option>
                        <option value="福井県">福井県</option>
                        <option value="山梨県">山梨県</option>
                        <option value="長野県">長野県</option>
                        <option value="岐阜県">岐阜県</option>
                        <option value="静岡県">静岡県</option>
                        <option value="愛知県">愛知県</option>
                        <option value="三重県">三重県</option>
                        <option value="滋賀県">滋賀県</option>
                        <option value="京都府">京都府</option>
                        <option value="大阪府">大阪府</option>
                        <option value="兵庫県">兵庫県</option>
                        <option value="奈良県">奈良県</option>
                        <option value="和歌山県">和歌山県</option>
                        <option value="鳥取県">鳥取県</option>
                        <option value="島根県">島根県</option>
                        <option value="岡山県">岡山県</option>
                        <option value="広島県">広島県</option>
                        <option value="山口県">山口県</option>
                        <option value="徳島県">徳島県</option>
                        <option value="香川県">香川県</option>
                        <option value="愛媛県">愛媛県</option>
                        <option value="高知県">高知県</option>
                        <option value="福岡県">福岡県</option>
                        <option value="佐賀県">佐賀県</option>
                        <option value="長崎県">長崎県</option>
                        <option value="熊本県">熊本県</option>
                        <option value="大分県">大分県</option>
                        <option value="宮崎県">宮崎県</option>
                        <option value="鹿児島県">鹿児島県</option>
                        <option value="沖縄県">沖縄県</option>
                    </select>
                </div>
                <div class="other_prefecture">
                    <label for="other_prefecture">それ以降の住所</label>
                    <input type="text" name="other_prefecture">
                </div>
            </div>
        </div>
        <div class="password">
            <p>パスワード</p>
            <input type="password" name="password">
        </div>
        <div class="password_confirm">
            <p>パスワード確認</p>
            <input type="password" name="password_confirm">
        </div>
        <div class="mail">
            <p>メールアドレス</p>
            <input type="mail" name="mail">
        </div>
        <div class="submit">
            <input type="submit" name="confirm" value="確認画面へ" class="button">
        </div>
    </form>
</body>

</html>
