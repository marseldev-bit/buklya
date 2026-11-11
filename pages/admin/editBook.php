<?php
$errors = [];

$sql = "SELECT * FROM books WHERE id = ?";
$stmt = $connect->prepare($sql);
$stmt->execute([$_GET['id']]);
$book = $stmt->fetch();

$sql = "SELECT * FROM genres";
$stmt = $connect->prepare($sql);
$stmt->execute([]);
$genres = $stmt->fetchAll(PDO::FETCH_ASSOC);

$sql = "SELECT * FROM authors";
$stmt = $connect->prepare($sql);
$stmt->execute([]);
$authors = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (isset($_POST['editBook'])) {
    if (empty($_POST['title'])) $errors['title'] = 'Название не может быть пустым';
    if (empty($_POST['author'])) $errors['author'] = 'Выберите автора';
    if (empty($_POST['genre'])) $errors['genre'] = 'Выберите жанр';
    if (empty($_POST['annotation'])) $errors['annotation'] = 'Введите аннотацию';
    if (empty($_POST['year'])) $errors['year'] = 'Укажите год издания';
    elseif ($_POST['year'] < 0) $errors['year'] = 'Некорректное значение';
    if (empty($_POST['translate'])) $errors['translate'] = 'Укажите перевод';

    if (!empty($_FILES['image']['name'])) {
        $formats = ['jpg', 'jpeg', 'png', 'webp'];
        if (!in_array(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION), $formats)) $errors['image'] = 'Файл должен быть формата jpg/jpeg, png или webp';
        elseif ($_FILES['image']['size'] > 1024 * 1024 * 2) $errors['image'] = 'Размер изображения не должен превышать 2 МБ';
        elseif (empty($errors)) {
            $path = 'assets/booksGallery/' . time() . '_' . $_FILES['image']['name'];
            unlink($book['img']);
            if (move_uploaded_file($_FILES['image']['tmp_name'], $path)) {
                $sql = "UPDATE books SET title = ?, author = ?, genre = ?, annotation = ?, year = ?, translate = ?, img = ? WHERE id = ?";
                $stmt = $connect->prepare($sql);
                $stmt->execute([$_POST['title'], $_POST['author'], $_POST['genre'], $_POST['annotation'], $_POST['year'], $_POST['translate'], $path, $book['id']]);
            }
            echo '<script>location.href="?page=managePanel"</script>';
        }
    } elseif (empty($errors)) {
        $sql = "UPDATE books SET title = ?, author = ?, genre = ?, annotation = ?, year = ?, translate = ? WHERE id = ?";
        $stmt = $connect->prepare($sql);
        $stmt->execute([$_POST['title'], $_POST['author'], $_POST['genre'], $_POST['annotation'], $_POST['year'], $_POST['translate'], $book['id']]);
        echo '<script>location.href="?page=managePanel"</script>';
    }
}
?>

