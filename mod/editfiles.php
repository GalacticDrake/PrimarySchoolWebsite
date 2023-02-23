<?php
    require('../db.php');
    include("../auth.php"); //include auth.php file on all secure pages

    $allid = $_GET['id'];
    $currfile = $_GET['currfile']; // used to redirect if there is any issue
    
    $weekno = substr($allid, -2); // get week no for querying
    $courseid = strtolower(substr($allid, 0, 8)); // get course id

    $filetitle = $_REQUEST['file-title']; // get file title
    $filedesc = $_REQUEST['file-description']; // get file description

    // if submission is required
    if(isset($_POST['submission'])) {
        $submission = 1;
    } else {
        $submission = 0;
    }

    // if checked, visible, otherwise hidden
    if(filter_has_var(INPUT_POST,'visible') == TRUE) {
        $visible = 1;
    } else {
        $visible = 0;
    }

    // delete file if flagged, and exit to prevent bottom code from executing
    if(filter_has_var(INPUT_POST,'delete') == TRUE) {
        header("Location: delete.php?id=$courseid$weekno&currfile=$currfile");
        exit();
    }

    // use datetime object to update time for which the file has been modified
        // note: by right, we should let the user choose their timezone and store in database
        // we will leave this for future implementation
    $tz = 'Asia/Kuala_Lumpur';
    $timestamp = time();
    $dt = new DateTime("now", new DateTimeZone($tz));
    $dt->setTimestamp($timestamp);
    $updatetime = $dt->format('Y-m-d H:i:s');

    // obtain file data
    $query_string_obt = "SELECT * FROM {$courseid} WHERE fileid = ?";
    $query_obtain = $con->prepare($query_string_obt);
    $query_obtain->bind_param("s", $currfile);
    $query_obtain->execute();
    $obtain_obj = $query_obtain->get_result();
    $obtain = mysqli_fetch_array($obtain_obj);

    // overwrite file details in database
    $query_string = "UPDATE {$courseid} SET filename = ?, filedesc = ?, visibility = ?, uploadtime = ?, filedir = ?, require_submission = ? WHERE fileid = ?";
    $query_update = $con->prepare($query_string);
    $query_update->bind_param("ssssssi", $filetitle, $filedesc, $visible, $updatetime, $filedir, $submission, $currfile);

    $filedir = $obtain["filedir"];

    // function only required for windows...
    function rename_win($oldfile, $newfile) {
        if (!rename($oldfile, $newfile)) {
            echo $newfile;
            if(copy($oldfile, $newfile)) {
                unlink($oldfile);
                return TRUE;
            }
            return FALSE;
        }
        return TRUE;
    }

    // without file directory in database, tell user the error
    if($obtain["filedir"] == NULL) {
        $_SESSION['filestatus'] = 2;
        header("Location: view.php?id=$allid&viewfiles=$currfile");
        exit();
    }

    echo $obtain["require_submission"];

    // if both have same names, dont change, unless visibility changes
    if($filetitle == $obtain["filename"] && $filedesc== $obtain["filedesc"]) {
        if($visible != $obtain["visibility"] || $submission != $obtain["require_submission"]) 
            $query_update->execute();    
        else
            $_SESSION['filestatus'] = 3;            
    
    // with different filename, change
    } else {
        if(rename_win($obtain["filedir"], "../files/$courseid/$filetitle")) {
            $filedir = "../files/$courseid/$filetitle";
            $query_update->execute();
            $_SESSION['filestatus'] = 0;
        }
    }
    
    header("Location: view.php?id=$allid&viewfiles=$currfile");
?>
