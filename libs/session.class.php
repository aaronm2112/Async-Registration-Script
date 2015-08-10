<?php

/**
 * Description of session
 * __construct($mysqli) - pass in mysqli object
 * setSessionVars($keys, $values) - Just an easier way of setting session variables. 
 * refreshSession($id) - refreshes session everytime a page is loaded
 * @author Aaron
 */
class session {

    public function __construct($mysqli) {
        $this->mysqli = $mysqli;
    }
    
    public function setSessionVars($keys, $values) {
        if(!is_array($keys) || !is_array($values)) {
            echo "Must pass in an array.";
        }
        $keycnt = count($keys);
        $valcnt = count($values);
        
        if($keycnt == $valcnt) {
            //these two must match
            for($i=0; $i < $keycnt && $valcnt; $i++) {
                $_SESSION[$keys[$i]] = $values[$i];
            }
        } else {
            echo "Error: Array lengths must match.";
        }
    }

    public function refreshSession($id) {

        $currenttime = time();

        //get expires time from db

        $stmt = $this->mysqli->prepare("SELECT expires,sessionid FROM active_users WHERE userid=?");
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($expires, $sessionid);
        
        if ($stmt->fetch()) {
            
            if ($currenttime > $expires) {
                
                echo "logged out";
                session_destroy();
                header("Location:login.php");
            } else {
                
                //get time left
                $timeleft = ($expires - $currenttime);
                //get what we should extend the session by (seconds)
                $extendby = -1 * ((($timeleft - 15 * 60)));

                $newtime = $currenttime + ($timeleft + $extendby);

                $stmt->close();
                
                $this->mysqli->query("UPDATE active_users SET expires = $newtime WHERE userid=$id") or die($this->mysqli->error);
                //echo "Time extended.";
                echo "We should extend by " . $extendby . " seconds<br>";
                echo "Session expires in " . round(($timeleft) / 60) . " minutes";
            }
        }
    }

    public function loggedIn() {
        if (isset($_SESSION['loggedin']) && ($_SESSION['loggedin']) === true) {
            return true;
        }
    }

}
