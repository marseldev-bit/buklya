<?php 
    if(isset($_SESSION['userID'])) {
        $sql = "SELECT * FROM users WHERE id = ?";
        $stmt = $connect->prepare($sql);
        $stmt->execute([$_SESSION['userID']]);
        $USER = $stmt->fetch(PDO::FETCH_ASSOC);
    }

    if(isset($_POST['exit'])) {
        session_unset();
        echo '<script>location.href = "?"</script>';
    }
?>