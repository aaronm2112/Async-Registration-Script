<?php

/**
 * classes/AJAX.class.php
 * Description of AJAX
 * createAJAX($optional_salt);
 * Creates nonce (string) based on sessionid, ip, and page name.
 * checkAJAX($optional_salt); 
 * Checks the provided nonce (string) to make sure it is still valid.
 * This is done to ensure the AJAX request is legitimate (sent froma web browser)
 * @author Aaron
 */
class AJAX {

    public function createAJAX($optional_salt = '') {
        return hash_hmac('sha256', session_id() . $optional_salt, date("YmdG") . 'someSalt' . $_SERVER['REMOTE_ADDR']);
    }

    public function checkAJAX($nonce, $optional_salt = '') {
        $lasthour = date("G") - 1 < 0 ? date('Ymd') . '23' : date("YmdG") - 1;
        if (hash_hmac('sha256', session_id() . $optional_salt, date("YmdG") . 'someSalt' . $_SERVER['REMOTE_ADDR']) == $nonce ||
                hash_hmac('sha256', session_id() . $optional_salt, $lasthour . 'someSalt' . $_SERVER['REMOTE_ADDR']) == $nonce) {
            return true;
        } else {
            return false;
        }
    }

}
