<?php
$sql = "SELECT * FROM collections WHERE status = ?";
$params = ['Опубликован'];

if (!empty($_GET['search'])) {
    $sql .= " AND title LIKE ?";
    $searchTerm = "%" . $_GET['search'] . "%";
    $params[] = $searchTerm;
}

if (!empty($_GET['genre'])) {
    $sql .= " AND genre = ?";
    $params[] = $_GET['genre'];
}

$stmt = $connect->prepare($sql);
$stmt->execute($params);
$collections = $stmt->fetchAll(PDO::FETCH_ASSOC);

$sql = "SELECT * FROM genres";
$stmt = $connect->prepare($sql);
$stmt->execute([]);
$genres = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<body class="collectionsPage">
    <main>
        <!-- Страница подборок -->
        <form method="get" class="collectionsHeader catHeader cont">
            <h1 class="titleBrown">Подборки</h1>
            <div class="filters">
                <input type="hidden" name="page" value="collections">
                <input type="text" name="search" placeholder="Название подборки..." <?php if (isset($_GET['search'])) { ?>value="<?= $_GET['search'] ?>" <?php } ?>>
                <select name="genre">
                    <option selected value="">Жанр</option>
                    <?php foreach ($genres as $g) { ?>
                        <option value="<?= $g['name'] ?>" <?php if (isset($_GET['genre']) && $_GET['genre'] == $g['name']) { ?>selected<?php } ?>><?= $g['name'] ?></option>
                    <?php } ?>
                </select>
                <button>Применить</button>
            </div>
        </form>

        <?php if(count($collections) == 0) { ?>
        <p class="empty cont">Такой подборки не нашлось</p>
        <?php } else { ?>
        <div class="collections">
            <?php foreach ($collections as $c) { ?>
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
        <?php } ?>
    </main>
</body>