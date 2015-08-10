<?php
session_start();

    //get the name of the current page, (in this case register.php)
if (!isset($_SESSION['loggedin'])) {
    //this session var should (loggedin) only be active if the user
    //is logged in, so we only want people who are not
    //logged in to be able to regiser

    $_SESSION['current_page'] = $_SERVER['SCRIPT_NAME'];
}

require_once 'libs/autoloader.php';
require_once 'libs/connect.class.php';

$ajax = new AJAX();
?>
<!DOCTYPE html>
<html>
    <head>
        <script src="http://code.jquery.com/jquery-1.8.3.min.js"></script>
        <script src="js/script.js"></script>
        <link rel="stylesheet" href="css/style.css" type="text/css">
        <meta charset="UTF-8">
        <title>Register</title>
    <div id="container">

        <div id="header">
            <h1>SAMPLE HEADER</h1>
        </div>

        <div id="navbar">
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="register.php">Register</a></li>
                <li><a href="login.php">Login</a></li>
            </ul>
        </div>

        <div id="main" style="width:100%;">
            <div id="return"></div>
            <form action="#" method="POST" id="register">
                <input name="nonce" id="nonce" type="hidden" value="<?php echo $ajax->createAJAX($_SERVER['SCRIPT_NAME']); ?>">
                Username <input type="text" name="username" id="username">
                <br>
                E-Mail <input type="text" name="email" id="email">
                <br>
                Re-type E-Mail <input type="text" name="reemail" id="reemail">
                <br>
                Password <input type="password" name="pass" id="pass">
                <br>
                Re-type password <input type="password" name="repass" id="repass">
                <br>
                <input type="button" name="submit" value="Submit" id="submitreg" disabled>
            </form>

        </div>
        <div style="clear:both;"></div>
    </div>
</body>
</html>






