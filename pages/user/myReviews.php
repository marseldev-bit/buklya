<?php
    $sql = "SELECT * FROM reviews WHERE user_id = ?";
    $stmt = $connect->prepare($sql);
    $stmt->execute([$USER['id']]);
    $reviews = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $sql = "SELECT b.* FROM books b JOIN reviews r ON b.id = r.book_id WHERE user_id = ?";
    $stmt = $connect->prepare($sql);
    $stmt->execute([$USER['id']]);
    $books = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if(isset($_POST['deleteReview'])) {
        $sql = "DELETE FROM reviews WHERE user_id = ? AND book_id = ?";
        $stmt = $connect->prepare($sql);
        $stmt->execute([$USER['id'], $_GET['delete']]);
        echo '<script>location.href="?page=myReviews"</script>';
    }
?>

<body class="myReviewsPage">
    <main class="cont">
        <!-- Мои рецензии -->
        <div class="myReviewsHeader catHeader cont">
            <h1 class="titleBrown">Мои рецензии</h1>
            <div class="filters">
                <input type="text" placeholder="Название книги...">
                <a href="#">Применить</a>
            </div>
        </div>

        <div class="myReviews cont">
            <?php for($i = 0; $i < count($reviews); $i += 1) { ?>
            <div class="myReview">
                <div class="myReviewBody">
                    <div class="myReviewHead">
                        <img src="<?= $books[$i]['img'] ?>" alt="">
                        <div class="myReviewInfo">
                            <h1><?= $books[$i]['title'] ?></h1>
                            <p><?= $books[$i]['author'] ?></p>
                            <div class="rating">
                                <p>Оценка: <?= $reviews[$i]['rating'] ?></p>
                                <svg width="15" height="15" viewBox="0 0 15 15" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M6.48449 0.690996C6.78384 -0.230314 8.08725 -0.230314 8.3866 0.690997L9.45617 3.98279C9.59005 4.39482 9.974 4.67378 10.4072 4.67378H13.8684C14.8372 4.67378 15.2399 5.91339 14.4562 6.48279L11.656 8.51724C11.3056 8.77188 11.1589 9.22325 11.2928 9.63527L12.3623 12.9271C12.6617 13.8484 11.6072 14.6145 10.8235 14.0451L8.02333 12.0107C7.67284 11.756 7.19825 11.756 6.84776 12.0107L4.04759 14.0451C3.26388 14.6145 2.2094 13.8484 2.50875 12.9271L3.57832 9.63527C3.71219 9.22325 3.56554 8.77188 3.21505 8.51724L0.41488 6.48279C-0.368834 5.91339 0.0339417 4.67378 1.00267 4.67378H4.46386C4.89709 4.67378 5.28105 4.39482 5.41492 3.98279L6.48449 0.690996Z"
                                        fill="white" />
                                </svg>

                            </div>
                            <p>Статус: <?= $reviews[$i]['status'] ?></p>
                        </div>
                    </div>

                    <p><?= $reviews[$i]['text'] ?></p>
                </div>
                
                <div class="myReviewOptions">
                    <a href="?page=myReviews&delete=<?= $books[$i]['id'] ?>" class="redbutton">Удалить</a>
                </div>
            </div>
            <?php } ?>
        </div>
    </main>
</body>

<?php
    if(isset($_GET['delete'])) {
        $sql = "SELECT b.* FROM books b JOIN reviews r ON b.id = r.book_id WHERE user_id = ? AND book_id = ?";
        $stmt = $connect->prepare($sql);
        $stmt->execute([$USER['id'], $_GET['delete']]);
        $book = $stmt->fetch(PDO::FETCH_ASSOC);
?>
<div class="modal">
    <form method="post" class="window deleteGenreModal">
        <a class="close" href="?page=myReviews"><h3>⨉</h3></a>
        <h1 class="titleBrown">Удаление комментария</h1>
        <p>Удалить Ваш комментарий к книге «<?= $book['title'] ?>»?</p>
        <div class="modalOptions">
            <a href="?page=myReviews">Закрыть</a>
            <button name="deleteReview" class="redbutton">Удалить</button>
        </div>
    </form>
</div>
<?php } ?>