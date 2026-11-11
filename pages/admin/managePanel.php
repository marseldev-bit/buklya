<body>
        <h1 class="titleBrown cont">Панель управления</h1>

        <div class="manageTabs cont">
            <a href="?page=managePanel" <?php if(!isset($_GET['manage'])) { ?>class="active"<?php } ?>>Книги</a>
            <a href="?page=managePanel&manage=collections" <?php if(isset($_GET['manage']) && $_GET['manage'] == 'collections') { ?>class="active"<?php } ?>>Подборки</a>
            <a href="?page=managePanel&manage=userCollections" <?php if(isset($_GET['manage']) && $_GET['manage'] == 'userCollections') { ?>class="active"<?php } ?>>Подборки пользователей</a>
            <a href="?page=managePanel&manage=authors" <?php if(isset($_GET['manage']) && $_GET['manage'] == 'authors') { ?>class="active"<?php } ?>>Авторы</a>
            <a href="?page=managePanel&manage=reviews" <?php if(isset($_GET['manage']) && $_GET['manage'] == 'reviews') { ?>class="active"<?php } ?>>Рецензии</a>
            <a href="?page=managePanel&manage=genres" <?php if(isset($_GET['manage']) && $_GET['manage'] == 'genres') { ?>class="active"<?php } ?>>Жанры</a>
        </div>

        <?php
        if (isset($_GET['manage'])) {
            if ($_GET['manage'] == 'collections') include('pages/admin/manageCollections.php');
            elseif ($_GET['manage'] == 'userCollections') include('pages/admin/manageUserCollections.php');
            elseif ($_GET['manage'] == 'authors') include('pages/admin/manageAuthors.php');
            elseif ($_GET['manage'] == 'reviews') include('pages/admin/manageReviews.php');
            elseif ($_GET['manage'] == 'genres') include('pages/admin/manageGenres.php');
        }
        else include('pages/admin/manageBooks.php');
        ?>
</body>