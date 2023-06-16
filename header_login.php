<header>
    <div class="link_header">
        <?php if (!empty($_SESSION['name_sei'] && !empty($_SESSION['name_mei']))) echo "<p>ようこそ" . $_SESSION['name_sei'] . $_SESSION['name_mei'] . "様</p>" ?>
        <form action="top.php" method="get">
            <input type="submit" name="confirm" value="新規スレッド作成" class="button_header">
            <input type="submit" name="confirm" value="ログアウト" class="button_header">
        </form>
    </div>
</header>
