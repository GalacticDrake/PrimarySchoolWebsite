<?php
    require('../db.php');
    include("../auth.php"); //include auth.php file on all secure pages

    $allid = $_GET['id'];
    
    $weekno = substr($allid, -2); // get week no for querying
    $courseid = strtolower(substr($allid, 0, 8)); // get course id
    $visible = 1; // default visibility = on

    // prevent unauthorised access
    if($_SESSION['role'] != "teacher") {
        header("Location: ../error.php");
        exit();
    }

    // find reqid that is not used
    $query_string_id = "SELECT MAX(reqid) AS oldreqid FROM {$courseid}_sub_t;";
    $query_newid = $con->prepare($query_string_id);
    $query_newid->execute();
    $newid_obj = $query_newid->get_result();
    $nnewid = mysqli_fetch_array($newid_obj);
    $nnewidval = (int)$nnewid["oldreqid"] + 1;
    $reqid = str_pad(strval($nnewidval), 6, '0', STR_PAD_LEFT);

    // create submission box
    $query_string_create = "INSERT INTO {$courseid}_sub_t (reqid, reqname, fileid, commence, deadline, week, visibility) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $query_create = $con->prepare($query_string_create);
    $query_create->bind_param("sssssii", $reqid, $reqname, $fileid, $commence, $deadline, $weekno, $visible);

    function createDateAndTime($date, $thour, $tminute, $period) {
        $month = substr($date, 0, 2);
        $day = substr($date, 3, 2);
        $year = substr($date, 6, 4);

        if($period == "pm") {
            $tthour = (int)$thour + 1;
            $hour = str_pad(strval($tthour), 2, '0', STR_PAD_LEFT);
        } else {
            $hour = str_pad($thour, 2, '0', STR_PAD_LEFT);
        }

        $minute = str_pad($tminute, 2, '0', STR_PAD_LEFT);

        return "$year-$month-$day $hour:$minute:00"; 
    }

    $reqname = $_REQUEST['submit-title'];
    $fileid = "000000"; // no file shall have this id, it is used for display purpose only

    $commence = createDateAndTime($_REQUEST['accessdate'], $_REQUEST['accesshour'], $_REQUEST['accessminute'], $_REQUEST['accessperiod']);
    $deadline = createDateAndTime($_REQUEST['deaddate'], $_REQUEST['deadhour'], $_REQUEST['deadminute'], $_REQUEST['deadperiod']);

    $query_create->execute();
    header("Location: ../course/view.php?id=$courseid");
?>