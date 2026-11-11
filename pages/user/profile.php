<?php
    $sql = "SELECT * FROM collections WHERE user_id = ?";
    $stmt = $connect->prepare($sql);
    $stmt->execute([$USER['id']]);
    $myCollections = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $myBooks = 0;
    foreach($myCollections as $mc) {
        $sql = "SELECT * FROM collections_books WHERE collection_id = ?";
        $stmt = $connect->prepare($sql);
        $stmt->execute([$mc['id']]);
        $count = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $myBooks += count($count);
    }

    if(isset($_POST['editName'])) {
        if(empty($_POST['newName'])) $errors['editName'] = 'Имя не может быть пустым';
        elseif(mb_strlen($_POST['newName']) > 20) $errors['editName'] = "Имя не должно быть длиннее 20 символов";
        elseif($_POST['newName'] == $USER['name']) $errors['editName'] = 'Имя осталось прежним';
        elseif(empty($errors)) {
            $sql = 'UPDATE users SET name = ? WHERE id = ?';
            $stmt = $connect->prepare($sql);
            $stmt->execute([$_POST['newName'], $USER['id']]);
            echo '<script>location.href="?page=profile"</script>';
        }
    }

    if (isset($_POST['setAvatar'])) {
        if (empty($_FILES['avatar']['name'])) $errors['editAvatar'] = 'Добавьте изображение';
        else {
            $formats = ['jpg', 'jpeg', 'png', 'webp'];
            if (!in_array(pathinfo($_FILES['avatar']['name'], PATHINFO_EXTENSION), $formats)) $errors['editAvatar'] = 'Файл должен быть формата jpg/jpeg, png или webp';
            elseif ($_FILES['avatar']['size'] > 1024 * 1024 * 2) $errors['editAvatar'] = 'Размер изображения не должен превышать 50КБ';
            elseif (empty($errors['editAvatar'])) {
                $path = 'assets/avatars/' . $USER['id'] . '_' . time() . '_' . $_FILES['avatar']['name'];
                if(isset($USER['img']) && file_exists($USER['img'])) unlink($USER['img']);
                if (move_uploaded_file($_FILES['avatar']['tmp_name'], $path)) {
                    $sql = "UPDATE users SET img = ? WHERE id = ?";
                    $stmt = $connect->prepare($sql);
                    $stmt->execute([$path, $USER['id']]);
                } else echo 'ошибка загрузки';
                echo '<script>location.href="?page=profile"</script>';
            }
        }
    }
?>

