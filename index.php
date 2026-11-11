<?php
    session_start();
    include('php/connectDB.php');
    include('php/loginControl.php');
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <link rel="shortcut icon" href="assets/images/favicon.png" type="image/x-icon">
    <script src="js/profile.js" defer></script>
    <script src="js/addAuthor.js" defer></script>
    <script src="js/addBook.js" defer></script>
    <title>Букля</title>
</head>
<body>
<?php
    if (isset($_GET['page']) && $_GET['page'] != 'main') include('pages/components/header.php');
?>

<?php
    if (isset($_GET['page'])) {
        if (isset($USER) && $USER['role'] == 'admin') {
            if ($_GET['page'] == 'managePanel') include('pages/admin/managePanel.php');
            if ($_GET['page'] == 'profile') include('pages/user/profile.php');
            if($_GET['page'] == 'addBook') include('pages/admin/addBook.php');
            if($_GET['page'] == 'addAuthor') include('pages/admin/addAuthor.php');
            if($_GET['page'] == 'editAuthor') include('pages/admin/editAuthor.php');
            if($_GET['page'] == 'editBook') include('pages/admin/editBook.php');
            if($_GET['page'] == 'createCollection') include('pages/admin/createCollection.php');
            if($_GET['page'] == 'createCollection2') include('pages/admin/createCollection2.php');
            if($_GET['page'] == 'editCollection') include('pages/admin/editCollection.php');
            if($_GET['page'] == 'editCollection2') include('pages/admin/editCollection2.php');
            if($_GET['page'] == 'favorites') include('pages/user/favorites.php');
            if($_GET['page'] == 'myCollections') include('pages/user/myCollections.php');
            if($_GET['page'] == 'myReviews') include('pages/user/myReviews.php');

        } elseif (isset($USER)) {
            if ($_GET['page'] == 'profile') include('pages/user/profile.php');
            if($_GET['page'] == 'createCollection') include('pages/admin/createCollection.php');
            if($_GET['page'] == 'createCollection2') include('pages/admin/createCollection2.php');
            if($_GET['page'] == 'editCollection') include('pages/admin/editCollection.php');
            if($_GET['page'] == 'editCollection2') include('pages/admin/editCollection2.php');
            if($_GET['page'] == 'favorites') include('pages/user/favorites.php');
            if($_GET['page'] == 'myCollections') include('pages/user/myCollections.php');
            if($_GET['page'] == 'myReviews') include('pages/user/myReviews.php');
        } elseif (!isset($USER)) {
            if ($_GET['page'] == 'reg') include('pages/user/reg.php');
            elseif ($_GET['page'] == 'login') include('pages/user/login.php');
        }
        if($_GET['page'] == 'main') include('pages/components/main.php');
        elseif($_GET['page'] == 'book') include('pages/content/book.php');
        elseif($_GET['page'] == 'books') include('pages/content/books.php');
        elseif($_GET['page'] == 'author') include('pages/content/author.php');
        elseif($_GET['page'] == 'authors') include('pages/content/authors.php');
        elseif($_GET['page'] == 'collection') include('pages/content/collection.php');
        elseif($_GET['page'] == 'collections') include('pages/content/collections.php');
    } else {
        include('pages/components/main.php');
    }

    include('pages/components/footer.php');
?>
</body>
</html>