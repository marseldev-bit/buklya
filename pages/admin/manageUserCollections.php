<?php
$sql = "SELECT * FROM collections WHERE (status = ? OR status = ? OR status = ?) AND author != ?";
$stmt = $connect->prepare($sql);
$stmt->execute(['На модерации', 'Опубликован', 'Отклонен', 'Букля']);
$collections = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (isset($_POST['public'])) {
    $sql = "UPDATE collections SET status = ? WHERE id = ?";
    $stmt = $connect->prepare($sql);
    $stmt->execute(['Опубликован', $_POST['collectionId']]);
    echo '<script>location.href="?page=managePanel&manage=userCollections"</script>';
}

if (isset($_POST['reject'])) {
    $sql = "DELETE FROM favorites WHERE collection_id = ?";
    $stmt = $connect->prepare($sql);
    $stmt->execute([$_POST['collectionId']]);

    $sql = "UPDATE collections SET status = ? WHERE id = ?";
    $stmt = $connect->prepare($sql);
    $stmt->execute(['Отклонен', $_POST['collectionId']]);
    echo '<script>location.href="?page=managePanel&manage=userCollections"</script>';
}

if (isset($_POST['moderation'])) {
    $sql = "DELETE FROM favorites WHERE collection_id = ?";
    $stmt = $connect->prepare($sql);
    $stmt->execute([$_POST['collectionId']]);

    $sql = "UPDATE collections SET status = ? WHERE id = ?";
    $stmt = $connect->prepare($sql);
    $stmt->execute(['На модерации', $_POST['collectionId']]);
    echo '<script>location.href="?page=managePanel&manage=userCollections"</script>';
}
?>

<main class="manageCollectionsPage manageUserCollectionsPage">
    <div class="catHeader">
        <div class="filters cont">
            <input type="number" placeholder="ID подборки...">
            <input type="text" placeholder="Название подборки...">
            <input type="number" placeholder="ID пользователя...">
            <select>
                <option>Все</option>
                <option>На модерации</option>
                <option>Опубликован</option>
                <option>Отклонен</option>
            </select>
            <a href="#">Применить</a>
        </div>
    </div>
    <?php if (count($collections) == 0) { ?>
        <h2 class="empty">Здесь пусто</h2>
    <?php } else { ?>
        <div class="collections">
            <?php foreach ($collections as $c) { ?>
                <div class="collectionBlock">
                    <a href="?page=collection&id=<?= $c['id'] ?>" class="collection">
                        <img src="<?= $c['img'] ?>" alt="">
                        <div class="collectionBlock">
                            <div class="collectionText">
                                <h1><?= $c['title'] ?></h1>
                                <p><?= $c['description'] ?></p>
                                <h2>Автор: <?= $c['author'] ?></h2>
                                <h2>Статус: <?= $c['status'] ?></h2>
                                <span class="mobile" href="?page=collection&id=<?= $c['id'] ?>">Смотреть</span>
                            </div>
                        </div>
                    </a>

                    <form method="post" class="collectionOptions">
                        <?php if ($c['status'] != 'Опубликован') { ?>
                            <button name="public" class="button">Опубликовать</button>
                        <?php } if ($c['status'] != 'На модерации') { ?>
                            <button name="moderation" class="button">На модерацию</button>
                        <?php } if ($c['status'] != 'Отклонен') { ?>
                           <button name="reject" class="redbutton">Отклонить</button> 
                        <?php } ?>
                        <input type="hidden" name="collectionId" value="<?= $c['id'] ?>">
                    </form>
                </div>
            <?php } ?>
        </div>
    <?php } ?>
</main>