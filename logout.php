<?php
    session_start();
        if(session_destroy()){ // destroy any session
            header("Location: login.php"); // redirect to login
        }
?>