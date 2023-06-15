<header>
        <div class="link">
            <p><?php if (!empty($_SESSION['name_sei'] && !empty($_SESSION['name_mei'])))echo "ようこそ".$_SESSION['name_sei'].$_SESSION['name_nai']."様"?></p>
            <a href="login.php">ログアウト</a>
        </div>
    </header>
