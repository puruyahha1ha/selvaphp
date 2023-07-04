<?php
session_start();



try {
    $dsn = 'mysql:dbname=mysql;host=localhost;charset=utf8;';
    $user = 'root';
    $password = 'kazuto060603';

    $pdo = new PDO($dsn, $user, $password);

    if (!empty($_POST['id'])) {
        $id = $_POST['id'];
    }
    if (!empty($_POST['man'])) {
        $man = $_POST['man'];
    }
    if (!empty($_POST['woman'])) {
        $woman = $_POST['woman'];
    }
    if (!empty($_POST['pref_name'])) {
        $pref_name = $_POST['pref_name'];
    }
    if (!empty($_POST['free_word'])) {
        $free_word = $_POST['free_word'];
    }

    if (!empty($_POST['confirm']) && $_POST['confirm'] === '検索する') {
        // SQL文をセット
        $sql = "SELECT id, name_sei, name_mei, gender, pref_name, address, created_at FROM members WHERE deleted_at IS NULL";
        if (isset($id)) {
            $sql .= " AND id = :id";
        }
        if (isset($man) && isset($woman)) {
            $sql .= " AND (gender = :man OR gender = :woman)";
        } elseif (isset($man) && empty($woman)) {
            $sql .= " AND gender = :man";
        } elseif (empty($man) && isset($woman)) {
            $sql .= " AND gender = :woman";
        }
        if (isset($pref_name)) {
            $sql .= " AND pref_name = :pref_name";
        }
        if (isset($free_word)) {
            $sql .= " AND (name_sei LIKE :free_word OR name_mei LIKE :free_word OR email LIKE :free_word)";
        }
        var_dump($id,$_POST,$sql);
        $prepare = $pdo->prepare($sql);
        if (isset($id)) {
            $prepare->bindValue(':id', $id, PDO::PARAM_INT);
        }
        if (isset($man) && isset($woman)) {
            $prepare->bindValue(':man', $man, PDO::PARAM_STR);
            $prepare->bindValue(':woman', $woman, PDO::PARAM_STR);
        } elseif (isset($man) && empty($woman)) {
            $prepare->bindValue(':man', $man, PDO::PARAM_STR);
        } elseif (empty($man) && isset($woman)) {
            $prepare->bindValue(':woman', $woman, PDO::PARAM_STR);
        }
        if (isset($pref_name)) {
            $prepare->bindValue(':pref_name', $pref_name, PDO::PARAM_STR);
        }
        if (isset($free_word)) {
            $prepare->bindValue(':free_word',  '%'.$free_word.'%', PDO::PARAM_STR);
        }
        $prepare->execute();
        $records = $prepare->fetchAll();
        var_dump($prepare,$records);

    } else {

        $prepare = $pdo->prepare('SELECT id, name_sei, name_mei, gender, pref_name, address, created_at FROM members WHERE deleted_at IS NULL;');
        $prepare->execute();
        $records = $prepare->fetchAll();
    }
    
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
    <link rel="stylesheet" href="https://unpkg.com/modern-css-reset/dist/reset.min.css" />
    <link rel="stylesheet" href="index.css">
    <title>会員一覧</title>
</head>

<body>
    <header>
        <h2>会員一覧</h2>
        <form action="top.php" action="get" class="header_top">
            <input type="submit" name="confirm" value="トップへ戻る" class="button_header">
        </form>
    </header>

    <main>
        <form action="member.php" method="post">
            <div class="search_form">
                <div class="search_row">
                    <p>ID</p>
                    <div class="search_input">
                        <input type="text" name="id" value="">
                    </div>
                </div>
                <div class="search_row">
                    <p>性別</p>
                    <div class="search_input">
                        <input type="checkbox" name="man" value="1"><label for="man">男性</label>
                        <input type="checkbox" name="woman" value="2"><label for="woman">女性</label>
                    </div>
                </div>
                <div class="search_row">
                    <p>都道府県</p>
                    <div class="search_input">
                        <select name="pref_name">
                            <option value="" selected></option>
                            <option value="北海道" <?php if (!empty($posts['pref_name']) && $posts['pref_name'] === '北海道') {
                                                    echo 'selected';
                                                } ?>>北海道</option>
                            <option value="青森県" <?php if (!empty($posts['pref_name']) && $posts['pref_name'] === '青森県') {
                                                    echo 'selected';
                                                } ?>>青森県</option>
                            <option value="岩手県" <?php if (!empty($posts['pref_name']) && $posts['pref_name'] === '岩手県') {
                                                    echo 'selected';
                                                } ?>>岩手県</option>
                            <option value="宮城県" <?php if (!empty($posts['pref_name']) && $posts['pref_name'] === '宮城県') {
                                                    echo 'selected';
                                                } ?>>宮城県</option>
                            <option value="秋田県" <?php if (!empty($posts['pref_name']) && $posts['pref_name'] === '秋田県') {
                                                    echo 'selected';
                                                } ?>>秋田県</option>
                            <option value="山形県" <?php if (!empty($posts['pref_name']) && $posts['pref_name'] === '山形県') {
                                                    echo 'selected';
                                                } ?>>山形県</option>
                            <option value="福島県" <?php if (!empty($posts['pref_name']) && $posts['pref_name'] === '福島県') {
                                                    echo 'selected';
                                                } ?>>福島県</option>
                            <option value="茨城県" <?php if (!empty($posts['pref_name']) && $posts['pref_name'] === '茨城県') {
                                                    echo 'selected';
                                                } ?>>茨城県</option>
                            <option value="栃木県" <?php if (!empty($posts['pref_name']) && $posts['pref_name'] === '栃木県') {
                                                    echo 'selected';
                                                } ?>>栃木県</option>
                            <option value="群馬県" <?php if (!empty($posts['pref_name']) && $posts['pref_name'] === '群馬県') {
                                                    echo 'selected';
                                                } ?>>群馬県</option>
                            <option value="埼玉県" <?php if (!empty($posts['pref_name']) && $posts['pref_name'] === '埼玉県') {
                                                    echo 'selected';
                                                } ?>>埼玉県</option>
                            <option value="千葉県" <?php if (!empty($posts['pref_name']) && $posts['pref_name'] === '千葉県') {
                                                    echo 'selected';
                                                } ?>>千葉県</option>
                            <option value="東京都" <?php if (!empty($posts['pref_name']) && $posts['pref_name'] === '東京都') {
                                                    echo 'selected';
                                                } ?>>東京都</option>
                            <option value="神奈川県" <?php if (!empty($posts['pref_name']) && $posts['pref_name'] === '神奈川県') {
                                                        echo 'selected';
                                                    } ?>>神奈川県</option>
                            <option value="新潟県" <?php if (!empty($posts['pref_name']) && $posts['pref_name'] === '新潟県') {
                                                    echo 'selected';
                                                } ?>>新潟県</option>
                            <option value="富山県" <?php if (!empty($posts['pref_name']) && $posts['pref_name'] === '富山県') {
                                                    echo 'selected';
                                                } ?>>富山県</option>
                            <option value="石川県" <?php if (!empty($posts['pref_name']) && $posts['pref_name'] === '石川県') {
                                                    echo 'selected';
                                                } ?>>石川県</option>
                            <option value="福井県" <?php if (!empty($posts['pref_name']) && $posts['pref_name'] === '福井県') {
                                                    echo 'selected';
                                                } ?>>福井県</option>
                            <option value="山梨県" <?php if (!empty($posts['pref_name']) && $posts['pref_name'] === '山梨県') {
                                                    echo 'selected';
                                                } ?>>山梨県</option>
                            <option value="長野県" <?php if (!empty($posts['pref_name']) && $posts['pref_name'] === '長野県') {
                                                    echo 'selected';
                                                } ?>>長野県</option>
                            <option value="岐阜県" <?php if (!empty($posts['pref_name']) && $posts['pref_name'] === '岐阜県') {
                                                    echo 'selected';
                                                } ?>>岐阜県</option>
                            <option value="静岡県" <?php if (!empty($posts['pref_name']) && $posts['pref_name'] === '静岡県') {
                                                    echo 'selected';
                                                } ?>>静岡県</option>
                            <option value="愛知県" <?php if (!empty($posts['pref_name']) && $posts['pref_name'] === '愛知県') {
                                                    echo 'selected';
                                                } ?>>愛知県</option>
                            <option value="三重県" <?php if (!empty($posts['pref_name']) && $posts['pref_name'] === '三重県') {
                                                    echo 'selected';
                                                } ?>>三重県</option>
                            <option value="滋賀県" <?php if (!empty($posts['pref_name']) && $posts['pref_name'] === '滋賀県') {
                                                    echo 'selected';
                                                } ?>>滋賀県</option>
                            <option value="京都府" <?php if (!empty($posts['pref_name']) && $posts['pref_name'] === '京都府') {
                                                    echo 'selected';
                                                } ?>>京都府</option>
                            <option value="大阪府" <?php if (!empty($posts['pref_name']) && $posts['pref_name'] === '大阪府') {
                                                    echo 'selected';
                                                } ?>>大阪府</option>
                            <option value="兵庫県" <?php if (!empty($posts['pref_name']) && $posts['pref_name'] === '兵庫県') {
                                                    echo 'selected';
                                                } ?>>兵庫県</option>
                            <option value="奈良県" <?php if (!empty($posts['pref_name']) && $posts['pref_name'] === '奈良県') {
                                                    echo 'selected';
                                                } ?>>奈良県</option>
                            <option value="和歌山県" <?php if (!empty($posts['pref_name']) && $posts['pref_name'] === '和歌山県') {
                                                        echo 'selected';
                                                    } ?>>和歌山県</option>
                            <option value="鳥取県" <?php if (!empty($posts['pref_name']) && $posts['pref_name'] === '鳥取県') {
                                                    echo 'selected';
                                                } ?>>鳥取県</option>
                            <option value="島根県" <?php if (!empty($posts['pref_name']) && $posts['pref_name'] === '島根県') {
                                                    echo 'selected';
                                                } ?>>島根県</option>
                            <option value="岡山県" <?php if (!empty($posts['pref_name']) && $posts['pref_name'] === '岡山県') {
                                                    echo 'selected';
                                                } ?>>岡山県</option>
                            <option value="広島県" <?php if (!empty($posts['pref_name']) && $posts['pref_name'] === '広島県') {
                                                    echo 'selected';
                                                } ?>>広島県</option>
                            <option value="山口県" <?php if (!empty($posts['pref_name']) && $posts['pref_name'] === '山口県') {
                                                    echo 'selected';
                                                } ?>>山口県</option>
                            <option value="徳島県" <?php if (!empty($posts['pref_name']) && $posts['pref_name'] === '徳島県') {
                                                    echo 'selected';
                                                } ?>>徳島県</option>
                            <option value="香川県" <?php if (!empty($posts['pref_name']) && $posts['pref_name'] === '香川県') {
                                                    echo 'selected';
                                                } ?>>香川県</option>
                            <option value="愛媛県" <?php if (!empty($posts['pref_name']) && $posts['pref_name'] === '愛媛県') {
                                                    echo 'selected';
                                                } ?>>愛媛県</option>
                            <option value="高知県" <?php if (!empty($posts['pref_name']) && $posts['pref_name'] === '高知県') {
                                                    echo 'selected';
                                                } ?>>高知県</option>
                            <option value="福岡県" <?php if (!empty($posts['pref_name']) && $posts['pref_name'] === '福岡県') {
                                                    echo 'selected';
                                                } ?>>福岡県</option>
                            <option value="佐賀県" <?php if (!empty($posts['pref_name']) && $posts['pref_name'] === '佐賀県') {
                                                    echo 'selected';
                                                } ?>>佐賀県</option>
                            <option value="長崎県" <?php if (!empty($posts['pref_name']) && $posts['pref_name'] === '長崎県') {
                                                    echo 'selected';
                                                } ?>>長崎県</option>
                            <option value="熊本県" <?php if (!empty($posts['pref_name']) && $posts['pref_name'] === '熊本県') {
                                                    echo 'selected';
                                                } ?>>熊本県</option>
                            <option value="大分県" <?php if (!empty($posts['pref_name']) && $posts['pref_name'] === '大分県') {
                                                    echo 'selected';
                                                } ?>>大分県</option>
                            <option value="宮崎県" <?php if (!empty($posts['pref_name']) && $posts['pref_name'] === '宮崎県') {
                                                    echo 'selected';
                                                } ?>>宮崎県</option>
                            <option value="鹿児島県" <?php if (!empty($posts['pref_name']) && $posts['pref_name'] === '鹿児島県') {
                                                        echo 'selected';
                                                    } ?>>鹿児島県</option>
                            <option value="沖縄県" <?php if (!empty($posts['pref_name']) && $posts['pref_name'] === '沖縄県') {
                                                    echo 'selected';
                                                } ?>>沖縄県</option>
                        </select>
                    </div>
                </div>
                <div class="search_row">
                    <p>フリーワード</p>
                    <div class="search_input">
                        <input type="text" name="free_word" value="">
                    </div>
                </div>
            </div>
            <div class="submit">
                <input type="submit" name="confirm" value="検索する" class="button_re">
            </div>
        </form>

        <table>
            <tr>
                <th>ID</th>
                <th>氏名</th>
                <th>性別</th>
                <th>住所</th>
                <th>登録日時</th>
            </tr>
            <?php foreach ($records as $val):?>
                <tr>
                    <td><?php echo $val['id']; ?></td>
                    <td><?php echo $val['name_sei'].'　'.$val['name_mei']; ?></td>
                    <td><?php if ($val['gender'] === '1') {echo '男性';} else {echo '女性';} ?></td>
                    <td><?php echo $val['pref_name'].$val['address']; ?></td>
                    <td><?php echo $val['created_at'] ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    </main>

</body>

</html>