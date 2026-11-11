<?php
$sql = "SELECT * FROM books";
$stmt = $connect->prepare($sql);
$stmt->execute([]);
$books = $stmt->fetchAll(PDO::FETCH_ASSOC);

$sql = "SELECT * FROM genres";
$stmt = $connect->prepare($sql);
$stmt->execute([]);
$genres = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (isset($_POST['insert'])) {
    $sql = "INSERT INTO collections_books (collection_id, book_id) VALUES (?, ?)";
    $stmt = $connect->prepare($sql);
    $stmt->execute([$_POST['myCollectionId'], $_GET['insertBook']]);
    echo '<script>location.href="?page=books"</script>';
}
?>

<body class="booksPage">
    <main>
        <!-- Каталог книг -->
        <div class="booksHeader catHeader cont">
            <h1 class="titleBrown">Книги</h1>

            <div class="filters">
                <input type="text" placeholder="Название книги...">
                <select>
                    <option disabled selected>Жанр</option>
                    <?php foreach ($genres as $g) { ?>
                        <option><?= $g['name'] ?></option>
                    <?php } ?>
                </select>
                <a href="#">Применить</a>
            </div>
        </div>

        <div class="books cont">
            <?php foreach ($books as $b) {
                $sql = "SELECT * FROM reviews WHERE book_id = ? AND status = ?";
                $stmt = $connect->prepare($sql);
                $stmt->execute([$b['id'], 'Опубликован']);
                $reviews = $stmt->fetchAll(PDO::FETCH_ASSOC);
                if (count($reviews) > 0) {
                    $count = 0;
                    $rate = 0;
                    foreach ($reviews as $r) {
                        $rate += $r['rating'];
                        $count += 1;
                    }
                    $rate /= $count;
                }
            ?>
                <div class="book">
                    <a href="?page=book&id=<?= $b['id'] ?>" class="bookInfo">
                        <img src="<?= $b['img'] ?>" alt="">
                        <h1><?= $b['title'] ?></h1>
                        <p><?= $b['author'] ?></p>

                        <?php if (count($reviews) > 0) { ?>
                            <div class="bookSocial">
                                <div class="bookRating">
                                    <h2><?= $rate ?></h2>
                                    <svg width="18" height="17" viewBox="0 0 18 17" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M7.91027 0.690967C8.20962 -0.230344 9.51303 -0.230344 9.81238 0.690967L11.2187 5.01924C11.3526 5.43126 11.7366 5.71022 12.1698 5.71022H16.7208C17.6895 5.71022 18.0923 6.94984 17.3086 7.51924L13.6267 10.1943C13.2762 10.4489 13.1296 10.9003 13.2635 11.3123L14.6698 15.6406C14.9692 16.5619 13.9147 17.328 13.131 16.7586L9.44911 14.0836C9.09863 13.8289 8.62403 13.8289 8.27354 14.0836L4.5917 16.7586C3.80798 17.328 2.7535 16.5619 3.05285 15.6406L4.45919 11.3123C4.59307 10.9003 4.44641 10.4489 4.09592 10.1943L0.414077 7.51924C-0.369637 6.94984 0.0331378 5.71022 1.00186 5.71022H5.55287C5.9861 5.71022 6.37006 5.43126 6.50393 5.01924L7.91027 0.690967Z"
                                            fill="#3C250F" />
                                    </svg>
                                </div>

                                <div class="bookReviews">
                                    <h2><?= $count ?></h2>
                                    <svg width="23" height="23" viewBox="0 0 23 23" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M11.5 2.15625C5.95144 2.15625 1.4375 5.93041 1.4375 10.5696C1.46301 11.7825 1.76987 12.9729 2.33389 14.047C2.89791 15.1211 3.70368 16.0495 4.68769 16.759C4.3628 17.7682 3.8796 18.7193 3.25615 19.5768C3.19617 19.6689 3.15799 19.7735 3.1445 19.8827C3.131 19.9918 3.14256 20.1025 3.17829 20.2065C3.21402 20.3105 3.27298 20.4049 3.35069 20.4827C3.4284 20.5604 3.52282 20.6195 3.62676 20.6553C5.56393 21.489 7.90759 19.6545 8.55729 18.6089C9.51857 18.8568 10.5073 18.9824 11.5 18.9826C17.0486 18.9826 21.5625 15.2085 21.5625 10.5696C21.5625 5.93041 17.0486 2.15625 11.5 2.15625ZM15.8125 12.9375H7.1875C6.99688 12.9375 6.81406 12.8618 6.67927 12.727C6.54448 12.5922 6.46875 12.4094 6.46875 12.2188C6.46875 12.0281 6.54448 11.8453 6.67927 11.7105C6.81406 11.5757 6.99688 11.5 7.1875 11.5H15.8125C16.0031 11.5 16.1859 11.5757 16.3207 11.7105C16.4555 11.8453 16.5313 12.0281 16.5313 12.2188C16.5313 12.4094 16.4555 12.5922 16.3207 12.727C16.1859 12.8618 16.0031 12.9375 15.8125 12.9375ZM15.8125 9.34375H7.1875C6.99688 9.34375 6.81406 9.26802 6.67927 9.13323C6.54448 8.99844 6.46875 8.81562 6.46875 8.625C6.46875 8.43438 6.54448 8.25156 6.67927 8.11677C6.81406 7.98198 6.99688 7.90625 7.1875 7.90625H15.8125C16.0031 7.90625 16.1859 7.98198 16.3207 8.11677C16.4555 8.25156 16.5313 8.43438 16.5313 8.625C16.5313 8.81562 16.4555 8.99844 16.3207 9.13323C16.1859 9.26802 16.0031 9.34375 15.8125 9.34375Z"
                                            fill="#3C250F" />
                                    </svg>
                                </div>
                            </div>
                        <?php } ?>
                    </a>

                    <div class="bookOptions">
                        <a <?php if (!isset($USER)) { ?>href="?page=books&meet" <?php } else { ?>href="assets/Книга.pdf" target="_blank" <?php } ?>>Читать</a>
                        <a <?php if (!isset($USER)) { ?>href="?page=books&meet" <?php } else { ?>href="?page=main&insertBook=<?= $b['id'] ?>" <?php } ?>><svg width="34" height="34" viewBox="0 0 34 34" fill="white"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M5.09998 0V34L17 28.9L28.9 34V0H5.09998ZM22.5125 15.7376L16.9999 21.25L11.4875 15.7376C9.96516 14.2153 9.96516 11.7473 11.4875 10.225C13.0096 8.70287 15.4777 8.70274 16.9999 10.225C18.5222 8.70287 20.9903 8.70274 22.5125 10.225C24.0347 11.7473 24.0347 14.2153 22.5125 15.7376Z" />
                            </svg>
                        </a>
                    </div>
                </div>
            <?php } ?>
        </div>
    </main>
</body>

<?php if (isset($_GET['insertBook'])) {
    $sql = "SELECT * FROM books WHERE id = ?";
    $stmt = $connect->prepare($sql);
    $stmt->execute([$_GET['insertBook']]);
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
            <a href="?page=books" class="close">
                <h3>⨉</h3>
            </a>
            <h1 class="titleBrown">Добавление книги в подборку</h1>
            <?php if (count($myCollections) == 0) { ?>
                <p class="empty">У Вас нет подборок, в которых не нашлась бы книга «<?= $book['title'] ?>»</p>
                <div class="modalOptions">
                    <a href="?page=books">Закрыть</a>
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

<?php if (isset($_GET['meet'])) { ?>
    <div class="modal">
        <div class="window meetModal">
            <a href="?page=books" class="close">
                <h3>⨉</h3>
            </a>
            <h1 class="titleBrown">Давайте сначала познакомимся</h1>
            <p>Чтобы разблокировать все возможности сайта, нужно зарегистрироваться или войти в аккаунт</p>
            <div class="modalOptions">
                <a href="?page=reg">Регистрация</a>
                <a href="?page=login">Войти</a>
            </div>
        </div>
    </div>
<?php } ?>