<body class="createBookPage">
    <main class="cont">
        <form method="post" enctype="multipart/form-data" class="create">
            <h1 class="titleBrown">Редактирование книги</h1>
            <div class="createFields">
                <div class="field">
                    <label>Название книги:</label>
                    <input name="title" type="text" placeholder="Укажите название..." <?php if (isset($_POST['title'])) { ?>value="<?= $_POST['title'] ?>" <?php } else { ?>value="<?= $book['title'] ?>" <?php } ?>>
                    <?php if (isset($errors['title'])) { ?>
                        <p class="fieldError"><?= $errors['title'] ?></p>
                    <?php } ?>
                </div>

                <div class="field">
                    <label>Автор</label>
                    <select name="author">
                        <option disabled selected>Автор</option>
                        <?php foreach ($authors as $a) { ?>
                            <option <?php if (isset($_POST['author']) && $_POST['author'] == $a['name']) { ?>selected<?php } elseif($a['name'] == $book['author']) { ?>selected<?php } ?>><?= $a['name'] ?></option>
                        <?php } ?>
                    </select>
                    <?php if (isset($errors['author'])) { ?>
                        <p class="fieldError"><?= $errors['author'] ?></p>
                    <?php } ?>
                </div>

                <div class="field">
                    <label>Жанр</label>
                    <select name="genre">
                        <option disabled selected>Жанр</option>
                        <?php foreach ($genres as $g) { ?>
                            <option <?php if (isset($_POST['genre']) && $_POST['genre'] == $g['name']) { ?>selected<?php } elseif($g['name'] == $book['genre']) { ?>selected<?php } ?>><?= $g['name'] ?></option>
                        <?php } ?>
                    </select>
                    <?php if (isset($errors['genre'])) { ?>
                        <p class="fieldError"><?= $errors['genre'] ?></p>
                    <?php } ?>
                </div>

                <div class="field">
                    <label>Аннотация:</label>
                    <textarea name="annotation" placeholder="Введите аннотацию..."><?php if (isset($_POST['title'])) {
                                                                                        echo $_POST['annotation'];
                                                                                    } else {
                                                                                        echo $book['annotation'];
                                                                                    } ?></textarea>
                    <?php if (isset($errors['annotation'])) { ?>
                        <p class="fieldError"><?= $errors['annotation'] ?></p>
                    <?php } ?>
                </div>

                <div class="field image">
                    <label>Обложка</label>
                    <div class="imageInput">
                        <label for="imageInput">Добавьте фото</label>
                        <p>Файл не выбран</p>
                    </div>
                    <input name="image" type="file" id="imageInput">
                    <?php if (isset($errors['image'])) { ?>
                        <p class="fieldError"><?= $errors['image'] ?></p>
                    <?php } ?>
                </div>

                <div class="doubleField">
                    <div class="field">
                        <label>Год публикации</label>
                        <input name="year" type="number" placeholder="Укажите год..." <?php if (isset($_POST['year'])) { ?>value="<?= $_POST['year'] ?>" <?php } else { ?>value="<?= $book['year'] ?>" <?php } ?>>
                        <?php if (isset($errors['year'])) { ?>
                            <p class="fieldError"><?= $errors['year'] ?></p>
                        <?php } ?>
                    </div>

                    <div class="field">
                        <label>Перевод</label>
                        <input name="translate" type="text" placeholder="Укажите перевод..." <?php if (isset($_POST['translate'])) { ?>value="<?= $_POST['translate'] ?>" <?php } else { ?>value="<?= $book['translate'] ?>" <?php } ?>>
                        <?php if (isset($errors['translate'])) { ?>
                            <p class="fieldError"><?= $errors['translate'] ?></p>
                        <?php } ?>
                    </div>
                </div>
            </div>

            <div class="createOptions">
                <button type="reset">Сбросить</button>
                <button type="submit" name="editBook">Создать</button>
            </div>
        </form>

        <svg class="desktop" width="300" height="300" viewBox="0 0 300 300" fill="none" xmlns="http://www.w3.org/2000/svg">
            <g clip-path="url(#clip0_225_35)">
                <path
                    d="M110.196 237.572L111.33 236.455C111.33 236.455 156.585 191.19 197.177 150.634V45.7521C197.177 40.2971 192.746 35.9077 187.321 35.9077L19.3952 35.8234C19.3952 29.0386 24.4387 25.615 29.5724 25.615C34.7061 25.615 197.188 25.615 197.188 25.615C207.665 25.615 207.359 34.8637 207.359 34.8637V140.446C212.739 135.044 217.943 129.847 222.881 124.93V19.0089C222.881 11.6333 216.634 9.771 213.647 9.771C212.011 9.771 27.7348 9.771 27.7348 9.771C0 9.771 0 35.4901 0 35.4901V272.096C0 277.541 4.39482 281.972 9.85525 281.972H93.8027C95.8394 273.836 100.45 260.849 109.583 239.09L110.196 237.572ZM48.4598 91.7719V72.5368H150.239V91.7719H48.4598Z"
                    fill="#3C250F" />
                <path
                    d="M222.881 213.768C217.637 219.021 212.435 224.204 207.38 229.278C207.38 229.278 207.38 248.648 207.38 250.105C207.38 251.212 207.765 257.709 202.336 257.709C202.336 257.709 199.3 257.723 197.198 257.723V239.47C181.676 254.98 168.572 268.086 161.482 275.197L154.951 281.993H187.333C192.757 281.993 197.199 277.561 197.199 272.117L213.048 272.096C223.632 272.096 222.914 261.651 222.914 261.651V213.778H222.883L222.881 213.768Z"
                    fill="#3C250F" />
                <path
                    d="M264.95 93.0439C253.279 104.756 117.124 240.865 117.124 240.865C117.124 240.865 97.8464 286.732 101.038 289.888C104.214 293.041 150.361 273.331 150.361 273.331C157.769 265.935 289.018 134.665 297.807 125.879C299.349 124.344 300.001 122.366 300.001 120.108C300.023 106.423 274.974 83.0202 264.95 93.0439ZM292.089 120.552C284.28 128.27 175.959 236.559 149.972 262.578L128.288 240.884L270.543 98.6295C270.733 98.4026 272.106 98.4026 274.205 99.2998C276.727 100.339 279.207 102.064 281.58 104.106L153.289 232.371C152.714 232.951 152.424 233.7 152.424 234.448C152.424 235.209 152.714 235.967 153.289 236.539C154.445 237.679 156.323 237.679 157.473 236.539L285.736 108.248C289.472 112.537 292.119 117.18 292.119 120.124C292.119 120.404 292.089 120.552 292.089 120.552Z"
                    fill="#3C250F" />
            </g>
            <defs>
                <clipPath id="clip0_225_35">
                    <rect width="300" height="300" fill="white" />
                </clipPath>
            </defs>
        </svg>
    </main>
</body>