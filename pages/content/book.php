<?php
$errors = [];
$sql = "SELECT * FROM books WHERE id = ?";
$stmt = $connect->prepare($sql);
$stmt->execute([$_GET['id']]);
$book = $stmt->fetch();

$sql = "SELECT r.*, u.name, u.img FROM reviews r JOIN users u ON r.user_id = u.id WHERE book_id = ? AND status = ?";
$stmt = $connect->prepare($sql);
$stmt->execute([$book['id'], 'Опубликован']);
$reviews = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (count($reviews) == 0) {
    $rate = 'Отсутствует';
} 
else {
    $count = 0;
    $rate = 0;
    foreach ($reviews as $r) {
        $rate += $r['rating'];
        $count += 1;
    }
    $rate /= $count;
}


if (isset($_POST['insert'])) {
    $sql = "INSERT INTO collections_books (collection_id, book_id) VALUES (?, ?)";
    $stmt = $connect->prepare($sql);
    $stmt->execute([$_POST['myCollectionId'], $_GET['insertBook']]);
    echo '<script>location.href="?page=book&id=' . $book['id'] . '"</script>';
}

if (isset($_POST['setReview'])) {
    if (empty($_POST['rating'])) $errors['rating'] = 'Оцените книгу';
    if (empty($_POST['reviewText'])) $errors['text'] = 'Введите текст рецензии';
    elseif (empty($errors)) {
        $sql = "INSERT INTO reviews (user_id, rating, text, book_id) VALUES (?, ?, ?, ?)";
        $stmt = $connect->prepare($sql);
        $stmt->execute([$USER['id'], $_POST['rating'], $_POST['reviewText'], $book['id']]);
        echo '<script>location.href="?page=book&id=' . $book['id'] . '"</script>';
    }
}
?>

<script src="js/book.js" defer></script>

