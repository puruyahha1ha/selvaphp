<header>
    <div class="link_header">
        <form action="top.php">
            <p><?php if (!empty($_SESSION['name_sei'] && !empty($_SESSION['name_mei']))) echo "ようこそ" . $_SESSION['name_sei'] . $_SESSION['name_mei'] . "様" ?></p>
            <input type="submit" name="confirm" value="ログアウト" class="button_header">
        </form>
    </div>
</header>
