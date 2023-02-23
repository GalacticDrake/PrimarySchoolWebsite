<?php
    require('../db.php');
    include("../auth.php"); //include auth.php file on all secure pages

    function redirectError() {
        header("Location: ../error.php");
        exit();
    }

    $courseid = strtolower($_SESSION['current_course']);
    $allid = $_GET['id'];
    $id_count = strlen($allid);

    // prevent abusing of URL
    if($id_count != 8)
        if($id_count != 10)
            redirectError();

    if(!isset($_SESSION["filestatus"]))
        $_SESSION["filestatus"] = 0;
?>

<!DOCTYPE html>

<head>
    <title>Submission</title>

    <meta name="viewport" content="width=device-width">
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/themes/cupertino/jquery-ui.min.css" integrity="sha512-ug/p2fTnYRx/TfVgL8ejTWolaq93X+48/FLS9fKf7AiazbxHkSEENdzWkOxbjJO/X1grUPt9ERfBt21iLh2dxg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="../css/main.css">
    <link rel="stylesheet" href="../css/view.css">
    <link rel="stylesheet" href="../css/mod.css">
    <link rel="stylesheet" href="../css/edit.css">
    <link rel="stylesheet" href="../css/add.css">
    <link rel="stylesheet" href="../css/add_week.css">
    <link rel="stylesheet" href="../css/submission.css">
    <link rel="stylesheet" href="../css/create_sub.css">
    <link rel="stylesheet" href="../css/mobile.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Oxygen">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <script src="https://code.jquery.com/jquery-3.6.0.js"></script>
    <script src="https://code.jquery.com/ui/1.13.0/jquery-ui.js"></script>
