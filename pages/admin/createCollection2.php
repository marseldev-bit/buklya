<?php
    $errors = [];

    $sql = "SELECT * FROM books";
    $stmt = $connect->prepare($sql);
    $stmt->execute([]);
    $books = $stmt->fetchAll();

    $collectionBooks = $_SESSION['collectionBooks'] ?? [];
    if(isset($_POST['chooseBooks'])) {
        if(empty($_POST['books'])) $errors['booklist'] = "Выберите хотя бы одну книгу";
        else {
            $_SESSION['collectionBooks'] = $_POST['books'];
            $collectionBooks = $_POST['books'];
        }
    }

    if(isset($_POST['createCollection'])) {
        if(empty($collectionBooks)) $errors['books'] = "Выберите хотя бы одну книгу";
        elseif(empty($errors)) {
            foreach($collectionBooks as $cb) {
                $sql = "INSERT INTO collections_books (collection_id, book_id) VALUES (?, ?)";
                $stmt = $connect->prepare($sql);
                $stmt->execute([$_GET['id'], $cb]);
            }
            unset($_SESSION['collectionBooks']);
            if(isset($_GET['admin'])) echo '<script>location.href="?page=managePanel&manage=collections"</script>';
            else echo '<script>location.href="?page=myCollections"</script>';
        }
    }

    if(isset($_POST['reset'])) {
        unset($_SESSION['collectionBooks']);
        echo '<script>location.href="?page=createCollection2&id='.$_GET['id'].'"</script>';
    }
?>

<body class="createCollectionPage">

    <main class="cont">
        <!-- Создание Подборки -->
        <form method="post" enctype="multipart/form-data" class="create">
            <h1 class="titleBrown">Создание подборки</h1>
            <h1 class="step">Шаг 2: Добавьте книги</h1>
            <div class="field">
                <div class="collBooks">
                    <?php
                    if (!empty($collectionBooks)) {
                        $chosenBooks = [];
                        $count = 0;
                        foreach ($collectionBooks as $cb) {
                            $sql = "SELECT * FROM books WHERE id = ?";
                            $stmt = $connect->prepare($sql);
                            $stmt->execute([$cb]);
                            array_push($chosenBooks, $stmt->fetch());
                        }

                        foreach ($chosenBooks as $cb) {
                            if ($count > 0) {
                    ?>
                                <div class="separateLine"></div>
                            <?php } ?>

                            <div class="collBook">
                                <div class="bookAuthor">
                                    <h1><?= $cb['title'] ?></h1>
                                    <h2><?= $cb['author'] ?></h2>
                                </div>
                            </div>
                    <?php $count += 1;
                        }
                    } ?>

                    <?php if (isset($errors['books'])) { ?>
                        <p class="fieldError"><?= $errors['books'] ?></p>
                    <?php } ?>

                    <a class="chooseBooks button">Выбрать книги</a>
                </div>
            </div>

            <div class="createOptions">
                <button name="reset">Сбросить</button>
                <button type="submit" name="createCollection">Создать</button>
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

<div class="modal">
    <form method="post" class="window booklistModal">
        <a class="close">
            <h3>⨉</h3>
        </a>
        <h1 class="titleBrown">Добавьте книги в подборку</h1>
        <div class="catHeader">
            <div class="filters">
                <input type="text" placeholder="Название книги или имя автора...">
                <a>Применить</a>
            </div>
        </div>
        <div class="booklist">
            <?php foreach ($books as $b) { ?>
                <div class="bookItem">
                    <div class="bookItemHead">
                        <img src="<?= $b['img'] ?>" alt="">
                        <div class="bookItemInfo">
                            <h1><?= $b['title'] ?></h1>
                            <h2><?= $b['author'] ?></h2>
                            <p>Перевод: <?= $b['translate'] ?></p>
                        </div>
                    </div>

                    <label>
                        <input type="checkbox" name="books[]" value="<?= $b['id'] ?>" <?php if (in_array($b['id'], $collectionBooks)) { ?>checked<?php } ?>>
                        <svg version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 405.272 405.272" style="enable-background:new 0 0 405.272 405.272;" xml:space="preserve">
                            <g>
                                <path d="M393.401,124.425L179.603,338.208c-15.832,15.835-41.514,15.835-57.361,0L11.878,227.836 c-15.838-15.835-15.838-41.52,0-57.358c15.841-15.841,41.521-15.841,57.355-0.006l81.698,81.699L336.037,67.064 c15.841-15.841,41.523-15.829,57.358,0C409.23,82.902,409.23,108.578,393.401,124.425z" />
                            </g>
                        </svg>
                    </label>
                </div>
            <?php } ?>
        </div>

        <?php if (isset($errors['booklist'])) { ?>
            <p class="fieldError"><?= $errors['booklist'] ?></p>
        <?php } ?>

        <div class="modalOptions">
            <button type="reset">Сбросить</button>
            <button name="chooseBooks">Сохранить</button>
        </div>
    </form>
</div>

<script src="js/createCollection2.js"></script>
<?php if(isset($_POST['chooseBooks']) && !empty($errors['booklist'])) { ?>
    <script>
        document.querySelector('.modal').style.display = "flex";
    </script>
<?php } ?>