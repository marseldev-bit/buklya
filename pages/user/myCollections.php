<?php
$sql = "SELECT * FROM collections WHERE user_id = ?";
$stmt = $connect->prepare($sql);
$stmt->execute([$USER['id']]);
$collections = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (isset($_POST['share'])) {
    $sql = "UPDATE collections SET status = ? WHERE id = ?";
    $stmt = $connect->prepare($sql);
    $stmt->execute(['На модерации', $_POST['shareId']]);
    echo '<script>location.href="?page=myCollections"</script>';
}

if (isset($_POST['deleteCollection'])) {
    $sql = "SELECT * FROM collections WHERE id = ?";
    $stmt = $connect->prepare($sql);
    $stmt->execute([$_GET['delete']]);
    $collection = $stmt->fetch();
    unlink($collection['img']);

    $sql = "DELETE FROM collections WHERE id = ?";
    $stmt = $connect->prepare($sql);
    $stmt->execute([$_GET['delete']]);
    echo '<script>location.href="?page=myCollections"</script>';
}
?>

<body class="collectionsPage">
    <main>
        <!-- Мои подборки -->
        <div class="myCollectionsHeader catHeader cont">
            <h1 class="titleBrown">Мои подборки</h1>
            <div class="filters">
                <input type="text" placeholder="Название подборки...">
                <a href="#">Применить</a>
                <a href="?page=createCollection">Создать</a>
            </div>
        </div>

        <?php if (count($collections) == 0) { ?>
            <h2 class="empty cont">Пора создать первую подборку книг!</h2>
        <?php } else { ?>
            <div class="collections cont">
                <?php foreach ($collections as $c) { ?>
                    <div class="myCollection">
                        <a href="?page=collection&id=<?= $c['id'] ?>" class="collection">
                            <img src="<?= $c['img'] ?>" alt="">
                            <div class="collectionBlock">
                                <div class="collectionText">
                                    <h1><?= $c['title'] ?></h1>
                                    <p><?= $c['description'] ?></p>
                                    <h2>Статус: <?= $c['status'] ?></h2>
                                    <span class="mobile tablet">Смотреть</span>
                                </div>
                            </div>
                        </a>

                        <form method="post" class="collectionOptions">
                            <a href="?page=editCollection&id=<?= $c['id'] ?>" class="button">Редактировать</a>
                            <?php if ($c['status'] == 'Создан') { ?>
                                <button class="button" name="share">Опубликовать</button>
                            <?php } ?>
                            <input type="hidden" name="shareId" value="<?= $c['id'] ?>">
                            <a href="?page=myCollections&delete=<?= $c['id'] ?>" class="redbutton">Удалить</a>
                        </form>
                    </div>
                <?php } ?>
            </div>
        <?php } ?>
    </main>
</body>

<?php
if (isset($_GET['delete'])) {
    $sql = "SELECT * FROM collections WHERE id = ?";
    $stmt = $connect->prepare($sql);
    $stmt->execute([$_GET['delete']]);
    $collection = $stmt->fetch();
?>
    <div class="modal">
        <form method="post" class="window deleteGenreModal">
            <a class="close" href="?page=myCollections">
                <h3>⨉</h3>
            </a>
            <h1 class="titleBrown">Удаление подборки</h1>
            <p>Удалить подборку «<?= $collection['title'] ?>»?</p>
            <div class="modalOptions">
                <a href="?page=myCollections">Закрыть</a>
                <button name="deleteCollection" class="redbutton">Удалить</button>
            </div>
        </form>
    </div>
<?php } ?>