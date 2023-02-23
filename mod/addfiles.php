<?php
    require('../db.php');
    include("../auth.php"); //include auth.php file on all secure pages

    $allid = $_GET['id'];
    $prevfile = $_GET['prevfile']; // used to redirect if cancel file upload
    $filetype = $_GET['type'];
    
    $weekno = substr($allid, -2); // get week no for querying
    $courseid = strtolower(substr($allid, 0, 8)); // get course i
    $global_visible = 1; // default 1

    // if submission is required
    if(filter_has_var(INPUT_POST,'submission') == TRUE) {
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

    // insert file details into the table courseid
    $query_string = "INSERT INTO {$courseid}(fileid, filename, uploadtime, size, week, filedir, visibility, global_visible, filetype, require_submission) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $query_insert = $con->prepare($query_string);
    $query_insert->bind_param("ssssssiisi", $newid, $filename, $uploadtime, $size, $weekno, $filedir, $visible, $global_visible, $filetype, $submission);

    // update the global lookup table for file ids
    // $query_update_string = "INSERT INTO fileids (fileid, filecat) VALUES (?, ?);";
    // $query_update_id = $con->prepare($query_update_string);
    // $query_update_id->bind_param("ss", $newid, $courseid);

    // find a new id for the file
        // this will always find the next highest unused id
        // needs a solution for files deleted in the center (an algorithm perhaps)
    $query_string_id = "SELECT MAX(fileid) AS fileval FROM {$courseid};";
    $query_newid = $con->prepare($query_string_id);
    $query_newid->execute();
    $newid_obj = $query_newid->get_result();
    $nnewid = mysqli_fetch_array($newid_obj);
    $nnewidval = (int)$nnewid["fileval"] + 1;
    $newid = str_pad(strval($nnewidval), 6, '0', STR_PAD_LEFT);

    if($filetype == "file") {
        // admin should create the folder
        $target_dir = "../files/$courseid/";
        $target_file = $target_dir.basename($_FILES["selectedFile"]["name"]);
    }

    // $filetype = strtolower(pathinfo($target_file, PATHINFO_EXTENSION)); // unlock if needed

    // use datetime object to update time for which the file has been modified
    // note: by right, we should let the user choose their timezone and store in database
    // we will leave this for future implementation
    $tz = 'Asia/Kuala_Lumpur';
    $timestamp = time();
    $dt = new DateTime("now", new DateTimeZone($tz));
    $dt->setTimestamp($timestamp);
    $uploadtime = $dt->format('Y-m-d H:i:s');
        
    if($filetype == "file") {
        if(file_exists($target_file)) {
            $_SESSION['filestatus'] = 1;
            header("Location: view.php?id=$allid&viewfiles=$prevfile");
        }
        else if(move_uploaded_file($_FILES["selectedFile"]["tmp_name"], $target_file)) {
            $_SESSION['filestatus'] = 0;

            $filename = $_FILES["selectedFile"]["name"];
            $size = $_FILES["selectedFile"]["size"];
            $filedir = "../files/$courseid/$filename";

            $query_insert->execute();
            // $query_update_id->execute();

            header("Location: view.php?id=$allid&viewfiles=$newid");
        } else {
            $_SESSION['filestatus'] = 1;
            header("Location: view.php?id=$allid&viewfiles=$prevfile");
        }
    } else {      
        $_SESSION['filestatus'] = 0;

        if($_POST['selectedLinkname'])
            $filename = $_REQUEST['selectedLinkname'];
        else 
            $filename = $_REQUEST['selectedLink'];

        $filedir = $_REQUEST["selectedLink"];

        $query_insert->execute();
        // $query_update_id->execute();

        header("Location: view.php?id=$allid&viewfiles=$newid");
    }   
?>
