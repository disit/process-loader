<?php

if(isset($storeSessionOnDB) && $storeSessionOnDB=='yes') {
    include_once(__DIR__ . '/session_handler.php');
    $session_handler = new DBSessionHandler($host, $username, $password, $dbname);
    session_set_save_handler($session_handler, true);
}
