<?php
    require_once "validation.php";
    require_once "connection.php";

    $nameErr = $surnameErr = $dobErr = $usernameErr = $passErr = $rePassErr = "";

    if($_SERVER["REQUEST_METHOD"] == "POST") {
        // 1. Preuzmi vrednosti iz input polja
        $name = $_POST['name'];
        $surname = $_POST['surname'];
        $gender = $_POST['gender'];
        $dob = $_POST['dob'];
        $username = $_POST['username'];
        $pass = $_POST['password'];
        $rePass = $_POST['retypePassword'];

        // 2. Izvrši validaciju
        $validation = true;
        if(text_validation($name)) {
            $nameErr = text_validation($name);
            $validation = false;
        }

        if(text_validation($surname)) {
            $surnameErr = text_validation($surname);
            $validation = false;
        }

        if(date_validation($dob)) {
            $dobErr = date_validation($dob);
            $validation = false;
        }

        if(username_validation($username, $conn)) {
            $usernameErr = username_validation($username, $conn);
            $validation = false;
        }

        if(password_validation($pass)) {
            $passErr = password_validation($pass);
            $validation = false;
        }

        if(retype_password_validation($pass, $rePass)) {
            $rePassErr = retype_password_validation($pass, $rePass);
            $validation = false;
        }

        // 3. Ukoliko su validirana polja, unesi u DB
        if($validation) {
            // 1. unosimo u tabelu users
            $pass = md5($pass);
            $q = "INSERT INTO `users`(`username`, `pass`) 
                  VALUES ('$username','$pass');";
            $conn->query($q);

            $q = "SELECT `id`
                  FROM `users`
                  WHERE `username` LIKE '$username';";
            $res = $conn->query($q);
            $row = $res->fetch_assoc();
            $id = $row['id'];

            // 2. unosimo u tabelu profiles
            $q = "INSERT INTO `profiles`(`name`, `surname`, `gender`, `dob`, `user_id`) 
                  VALUES ('$name','$surname','$gender','$dob','$id')";
            $conn->query($q);
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Register</title>
</head>
<body>
    <form action="#" method="post">
        <p>
            Name:
            <input type="text" name="name">
            <span class="err">* <?php echo $nameErr; ?></span>
        </p>
        <p>
            Surname:
            <input type="text" name="surname">
            <span class="err">* <?php echo $surnameErr; ?></span>
        </p>
        <p>
            Gender:
            <input type="radio" name="gender" value="m">Male
            <input type="radio" name="gender" value="f">Female
            <input type="radio" name="gender" value="o" checked>Other
        </p>
        <p>
            Data of birth:
            <input type="date" name="dob">
            <span class="err">* <?php echo $dobErr; ?></span>
        </p>
        <p>
            Username:
            <input type="text" name="username">
            <span class="err">* <?php echo $usernameErr; ?></span>
        </p>
        <p>
            Password:
            <input type="password" name="password">
            <span class="err">* <?php echo $passErr; ?></span>
        </p>
        <p>
            Retype password:
            <input type="password" name="retypePassword">
            <span class="err">* <?php echo $rePassErr; ?></span>
        </p>
        <p>
            <input type="submit" value="Submit">
        </p>
    </form>
</body>
</html>