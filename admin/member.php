<?php
session_start();

if ($_GET['confirm'] == 'トップへ戻る') {
    $_SESSION['search'] = [];
    $_SESSION['ok'] = [];
    header('Location: top.php', true, 307);
    exit;
}
try {
    $dsn = 'mysql:dbname=mysql;host=localhost;charset=utf8;';
    $user = 'root';
    $password = 'kazuto060603';

    $pdo = new PDO($dsn, $user, $password);

    // 表示件数
    $max_view = 10;

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
        // 検索条件を保存
        if (!empty($_POST['id'])) {
            $_SESSION['search']['id'] = $_POST['id'];
        } else {
            $_SESSION['search']['id'] = null;
        }
        if (!empty($_POST['man'])) {
            $_SESSION['search']['man'] = $_POST['man'];
        } else {
            $_SESSION['search']['man'] = null;
        }
        if (!empty($_POST['woman'])) {
            $_SESSION['search']['woman'] = $_POST['woman'];
        } else {
            $_SESSION['search']['woman'] = null;
        }
        if (!empty($_POST['pref_name'])) {
            $_SESSION['search']['pref_name'] = $_POST['pref_name'];
        } else {
            $_SESSION['search']['pref_name'] = null;
        }
        if (!empty($_POST['free_word'])) {
            $_SESSION['search']['free_word'] = $_POST['free_word'];
        } else {
            $_SESSION['search']['free_word'] = null;
        }

        // SQL文を準備
        $sql = "SELECT id, name_sei, name_mei, gender, pref_name, address, created_at FROM members WHERE deleted_at IS NULL";
        $count_sql = "SELECT COUNT(*) AS count FROM members WHERE deleted_at IS NULL";

        if (isset($id)) {
            $sql .= " AND id = :id";
            $count_sql .= " AND id = :id";
        }
        if (isset($man) && isset($woman)) {
            $sql .= " AND (gender = :man OR gender = :woman)";
            $count_sql .= " AND (gender = :man OR gender = :woman)";
        } elseif (isset($man) && empty($woman)) {
            $sql .= " AND gender = :man";
            $count_sql .= " AND gender = :man";
        } elseif (empty($man) && isset($woman)) {
            $sql .= " AND gender = :woman";
            $count_sql .= " AND gender = :woman";
        }
        if (isset($pref_name)) {
            $sql .= " AND pref_name = :pref_name";
            $count_sql .= " AND pref_name = :pref_name";
        }
        if (isset($free_word)) {
            $sql .= " AND (name_sei LIKE :free_word OR name_mei LIKE :free_word OR email LIKE :free_word)";
            $count_sql .= " AND (name_sei LIKE :free_word OR name_mei LIKE :free_word OR email LIKE :free_word)";
        }

        // 初回検索時
        $sql .= " ORDER BY id ASC LIMIT 10";

        $prepare = $pdo->prepare($sql);
        $count = $pdo->prepare($count_sql);

        if (isset($id)) {
            $prepare->bindValue(':id', $id, PDO::PARAM_INT);
            $count->bindValue(':id', $id, PDO::PARAM_INT);
        }
        if (isset($man) && isset($woman)) {
            $prepare->bindValue(':man', $man, PDO::PARAM_STR);
            $prepare->bindValue(':woman', $man, PDO::PARAM_STR);
            $count->bindValue(':man', $woman, PDO::PARAM_STR);
            $count->bindValue(':woman', $woman, PDO::PARAM_STR);
        } elseif (isset($man) && empty($woman)) {
            $prepare->bindValue(':man', $man, PDO::PARAM_STR);
            $count->bindValue(':man', $man, PDO::PARAM_STR);
        } elseif (empty($man) && isset($woman)) {
            $prepare->bindValue(':woman', $woman, PDO::PARAM_STR);
            $count->bindValue(':woman', $woman, PDO::PARAM_STR);
        }
        if (isset($pref_name)) {
            $prepare->bindValue(':pref_name', $pref_name, PDO::PARAM_STR);
            $count->bindValue(':pref_name', $pref_name, PDO::PARAM_STR);
        }
        if (isset($free_word)) {
            $prepare->bindValue(':free_word',  '%' . $free_word . '%', PDO::PARAM_STR);
            $count->bindValue(':free_word',  '%' . $free_word . '%', PDO::PARAM_STR);
        }

        $count->execute();
        $tatal_count = $count->fetch(PDO::FETCH_ASSOC);
        $pages = ceil($tatal_count['count'] / $max_view);

        $prepare->execute();
        $records = $prepare->fetchAll(PDO::FETCH_ASSOC);

        $now = 1;
        $range = 2;

    } elseif (isset($_GET['page_id'])) {
        // ページング押下処理

        $now = $_GET['page_id'];
        if (isset($_GET['id_sort'])) {
            $id_sort = $_GET['id_sort'];
        }
        if (isset($_GET['create_sort'])) {
            $create_sort = $_GET['create_sort'];
        }

        if (isset($_SESSION['search']) || isset($_GET['id_sort']) || isset($_GET['create_sort'])) {

            // SQL文を準備
            $sql = "SELECT id, name_sei, name_mei, gender, pref_name, address, created_at FROM members WHERE deleted_at IS NULL";
            $count_sql = "SELECT COUNT(*) AS count FROM members WHERE deleted_at IS NULL";

            if (isset($_SESSION['search']['id'])) {
                $sql .= " AND id = :id";
                $count_sql .= " AND id = :id";
            }
            if (isset($_SESSION['search']['man']) && isset($_SESSION['search']['woman'])) {
                $sql .= " AND (gender = :man OR gender = :woman)";
                $count_sql .= " AND (gender = :man OR gender = :woman)";
            } elseif (isset($_SESSION['search']['man']) && empty($_SESSION['search']['woman'])) {
                $sql .= " AND gender = :man";
                $count_sql .= " AND gender = :man";
            } elseif (empty($_SESSION['search']['man']) && isset($_SESSION['search']['woman'])) {
                $sql .= " AND gender = :woman";
                $count_sql .= " AND gender = :woman";
            }
            if (isset($_SESSION['search']['pref_name'])) {
                $sql .= " AND pref_name = :pref_name";
                $count_sql .= " AND pref_name = :pref_name";
            }
            if (isset($_SESSION['search']['free_word'])) {
                $sql .= " AND (name_sei LIKE :free_word OR name_mei LIKE :free_word OR email LIKE :free_word)";
                $count_sql .= " AND (name_sei LIKE :free_word OR name_mei LIKE :free_word OR email LIKE :free_word)";
            }

            if (isset($id_sort)) {
                $sql .= " ORDER BY id :id_sort LIMIT :start, :max;";
            } elseif (isset($create_sort)) {
                $sql .= " ORDER BY created_at :create_sort LIMIT :start, :max;";
            } else {
                $sql .= " ORDER BY id ASC LIMIT :start, :max;";
            }

            $prepare = $pdo->prepare($sql);
            $count = $pdo->prepare($count_sql);


            if (isset($_SESSION['search']['id'])) {
                $prepare->bindValue(':id', $_SESSION['search']['id'], PDO::PARAM_INT);
                $count->bindValue(':id', $_SESSION['search']['id'], PDO::PARAM_INT);
            }
            if (isset($_SESSION['search']['man']) && isset($_SESSION['search']['woman'])) {
                $prepare->bindValue(':man', $_SESSION['search']['man'], PDO::PARAM_STR);
                $count->bindValue(':man', $_SESSION['search']['man'], PDO::PARAM_STR);
                $prepare->bindValue(':woman', $_SESSION['search']['woman'], PDO::PARAM_STR);
                $count->bindValue(':woman', $_SESSION['search']['woman'], PDO::PARAM_STR);
            } elseif (isset($_SESSION['search']['man']) && empty($_SESSION['search']['woman'])) {
                $prepare->bindValue(':man', $_SESSION['search']['man'], PDO::PARAM_STR);
                $count->bindValue(':man', $_SESSION['search']['man'], PDO::PARAM_STR);
            } elseif (empty($_SESSION['search']['man']) && isset($_SESSION['search']['woman'])) {
                $prepare->bindValue(':woman', $_SESSION['search']['woman'], PDO::PARAM_STR);
                $count->bindValue(':woman', $_SESSION['search']['woman'], PDO::PARAM_STR);
            }
            if (isset($_SESSION['search']['pref_name'])) {
                $prepare->bindValue(':pref_name', $_SESSION['search']['pref_name'], PDO::PARAM_STR);
                $count->bindValue(':pref_name', $_SESSION['search']['pref_name'], PDO::PARAM_STR);
            }
            if (isset($_SESSION['search']['free_word'])) {
                $prepare->bindValue(':free_word',  '%' . $_SESSION['search']['free_word'] . '%', PDO::PARAM_STR);
                $count->bindValue(':free_word',  '%' . $_SESSION['search']['free_word'] . '%', PDO::PARAM_STR);
            }
            if (isset($id_sort)) {
                $prepare->bindValue(':id_sort', $id_sort, PDO::PARAM_STR);
            }
            if (isset($create_sort)) {
                $prepare->bindValue(':create_sort', $create_sort, PDO::PARAM_STR);
            }
            $prepare->bindValue(':start', ($now - 1) * $max_view, PDO::PARAM_INT);
            $prepare->bindValue(':max', $max_view, PDO::PARAM_INT);
            var_dump($prepare);
            $prepare->execute();
            $records = $prepare->fetchAll(PDO::FETCH_ASSOC);

            $count->execute();
            $tatal_count = $count->fetch(PDO::FETCH_ASSOC);
            $pages = ceil($tatal_count['count'] / $max_view);
        } else {

            $prepare = $pdo->prepare('SELECT id, name_sei, name_mei, gender, pref_name, address, created_at FROM members WHERE deleted_at IS NULL ORDER BY id ASC LIMIT :start, :max;');
            $prepare->bindValue(':start', ($now - 1) * $max_view, PDO::PARAM_INT);
            $prepare->bindValue(':max', $max_view, PDO::PARAM_INT);
            $prepare->execute();
            $records = $prepare->fetchAll(PDO::FETCH_ASSOC);

            $count = $pdo->prepare('SELECT COUNT(*) AS count FROM members WHERE deleted_at IS NULL');
            $count->execute();
            $tatal_count = $count->fetch();
            $pages = ceil($tatal_count['count'] / $max_view);
        }

        if ($now == 1 || $now == $pages) {
            $range = 2;
        } else {
            $range = 1;
        }

        var_dump($tatal_count, $id_sort,$create_sort);
    } else {

        // 初期表示
        $count = $pdo->prepare('SELECT COUNT(*) AS count FROM members WHERE deleted_at IS NULL');
        $count->execute();
        $tatal_count = $count->fetch();
        $pages = ceil($tatal_count['count'] / $max_view);
        $now = 1;

        $prepare = $pdo->prepare('SELECT id, name_sei, name_mei, gender, pref_name, address, created_at FROM members WHERE deleted_at IS NULL ORDER BY id ASC LIMIT :start, :max;');

        $prepare->bindValue(':start', $now - 1, PDO::PARAM_INT);
        $prepare->bindValue(':max', $max_view, PDO::PARAM_INT);
        $prepare->execute();
        $records = $prepare->fetchAll(PDO::FETCH_ASSOC);

        $range = 2;

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
        <form action="member.php" action="get" class="header_top">
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
                        <input type="checkbox" name="man" value="1"><label>男性</label>
                        <input type="checkbox" name="woman" value="2"><label>女性</label>
                    </div>
                </div>
                <div class="search_row">
                    <p>都道府県</p>
                    <div class="search_input">
                        <select name="pref_name">
                            <option value="" selected></option>
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
                <th>ID<a href="./member.php?page_id=1&id_sort=<?php if ($id_sort == "desc") {echo "asc";} else {echo "desc";}?>" class="sort">▼</a></th>
                <th>氏名</th>
                <th>性別</th>
                <th>住所</th>
                <th>登録日時<a href="./member.php?page_id=1&create_sort=<?php if ($create_sort == "desc") {echo "asc";} else {echo "desc";}?>" class="sort">▼</a></th>
            </tr>
            <?php foreach ($records as $val) : ?>
                <tr>
                    <td><?php echo $val['id']; ?></td>
                    <td><?php echo $val['name_sei'] . '　' . $val['name_mei']; ?></td>
                    <td><?php if ($val['gender'] === '1') {
                            echo '男性';
                        } else {
                            echo '女性';
                        } ?></td>
                    <td><?php echo $val['pref_name'] . $val['address']; ?></td>
                    <td><?php echo $val['created_at'] ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
        <div class="under_page">
            <?php if ($now >= 2) : ?>
                <a href="./member.php?page_id=<?php echo ($now - 1); ?>" class="pagenation_prev">前へ＞</a>
            <?php else : ?>
                <span></span>
            <?php endif; ?>

            <?php for ($n = 1; $n <= $pages; $n++) : ?>
                <?php if ($n >= $now - $range && $n <= $now + $range) : ?>
                    <?php if ($n == $now) : ?>
                        <span class='pagenation_now'><?php echo $now; ?></span>
                    <?php else : ?>
                        <a href='./member.php?page_id=<?php echo $n; ?>' class='pagenation'><?php echo $n; ?></a>
                    <?php endif; ?>
                <?php endif; ?>
            <?php endfor; ?>

            <?php if ($now < $pages) : ?>
                <a href="./member.php?page_id=<?php echo ($now + 1); ?>" class="pagenation_next">次へ＞</a>
            <?php else : ?>
                <span></span>
            <?php endif; ?>
        </div>

    </main>

</body>

</html>