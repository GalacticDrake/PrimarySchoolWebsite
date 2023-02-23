<?php
    require('../db.php');
    include("../auth.php"); //include auth.php file on all secure pages

    $allid = $_GET['id'];
    $currfile = $_GET['currfile']; // used to redirect if there is any issue
    $filetype = $_GET['type']; // get file type
    
    $weekno = substr($allid, -2); // get week no for querying
    $courseid = strtolower(substr($allid, 0, 8)); // get course id

    // find id to replace deleted file for viewing after redirection
    $query_string_rep = "SELECT MIN(fileid) FROM {$courseid} WHERE week = ?";
    $query_replace = $con->prepare($query_string_rep);
    $query_replace->bind_param("s", $weekno);
    $query_replace->execute();
    $replace_obj = $query_replace->get_result();
    $replace = mysqli_fetch_array($replace_obj);
    $firstfile = $replace[0];

    // obtain file data
    $query_string_obt = "SELECT * FROM {$courseid} WHERE fileid = ?";
    $query_obtain = $con->prepare($query_string_obt);
    $query_obtain->bind_param("s", $currfile);
    $query_obtain->execute();
    $obtain_obj = $query_obtain->get_result();
    $obtain = mysqli_fetch_array($obtain_obj);

    // delete file
    $query_string_rem = "DELETE FROM {$courseid} WHERE fileid = ?";
    $query_remove = $con->prepare($query_string_rem);
    $query_remove->bind_param("s", $currfile);

    // delete file from global directory
    // $query_string_rem_full = "DELETE FROM fileids WHERE fileid = ?";
    // $query_remove_full = $con->prepare($query_string_rem_full);
    // $query_remove_full->bind_param("s", $currfile);

    // without file directory in database, tell user the error
    if($filetype == "file") {
        if(unlink($obtain["filedir"])) {
            $query_remove->execute();
            // $query_remove_full->execute();
            $_SESSION['filestatus'] = 0;
            header("Location: view.php?id=$allid&viewfiles=$firstfile");
        } else {
            $_SESSION['filestatus'] = 4;
            header("Location: view.php?id=$allid&viewfiles=$currfile");
        }
    } else {
        $query_remove->execute();
        // $query_remove_full->execute();
        $_SESSION['filestatus'] = 0;
        header("Location: view.php?id=$allid&viewfiles=$firstfile");
    }
?>
