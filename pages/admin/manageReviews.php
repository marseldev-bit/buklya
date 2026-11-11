<?php
    $sql = "SELECT * FROM reviews";
    $stmt = $connect->prepare($sql);
    $stmt->execute([]);
    $reviews = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $sql = "SELECT b.* FROM books b JOIN reviews r ON b.id = r.book_id";
    $stmt = $connect->prepare($sql);
    $stmt->execute([]);
    $books = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $sql = "SELECT u.* FROM users u JOIN reviews r ON u.id = r.user_id";
    $stmt = $connect->prepare($sql);
    $stmt->execute([]);
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if(isset($_POST['public'])) {
        $sql = "UPDATE reviews SET status = ? WHERE id = ?";
        $stmt = $connect->prepare($sql);
        $stmt->execute(['Опубликован', $_POST['reviewId']]);
        echo '<script>location.href="?page=managePanel&manage=reviews"</script>';
    }

    if(isset($_POST['moderate'])) {
        $sql = "UPDATE reviews SET status = ? WHERE id = ?";
        $stmt = $connect->prepare($sql);
        $stmt->execute(['На модерации', $_POST['reviewId']]);
        echo '<script>location.href="?page=managePanel&manage=reviews"</script>';
    }

    if(isset($_POST['reject'])) {
        $sql = "UPDATE reviews SET status = ? WHERE id = ?";
        $stmt = $connect->prepare($sql);
        $stmt->execute(['Отклонен', $_POST['reviewId']]);
        echo '<script>location.href="?page=managePanel&manage=reviews"</script>';
    }
?>

<main class="manageReviewsPage">
    <div class="catHeader">
        <div class="filters cont">
            <input type="number" placeholder="ID книги...">
            <input type="text" placeholder="Название книги...">
            <input type="number" placeholder="ID пользователя...">
            <select>
                <option>Все</option>
                <option>На модерации</option>
                <option>Отклоненные</option>
                <option>Опубликованные</option>
            </select>
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

                <h1><?= $users[$i]['name'] ?></h1>
                <p><?= $reviews[$i]['text'] ?></p>
            </div>

            <form method="post" class="myReviewOptions">
                <?php if($reviews[$i]['status'] != "На модерации") { ?>
                <button name="moderate" class="button">На модерацию</button>
                <?php } if($reviews[$i]['status'] != "Опубликован") { ?>
                <button name="public" class="button">Опубликовать</button>
                <?php } if($reviews[$i]['status'] != "Отклонен") { ?>
                <button name="reject" class="redbutton">Отклонить</button>
                <?php } ?>
                <input type="hidden" name="reviewId" value="<?= $reviews[$i]['id'] ?>">
            </form>
        </div>
        <?php } ?>
    </div>
</main>