<?php

/*
 * ajax/registerpost.php
 * This file responsible for handling the post data from register.php
 * This is where the AJAX post request is sent.
 * Handles errors, adding user etc. with register.class.php class.
 * Include ajax class to check unique nonce (string)
 */
session_start();

require_once '../libs/autoloader.php';
require_once '../libs/connect.class.php';


$register = new register($mysqli);
$ajax = new AJAX();

$nonce = $_POST['nonce'];
$username = $_POST['username'];
$email1 = $_POST['email'];
$email2 = $_POST['reemail'];
$pass1 = $_POST['pass'];
$pass2 = $_POST['repass'];

// check if session is still alive.
if (isset($_SESSION['current_page'])) {


    if ($ajax->checkAJAX($nonce, $_SESSION['current_page'])) {

        if (isset($_POST['submit'])) {

            $register->Username($username);
            $register->Email($email1, $email2);
            $register->Password($pass1, $pass2);

            if (empty($register->errors)) {
                //if no errors, add user.
                $register->addUser($username, $pass1, $email1);

                if (!isset($mysqli->errorno)) {
                    //query went through without a problem
                    //echo success, then clear form, disable button, and destroy session

                    echo "<div id=\"return_success\">";
                    echo "<ul style=\"list-style: none;\">";
                    echo "<li><b>Your user has been added to the database!</b></li>";
                    echo "</ul>";
                    echo "</div>";
                    ?>
                    <script type="text/javascript">
                        $("#register").find("input[type=text], [type=password]").val("");
                        $("#submitreg").prop("disabled", true);
                    </script>
                    <?php
                    //destroy the session ($_SESSION['current_page'])
                    session_unset();
                    session_destroy();
                } else {
                    //query error found
                    echo "<div id=\"return_error\">";
                    echo "<ul style=\"list-style: none;\">";
                    echo "<li><b>The following error occured:" . $mysqli->errorno . "</b></li>";
                    echo "</ul>";
                    echo "</div>";
                    ?>
                    <?php

                }
            } else {
                //user still has form errors, remove submit  post value, and disable button and prevent user from registering
                //display message, disable button again, remove submit post value, fadeout message.
                echo "<div id=\"return_error\">";
                echo "<ul style=\"list-style: none;\">";
                echo "<li><b>You can't do that! Don't be silly!</b></li>";
                echo "</ul>";
                echo "</div>";
                ?>
                <script type="text/javascript">
                    $("#return_error").fadeOut(3000);
                    $("#submit").remove();
                    $('#submitreg').prop("disabled", true);
                </script>
                <?php

            }
        }

        if (!isset($_POST['submit'])) {

            $register->Username($username);
            $register->Email($email1, $email2);
            $register->Password($pass1, $pass2);



            // no errors are found
            if (empty($register->errors)) {

                echo "<div id=\"return_success\">";
                echo "<ul style=\"list-style: none;\">";
                echo "<li><b>Everything looks good! Click the submit button to register.</b></li>";
                echo "</ul>";
                echo "</div>";
                ?>
                <script type="text/javascript">
                    $("#return_success").fadeOut(3000);
                    $('#submitreg').prop('disabled', false);
                </script>

                <?php

            } else {
                //errors found
                echo "<div id=\"return_error\">";
                echo "<ul style=\"list-style: none;\">";
                foreach ($register->errors as $error) {
                    echo "<li><b>" . $error . "</b></li>";
                }
                echo "</ul>";
                echo "</div>";
                ?>
                <script type="text/javascript">
                    $('#submitreg').prop('disabled', true);
                </script>
                <?php

            }
        }
    } else {
        //nonce strings don't match, die();
        die("Error: Please refresh this page.");
    }
} else {
    die("It looks like you've already registered.");
}