<body class="bookPage">
    <main>
        <!-- Страница одной книги -->
        <h1 class="titleBrown cont"><?= $book['title'] ?></h1>
        <div class="novel cont">
            <img src="<?= $book['img'] ?>" alt="">
            <div class="novelInfo">
                <p><?= $book['annotation'] ?></p>

                <div class="chars">
                    <div class="char">
                        <h2>Автор</h2>
                        <h3><?= $book['author'] ?></h3>
                    </div>

                    <div class="char">
                        <h2>Жанр</h2>
                        <h3><?= $book['genre'] ?></h3>
                    </div>

                    <div class="char">
                        <h2>Год публикации</h2>
                        <h3><?= $book['year'] ?></h3>
                    </div>

                    <div class="char">
                        <h2>Перевод</h2>
                        <h3><?= $book['translate'] ?></h3>
                    </div>

                    <div class="char">
                        <h2>Рейтинг</h2>
                        <div class="rating">
                            <h3><?= $rate ?></h3>
                            <svg width="18" height="17" viewBox="0 0 18 17" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M7.91027 0.690967C8.20962 -0.230344 9.51303 -0.230344 9.81238 0.690967L11.2187 5.01924C11.3526 5.43126 11.7366 5.71022 12.1698 5.71022H16.7208C17.6895 5.71022 18.0923 6.94984 17.3086 7.51924L13.6267 10.1943C13.2762 10.4489 13.1296 10.9003 13.2635 11.3123L14.6698 15.6406C14.9692 16.5619 13.9147 17.328 13.131 16.7586L9.44911 14.0836C9.09863 13.8289 8.62403 13.8289 8.27354 14.0836L4.5917 16.7586C3.80798 17.328 2.7535 16.5619 3.05285 15.6406L4.45919 11.3123C4.59307 10.9003 4.44641 10.4489 4.09592 10.1943L0.414077 7.51924C-0.369637 6.94984 0.0331378 5.71022 1.00186 5.71022H5.55287C5.9861 5.71022 6.37006 5.43126 6.50393 5.01924L7.91027 0.690967Z"
                                    fill="#3C250F" />
                            </svg>
                        </div>
                    </div>
                </div>

                <?php if (isset($USER)) { ?>
                    <div class="novelOptions">
                        <a href="assets/Книга.pdf" target="_blank" class="button">Читать</a>
                        <a href="?page=book&id=<?= $book['id'] ?>&insertBook" class="button">Добавить в подборку</a>
                        <?php
                        $sql = "SELECT * FROM reviews WHERE user_id = ? AND book_id = ?";
                        $stmt = $connect->prepare($sql);
                        $stmt->execute([$USER['id'], $book['id']]);
                        $issetReview = $stmt->fetch();
                        if (empty($issetReview)) {
                        ?>
                            <a href="?page=book&id=<?= $book['id'] ?>&review" class="button">Рецензия</a>
                        <?php } ?>
                    </div>
                <?php } ?>
            </div>
        </div>

        <?php if(count($reviews) > 0) { ?>
        <div class="reviewsHeader cont">
            <h1 class="titleBrown">Рецензии</h1>
            <h2><?= count($reviews) ?></h2>
        </div>

        <div class="reviews cont">
            <?php foreach ($reviews as $r) { ?>
                <div class="review">
                    <div class="reviewHead">
                        <img src="<?= $r['img'] ?>" alt="">
                        <h1><?= $r['name'] ?></h1>
                        <div class="stars">
                            <?php for ($i = 0; $i < $r['rating']; $i += 1) { ?>
                                <svg width="25" height="24" viewBox="0 0 25 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M11.2394 0.690968C11.5387 -0.230343 12.8421 -0.230344 13.1415 0.690967L15.3336 7.43768C15.4675 7.8497 15.8515 8.12866 16.2847 8.12866H23.3786C24.3473 8.12866 24.7501 9.36828 23.9664 9.93768L18.2273 14.1074C17.8768 14.362 17.7301 14.8134 17.864 15.2254L20.0562 21.9721C20.3555 22.8934 19.301 23.6596 18.5173 23.0902L12.7782 18.9205C12.4277 18.6658 11.9531 18.6658 11.6026 18.9205L5.86355 23.0902C5.07984 23.6596 4.02535 22.8934 4.32471 21.9721L6.51685 15.2254C6.65072 14.8134 6.50406 14.362 6.15357 14.1074L0.41448 9.93768C-0.369233 9.36828 0.0335414 8.12866 1.00226 8.12866H8.09618C8.5294 8.12866 8.91336 7.8497 9.04723 7.43768L11.2394 0.690968Z"
                                        fill="white" />
                                </svg>
                            <?php }
                            for ($i = 0; $i < 5 - $r['rating']; $i += 1) { ?>
                                <svg width="25" height="24" viewBox="0 0 25 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <mask id="stroke0_70_1092" fill="white">
                                        <path d="M11.2394 0.690968C11.5387 -0.230343 12.8421 -0.230344 13.1415 0.690967L15.3336 7.43768C15.4675 7.8497 15.8515 8.12866 16.2847 8.12866H23.3786C24.3473 8.12866 24.7501 9.36828 23.9664 9.93768L18.2273 14.1074C17.8768 14.362 17.7301 14.8134 17.864 15.2254L20.0562 21.9721C20.3555 22.8934 19.301 23.6596 18.5173 23.0902L12.7782 18.9205C12.4277 18.6658 11.9531 18.6658 11.6026 18.9205L5.86355 23.0902C5.07984 23.6596 4.02535 22.8934 4.32471 21.9721L6.51685 15.2254C6.65072 14.8134 6.50406 14.362 6.15357 14.1074L0.41448 9.93768C-0.369233 9.36828 0.0335414 8.12866 1.00226 8.12866H8.09618C8.5294 8.12866 8.91336 7.8497 9.04723 7.43768L11.2394 0.690968Z" />
                                    </mask>
                                    <path d="M8.09618 8.12866H1.00226C0.0335415 8.12866 -0.369233 9.36828 0.414481 9.93768L6.15358 14.1074C6.50406 14.362 6.65072 14.8134 6.51685 15.2254L4.32471 21.9721C4.02535 22.8934 5.07984 23.6596 5.86355 23.0902L11.6026 18.9205C11.9531 18.6658 12.4277 18.6658 12.7782 18.9205L18.5173 23.0902C19.301 23.6596 20.3555 22.8934 20.0562 21.9721L17.864 15.2254C17.7301 14.8134 17.8768 14.362 18.2273 14.1074L23.9664 9.93768C24.7501 9.36828 24.3473 8.12866 23.3786 8.12866H16.2847C15.8515 8.12866 15.4675 7.8497 15.3336 7.43768L13.1415 0.690967C12.8421 -0.230344 11.5387 -0.230343 11.2394 0.690968L9.04723 7.43768C8.91336 7.8497 8.5294 8.12866 8.09618 8.12866Z" stroke-width="2" stroke="white" mask="url(#stroke0_70_1092)" />
                                </svg>
                            <?php } ?>
                        </div>
                    </div>

                    <p><?= $r['text'] ?></p>
                </div>
            <?php } ?>
        </div>
        <?php } ?>
    </main>
</body>

