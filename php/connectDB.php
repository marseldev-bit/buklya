<?php
    try { 
        $connect = new PDO ('mysql:host=localhost;dbname=Buklya;charset=utf8', 'root','');
    }
    catch(PDOException $error) {
        echo $error;
    }
?>