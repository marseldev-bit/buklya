<?php
    $sql = "SELECT * FROM authors";
    $stmt = $connect->prepare($sql);
    $stmt->execute([]);
    $authors = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<body class="authorsPage">
    <main>
        <!-- Страница с авторами -->
        <div class="authorsHeader catHeader cont">
            <h1 class="titleBrown">Авторы</h1>
            <div class="filters">
                <input type="text" placeholder="Имя автора...">
                <a href="#">Применить</a>
            </div>
        </div>

        <div class="authors cont">
            <?php foreach($authors as $a) { ?>
            <div class="author">
                <img src="<?= $a['img'] ?>" alt="">
                <h1><?= $a['name'] ?></h1>
                <p>Произведений: <?= $a['books'] ?></p>
                <a href="?page=author&id=<?= $a['id'] ?>">К автору</a>
            </div>
            <?php } ?>
        </div>
    </main>
</body>