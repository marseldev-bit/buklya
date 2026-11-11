<?php
    $sql = "SELECT * FROM books";
    $stmt = $connect->prepare($sql);
    $stmt->execute([]);
    $books = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if(isset($_POST['deleteBook'])) {
        $sql = "SELECT * FROM books WHERE id = ?";
        $stmt = $connect->prepare($sql);
        $stmt->execute([$_GET['delete']]);
        $book = $stmt->fetch();
        unlink($book['img']);

        $sql = "DELETE FROM books WHERE id = ?";
        $stmt = $connect->prepare($sql);
        $stmt->execute([$_GET['delete']]);
        echo '<script>location.href="?page=managePanel"</script>';
    }
?>

<main class="manageBooksPage">
    <div class="catHeader">
        <div class="filters cont">
            <input type="number" placeholder="ID книги...">
            <input type="text" placeholder="Название книги...">
            <a href="#">Применить</a>
            <a href="?page=addBook">Добавить книгу</a>
        </div>
    </div>

    <div class="books cont">
        <?php foreach($books as $b) { ?>
        <div class="book">
            <a href="?page=book&id=<?= $b['id'] ?>" class="bookInfo">
                <img src="<?= $b['img'] ?>" alt="">
                <h1><?= $b['title'] ?></h1>
                <p><?= $b['author'] ?></p>
            </a>

            <div class="bookOptions">
                <a href="?page=editBook&id=<?= $b['id'] ?>">Редактировать</a>
                <a href="?page=managePanel&delete=<?= $b['id'] ?>"><svg width="39" height="39" viewBox="0 0 39 39" fill="#ED0000"
                        xmlns="http://www.w3.org/2000/svg">
                        <g clip-path="url(#clip0_93_586)">
                            <path
                                d="M17.548 0.0305176C14.3399 0.0305176 11.705 2.68061 11.705 5.88867V7.83128H1.94263C0.865556 7.83516 -0.00417438 8.71206 1.50719e-05 9.78913C0.00199554 10.8617 0.869974 11.7314 1.94255 11.7355V11.7317H37.0574V11.7355C38.13 11.7317 38.9979 10.8618 39 9.78913C39.0039 8.71206 38.1345 7.83554 37.0574 7.83136H27.2951V5.88874C27.2951 2.68069 24.6602 0.0305176 21.4521 0.0305176H17.548ZM17.548 3.9309H21.4521C22.5523 3.9309 23.41 4.78852 23.41 5.88867V7.83128H15.6053V5.88874C15.6053 4.78859 16.4477 3.9309 17.548 3.9309ZM4.31942 15.632L5.5726 33.2713C5.80142 36.4655 8.50262 38.9695 11.705 38.9695H27.2951C30.4976 38.9695 33.1988 36.4655 33.4276 33.2713L34.696 15.632H4.31942Z" />
                        </g>
                        <defs>
                            <clipPath id="clip0_93_586">
                                <rect width="39" height="39" fill="white" />
                            </clipPath>
                        </defs>
                    </svg>
                </a>
            </div>
        </div>
        <?php } ?>
    </div>
</main>

<?php
    if(isset($_GET['delete'])) {
        $sql = "SELECT * FROM books WHERE id = ?";
        $stmt = $connect->prepare($sql);
        $stmt->execute([$_GET['delete']]);
        $book = $stmt->fetch();
?>
<div class="modal">
    <form method="post" class="window deleteGenreModal">
        <a class="close" href="?page=managePanel"><h3>⨉</h3></a>
        <h1 class="titleBrown">Удаление книги</h1>
        <p>Удалить книгу «<?= $book['title'] ?>»?</p>
        <div class="modalOptions">
            <a href="?page=managePanel">Закрыть</a>
            <button name="deleteBook" class="redbutton">Удалить</button>
        </div>
    </form>
</div>
<?php } ?>