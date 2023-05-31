<?php
    session_start(); // Otvaranje sesije

    require_once "validation.php";
    require_once "connection.php";

    $usernameErr = $passErr = "";

    if($_SERVER["REQUEST_METHOD"] == "POST") {
        // Korisnik zeli da pokusa logovanje
        $username = $_POST["username"];
        $pass = $_POST["password"];
        $validation = true;

        if(username_validation($username, $conn) == "Username already exists") {
            // Ukoliko username postoji u bazi, onda vrsimo proveru lozinke
            $q = "SELECT *
                  FROM `users`
                  WHERE `username` = '$username';";
            $res = $conn->query($q);
            $row = $res->fetch_assoc();
            $id = $row['id'];
            $db_pass = $row['pass']; // ovo je md5 pass, dakle sifriran
            
            if($db_pass == md5($pass)) {
                // Ako su isti pass iz baze i pass koji je korisnik uneo, vrsimo logovanje i redirekciju na stranicu followers.php
                $_SESSION['id_loged'] = $id;
                $_SESSION['username_loged'] = $username;
                header("Location:followers.php");
            }
            else {
                // U suprotnom, ukoliko pass ne odgovara ispisujemo gresku
                $passErr = "Password is wrong";
                $validation = false;
            }
        }
        else {
            // Ukoliko username ne postoji u bazi, ispisujemo poruku
            $usernameErr = "Username is wrong";
            $validation = false;
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <form action="" method="post">
        <p>
            <label for="">Username:</label>
            <input type="text" name="username" id="">
            <span class="err">* <?php echo $usernameErr; ?></span>
        </p>
        <p>
            <label for="">Password:</label>
            <input type="password" name="password" id="">
            <span class="err">* <?php echo $passErr; ?></span>
        </p>
        <p>
            <input type="submit" value="Log In">
        </p>
    </form>
</body>
</html>