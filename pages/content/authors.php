<?php
$sql = "SELECT * FROM authors";
$params = [];

if (!empty($_GET['search'])) {
    $sql .= " WHERE name LIKE ?";
    $searchTerm = "%" . $_GET['search'] . "%";
    $params[] = $searchTerm;
}

$stmt = $connect->prepare($sql);
$stmt->execute($params);
$authors = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<body class="authorsPage">
    <main>
        <!-- Страница с авторами -->
        <form method="get" class="authorsHeader catHeader cont">
            <h1 class="titleBrown">Авторы</h1>
            <div class="filters">
                <input type="hidden" name="page" value="authors">
                <input type="text" name="search" placeholder="Имя автора..." <?php if(isset($_GET['search'])) { ?>value="<?= $_GET['search'] ?>"<?php } ?>>
                <button>Применить</button>
            </div>
        </form>

        <?php if(count($authors) == 0) { ?>
        <p class="empty cont">Мы не нашли такого автора!</p>
        <?php } else { ?>
        <div class="authors cont">
            <?php foreach ($authors as $a) { ?>
                <div class="author">
                    <img src="<?= $a['img'] ?>" alt="">
                    <h1><?= $a['name'] ?></h1>
                    <p>Произведений: <?= $a['books'] ?></p>
                    <a href="?page=author&id=<?= $a['id'] ?>">К автору</a>
                </div>
            <?php } ?>
        </div>
        <?php } ?>
    </main>
</body>