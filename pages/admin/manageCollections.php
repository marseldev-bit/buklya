<?php
    $sql = "SELECT * FROM collections WHERE author = ?";
    $stmt = $connect->prepare($sql);
    $stmt->execute(['Букля']);
    $collections = $stmt->fetchAll();

    if(isset($_POST['deleteCollection'])) {
        $sql = "SELECT * FROM collections WHERE id = ?";
        $stmt = $connect->prepare($sql);
        $stmt->execute([$_GET['delete']]);
        $collection = $stmt->fetch();
        unlink($collection['img']);

        $sql = "DELETE FROM collections WHERE id = ?";
        $stmt = $connect->prepare($sql);
        $stmt->execute([$_GET['delete']]);
        echo '<script>location.href="?page=managePanel&manage=collections"</script>';
    }
?>

<main class="manageCollectionsPage">
    <div class="catHeader">
        <div class="filters cont">
            <input type="number" placeholder="ID подборки...">
            <input type="text" placeholder="Название подборки...">
            <a href="#">Применить</a>
            <a href="?page=createCollection&admin">Создать подборку</a>
        </div>
    </div>

    <div class="collections">
        <?php foreach($collections as $c) { ?>
        <div class="collectionBlock">
            <a href="?page=collection&id=<?= $c['id'] ?>" class="collection">
                <img src="<?= $c['img'] ?>" alt="">
                <div class="collectionBlock">
                    <div class="collectionText">
                        <h1><?= $c['title'] ?></h1>
                        <p><?= $c['description'] ?></p>
                        <h2>Автор: <?= $c['author'] ?></h2>
                    </div>
                </div>
            </a>

            <div class="collectionOptions">
                <a href="?page=editCollection&admin&id=<?= $c['id'] ?>" class="button">Редактировать</a>
                <a href="?page=managePanel&manage=collections&delete=<?= $c['id'] ?>" class="redbutton">Удалить</a>
            </div>
        </div>
        <?php } ?>
    </div>
</main>

<?php
    if(isset($_GET['delete'])) {
        $sql = "SELECT * FROM collections WHERE id = ?";
        $stmt = $connect->prepare($sql);
        $stmt->execute([$_GET['delete']]);
        $collection = $stmt->fetch();
?>
<div class="modal">
    <form method="post" class="window deleteGenreModal">
        <a class="close" href="?page=managePanel&manage=collections"><h3>⨉</h3></a>
        <h1 class="titleBrown">Удаление подборки</h1>
        <p>Удалить подборку «<?= $collection['title'] ?>»?</p>
        <div class="modalOptions">
            <a href="?page=managePanel&manage=collections">Закрыть</a>
            <button name="deleteCollection" class="redbutton">Удалить</button>
        </div>
    </form>
</div>
<?php } ?>