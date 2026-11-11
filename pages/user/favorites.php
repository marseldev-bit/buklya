<?php
    $sql = "SELECT c.* FROM collections c JOIN favorites f ON f.collection_id = c.id WHERE f.user_id = ?";
    $stmt = $connect->prepare($sql);
    $stmt->execute([$USER['id']]);
    $favorites = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<body class="collectionsPage">
    <main>
        <div class="collectionsHeader catHeader cont">
            <h1 class="titleBrown">Сохраненные подборки</h1>
        </div>

        <?php if(count($favorites) == 0) { ?>
        <div class="emptyFavorites cont">
            <h2 class="empty">У Вас нет сохраненных подборок, пора это исправить!</h2>
            <a href="?page=collections" class="button">К подборкам</a>
        </div>
        <?php } else { ?>
    <div class="collections">
        <?php foreach($favorites as $f) { ?>
        <div class="collectionBlock">
            <a href="?page=collection&id=<?= $f['id'] ?>" class="collection">
                <img src="<?= $f['img'] ?>" alt="">
                <div class="collectionBlock">
                    <div class="collectionText">
                        <h1><?= $f['title'] ?></h1>
                        <p><?= $f['description'] ?></p>
                        <h2>Автор: <?= $f['author'] ?></h2>
                    </div>
                </div>
            </a>
        </div>
        <?php } ?>
    </div>
    <?php } ?>
    </main>
</body>