<?php
$sql = "SELECT * FROM collections WHERE status != ? AND author != ?";
$params = ['Создан', 'Букля'];

if (!empty($_GET['search'])) {
    $sql .= " AND id = ?";
    $params[] = $_GET['search'];
}

if (!empty($_GET['title'])) {
    $sql .= " AND title LIKE ?";
    $searchTerm = "%" . $_GET['title'] . "%";
    $params[] = $searchTerm;
}

if (!empty($_GET['userid'])) {
    $sql .= " AND user_id = ?";
    $params[] = $_GET['userid'];
}

if (!empty($_GET['status'])) {
    $sql .= " AND status = ?";
    $params[] = $_GET['status'];
}

$stmt = $connect->prepare($sql);
$stmt->execute($params);
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
        <form method="get" class="filters cont">
            <input type="hidden" name="page" value="managePanel">
            <input type="hidden" name="manage" value="userCollections">
            <input name="search" type="number" placeholder="ID подборки..." <?php if(isset($_GET['search'])) { ?>value="<?= $_GET['search'] ?>"<?php } ?>>
            <input name="title" type="text" placeholder="Название подборки..." <?php if(isset($_GET['title'])) { ?>value="<?= $_GET['title'] ?>"<?php } ?>>
            <input name="userid" type="number" placeholder="ID пользователя..." <?php if(isset($_GET['userid'])) { ?>value="<?= $_GET['userid'] ?>"<?php } ?>>
            <select name="status">
                <option value="">Все</option>
                <option value="На модерации" <?php if(isset($_GET['status']) && $_GET['status'] == 'На модерации') { ?>selected<?php } ?>>На модерации</option>
                <option value="Отклонен" <?php if(isset($_GET['status']) && $_GET['status'] == 'Отклонен') { ?>selected<?php } ?>>Отклоненные</option>
                <option value="Опубликован" <?php if(isset($_GET['status']) && $_GET['status'] == 'Опубликован') { ?>selected<?php } ?>>Опубликованные</option>
            </select>
            <button>Применить</button>
        </form>
    </div>
    <?php if (count($collections) == 0) { ?>
        <h2 class="empty cont">Здесь пусто</h2>
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