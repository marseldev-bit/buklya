<?php
    $sql = "SELECT * FROM collections WHERE status = ?";
    $stmt = $connect->prepare($sql);
    $stmt->execute(['Опубликован']);
    $collections = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $sql = "SELECT * FROM genres";
    $stmt = $connect->prepare($sql);
    $stmt->execute([]);
    $genres = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<body class="collectionsPage">
    <main>
        <!-- Страница подборок -->
        <div class="collectionsHeader catHeader cont">
            <h1 class="titleBrown">Подборки</h1>
            <div class="filters">
                <input type="text" placeholder="Название подборки...">
                <select>
                    <option disabled selected>Жанр</option>
                    <?php foreach($genres as $g) { ?>
                    <option><?= $g['name'] ?></option>
                    <?php } ?>
                </select>
                <a href="#">Применить</a>
            </div>
        </div>

        <div class="collections">
            <?php foreach($collections as $c) { ?>
                <a href="?page=collection&id=<?= $c['id'] ?>" class="collection">
                    <img src="<?= $c['img'] ?>" alt="">
                    <div class="collectionBlock">
                        <div class="collectionText">
                            <h1><?= $c['title'] ?></h1>
                            <p><?= $c['description'] ?></p>
                            <h2>Автор: <?= $c['author'] ?></h2>
                            <span class="mobile tablet">Смотреть</span>
                        </div>
                    </div>
                </a>
                <?php } ?>
            </div>
    </main>
</body>