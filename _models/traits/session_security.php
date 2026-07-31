<?php

trait session_security
{
    private function session_protection()
    {
        if (!isset($_SESSION['_uthip'])) {
            $_SESSION['_uthip'] = $_SERVER['REMOTE_ADDR'].'-'.$_SERVER['HTTP_USER_AGENT'];
        } elseif ($_SESSION['_uthip'] != $_SERVER['REMOTE_ADDR'].'-'.$_SERVER['HTTP_USER_AGENT']) {
            //PENDING
            //problem in mobile..s
            //echo "<h1>BAD ACCESS! Please Refresh Page</h1>";
            //session_destroy();
            //exit;
        }
    }
}

?>