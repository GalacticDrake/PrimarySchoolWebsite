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
        $query_string_max = "SELECT MAX(fileid) FROM {$courseid}_sub_t";
        $query_maxid = $con->prepare($query_string_max);
        $query_maxid->execute();
        $maxid_obj = $query_maxid->get_result();
        $maxid = mysqli_fetch_array($maxid_obj);
        $max = (float)$maxid[0];

        // // put 0 to require_submission for unchecked files
        // $query_string_require = "UPDATE {$courseid} SET require_submission = 0 WHERE fileid = ?";
        // $query_require = $con->prepare($query_string_require);
        // $query_require->bind_param("s", $fileid);

        // delete file details into the table courseid
        $query_string = "DELETE FROM {$courseid}_sub_t WHERE reqid = ? AND fileid = ?";
        $query_remove = $con->prepare($query_string);
        $query_remove->bind_param("ss", $reqid, $fileid);

        $fileno = 1; 

        // this is not a good way for big databases
        while($fileno <= $max) {
            $filenos = str_pad(strval($fileno), 6, '0', STR_PAD_LEFT);
            
            if(isset($_POST["file_$filenos"])) {
                $fileid = $filenos;
                $query_remove->execute();
                // $query_require->execute();
            }

            $fileno++;
        }

        header("Location: view.php?id=$courseid&viewsubmission=$reqid");
        exit();
    }  else {
        $studentid = $_SESSION["username"];

        // delete file details into the table courseid
        $query_string = "DELETE FROM {$courseid}_sub_s WHERE studentid = ? AND fileid = ?";
        $query_remove = $con->prepare($query_string);
        $query_remove->bind_param("ss", $studentid, $fileid);

        // obtain file data
        $query_string_obt = "SELECT * FROM {$courseid}_sub_s WHERE fileid = ?";
        $query_obtain = $con->prepare($query_string_obt);
        $query_obtain->bind_param("s", $fileid);

        // find maximum id
        $query_string_max = "SELECT MAX(fileid) FROM {$courseid}_sub_s";
        $query_maxid = $con->prepare($query_string_max);
        $query_maxid->execute();
        $maxid_obj = $query_maxid->get_result();
        $maxid = mysqli_fetch_array($maxid_obj);
        $max = (float)$maxid[0];

        $fileno = 1; 

        // this is not a good way for big databases
        while($fileno <= $max) {
            $filenos = str_pad(strval($fileno), 6, '0', STR_PAD_LEFT);
            
            if(isset($_POST["file_$filenos"])) {
                $fileid = $filenos;

                $query_obtain->execute();
                $obtain_obj = $query_obtain->get_result();
                $obtain = mysqli_fetch_array($obtain_obj);

                if(unlink($obtain["filedir"])) {
                    $query_remove->execute();
                }
            }

            $fileno++;
        }

        header("Location: view.php?id=$courseid&viewsubmission=$reqid");
    }  
?>
