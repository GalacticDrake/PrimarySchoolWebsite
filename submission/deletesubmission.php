<?php
    require('../db.php');
    include("../auth.php"); //include auth.php file on all secure pages

    $allid = $_GET['id'];
    $currreq = $_GET['deletesubmission']; // used to redirect if there is any issue
    
    $weekno = substr($allid, -2); // get week no for querying
    $courseid = strtolower(substr($allid, 0, 8)); // get course id

    if($_SESSION['role'] == "teacher") {
        // delete submission
        $query_string_del = "DELETE FROM {$courseid}_sub_t WHERE reqid = ?";
        $query_delete = $con->prepare($query_string_del);
        $query_delete->bind_param("s", $currreq);

        // put 0 to require_submission for unchecked files
        $query_string_require = "UPDATE {$courseid} SET require_submission = 0 WHERE fileid = ?";
        $query_require = $con->prepare($query_string_require);
        $query_require->bind_param("s", $fileid);

        // find all files related to the submission
        $query_string_related = "SELECT fileid FROM {$courseid}_sub_t WHERE reqid = ?";
        $query_related = $con->prepare($query_string_related);
        $query_related->bind_param("s", $currreq);
        $query_related->execute();
        $related_obj = $query_related->get_result();

        while($related = mysqli_fetch_array($related_obj)) {
            $fileid = $related["fileid"];
            $query_require->execute();
        }

        $query_delete->execute();

        header("Location: ../course/view.php?id=$courseid");
        exit();
    }
?>
