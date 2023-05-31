<?php
    function text_validation($text) {
        if(empty($text)) {
            return "Enter value";
        } 
        elseif (strlen($text) > 50) {
            return "The field must be less than 50 characters";
        }
        elseif (ctype_alpha( str_replace(" ", "", $text) ) == false) {
            return "The field can only contains letters and spaces";
        }
        else {
            return false;
        }
    }

    function date_validation($dob) {
        $year = (int)substr($dob, 0, 4);
        if($year < 1900) {
            return "Date of birth can't be before 01-01-1900";
        }
        else {
            return false;
        }
    }
    function username_validation($username, $conn) {
        if(empty($username)) {
            return "Enter value";
        } 
        elseif (strlen($username) > 50) {
            return "The field must be less than 50 characters";
        }
        elseif ($username != str_replace(" ", "", $username)) {
            // elseif(prag_match('/\s/' ,$username))
            return "The field can't contains spaces";
        }
        else {
            $q = "SELECT *
                  FROM `users`
                  WHERE `username` = '$username';";
            $res = $conn->query($q);
            if($res->num_rows == 0) {
                return false; // Ovo je pogodno za registraciju jer nema dupliranja korisnickog imena
            }
            else {
                return "Username already exists";
            }
        }
    }

    function password_validation($pass) {
        if(empty($pass)) {
            return "Enter value";
        }
        elseif(strlen($pass)<5 || strlen($pass)>25) {
            return "Password have to be between 5 and 25 characters";
        }
        elseif($pass != str_replace(" ", "", $pass)) {
            return "Password can't contants spaces";
        }
        else {
            return false;
        }
    }

    function retype_password_validation($pass, $re_pass) {
        if($pass != $re_pass) {
            return "Password and retype password must be the same";
        }
        else {
            return false;
        }
    }

?>