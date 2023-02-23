<?php
    require('../db.php');
    include("../auth.php"); //include auth.php file on all secure pages

    $courseid = $_GET['id'];
    $reqid = $_GET['submit']; // used to redirect if cancel file upload

    if($_SESSION['role'] == "teacher") {
        // find all details about the submission
        $query_string = "SELECT * FROM {$courseid}_sub_t WHERE reqid = ? AND fileid = '000000'";
        $query_submission = $con->prepare($query_string);
        $query_submission->bind_param("s", $reqid);
        $query_submission->execute();
        $submission_obj = $query_submission->get_result();
        $sub = mysqli_fetch_array($submission_obj);

        // find maximum id
        $query_string_max = "SELECT MAX(fileid) FROM {$courseid}";
        $query_maxid = $con->prepare($query_string_max);
        $query_maxid->execute();
        $maxid_obj = $query_maxid->get_result();
        $maxid = mysqli_fetch_array($maxid_obj);
        $max = (float)$maxid[0];

        // put 1 to require_submission for checked files
        $query_string_require = "UPDATE {$courseid} SET require_submission = 1 WHERE fileid = ?";
        $query_require = $con->prepare($query_string_require);
        $query_require->bind_param("s", $fileid);

        // insert file details into the table courseid
        $query_string = "INSERT INTO {$courseid}_sub_t (reqid, reqname, fileid, commence, deadline, week, visibility, cat) VALUES (?, ?, ?, ?, ?, ?, ?, 'assessment')";
        $query_insert = $con->prepare($query_string);
        $query_insert->bind_param("sssssii", $reqid, $sub["reqname"], $fileid, $sub["commence"], $sub["deadline"], $sub["week"], $sub["visibility"]);

        $fileno = 1; 

        // this is not a good way for big databases
        while($fileno <= $max) {
            $filenos = str_pad(strval($fileno), 6, '0', STR_PAD_LEFT);
            
            if(isset($_POST["file_$filenos"])) {
                $fileid = $filenos;
                $query_insert->execute();
                $query_require->execute();
            }

            $fileno++;
        }

        header("Location: view.php?id=$courseid&viewsubmission=$reqid");
        exit();
    } else {
        // use datetime object to update time for which the file has been modified
        // note: by right, we should let the user choose their timezone and store in database
        // we will leave this for future implementation
        $tz = 'Asia/Kuala_Lumpur';
        $timestamp = time();
        $dt = new DateTime("now", new DateTimeZone($tz));
        $dt->setTimestamp($timestamp);
        $updatetime = $dt->format('Y-m-d H:i:s');

        // insert file details into the table courseid
        $query_string = "INSERT INTO {$courseid}_sub_s (reqid, studentid, fileid, filename, uploaddate, filedir) VALUES (?, ?, ?, ?, ?, ?)";
        $query_insert = $con->prepare($query_string);
        $query_insert->bind_param("ssssss", $reqid, $_SESSION['username'], $fileid, $filename, $updatetime, $filedir);

        // find a new id for the file
            // this will always find the next highest unused id
            // needs a solution for files deleted in the center (an algorithm perhaps)
        $query_string_id = "SELECT MAX(fileid) AS fileval FROM {$courseid}_sub_s;";
        $query_newid = $con->prepare($query_string_id);
        $query_newid->execute();
        $newid_obj = $query_newid->get_result();
        $nnewid = mysqli_fetch_array($newid_obj);
        $nnewidval = (int)$nnewid["fileval"] + 1;
        $newid = str_pad(strval($nnewidval), 6, '0', STR_PAD_LEFT);

        // admin should create the folder
        $target_dir = "../files/{$courseid}_students/";
        $target_file = $target_dir.basename($_FILES["selectedFile"]["name"]);

        if(file_exists($target_file)) {
            $_SESSION['filestatus'] = 1;
            header("Location: view.php?id=$courseid&viewsubmission=$reqid");
        }
        else if(move_uploaded_file($_FILES["selectedFile"]["tmp_name"], $target_file)) {
            $_SESSION['filestatus'] = 0;

            $fileid = $newid;
            $filename = $_FILES["selectedFile"]["name"];
            $size = $_FILES["selectedFile"]["size"];
            $filedir = "../files/{$courseid}_students/$filename";

            $query_insert->execute();

            header("Location: view.php?id=$courseid&viewsubmission=$reqid");
        } else {
            $_SESSION['filestatus'] = 1;
            header("Location: view.php?id=$courseid&viewsubmission=$reqid");
        }
    }    
?>
