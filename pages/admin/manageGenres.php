<?php
    $errors = [];
    $sql = "SELECT * FROM genres";
    $stmt = $connect->prepare($sql);
    $stmt->execute([]);
    $genres = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if(isset($_POST['addGenre'])) {
        if(empty($_POST['genre'])) $errors['addGenre'] = 'Введите название жанра';
        $sql = "SELECT * FROM genres WHERE name = ?";
        $stmt = $connect->prepare($sql);
        $stmt->execute([$_POST['genre']]);
        $isGenre = $stmt->fetch(PDO::FETCH_ASSOC);
        if(!empty($isGenre['name'])) $errors['addGenre'] = 'Такой жанр уже существует';
        if(empty($errors['addGenre'])) {
            $sql = "INSERT INTO genres (name) VALUES (?)";
            $stmt = $connect->prepare($sql);
            $stmt->execute([$_POST['genre']]);
            echo '<script>location.href="?page=managePanel&manage=genres"</script>';
        }
    }

    if(isset($_POST['editGenre'])) {
        if(empty($_POST['genre'])) $errors['editGenre'] = 'Введите название жанра';
        $sql = "SELECT * FROM genres WHERE name = ? AND id != ?";
        $stmt = $connect->prepare($sql);
        $stmt->execute([$_POST['genre'], $_GET['edit']]);
        $isGenre = $stmt->fetch(PDO::FETCH_ASSOC);
        if(!empty($isGenre['name'])) $errors['editGenre'] = 'Такой жанр уже существует';
        if(empty($errors['editGenre'])) {
            $sql = "UPDATE genres SET name = ? WHERE id = ?";
            $stmt = $connect->prepare($sql);
            $stmt->execute([$_POST['genre'], $_GET['edit']]);
            echo '<script>location.href="?page=managePanel&manage=genres"</script>';
        }
    }

    if(isset($_POST['deleteGenre'])) {
        $sql = "DELETE FROM genres WHERE id = ?";
        $stmt = $connect->prepare($sql);
        $stmt->execute([$_GET['delete']]);
        echo '<script>location.href="?page=managePanel&manage=genres"</script>';
    }
?>

<main class="manageGenresPage">
    <div class="catHeader">
        <div class="filters cont">
            <input type="number" placeholder="ID жанра...">
            <input type="text" placeholder="Название жанра...">
            <a href="#">Применить</a>
            <a href="?page=managePanel&manage=genres&addGenre">Добавить жанр</a>
        </div>
    </div>

    <div class="genres cont">
        <?php foreach($genres as $g) { ?>
        <div class="genre">
            <h1><?= $g['name'] ?></h1>
            <div class="genreContent">
                <p>Книг: 23</p>
                <p>Подборок: 16</p>
            </div>
            <div class="genreOptions">
                <a href="?page=managePanel&manage=genres&edit=<?= $g['id'] ?>">
                    <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <g clip-path="url(#clip0_321_5)">
                            <path d="M0 15.8333L12.2917 3.54167L16.4583 7.70833L4.16667 20H0V15.8333ZM19.6875 4.47917L17.6562 6.51042L13.4896 2.34375L15.5208 0.3125C15.7292 0.104167 15.9896 0 16.3021 0C16.6146 0 16.875 0.104167 17.0833 0.3125L19.6875 2.91667C19.8958 3.125 20 3.38542 20 3.69792C20 4.01042 19.8958 4.27083 19.6875 4.47917Z" fill="white" />
                        </g>
                        <defs>
                            <clipPath id="clip0_321_5">
                                <rect width="20" height="20" fill="white" />
                            </clipPath>
                        </defs>
                    </svg>
                </a>
                <a href="?page=managePanel&manage=genres&delete=<?= $g['id'] ?>">
                    <svg width="39" height="39" viewBox="0 0 39 39" fill="white"
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
</main>

<?php if(isset($_GET['addGenre'])) { ?>
<div class="modal">
    <form method="post" class="window">
        <a class="close" href="?page=managePanel&manage=genres"><h3>⨉</h3></a>
        <h1 class="titleBrown">Добавление жанра</h1>
        <div class="field">
            <label>Жанр:</label>
            <input type="text" name="genre" placeholder="Введите название жанра..." <?php if(isset($_POST['genre'])) { ?>value="<?= $_POST['genre'] ?>"<?php } ?>>
            <?php if(isset($errors['addGenre'])) { ?>
            <p class="fieldError"><?= $errors['addGenre'] ?></p>
            <?php } ?>
        </div>
        <div class="modalOptions">
            <button type="reset">Сбросить</button>
            <button name="addGenre">Добавить</button>
        </div>
    </form>
</div>
<?php } ?>

<?php
    if(isset($_GET['edit'])) {
        $sql = "SELECT * FROM genres WHERE id = ?";
        $stmt = $connect->prepare($sql);
        $stmt->execute([$_GET['edit']]);
        $genre = $stmt->fetch();
?>
<div class="modal">
    <form method="post" class="window">
        <a class="close" href="?page=managePanel&manage=genres"><h3>⨉</h3></a>
        <h1 class="titleBrown">Редактирование жанра</h1>
        <div class="field">
            <label>Жанр:</label>
            <input type="text" name="genre" placeholder="Введите название жанра..." <?php if(isset($_POST['genre'])) { ?>value="<?= $_POST['genre'] ?>"<?php } else { ?>value="<?= $genre['name'] ?>"<?php } ?>>
            <?php if(isset($errors['editGenre'])) { ?>
            <p class="fieldError"><?= $errors['editGenre'] ?></p>
            <?php } ?>
        </div>
        <div class="modalOptions">
            <button type="reset">Сбросить</button>
            <button name="editGenre">Сохранить</button>
        </div>
    </form>
</div>
<?php } ?>

<?php
    if(isset($_GET['delete'])) {
        $sql = "SELECT * FROM genres WHERE id = ?";
        $stmt = $connect->prepare($sql);
        $stmt->execute([$_GET['delete']]);
        $genre = $stmt->fetch();
?>
<div class="modal">
    <form method="post" class="window deleteGenreModal">
        <a class="close" href="?page=managePanel&manage=genres"><h3>⨉</h3></a>
        <h1 class="titleBrown">Удаление жанра</h1>
        <p>Удалить жанр «<?= $genre['name'] ?>»?</p>
        <div class="modalOptions">
            <a href="?page=managePanel&manage=genres">Закрыть</a>
            <button name="deleteGenre" class="redbutton">Удалить</button>
        </div>
    </form>
</div>
<?php } ?>