<?php
    require('../db.php');
    include("../auth.php"); //include auth.php file on all secure pages

    $courseid = $_GET['id'];
    $currsub = $_GET['currreq']; // used to redirect if there is any issue
    $visible = $_GET['visible']; // get visibility value

    // overwrite file details in database
    $query_string = "UPDATE {$courseid}_sub_t SET visibility = ? WHERE reqid = ?";
    $query_update = $con->prepare($query_string);
    $query_update->bind_param("ss", $visible, $currsub);
    $query_update->execute();

    header("Location: view.php?id=$courseid&viewsubmission=$currsub");
?>