<?php if (isset($_GET['insertBook'])) {
    $sql = "SELECT * FROM books WHERE id = ?";
    $stmt = $connect->prepare($sql);
    $stmt->execute([$_GET['id']]);
    $book = $stmt->fetch();

    $sql = "SELECT c.* FROM collections c WHERE c.user_id = ? AND c.id NOT IN (
      SELECT collection_id
      FROM collections_books
      WHERE book_id = ?
    )";
    $stmt = $connect->prepare($sql);
    $stmt->execute([$USER['id'], $book['id']]);
    $myCollections = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
    <div class="modal">
        <form method="post" class="window insertBookModal">
            <a href="?page=book&id=<?= $book['id'] ?>" class="close">
                <h3>⨉</h3>
            </a>
            <h1 class="titleBrown">Добавление книги в подборку</h1>
            <?php if (count($myCollections) == 0) { ?>
                <p class="empty">У Вас нет подборок, в которых не нашлась бы книга «<?= $book['title'] ?>»</p>
                <div class="modalOptions">
                    <a href="?page=book&id=<?= $book['id'] ?>">Закрыть</a>
                    <a href="?page=myCollections">Мои подборки</a>
                </div>
            <?php } else { ?>
                <p>Выберите подборку, в которую хотите добавить книгу «<?= $book['title'] ?>»</p>
                <div class="insertCollections">
                    <?php foreach ($myCollections as $c) { ?>
                        <form method="post">
                            <div class="insertColl">
                                <img src="<?= $c['img'] ?>" alt="">
                                <div class="insertCollInfo">
                                    <h2><?= $c['title'] ?></h2>
                                    <button name="insert" class="button">Добавить</button>
                                    <input type="hidden" name="myCollectionId" value="<?= $c['id'] ?>">
                                </div>
                            </div>
                        </form>
                    <?php } ?>
                </div>
            <?php } ?>
        </form>
    </div>
<?php } ?>