<body class="profilePage">
    <main class="cont">
        <!-- Профиль -->
        <div class="profileBlock">
            <h1 class="titleBrown desktop">Профиль</h1>
            <div class="profile">
                <a href="?page=profile&avatar" method="post" class="avatar">
                    <?php if ($USER['img']) { ?>
                        <img src="<?= $USER['img'] ?>" alt="">
                    <?php } else { ?>
                        <label class="avatarInput">
                            <p>Добавить<br>фото</p>
                            <p>+</p>
                        </label>
                    <?php } ?>
                </a>

                <div class="profileInfo">
                    <div class="profileName">
                        <h1 class="titleBrown"><?= $USER['name'] ?></h1>
                        <a href="?page=profile&editName">
                            <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <g clip-path="url(#clip0_321_5)">
                                    <path d="M0 15.8333L12.2917 3.54167L16.4583 7.70833L4.16667 20H0V15.8333ZM19.6875 4.47917L17.6562 6.51042L13.4896 2.34375L15.5208 0.3125C15.7292 0.104167 15.9896 0 16.3021 0C16.6146 0 16.875 0.104167 17.0833 0.3125L19.6875 2.91667C19.8958 3.125 20 3.38542 20 3.69792C20 4.01042 19.8958 4.27083 19.6875 4.47917Z" fill="#2C1C0B" />
                                </g>
                                <defs>
                                    <clipPath id="clip0_321_5">
                                        <rect width="20" height="20" fill="white" />
                                    </clipPath>
                                </defs>
                            </svg>
                        </a>
                        <?php if($USER['role'] == 'admin') { ?>
                        <h2>Администратор</h2>
                        <?php } ?>
                    </div>
                    <div class="profileData">
                        <p><span class="desktop">Эл. почта:</span> <?= $USER['email'] ?></p>
                        <p class="desktop tablet">Подборок: <?= count($myCollections) ?></p>
                        <p class="desktop tablet">Книг добавлено: <?= $myBooks ?></p>
                    </div>
                </div>
            </div>

            <form method="post" class="profileOptions cont">
                <?php if($USER['role'] == 'admin') { ?>
                <a href="?page=managePanel" class="button">Панель управления</a>
                <?php } ?>
                <a href="?page=myCollections" class="button">Мои подборки</a>
                <a href="?page=favorites" class="button">Сохраненные подборки</a>
                <a href="?page=myReviews" class="button">Мои рецензии</a>
                <a href="?page=profile&exit" class="redbutton">Выйти</a>
            </form>
        </div>

        <svg class="desktop" width="319" height="319" viewBox="0 0 319 319" fill="none" xmlns="http://www.w3.org/2000/svg">
            <rect width="319" height="319" fill="white" />
            <path
                d="M164.998 62.9128C168.682 58.8891 182.153 46.7329 210.556 46.422C238.441 46.422 280.412 62.476 292.466 67.332V239.138C276.866 233.625 240.052 221.875 209.48 221.741C188.492 221.741 173.829 230.915 164.998 238.955V62.9128ZM273.45 244.507C255.342 243.79 230.388 242.972 208.708 242.89C193.915 242.89 182.507 244.169 174.185 245.636C181.638 239.363 193.273 232.74 209.729 232.74C230.763 232.835 255.782 239.115 273.45 244.507ZM26.5231 67.3314C38.5847 62.4816 80.6497 46.4269 109.18 46.4269C136.985 46.7322 150.358 58.9147 153.998 62.9065V238.981C145.147 230.93 130.399 221.741 109.224 221.741C78.8896 221.874 42.1186 233.624 26.5237 239.137L26.5231 67.3314ZM144.889 245.68C136.404 244.197 124.639 242.889 109.236 242.889C88.1823 242.971 63.5601 243.789 45.5871 244.502C63.2791 239.109 88.3543 232.834 109.514 232.74C125.877 232.74 137.467 239.395 144.889 245.68ZM319 257.531C319 262.394 314.983 266.354 309.823 266.359L159.498 272.578L8.94882 266.354C4.01678 266.354 0 262.394 0 257.531V72.7457C0 67.8641 4.01678 63.8947 8.94882 63.8947H15.5195V251.089C15.9051 254.96 18.9574 256.348 21.3007 256.581C21.7929 256.631 71.6074 254.041 110.29 253.892C141.881 253.892 158.778 260.515 159.487 260.515C160.22 260.515 177.104 253.892 209.707 253.892C247.38 254.041 297.194 256.591 297.684 256.581C301.341 256.534 303.353 253.839 303.465 251.089V63.8947H310.051C314.983 63.8947 318.999 67.8641 318.999 72.7457V257.531H319Z"
                fill="#3C250F" />
        </svg>

    </main>
</body>

</html>

<?php if(isset($_GET['editName'])) { ?>
<div class="modal">
    <form method="post" class="window">
        <a class="close" href="?page=profile"><h3>⨉</h3></a>
        <h1 class="titleBrown">Как к вам обращаться?</h1>
        <div class="field">
            <label>Новое имя:</label>
            <input type="text" name="newName" placeholder="Введите имя..." <?php if(isset($_POST['newName'])) { ?>value="<?= $_POST['newName'] ?>"<?php } else { ?>value="<?= $USER['name'] ?>"<?php } ?>>
            <?php if(isset($errors['editName'])) { ?>
            <p class="fieldError"><?= $errors['editName'] ?></p>
            <?php } ?>
        </div>
        <div class="modalOptions">
            <button type="reset">Сбросить</button>
            <button name="editName">Сохранить</button>
        </div>
    </form>
</div>
<?php } ?>

<?php if(isset($_GET['avatar'])) { ?>
<div class="modal">
    <form method="post" enctype="multipart/form-data" class="window avatarModal">
        <a class="close" href="?page=profile"><h3>⨉</h3></a>
        <h1 class="titleBrown">Ваш портрет</h1>
        <div class="field">
            <label for="newAvatar">Добавьте фото</label>
            <p>Фото не выбрано</p>
            <input type="file" name="avatar" id="newAvatar" hidden>
        </div>
        <div class="modalOptions">
            <a href="?page=profile">Закрыть</a>
            <button name="setAvatar">Сохранить</button>
        </div>
    </form>
</div>
<?php } ?>

<?php if(isset($_GET['exit'])) { ?>
<div class="modal">
    <form method="post" class="window exitModal">
        <a class="close" href="?page=profile"><h3>⨉</h3></a>
        <h1 class="titleBrown">Покинуть библиотеку?</h1>
        <div class="modalOptions">
            <a href="?page=profile">Закрыть</a>
            <button name="exit" class="redbutton">Выйти</button>
        </div>
    </form>
</div>
<?php } ?>