</head>
<body>
    <div class="wrapper">
        <div class="header">
            <nav class="topnav" id="myTopnav">
                <ul id="navul">
                    <li class="li-close">
                        <a onclick="closeNavbar()">
                            <span class="material-icons close">
                                close
                            </span>
                        </a>
                    </li>
                    <li><a href="../dashboard.php">Home</a></li>
                    <li><a href="../courses.php">Courses</a></li>
                    <li><a href="../timetable/view.php">Timetable</a></li>
                    <li><a href="../assessments.php">Assessments</a></li>
                </ul> 
                <a class="icon" id="icon" onclick="openNavbar()">
                    <span class="material-icons">menu</span>
                </a>
                <div class="overlay" onclick="closeNavbar()"></div>            
            </nav>
            <div class="profile">
                <div class="dropdown">
                     <div class="profile-details">
                        <div class="profpic"></div>
                        <span>
                            <?php 
                                $word = explode(' ', $_SESSION['display_name']);
                                echo $word[0];
                            ?>
                        </span>
                    </div>
                    <span class="material-icons">expand_more</span>
                    <div class="dropdown-content">                        
                        <div><a class="dropdown-option" href="../profile.php">Account settings</a></div>
                        <div><a class="dropdown-option" href="../logout.php">Logout</a></div>
                        <div class="dropdown-title">Gryffindor System</div>
                    </div>
                </div>
            </div>
        </div>
        <section class="container">
            <div class="sidenav sidemargin" id="sidenav">
                <a class="sideicon showicon" onclick="openSidetab()" id="sideopen">              
                    <span class="material-icons close sideclose">
                        expand_more
                    </span>
                </a>
                <a class="sideicon" onclick="closeSidetab()" id="sideclose">                
                    <span class="material-icons close sideclose">
                        close
                    </span>
                </a>
                <div class="sidenav-title">
                    Sidepanel
                </div>
                <div class="sidetabs">
                    <div class="sidetabs-title">Quick Access</div>
                    <ul class="quick-access">
                        <?php 
                            $max = sizeof($_SESSION["quickaccessid"]);
                            $count = 0;

                            while($count < $max) {                                
                                echo
                                '
                                <li><a href="../course/view.php?id='.$_SESSION["quickaccessid"][$count].'">'.$_SESSION["quickaccessname"][$count].'</a></li>
                                ';
                                $count++;
                            }
                        ?>
                    </ul>
                </div>
                <div class="sidetabs">
                    <div class="sidetabs-title">Resources</div>
                </div>
            </div>
            <div class="container-inner">
                <div class="view-header">
                    <a class="black-link" href="../course/view.php?id=<?php echo $courseid; ?>">
                        <div class="view-code">
                            <?php
                                echo strtoupper($courseid);
                            ?>
                        </div>
                        <div class="view-title">
                            <?php
                                $course_name = "SELECT coursename FROM courses WHERE courseid='".$courseid."'";
                                $result = mysqli_query($con, $course_name) or die(mysqli_error($con));

                                $display = mysqli_fetch_assoc($result);
                                echo $display["coursename"];
                            ?>
                        </div>
                    </a>
                </div>
                <div class="tabs">                    
                    <div class="file-box">
                        <?php
                            if(isset($_GET['createsubmission'])) {
                                if($_SESSION['role'] == "teacher") {
                                    echo '
                                    <div class="file-header">
                                        <div class="file-status">Create new submission</div>
                                    </div>
                                    <div class="file-container">
                                        <form action="createsubmission.php?id='.$allid.'" method="POST">
                                            <div class="file-desc edit-sub">
                                                <div>
                                                    <div class="file-edit">
                                                        <label class="edit-label" for="submit-title">Submission title</label>
                                                        <input type="text" name="submit-title" id="submit-title" required>
                                                    </div>
                                                    <div class="file-edit">
                                                        <label class="edit-label">Commencement</label>
                                                        <input type="text" name="accessdate" id="accessdate" class="datepicker" required>
                                                        <select name="accesshour" class="timepicker">';
                                                            $i = 1;
                                                            while($i <= 12) {
                                                                echo '
                                                                <option value="'.$i.'">'.$i.'</option>
                                                                ';
                                                                $i++;
                                                            }
                                                        echo '
                                                        </select>
                                                        <select name="accessminute" class="timepicker">';
                                                            $i = 0;
                                                            while($i < 60) {
                                                                echo '
                                                                <option value="'.$i.'">'.str_pad($i, 2, '0', STR_PAD_LEFT).'</option>
                                                                ';
                                                                $i++;
                                                            }
                                                        echo '
                                                        </select>
                                                        <select name="accessperiod" class="timepicker">
                                                            <option value="am">am</option>
                                                            <option value="pm">pm</option>
                                                        </select>                                                    
                                                    </div>
                                                    <div class="file-edit">
                                                        <label class="edit-label">Deadline</label>
                                                        <input type="text" name="deaddate" id="deaddate" class="datepicker" required>
                                                        <select name="deadhour" class="timepicker">';
                                                            $i = 1;
                                                            while($i <= 12) {
                                                                echo '
                                                                <option value="'.$i.'">'.$i.'</option>
                                                                ';
                                                                $i++;
                                                            }
                                                        echo '
                                                        </select>
                                                        <select name="deadminute" class="timepicker">';
                                                            $i = 0;
                                                            while($i < 60) {
                                                                echo '
                                                                <option value="'.$i.'">'.str_pad($i, 2, '0', STR_PAD_LEFT).'</option>
                                                                ';
                                                                $i++;
                                                            }
                                                        echo '
                                                        </select>
                                                        <select name="deadperiod" class="timepicker">
                                                            <option value="am">am</option>
                                                            <option value="pm">pm</option>
                                                        </select>                                                    
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="v-desc">You can add files once you created this submission page.</div>
                                            <div id="v-desc" class="v-desc">This document is visible to students.</div>
                                            <div id="d-desc" class="v-desc d-desc"></div>
                                            <div class="file-options-box">
                                                <label class="file-options" name="visible" onclick="changeVisible()">
                                                    <input type="checkbox" id="visible" name="visible" checked=true>
                                                    <span class="material-icons" id="v-icon">
                                                        visibility_off
                                                    </span>
                                                    <div class="options-label" id="v-text">Hide</div>
                                                </label>
                                            </div>
                                            <div class="file-submission">
                                                <div class="submit-left">
                                                </div>
                                                <div class="submit-right">
                                                    <a class="cancel" href="view.php?id='.$allid.'&viewfiles='.$_SESSION['fileid'].'">Cancel</a>
                                                    <button type="submit" value="submit">Save</button>
                                                </div>
                                            </div>
                                        </form>                      
                                    </div>';
                                }
                            }
                        ?>
                        <?php
                            // format file size to readable format
                            function countSize($bytes) {
                                // http://www.phpshare.org
                                if ($bytes >= 1073741824) {
                                    $bytes = number_format($bytes / 1073741824, 2) . ' GB';
                                }
                                elseif ($bytes >= 1048576)
                                {
                                    $bytes = number_format($bytes / 1048576, 2) . ' MB';
                                }
                                elseif ($bytes >= 1024)
                                {
                                    $bytes = number_format($bytes / 1024, 2) . ' KB';
                                }
                                elseif ($bytes > 1)
                                {
                                    $bytes = $bytes . ' bytes';
                                }
                                elseif ($bytes == 1)
                                {
                                    $bytes = $bytes . ' byte';
                                }
                                else
                                {
                                    $bytes = '0 bytes';
                                }

                                return $bytes;
                            }

                            // format date to readable format
                            function dateAndTime($fulldate) {
                                $year = substr($fulldate, 0, 4);
                                $month = substr($fulldate, 5, 2);
                                $day = substr($fulldate, 8, 2);
                                $hours = substr($fulldate, 11, 2);
                                $mins = substr($fulldate, 14, 2);

                                if((int)$hours >= 13) {
                                    $hours = $hours - 12;
                                    $period = "p.m.";                            
                                } else {
                                    $period = "a.m.";
                                }

                                return "$day/$month/$year at $hours:$mins $period";
                            }

                            // view files
                            // can only access from viewsubmissions
                            if(isset($_GET['viewsubmission'])) {
                                $reqid = $_GET['viewsubmission'];

                                // query submission
                                $query_string = "SELECT * FROM {$courseid}_sub_t WHERE reqid = ?;";
                                $querysubmission = $con->prepare($query_string);
                                $querysubmission->bind_param("s", $reqid);
                                $querysubmission->execute();
                                $submission = $querysubmission->get_result();

                                // query submission onceoff
                                $query_string_once = "SELECT DISTINCT * FROM {$courseid}_sub_t WHERE reqid = ?;";
                                $queryonce = $con->prepare($query_string_once);
                                $queryonce->bind_param("s", $reqid);
                                $queryonce->execute();
                                $once_obj = $queryonce->get_result();
                                $once = mysqli_fetch_array($once_obj);

                                // query file
                                $query_string_file = "SELECT * FROM {$courseid} WHERE fileid = ?;";
                                $queryfile = $con->prepare($query_string_file);
                                $queryfile->bind_param("s", $tempfileid);

                                // prevent students from accessing unauthorised file
                                if($once['visibility'] == 1 || $_SESSION['role'] == "teacher") {
                                    echo 
                                    '<div class="file-header">
                                        <div class="file-status">Submission</div>
                                    </div>';

                                    echo 
                                    '<div class="file-nav">
                                        <div class="file-nav-title">Reference files</div>';
                                    
                                    if($_SESSION['role'] == "teacher") {
                                        echo '
                                            <div class="file-header-option">
                                                <a class="blue-link" href="view.php?id='.$courseid.'&removefiles='.$reqid.'">
                                                    <div class="file-header-link">
                                                        <span class="material-icons">delete_sweep</span>
                                                        <span>Remove documents or links</span>
                                                    </div>                                        
                                                </a>
                                            </div> 
                                            <div class="row-of-files">
                                        ';
                                    }

                                    while($sub = mysqli_fetch_array($submission)) {
                                        $tempfileid = $sub["fileid"];

                                        if($tempfileid == "000000")
                                            continue;

                                        $queryfile->execute();
                                        $filefinder_obj = $queryfile->get_result();
                                        $filefinder = mysqli_fetch_array($filefinder_obj);

                                        if($filefinder['filedir'] === NULL) {
                                            echo
                                            '<div class="tabs-option file-error">';
                                        } else {
                                            echo
                                            '<div class="tabs-option">';
                                        }

                                        if($filefinder["updatetime"] == NULL) {
                                            $modifieddate = $filefinder["uploadtime"];
                                        } else {
                                            $modifieddate = $filefinder["updatetime"];
                                        }

                                        echo
                                        '
                                            <div class="tabs-grid">
                                                <a class="blue-link" href="'.$filefinder["filedir"].'" download>
                                                    <div class="tabs-option-title"> '. $filefinder['filename'] .'</div>
                                                </a>
                                                <div class="tabs-option-title"> '. $modifieddate .'</div>
                                            </div>
                                        </div>';
                                    }                               

                                    echo '</div></div>';

                                    echo '
                                    <div class="file-container">
                                        <div class="file-desc file-flex">
                                            <div>Description</div>
                                            <div class="submit-detail">
                                                <div class="view-left">Title: </div>
                                                <div class="view-right">'.$once['reqname'].'</div>
                                            </div>
                                            <div class="submit-detail">
                                                <div class="view-left">Commencement: </div>
                                                <div class="view-right">'.dateAndTime($once['commence']).'</div>
                                            </div>
                                            <div class="submit-detail">
                                                <div class="view-left">Deadline: </div>
                                                <div class="view-right">'.dateAndTime($once['deadline']).'</div>
                                            </div>';
                                            if($_SESSION['role'] == "teacher") {
                                                echo '
                                                <div class="submit-detail">
                                                    <div class="view-left">Visibility: </div>
                                                    <div class="view-right">';
                                                        if($once['visibility'] == 1) {
                                                            echo "This submission is visible to students";
                                                        } else {
                                                            echo "This submission is hidden from students";
                                                        }
                                                    echo '
                                                    </div>
                                                </div>
                                                ';
                                            }

                                    echo '</div>';
                                        
                                    // students to see their own files
                                    if($_SESSION['role'] == "student") {
                                        $studentid = $_SESSION['username'];

                                        // query submission
                                        $query_string_student = "SELECT * FROM {$courseid}_sub_s WHERE studentid = ?;";
                                        $querystudent = $con->prepare($query_string_student);
                                        $querystudent->bind_param("s", $studentid);
                                        $querystudent->execute();
                                        $student = $querystudent->get_result();

                                        echo
                                        '<div class="view-box">
                                            <div>Your submission</div>
                                            <div class="view-desc">Click on the blue words to download the files.</div>';

                                        while($stud = mysqli_fetch_array($student)) {  
                                                        
                                            // bukak container
                                            if($stud['filedir'] === NULL) {
                                                echo
                                                '<div class="view-each file-error">';
                                            } else {
                                                echo
                                                '<div class="view-each">';
                                            }
    
                                            $modifieddate = $stud["uploaddate"];
    
                                            echo
                                            '                                                
                                                    <div class="view-row">
                                                        <div class="view-middle">
                                                            <a class="blue-link" href="'.$stud["filedir"].'" download>
                                                                '. $stud['filename'] .'
                                                            </a>
                                                        </div>
                                                        <div class="view-right green"> Updated on '.dateAndTime($modifieddate).'</div>
                                                    </div>
                                                </div>
                                            ';
                                        }
                                        
                                        // close container
                                        echo '</div></div>';
                                    }

                                    if($_SESSION['role'] == "parent") {
                                        // query all children
                                        $query_string_all = "SELECT studentid, name FROM studentcourses INNER JOIN students ON studentcourses.studentid = students.username WHERE courseid = '{$courseid}' AND parentid = ?;";
                                        $querychildren = $con->prepare($query_string_all);
                                        $querychildren->bind_param("s", $_SESSION["username"]);
                                        $querychildren->execute();
                                        $allstudent = $querychildren->get_result();
                                        $childrenno = mysqli_num_rows($allstudent);

                                        // if child uploads more than one file
                                        $query_string_files = "SELECT * FROM {$courseid}_sub_s WHERE studentid = ?";
                                        $queryfiles = $con->prepare($query_string_files);
                                        $queryfiles->bind_param("s", $studentid);

                                        // query all students who submitted
                                        $query_string_submit = "SELECT studentid, name, {$courseid}_sub_s.fileid, uploaddate, filedir, filename FROM (({$courseid}_sub_s
                                        INNER JOIN students ON {$courseid}_sub_s.studentid = students.username) 
                                        INNER JOIN {$courseid}_sub_t ON {$courseid}_sub_s.reqid = {$courseid}_sub_t.reqid) WHERE {$courseid}_sub_t.reqid = '{$reqid}';";
                                        $querysubmit = $con->prepare($query_string_submit);
                                        $querysubmit->execute();
                                        $submitted = $querysubmit->get_result();

                                        $array_of_students = [];
                                        $array_of_studentnames = [];
                                        $count = 0;

                                        // fetch all children related to this course
                                        while($all_students = mysqli_fetch_array($allstudent)) {
                                            $array_of_students[] = $all_students["studentid"];
                                            $array_of_studentnames[] = $all_students["name"];
                                        }

                                        // create main container
                                        if(sizeof($array_of_students) != 0) {
                                            echo '<div class="view-box">
                                                    <div>Children\'s submission</div>
                                                    <div class="view-desc">Click on the blue words to download the files.</div>
                                                    ';
                                        }

                                        // fetch children who submit
                                        while($spec_student = mysqli_fetch_array($submitted)) {

                                            if($spec_student["studentid"] != $array_of_students[$count])
                                                continue;
                                            
                                            // for the children that did not submit
                                            while($array_of_students[$count] != $spec_student["studentid"]) {
                                                echo
                                                '
                                                    <div class="view-each">
                                                        <div class="view-row">
                                                            <div class="view-left">Student ID: </div>
                                                            <div class="view-right">'.$array_of_students[$count].'</div>
                                                        </div>
                                                        <div class="view-row">
                                                            <div class="view-left">Student name: </div>
                                                            <div class="view-right">'.$array_of_studentnames[$count].'</div>
                                                        </div>
                                                        <div class="view-row">
                                                            <div class="view-left">File: </div>
                                                            <div class="view-right red">Not submitted</div>
                                                        </div>
                                                    </div>
                                                ';

                                                $count++;
                                            }
                                            
                                            // for children who submitted
                                            // for students who submitted
                                            $studentid = $spec_student["studentid"];
                                            $queryfiles->execute();
                                            $queryfiles_obj = $queryfiles->get_result();
 
                                            echo
                                            '
                                                <div class="view-each">
                                                    <div class="view-row">
                                                        <div class="view-left">Student ID: </div>
                                                        <div class="view-right">'.$spec_student["studentid"].'</div>
                                                    </div>
                                                    <div class="view-row">
                                                        <div class="view-left">Student name: </div>
                                                        <div class="view-right">'.$spec_student["name"].'</div>
                                                    </div>
                                                    <div class="view-row">
                                                        <div class="view-left">File: </div>
                                                        <div class="view-right">';
                                                            while($studentfiles = mysqli_fetch_array($queryfiles_obj)) {
                                                            
                                                                echo '
                                                                <div class="view-row">
                                                                    <div class="view-middle"><a class="blue-link" href="'.$studentfiles["filedir"].'" download>'.$studentfiles["filename"].'</a></div>
                                                                    <div class="view-right green">Updated on '.dateAndTime($studentfiles["uploaddate"]).'</div>
                                                                </div>';

                                                            }
                                                        echo '
                                                        </div>
                                                    </div>
                                                </div>
                                            ';

                                            $count++;
                                        }

                                        // for the children that did not submit
                                        while($count < $childrenno) {
                                            echo
                                            '
                                                <div class="view-each">
                                                    <div class="view-row">
                                                        <div class="view-left">Student ID: </div>
                                                        <div class="view-right">'.$array_of_students[$count].'</div>
                                                    </div>
                                                    <div class="view-row">
                                                        <div class="view-left">Student name: </div>
                                                        <div class="view-right">'.$array_of_studentnames[$count].'</div>
                                                    </div>
                                                    <div class="view-row">
                                                        <div class="view-left">File: </div>
                                                        <div class="view-right red">Not submitted</div>
                                                    </div>
                                                </div>
                                            ';

                                            $count++;
                                        }

                                        // close main container
                                        if(sizeof($array_of_students) != 0) {
                                            echo '</div>';
                                        }
                                    }

                                    // teachers to see which student submitted
                                    if($_SESSION['role'] == "teacher") {
                                        // query all students in the course
                                        $query_string_all = "SELECT studentid, name FROM studentcourses INNER JOIN students ON studentcourses.studentid = students.username WHERE courseid = '{$courseid}';";
                                        $queryallstudent = $con->prepare($query_string_all);
                                        $queryallstudent->execute();
                                        $allstudent = $queryallstudent->get_result();

                                        // query students who submitted
                                        $query_string_submit = "SELECT studentid, name, {$courseid}_sub_s.fileid, uploaddate, filedir, filename FROM (({$courseid}_sub_s
                                        INNER JOIN students ON {$courseid}_sub_s.studentid = students.username) 
                                        INNER JOIN {$courseid}_sub_t ON {$courseid}_sub_s.reqid = {$courseid}_sub_t.reqid) WHERE {$courseid}_sub_t.reqid = '{$reqid}';";
                                        $querysubmit = $con->prepare($query_string_submit);
                                        $querysubmit->execute();
                                        $submitted = $querysubmit->get_result();

                                        $array_of_students = [];
                                        $array_of_studentnames = [];
                                        $count = 0;

                                        // if student uploads more than one file
                                        $query_string_files = "SELECT * FROM {$courseid}_sub_s WHERE studentid = ?";
                                        $queryfiles = $con->prepare($query_string_files);
                                        $queryfiles->bind_param("s", $studentid);

                                        // fetch all students related to this course
                                        while($all_students = mysqli_fetch_array($allstudent)) {
                                            $array_of_students[] = $all_students["studentid"];
                                            $array_of_studentnames[] = $all_students["name"];
                                        }

                                        // create main container
                                        if(sizeof($array_of_students) != 0) {
                                            echo '<div class="view-box">
                                                    <div>Students\' submission</div>
                                                    <div class="view-desc">Click on the blue words to download the files.</div>
                                                    ';
                                        }

                                        // fetch students who submit
                                        while($spec_student = mysqli_fetch_array($submitted)) {
                                            
                                            // for the students that did not submit
                                            while($array_of_students[$count] != $spec_student["studentid"]) {
                                                echo
                                                '
                                                    <div class="view-each">
                                                        <div class="view-row">
                                                            <div class="view-left">Student ID: </div>
                                                            <div class="view-right">'.$array_of_students[$count].'</div>
                                                        </div>
                                                        <div class="view-row">
                                                            <div class="view-left">Student name: </div>
                                                            <div class="view-right">'.$array_of_studentnames[$count].'</div>
                                                        </div>
                                                        <div class="view-row">
                                                            <div class="view-left">File: </div>
                                                            <div class="view-right red">Not submitted</div>
                                                        </div>
                                                    </div>
                                                ';

                                                $count++;
                                            }
                                            
                                            // for students who submitted
                                            $studentid = $spec_student["studentid"];
                                            $queryfiles->execute();
                                            $queryfiles_obj = $queryfiles->get_result();

                                            echo
                                            '
                                                <div class="view-each">
                                                    <div class="view-row">
                                                        <div class="view-left">Student ID: </div>
                                                        <div class="view-right">'.$spec_student["studentid"].'</div>
                                                    </div>
                                                    <div class="view-row">
                                                        <div class="view-left">Student name: </div>
                                                        <div class="view-right">'.$spec_student["name"].'</div>
                                                    </div>
                                                    <div class="view-row">
                                                        <div class="view-left">File: </div>
                                                        <div class="view-right">';
                                                            while($studentfiles = mysqli_fetch_array($queryfiles_obj)) {
                                                            
                                                                echo '
                                                                <div class="view-row">
                                                                    <div class="view-middle"><a class="blue-link" href="'.$studentfiles["filedir"].'" downwload>'.$studentfiles["filename"].'</a></div>
                                                                    <div class="view-right green">Updated on '.dateAndTime($studentfiles["uploaddate"]).'</div>
                                                                </div>';

                                                            }
                                                        echo '
                                                        </div>
                                                    </div>
                                                </div>
                                            ';

                                            $count++;
                                        }

                                        // for the students that did not submit
                                        while($count < sizeof($array_of_students)) {
                                            echo
                                            '
                                                <div class="view-each">
                                                    <div class="view-row">
                                                        <div class="view-left">Student ID: </div>
                                                        <div class="view-right">'.$array_of_students[$count].'</div>
                                                    </div>
                                                    <div class="view-row">
                                                        <div class="view-left">Student name: </div>
                                                        <div class="view-right">'.$array_of_studentnames[$count].'</div>
                                                    </div>
                                                    <div class="view-row">
                                                        <div class="view-left">File: </div>
                                                        <div class="view-right red">Not submitted</div>
                                                    </div>
                                                </div>
                                            ';

                                            $count++;
                                        }

                                        // close main container
                                        if(sizeof($array_of_students) != 0) {
                                            echo '</div>';
                                        }
                                    }                                    
                                    
                                    echo '
                                        <div class="file-options-box teacher">';

                                        if($_SESSION['role'] != "parent") {
                                            if($_SESSION['role'] == "teacher") {
                                                echo '
                                                <div class="file-options">
                                                    <a class="black-link" href="view.php?id='.$courseid.'&addsubmission='.$reqid.'">
                                                        <div class="options-icon">
                                                            <span class="material-icons">
                                                                add
                                                            </span>
                                                        </div>
                                                        <div class="options-label">Add</div>
                                                    </a>
                                                </div>';                                           
                                                if($once['visibility'] == 1) {
                                                    echo ' 
                                                    <div class="file-options">
                                                        <form action="visible.php?id='.$courseid.'&currreq='.$reqid.'&visible=0" method="POST" class="v-form">
                                                            <button class="options-icon" type="submit">
                                                                <span class="material-icons">
                                                                    visibility_off
                                                                </span>
                                                                <div class="options-label">Hide</div>
                                                            </button>                                                
                                                        </form>
                                                    </div>';
                                                } else {
                                                    echo '
                                                    <div class="file-options">
                                                        <form action="visible.php?id='.$courseid.'&currreq='.$reqid.'&visible=1" method="POST" class="v-form">
                                                            <button class="options-icon" type="submit">
                                                                <span class="material-icons">
                                                                    visibility
                                                                </span>
                                                                <div class="options-label">Show</div>
                                                            </button>
                                                        </form>
                                                    </div>';
                                                }
                                            echo
                                                '</div>';
                                            
                                            echo '
                                                <div class="delete-submission">
                                                    <a href="warning.php?id='.$courseid.'&deletesubmission='.$reqid.'">
                                                        <button class="options-icon" type="submit">
                                                            <div class="options-label">Remove submission</div>
                                                        </button>                                                
                                                    </a>
                                                </div>';
                                            } else {
                                                echo '
                                                <div class="file-options">
                                                    <a class="black-link" href="view.php?id='.$courseid.'&addsubmission='.$reqid.'">
                                                        <div class="options-icon">
                                                            <span class="material-icons">
                                                                add
                                                            </span>
                                                        </div>
                                                        <div class="options-label">Add</div>
                                                    </a>
                                                </div>
                                                <div class="file-options">
                                                    <form action="view.php?id='.$courseid.'&removefiles='.$reqid.'" method="POST" class="v-form">
                                                        <button class="options-icon" type="submit">
                                                            <span class="material-icons">
                                                                delete
                                                            </span>
                                                            <div class="options-label">Remove</div>
                                                        </button>                                                
                                                    </form>
                                                </div>';
                                            }
                                        }
                                        echo '
                                        </div>
                                    </div>';
                                }
                            }
                        ?>
                        <?php
                            if(isset($_GET['addsubmission'])) {
                                $reqid = $_GET['addsubmission'];

                                if($_SESSION['role'] == "teacher") {
                                    $query_string_allfiles = "SELECT * FROM {$courseid} WHERE require_submission = 1";
                                    $query_allfiles = $con->prepare($query_string_allfiles);
                                    $query_allfiles->execute();
                                    $allfiles_obj = $query_allfiles->get_result();

                                    $query_string = "SELECT DISTINCT reqname FROM {$courseid}_sub_t WHERE reqid = ?;";
                                    $querysubmission = $con->prepare($query_string);
                                    $querysubmission->bind_param("s", $reqid);
                                    $querysubmission->execute();
                                    $submission = $querysubmission->get_result();
                                    $sub = mysqli_fetch_array($submission);

                                    echo '
                                    <div>
                                        <div>
                                            Submission Title: '.$sub["reqname"].'
                                        </div>
                                        <div>
                                            Select at least one file for your submission page. 
                                        </div>
                                    </div>';

                                    echo 
                                    '<div>
                                        <div class="file-select">
                                            <form action="addsubmission.php?id='.$courseid.'&submit='.$reqid.'" method="POST">
                                    ';

                                    while($file = mysqli_fetch_array($allfiles_obj)) {
                                        echo
                                        '
                                        <div>
                                            <input type="checkbox" name="file_'.$file["fileid"].'" id="file_'.$file["fileid"].'" value="'.$file["fileid"].'">
                                            <label class="week-avail" for="file_'.$file["fileid"].'">
                                                '.$file["filename"].'
                                            </label>
                                        </div>';
                                    }

                                    echo '</div>';

                                    echo '
                                    <div class="file-submission">
                                        <div class="submit-left">
                                        </div>
                                        <div class="submit-right">
                                            <a class="cancel" href="view.php?id='.$courseid.'&viewsubmission='.$reqid.'">Cancel</a>
                                            <button type="submit" name="submit" value="submit">Save</button>
                                        </div>
                                    </div>
                                    </form>
                                    </div>';
                                } else {
                                    echo '
                                    <div class="file-header">
                                        <div class="file-status">Add Files</div>
                                    </div>
                                    <div class="file-container">
                                        <form action="addsubmission.php?id='.$courseid.'&submit='.$reqid.'" method="POST" enctype="multipart/form-data">
                                            <div class="file-desc">
                                                <div>
                                                    <div class="file-edit">
                                                        <label for="selectedFile" class="fileupload">
                                                            <input type="file" id="selectedFile" name="selectedFile" required>
                                                            <span id="file-selected">Click to add a file</span>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="file-submission">
                                                <div class="submit-left">
                                                </div>
                                                <div class="submit-right">
                                                    <a class="cancel" href="view.php?id='.$courseid.'&viewsubmission='.$reqid.'">Cancel</a>
                                                    <button type="submit" value="submit">Save</button>
                                                </div>
                                            </div>
                                        </form>                      
                                    </div>';
                                }
                            }
                        ?>
                        <?php
                            if(isset($_GET['removefiles'])) {
                                $reqid = $_GET['removefiles'];

                                if($_SESSION['role'] == "teacher") {
                                    $query_string_allfiles = "SELECT filename FROM {$courseid} WHERE fileid = ?";
                                    $query_allfiles = $con->prepare($query_string_allfiles);
                                    $query_allfiles->bind_param("s", $fileid);

                                    $query_string = "SELECT DISTINCT reqname FROM {$courseid}_sub_t WHERE reqid = ?;";
                                    $querysubmission = $con->prepare($query_string);
                                    $querysubmission->bind_param("s", $reqid);
                                    $querysubmission->execute();
                                    $submission = $querysubmission->get_result();
                                    $sub = mysqli_fetch_array($submission);

                                    $query_string_file = "SELECT fileid FROM {$courseid}_sub_t WHERE reqid = ?;";
                                    $queryfile = $con->prepare($query_string_file );
                                    $queryfile->bind_param("s", $reqid);
                                    $queryfile->execute();
                                    $file_obj = $queryfile->get_result();

                                    echo '
                                    <div>
                                        <div>
                                            Submission Title: '.$sub["reqname"].'
                                        </div>
                                        <div>
                                            These are the files you placed in your submission page. Click at least one to remove. 
                                        </div>
                                    </div>';

                                    echo 
                                    '<div>
                                        <div class="file-select">
                                            <form action="removefiles.php?id='.$courseid.'&submit='.$reqid.'" method="POST">
                                    ';

                                    while($file = mysqli_fetch_array($file_obj)) {
                                        if($file['fileid'] == "000000")
                                            continue;

                                        $fileid = $file['fileid'];
                                        $query_allfiles->execute();
                                        $allfiles_obj = $query_allfiles->get_result();
                                        $filename = mysqli_fetch_array($allfiles_obj);

                                        echo
                                        '
                                        <div>
                                            <input type="checkbox" name="file_'.$file["fileid"].'" id="file_'.$file["fileid"].'" value="'.$file["fileid"].'">
                                            <label class="week-avail" for="file_'.$file["fileid"].'">
                                                '.$filename["filename"].'
                                            </label>
                                        </div>';
                                    }

                                    echo '</div>';

                                    echo '
                                    <div class="file-submission">
                                        <div class="submit-left"></div>
                                        <div class="submit-right">
                                            <a class="cancel" href="view.php?id='.$courseid.'&viewsubmission='.$reqid.'">Cancel</a>
                                            <button type="submit" name="submit" value="submit">Save</button>
                                        </div>
                                    </div>
                                    </form>
                                    </div>';
                                } else {
                                    $studentid = $_SESSION["username"];

                                    $query_string = "SELECT DISTINCT reqname FROM {$courseid}_sub_t WHERE reqid = ?;";
                                    $querysubmission = $con->prepare($query_string);
                                    $querysubmission->bind_param("s", $reqid);
                                    $querysubmission->execute();
                                    $submission = $querysubmission->get_result();
                                    $sub = mysqli_fetch_array($submission);

                                    $query_string_file = "SELECT * FROM {$courseid}_sub_s WHERE studentid = ?;";
                                    $queryfile = $con->prepare($query_string_file );
                                    $queryfile->bind_param("s", $studentid);
                                    $queryfile->execute();
                                    $file_obj = $queryfile->get_result();

                                    echo '
                                    <div class="remove-header">
                                        <div>
                                            Submission Title: '.$sub["reqname"].'
                                        </div>
                                        <div class="remove-subtitle">
                                            These are the files you have submitted. Click at least one to remove. 
                                        </div>
                                    </div>';

                                    echo 
                                    '<div class="remove-container">
                                        <form action="removefiles.php?id='.$courseid.'&submit='.$reqid.'" method="POST">
                                    ';

                                    while($file = mysqli_fetch_array($file_obj)) {
                                        echo
                                        '
                                        <div class="remove-box">
                                            <input type="checkbox" name="file_'.$file["fileid"].'" id="file_'.$file["fileid"].'" value="'.$file["fileid"].'">
                                            <label class="week-avail" for="file_'.$file["fileid"].'">
                                                '.$file["filename"].'
                                            </label>
                                        </div>';
                                    }

                                    echo '
                                    <div class="file-submission">
                                        <div class="submit-left"></div>
                                        <div class="submit-right">
                                            <a class="cancel" href="view.php?id='.$courseid.'&viewsubmission='.$reqid.'">Cancel</a>
                                            <button type="submit" name="submit" value="submit">Save</button>
                                        </div>
                                    </div>
                                    </form>
                                    </div>';
                                }
                            }
                        ?>
                    </div>
                </div>
            </div>
        </section>
    </div>
    <footer>
        Gryffindor learning management system. Made with <span class="material-icons material-icons-outlined" style="font-size: 16px; color: red;">favorite</span> by FSR GmbH.
    </footer>
    <script src="../js/options.js"></script>
    <script src="../js/responsive.js"></script>
    <script src="../js/displayfile.js"></script>
    <script>
        $(function() {
            $("#accessdate").datepicker({ minDate: 0 });
            $("#deaddate").datepicker({ minDate: 1 });
        });
    </script>
</body>