<?php if (isset($_GET['review'])) { ?>
    <div class="modal">
        <form method="post" class="window reviewModal">
            <a href="?page=book&id=<?= $book['id'] ?>" class="close">
                <h3>⨉</h3>
            </a>
            <h1 class="titleBrown">Ваша рецензия</h1>

            <div class="field">
                <h1>Оценка</h1>
                <div class="rating">
                    <svg width="25" height="24" viewBox="0 0 25 24" fill="transparent" xmlns="http://www.w3.org/2000/svg">
                        <path d="M11.2394 0.690968C11.5387 -0.230343 12.8421 -0.230344 13.1415 0.690967L15.3336 7.43768C15.4675 7.8497 15.8515 8.12866 16.2847 8.12866H23.3786C24.3473 8.12866 24.7501 9.36828 23.9664 9.93768L18.2273 14.1074C17.8768 14.362 17.7301 14.8134 17.864 15.2254L20.0562 21.9721C20.3555 22.8934 19.301 23.6596 18.5173 23.0902L12.7782 18.9205C12.4277 18.6658 11.9531 18.6658 11.6026 18.9205L5.86355 23.0902C5.07984 23.6596 4.02535 22.8934 4.32471 21.9721L6.51685 15.2254C6.65072 14.8134 6.50406 14.362 6.15357 14.1074L0.41448 9.93768C-0.369233 9.36828 0.0335414 8.12866 1.00226 8.12866H8.09618C8.5294 8.12866 8.91336 7.8497 9.04723 7.43768L11.2394 0.690968Z" />
                    </svg>

                    <svg width="25" height="24" viewBox="0 0 25 24" fill="transparent" xmlns="http://www.w3.org/2000/svg">
                        <path d="M11.2394 0.690968C11.5387 -0.230343 12.8421 -0.230344 13.1415 0.690967L15.3336 7.43768C15.4675 7.8497 15.8515 8.12866 16.2847 8.12866H23.3786C24.3473 8.12866 24.7501 9.36828 23.9664 9.93768L18.2273 14.1074C17.8768 14.362 17.7301 14.8134 17.864 15.2254L20.0562 21.9721C20.3555 22.8934 19.301 23.6596 18.5173 23.0902L12.7782 18.9205C12.4277 18.6658 11.9531 18.6658 11.6026 18.9205L5.86355 23.0902C5.07984 23.6596 4.02535 22.8934 4.32471 21.9721L6.51685 15.2254C6.65072 14.8134 6.50406 14.362 6.15357 14.1074L0.41448 9.93768C-0.369233 9.36828 0.0335414 8.12866 1.00226 8.12866H8.09618C8.5294 8.12866 8.91336 7.8497 9.04723 7.43768L11.2394 0.690968Z" />
                    </svg>

                    <svg width="25" height="24" viewBox="0 0 25 24" fill="transparent" xmlns="http://www.w3.org/2000/svg">
                        <path d="M11.2394 0.690968C11.5387 -0.230343 12.8421 -0.230344 13.1415 0.690967L15.3336 7.43768C15.4675 7.8497 15.8515 8.12866 16.2847 8.12866H23.3786C24.3473 8.12866 24.7501 9.36828 23.9664 9.93768L18.2273 14.1074C17.8768 14.362 17.7301 14.8134 17.864 15.2254L20.0562 21.9721C20.3555 22.8934 19.301 23.6596 18.5173 23.0902L12.7782 18.9205C12.4277 18.6658 11.9531 18.6658 11.6026 18.9205L5.86355 23.0902C5.07984 23.6596 4.02535 22.8934 4.32471 21.9721L6.51685 15.2254C6.65072 14.8134 6.50406 14.362 6.15357 14.1074L0.41448 9.93768C-0.369233 9.36828 0.0335414 8.12866 1.00226 8.12866H8.09618C8.5294 8.12866 8.91336 7.8497 9.04723 7.43768L11.2394 0.690968Z" />
                    </svg>

                    <svg width="25" height="24" viewBox="0 0 25 24" fill="transparent" xmlns="http://www.w3.org/2000/svg">
                        <path d="M11.2394 0.690968C11.5387 -0.230343 12.8421 -0.230344 13.1415 0.690967L15.3336 7.43768C15.4675 7.8497 15.8515 8.12866 16.2847 8.12866H23.3786C24.3473 8.12866 24.7501 9.36828 23.9664 9.93768L18.2273 14.1074C17.8768 14.362 17.7301 14.8134 17.864 15.2254L20.0562 21.9721C20.3555 22.8934 19.301 23.6596 18.5173 23.0902L12.7782 18.9205C12.4277 18.6658 11.9531 18.6658 11.6026 18.9205L5.86355 23.0902C5.07984 23.6596 4.02535 22.8934 4.32471 21.9721L6.51685 15.2254C6.65072 14.8134 6.50406 14.362 6.15357 14.1074L0.41448 9.93768C-0.369233 9.36828 0.0335414 8.12866 1.00226 8.12866H8.09618C8.5294 8.12866 8.91336 7.8497 9.04723 7.43768L11.2394 0.690968Z" />
                    </svg>

                    <svg width="25" height="24" viewBox="0 0 25 24" fill="transparent" xmlns="http://www.w3.org/2000/svg">
                        <path d="M11.2394 0.690968C11.5387 -0.230343 12.8421 -0.230344 13.1415 0.690967L15.3336 7.43768C15.4675 7.8497 15.8515 8.12866 16.2847 8.12866H23.3786C24.3473 8.12866 24.7501 9.36828 23.9664 9.93768L18.2273 14.1074C17.8768 14.362 17.7301 14.8134 17.864 15.2254L20.0562 21.9721C20.3555 22.8934 19.301 23.6596 18.5173 23.0902L12.7782 18.9205C12.4277 18.6658 11.9531 18.6658 11.6026 18.9205L5.86355 23.0902C5.07984 23.6596 4.02535 22.8934 4.32471 21.9721L6.51685 15.2254C6.65072 14.8134 6.50406 14.362 6.15357 14.1074L0.41448 9.93768C-0.369233 9.36828 0.0335414 8.12866 1.00226 8.12866H8.09618C8.5294 8.12866 8.91336 7.8497 9.04723 7.43768L11.2394 0.690968Z" />
                    </svg>
                    <input type="hidden" name="rating" <?php if (isset($_POST['rating'])) { ?>value="<?= $_POST['rating'] ?>" <?php } ?>>
                </div>
                <?php if (isset($errors['rating'])) { ?>
                    <p class="fieldError"><?= $errors['rating'] ?></p>
                <?php } ?>
            </div>

            <div class="field">
                <h1>Рецензия:</h1>
                <textarea name="reviewText" placeholder="Ваше мнение..."><?= $_POST['text'] ?? '' ?></textarea>
                <?php if (isset($errors['text'])) { ?>
                    <p class="fieldError"><?= $errors['text'] ?></p>
                <?php } ?>
            </div>

            <div class="modalOptions">
                <button type="reset">Сбросить</button>
                <button name="setReview">Поделиться</button>
            </div>
        </form>
    </div>
<?php } ?>