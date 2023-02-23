<?php
    require('../db.php');
    include("../auth.php"); //include auth.php file on all secure pages

    $allid = $_GET['id'];
    $currfile = $_GET['currfile']; // used to redirect if there is any issue
    $visible = $_GET['visible']; // get visibility value
    
    $weekno = substr($allid, -2); // get week no for querying
    $courseid = strtolower(substr($allid, 0, 8)); // get course id

    // overwrite file details in database
    $query_string = "UPDATE {$courseid} SET visibility = ? WHERE fileid = ?";
    $query_update = $con->prepare($query_string);
    $query_update->bind_param("ss", $visible, $currfile);
    $query_update->execute();

    header("Location: view.php?id=$allid&viewfiles=$currfile");
?>