<?php

/**
 * classes/register.class.php
 * Description of register
 * Handles all of the user input for registration.
 * Checks username length, and if it's already in use.
 * Checks validity of email, makes sure both emails match.
 * Ensures password complexity, and makes sure they both match. 
 * If the user passes registration, they are added to the temp_users table.
 * @author Aaron
 */
class register {

    public $errors = array();
    
    public function __construct($mysqli) {
        //pass in connection, create property
        $this->mysqli = $mysqli;
        
    }

    public function Username($username) {

        $username = filter_var($username, FILTER_SANITIZE_STRING);
        $len = strlen($username);

        if (empty($username)) {
            $this->errors[] = "You must provide a username.";
        }

        if (!empty($username)) {

            if ($len < 3 || $len > 20) {
                $this->errors[] = "Your username must be 3 to 20 characters in length.";
            }
        }

        if (!$len < 3 || !$len > 20) {
            $stmt = $this->mysqli->prepare("SELECT username FROM users WHERE username = ?");
            $stmt->bind_param('s', $username);
            $stmt->execute();

            if ($stmt->num_rows == 1) {
                // usename is found, throw out error
                $this->errors[] = "That username is already in use.";
            }
            $stmt->close();
        }
    }

    public function Password($password, $repassword) {
        $len = strlen($password);


        if (empty($password)) {
            $this->errors[] = "You must provide a password. Your password must have at least one lower and uppercase letter, and be 6-25 characters in length.";
        }

        if (!empty($password)) {

            if ($password !== $repassword) {
                $this->errors[] = "Your passwords do not match.";
            }

            if (!preg_match('/[A-Z]/', $password)) {
                $this->errors[] = "Your password must contain at least one upper-case letter.";
            }

            if (!preg_match('/[a-z]/', $password)) {
                $this->errors[] = "Your password must contain at least one lower-case letter.";
            }

            if ($len < 6 || $len > 25) {
                $this->errors[] = "Your password must be between 6 and 25 characters in length.";
            }
        }
    }

    public function Email($email, $reemail) {

        if (empty($email)) {
            $this->errors[] = "You must provide a valid E-Mail.";
        }

        //check if provided email is valid, but first check that the user typed something in.
        if (!empty($email)) {

            if ($email !== $reemail) {
                $this->errors[] = "The E-Mail's that you provided do not match.";
            }

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $this->errors[] = "The E-Mail that you provided is not valid.";
            }
        }

        if (filter_var($email, FILTER_VALIDATE_EMAIL) && $email == $reemail) {
            $stmt = $this->mysqli->prepare("SELECT * FROM temp_users,users WHERE users.email = ? OR temp_users.email = ?") or die ("There was an error of some sort.");
            $stmt->bind_param('ss', $email,$email);
            $stmt->execute();
            $stmt->store_result();
           

            if ($stmt->num_rows > 0) {
                // email is found, throw out error
                $this->errors[] = "That E-Mail is already in use.";
            }
            $stmt->free_result();
            $stmt->close();
        }
    }

    public function addUser($username, $password, $email) {
        $code = md5(mt_rand(25, 25));
        $password = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $this->mysqli->prepare("INSERT INTO temp_users (username,email,password,code) VALUES(?,?,?,?)");
        $stmt->bind_param('ssss', $username, $email, $password, $code);
        $stmt->execute();
        $stmt->close();
    }

}
