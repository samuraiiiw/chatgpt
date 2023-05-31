<?php
    session_start();
    require_once "connection.php";

    if(empty($_SESSION['id_loged'])) {
        header('Location:login.php');
    }

    $id = $_SESSION['id_loged'];
    // echo $_SESSION['username_loged'];

    // Ukoliko je u url upisan follow onda vršimo unos reda u tabelu jer želimo da zapratimo korisnika
    if(!empty($_GET['follow'])) {
        $friend_id = $conn->real_escape_string($_GET['follow']);
        $q = "INSERT INTO `followers`(`sender_id`, `recever_id`)
              VALUES('$id', '$friend_id');";
        $conn->query($q);
    }

    // Ukoliko je u url upisan unfollow onda vršimo brisanje reda u tabeli jer želimo da otpratimo korisnika
    if(!empty($_GET['unfollow'])) {
        $friend_id = $conn->real_escape_string($_GET['unfollow']);
        $q = "DELETE FROM `followers`
              WHERE `sender_id`='$id' AND `recever_id`='$friend_id';";
        // $conn->query($q);
    }

    $q = "SELECT u.id, u.username AS 'username', CONCAT(p.name, ' ', p.surname) as 'fullname'
          FROM users AS u
          INNER JOIN profiles AS p
          ON u.id = p.user_id
          WHERE u.id != $id;";

    $res = $conn->query($q);

    if($res->num_rows == 0) {
        echo "<p>No users in DB</p>";
    }
    else {
        echo "
        <table border='1'>
            <tr>
                <th>ID</th>
                <th>username</th>
                <th>full name</th>
                <th>action</th>
            </tr>
        ";

        foreach($res as $row) {
            $friend_id = $row['id'];

            // Da li ja kao ulogovani pratim korisnika?
            $q = "SELECT *
                  FROM followers
                  WHERE sender_id = $id AND recever_id = $friend_id;";

            // Ovaj upit vraća broj redova koji može biti:
            // 0 - u slučaju kada ja ne pratim korisnika
            // 1 - u slučaju kada ja pratim korisnika
            $res1 = $conn->query($q);
            $row1 = $res1->num_rows; // 0 ili 1  
            
            // Da li korisnik prati mene?
            $q = "SELECT *
                  FROM followers
                  WHERE sender_id = $friend_id AND recever_id = $id;";
            
            // Ovaj upit vraća broj redova koji može biti:
            // 0 - kada friend (korisnik) ne prati mene
            // 1 - kada friend (korisnik) prati mene
            $res2 = $conn->query($q);
            $row2 = $res2->num_rows; // 0 ili 1

            echo "
            <tr>   
                <td>$row[id]</td>
                <td>$row[username]</td>
                <td>$row[fullname]</td>
            ";

            // Ako ja ne pratim korisnika
            if($row1 == 0) {
                // Niti korisnik prati mene
                if($row2 == 0) {
                    $action = "follow";
                }
                // Ali korisnik prati mene
                else {
                    $action = "follow back";
                }
                echo "<td><a href='followers.php?follow=$friend_id'>$action</a></td></tr>";
            }
            // Ako ja pratim korisnika
            else {
                $action = "unfollow";
                echo "<td><a href='followers.php?unfollow=$friend_id'>$action</a></td></tr>";
            }
        }
        echo "</table>";
    }
    

    



?>