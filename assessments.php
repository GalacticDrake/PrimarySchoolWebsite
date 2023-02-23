<?php
    require('db.php');
    include("auth.php"); //include auth.php file on all secure pages
?>

<!DOCTYPE html>

<head>
    <title>Assessments</title>

    <meta name="viewport" content="width=device-width">
    
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="css/assessments.css">
    <link rel="stylesheet" href="css/mini-calendar.css">
    <link rel="stylesheet" href="css/mobile.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Oxygen">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
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
                    <li><a href="dashboard.php">Home</a></li>
                    <li><a href="courses.php">Courses</a></li>
                    <li><a href="timetable/view.php">Timetable</a></li>
                    <li class="active"><a>Assessments</a></li>
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
                        <div><a class="dropdown-option" href="profile.php">Account settings</a></div>
                        <div><a class="dropdown-option" href="logout.php">Logout</a></div>
                        <div class="dropdown-title">Gryffindor System</div>
                    </div>
                </div>
            </div>
        </div>
        <section class="container">
            <div class="sidenav" id="sidenav">
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
                    <div class="sidetabs-title">View</div>
                    <select name="view" id="view" onchange="changeView()">
                        <option value="grid">Grid</option>
                        <option value="list">List</option>
                    </select>
                </div>
                <div class="sidetabs">
                    <div class="sidetabs-title">Sort</div>
                    <select name="view" id="view">
                        <option>A-Z</option>
                        <option>Course category</option>
                    </select>
                </div>
                
                <div class="sidetabs">
                    <div class="sidetabs-title">Filter</div>
                </div>
            </div>
            
            <div class="container-inner">
                <?php /* assignment tabs */
                    
                    $count = 0;    
                
                     switch($_SESSION['role']) {
                        case 'student':
                            echo '<div class="tabs tabs-grid">
                                <div class="inner-tabs inner-tabs-grid">'; 
                                    $user_assessment_assignment = "SELECT DISTINCT courseid, coursename FROM upcomingassessment WHERE (studentid= '".$_SESSION['username']."') AND (assessment_type='Assignment')";
                                    $result = mysqli_query($con, $user_assessment_assignment) or die(mysqli_error($con));
                             
                                    $quick_access_id = array_fill(0, 4, "");
                                    $quick_access_name = array_fill(0, 4, "");

                                    while($course_list_assignment =  mysqli_fetch_assoc($result)) {
                                                echo '<a href="assessment/view.php?id='.$course_list_assignment['courseid'].'"><div class="upcoming-event">
                                                    <div class="event-title">'.$course_list_assignment['coursename'].'</div>';

                                                    $assignment = "SELECT assessment_name FROM upcomingassessment WHERE (coursename='".$course_list_assignment['coursename']."') AND (assessment_type='Assignment') AND (studentid= '".$_SESSION['username']."')";
                                                    $result_assg = mysqli_query($con, $assignment) or die(mysqli_error($con));
                                                    while($assignment_list =  mysqli_fetch_assoc($result_assg)) {
                                                        echo '<div class="event">'.$assignment_list['assessment_name'].'</div>';
                                                    }
                                                echo '</div></a>';
                                        
                                             // save for quick access
                                            if($count < 4) {
                                                if($quick_access_id[$count] == "" && !isset($_SESSION['quickaccessid'])) {
                                                    $quick_access_id[$count] = $course_list_assignment['courseid'];
                                                    $quick_access_name[$count] = $course_list_assignment['coursename'];
                                                    $count++;
                                                }
                                            }
                                    }
                                echo '</div>
                                <div class="tab-title">Assignments</div>             
                                </div>';
                                break;
                            case 'teacher':
                                    echo '<div class="tabs tabs-grid">
                                    <div class="inner-tabs inner-tabs-grid">';
                                    $user_assessment_assignment = "SELECT DISTINCT courseid, coursename FROM courseassessment WHERE (teacherid= '".$_SESSION['username']."') AND (assessment_type='Assignment')";
                                    $result = mysqli_query($con, $user_assessment_assignment) or die(mysqli_error($con));
                             
                                    $quick_access_id = array_fill(0, 4, "");
                                    $quick_access_name = array_fill(0, 4, "");


                                    while($course_list_assignment =  mysqli_fetch_assoc($result)) {
                                        echo '<a href="assessment/view.php?id='.strtolower($course_list_assignment['courseid']).'"><div class="upcoming-event">
                                                    <div class="event-title">'.$course_list_assignment['coursename'].'</div>';

                                        $assignment = "SELECT assessment_name FROM courseassessment WHERE (coursename='".$course_list_assignment['coursename']."') AND (assessment_type='Assignment') AND (teacherid= '".$_SESSION['username']."')";
                                        $result_assg = mysqli_query($con, $assignment) or die(mysqli_error($con));
                                        while($assignment_list =  mysqli_fetch_assoc($result_assg)) {
                                            echo '<div class="event">'.$assignment_list['assessment_name'].'</div>';
                                            
                                             // save for quick access
                                            if($count < 4) {
                                                if($quick_access_id[$count] == "" && !isset($_SESSION['quickaccessid'])) {
                                                    $quick_access_id[$count] = $course_list_exam['courseid'];
                                                    $quick_access_name[$count] = $course_list_exam['coursename'];
                                                    $count++;
                                                }
                                            }
                                                        
                                        }
                                        echo '</div> </a>';
                                    }
                                    echo '</div>
                                    <div class="tab-title">Assignments</div>             
                                </div>';
                                break;
                             
                         case 'parent':
                                  
                            echo '<div class="tabs tabs-grid">
                                <div class="inner-tabs inner-tabs-grid">'; 
                                $children = "SELECT name, username FROM students WHERE parentid= '".$_SESSION['username']."'";
                                $result_child = mysqli_query($con, $children) or die(mysqli_error($con));

                                $quick_access_id = array_fill(0, 4, "");
                                $quick_access_name = array_fill(0, 4, "");
                                  
                                $dupe_check = [];
                                while($rows = mysqli_fetch_assoc($result_child)) {
                                        
                                    $user_assessment_assignment = "SELECT DISTINCT courseid, coursename FROM upcomingassessment WHERE (studentid= '".$rows['username']."') AND (assessment_type='Assignment')";
                                    $result = mysqli_query($con, $user_assessment_assignment) or die(mysqli_error($con));

                                    while($course_list_assignment =  mysqli_fetch_assoc($result)) {
                                        for($j = 0; $j < sizeof($dupe_check) + 1; $j++) {
                                            if($j == sizeof($dupe_check)) {
                                                $dupe_check[$j] = $course_list_assignment['coursename'];
                                                echo '<a href="assessment/view.php?id='.$course_list_assignment['courseid'].'"><div class="upcoming-event">
                                                    <div class="event-title">'.$course_list_assignment['coursename'].'</div>';

                                                    $assignment = "SELECT courseid, assessment_name FROM upcomingassessment WHERE (coursename='".$course_list_assignment['coursename']."') AND (assessment_type='Assignment') AND (studentid= '".$rows['username']."')";
                                                    $result_assignment = mysqli_query($con, $assignment) or die(mysqli_error($con));
                                                    while($assignment_list =  mysqli_fetch_assoc($result_assignment)) {
                                                        echo '<div class="event">'.$assignment_list['assessment_name'].'</div>';
                                                    }
                                                    echo '</div></a>';
                                            
                                                     if($count < 4) {
                                                        if($quick_access_id[$count] == "" && !isset($_SESSION['quickaccessid'])) {
                                                            $quick_access_id[$count] = $course_list_assignment['courseid'];
                                                            $quick_access_name[$count] = $course_list_assignment['coursename'];
                                                            $count++;
                                                        }
                                                    }
                                                
                                                    break;
                                            }
                                            else if($dupe_check[$j] == $course_list_assignment['coursename']) {
                                                break;
                                            }                                      
                                        }
                                    }
                                }
                                echo '</div>
                                <div class="tab-title">Assignment</div>             
                            </div>';
                        break;
                     }
                ?>
                <?php /* exam/quiz tabs */
                 
                    $count = 0;    
                
                    switch($_SESSION['role']) {
                            
                        case 'student':
                            echo '<div class="tabs tabs-grid">
                                <div class="inner-tabs inner-tabs-grid">';

                                    $user_assessment_exam = "SELECT DISTINCT courseid, coursename FROM upcomingassessment WHERE (studentid= '".$_SESSION['username']."') AND (assessment_type='Exam/Quiz')";
                                    $result = mysqli_query($con, $user_assessment_exam) or die(mysqli_error($con));
                            
                                    $quick_access_id = array_fill(0, 4, "");
                                    $quick_access_name = array_fill(0, 4, "");

                                    while($course_list_exam =  mysqli_fetch_assoc($result)) {
                                            echo '<a href="assessment/view.php?id='.$course_list_exam['courseid'].'"><div class="upcoming-event">
                                                <div class="event-title">'.$course_list_exam['coursename'].'</div>';

                                                $exam = "SELECT assessment_name FROM upcomingassessment WHERE (coursename='".$course_list_exam['coursename']."') AND (assessment_type='Exam/Quiz') AND (studentid= '".$_SESSION['username']."')";
                                                $result_exam = mysqli_query($con, $exam) or die(mysqli_error($con));
                                                while($exam_list =  mysqli_fetch_assoc($result_exam)) {
                                                        echo '<div class="event">'.$exam_list['assessment_name'].'</div>';
                                                }
                                            echo '</div></a>';
                                        
                                             // save for quick access
                                            if($count < 4) {
                                                if($quick_access_id[$count] == "" && !isset($_SESSION['quickaccessid'])) {
                                                    $quick_access_id[$count] = $course_list_exam['courseid'];
                                                    $quick_access_name[$count] = $course_list_exam['coursename'];
                                                    $count++;
                                                }
                                            }
                                    }

                                echo '</div>';
                             echo '<div class="tab-title">Exam/Quiz</div>
                            </div>';
                            break;
                        case 'teacher':
                            echo '<div class="tabs tabs-grid">
                                <div class="inner-tabs inner-tabs-grid">';

                                    $user_assessment_exam = "SELECT DISTINCT courseid, coursename FROM courseassessment WHERE (teacherid= '".$_SESSION['username']."') AND (assessment_type='Exam/Quiz')";
                                    $result = mysqli_query($con, $user_assessment_exam) or die(mysqli_error($con));
                            
                                    $quick_access_id = array_fill(0, 4, "");
                                    $quick_access_name = array_fill(0, 4, "");

                                    while($course_list_exam =  mysqli_fetch_assoc($result)) {
                                            echo '<a href="assessment/view.php?id='.strtolower($course_list_exam['courseid']).'"><div class="upcoming-event">
                                                <div class="event-title">'.$course_list_exam['coursename'].'</div>';

                                                $exam = "SELECT assessment_name FROM courseassessment WHERE (coursename='".$course_list_exam['coursename']."') AND (assessment_type='Exam/Quiz') AND (teacherid= '".$_SESSION['username']."')";
                                                $result_exam = mysqli_query($con, $exam) or die(mysqli_error($con));
                                                while($exam_list =  mysqli_fetch_assoc($result_exam)) {
                                                    echo '<div class="event">'.$exam_list['assessment_name'].'</div>';
                                                    
                                                    // save for quick access
                                                    if($count < 4) {
                                                        if($quick_access_id[$count] == "" && !isset($_SESSION['quickaccessid'])) {
                                                            $quick_access_id[$count] = $course_list_exam['courseid'];
                                                            $quick_access_name[$count] = $course_list_exam['coursename'];
                                                            $count++;
                                                        }
                                                    }
                                                }
                                            echo '</div> </a>';
                                    }

                                echo '</div>';
                             echo '<div class="tab-title">Exam/Quiz</div>
                            </div>';
                            break;
                            
                        case 'parent':
                           echo '<div class="tabs tabs-grid">
                                <div class="inner-tabs inner-tabs-grid">'; 
                                $children = "SELECT name, username FROM students WHERE parentid= '".$_SESSION['username']."'";
                                $result_child = mysqli_query($con, $children) or die(mysqli_error($con));

                                $quick_access_id = array_fill(0, 4, "");
                                $quick_access_name = array_fill(0, 4, "");
                                  
                                $dupe_check = [];
                                while($rows = mysqli_fetch_assoc($result_child)) {
                                        
                                    $user_assessment_exam = "SELECT DISTINCT courseid, coursename FROM upcomingassessment WHERE (studentid= '".$rows['username']."') AND (assessment_type='Exam/Quiz')";
                                    $result = mysqli_query($con, $user_assessment_exam) or die(mysqli_error($con));

                                    while($course_list_exam =  mysqli_fetch_assoc($result)) {
                                        for($j = 0; $j < sizeof($dupe_check) + 1; $j++) {
                                            if($j == sizeof($dupe_check)) {
                                                $dupe_check[$j] = $course_list_exam['coursename'];
                                                echo '<a href="assessment/view.php?id='.$course_list_exam['courseid'].'"><div class="upcoming-event">
                                                    <div class="event-title">'.$course_list_exam['coursename'].'</div>';

                                                    $exam = "SELECT courseid, assessment_name FROM upcomingassessment WHERE (coursename='".$course_list_exam['coursename']."') AND (assessment_type='Exam/Quiz') AND (studentid= '".$rows['username']."')";
                                                    $result_exam = mysqli_query($con, $exam) or die(mysqli_error($con));
                                                    while($exam_list =  mysqli_fetch_assoc($result_exam)) {
                                                        echo '<div class="event">'.$exam_list['assessment_name'].'</div>';
                                                    }
                                                    echo '</div></a>';
                                            
                                                     if($count < 4) {
                                                        if($quick_access_id[$count] == "" && !isset($_SESSION['quickaccessid'])) {
                                                            $quick_access_id[$count] = $course_list_exam['courseid'];
                                                            $quick_access_name[$count] = $course_list_exam['coursename'];
                                                            $count++;
                                                        }
                                                    }
                                                
                                                    break;
                                            }
                                            else if($dupe_check[$j] == $course_list_exam['coursename']) {
                                                break;
                                            }                                      
                                        }
                                    }
                                }
                                echo '</div>
                                <div class="tab-title">Exam/Quiz</div>             
                            </div>';
                        break;
                    }
                ?>
                <?php /* grades */
                
                    $count = 0;
                    
                    switch($_SESSION['role']) {
                        case 'student':
                            echo '<div class="tabs tabs-grid">
                                <div class="inner-tabs inner-tabs-grid">';
                                $user_grade = "SELECT avgmarks, coursename, courseid FROM coursemarks WHERE studentid= '".$_SESSION['username']."'";
                                $result_grade = mysqli_query($con, $user_grade) or die(mysqli_error($con));
                            
                                $course_name;
                            
                                $quick_access_id = array_fill(0, 4, "");
                                $quick_access_name = array_fill(0, 4, "");

                                while($grade =  mysqli_fetch_assoc($result_grade)) {
                                    echo '<a href="assessment/grades.php?id='.$grade['courseid'].'"><div class="upcoming-event">
                                        <div class="event-title">'.$grade['coursename'].'</div>
                                        <div class="event">
                                            <div class="grade-view" style="background-image: conic-gradient(pink '.$grade['avgmarks'].'%, rgb(193, 250, 255) 0);">'.$grade['avgmarks'].'%</div>
                                        </div>
                                    </div></a>';
                                   
                                    if($count < 4) {
                                        if($quick_access_id[$count] == "" && !isset($_SESSION['quickaccessid'])) {
                                            $quick_access_id[$count] = $grade['courseid'];
                                            $quick_access_name[$count] = $grade['coursename'];
                                            $count++;
                                        }
                                    }
                                }
                            
                                echo '</div> 
                                 <div class="tab-title">Grades</div>
                            </div>';
                            break;
                            
                        case 'teacher':
                             echo '<div class="tabs tabs-grid">
                                <div class="inner-tabs inner-tabs-grid">';
                                $user_grade = "SELECT courseid, coursename, assessment_name, marks FROM studentavgmarks WHERE teacherid= '".$_SESSION['username']."'";
                                $result_grade = mysqli_query($con, $user_grade) or die(mysqli_error($con));
                            
                                $quick_access_id = array_fill(0, 4, "");
                                $quick_access_name = array_fill(0, 4, "");

                                while($grade =  mysqli_fetch_assoc($result_grade)) {
                                    echo '<a href="assessment/grading.php?id='.$grade['courseid'].'"><div class="upcoming-event">
                                        <div class="event-title">'.$grade['coursename'].' '.$grade['assessment_name'].'</div>
                                        <div class="event">
                                            <div class="grade-view" style="background-image: conic-gradient(pink '.$grade['marks'].'%, rgb(193, 250, 255) 0);">'.$grade['marks'].'%</div>
                                        </div>
                                    </div> </a>';
                                    
                                    // save for quick access
                                    if($count < 4) {
                                        if($quick_access_id[$count] == "" && !isset($_SESSION['quickaccessid'])) {
                                            $quick_access_id[$count] = $grade['courseid'];
                                            $quick_access_name[$count] = $grade['coursename'];
                                            $count++;
                                        }
                                    }
                                }
                                echo '</div> 
                                 <div class="tab-title">Grades</div>
                            </div>';
                            break;
                            
                        case 'parent':
                             echo '<div class="tabs tabs-grid">
                                    <div class="inner-tabs inner-tabs-grid">'; 
                                    $children = "SELECT name, username FROM students WHERE parentid= '".$_SESSION['username']."'";
                                    $result_child = mysqli_query($con, $children) or die(mysqli_error($con));
                                   
                                    $quick_access_id = array_fill(0, 4, "");
                                    $quick_access_name = array_fill(0, 4, "");
                            
                                    while($rows = mysqli_fetch_assoc($result_child)) {
                                      
                                        $user_grade = "SELECT avgmarks, coursename, courseid FROM coursemarks WHERE studentid= '".$rows['username']."'";
                                        $result_grade = mysqli_query($con, $user_grade) or die(mysqli_error($con));

                                        while($grade =  mysqli_fetch_assoc($result_grade)) {
                                            echo '<a href="assessment/grades.php?id='.$grade['courseid'].'"><div class="upcoming-event">
                                                <div class="event-title">'.$rows['username'].' '.$grade['coursename'].'</div>
                                                <div class="event">
                                                    <div class="grade-view" style="background-image: conic-gradient(pink '.$grade['avgmarks'].'%, rgb(193, 250, 255) 0);">'.$grade['avgmarks'].'%</div>
                                                </div>
                                            </div></a>';
                                                    
                                            // save for quick access
                                            if($count < 4) {
                                                if($quick_access_id[$count] == "" && !isset($_SESSION['quickaccessid'])) {
                                                    $quick_access_id[$count] = $grade['courseid'];
                                                    $quick_access_name[$count] = $grade['coursename'];
                                                    $count++;
                                                }
                                            }  
                                        }
                                    }
                             echo '</div> 
                                 <div class="tab-title"><a href="assessment/maingrades.php">Grades</a></div>
                            </div>';
                            break; 
                    }
                ?>
            </div>
        </section>
    </div>
    <footer>
        Gryffindor learning management system. Made with <span class="material-icons material-icons-outlined" style="font-size: 16px; color: red;">favorite</span> by FSR GmbH.
    </footer>
    <script src="js/options.js"></script>
    <script src="js/responsive.js"></script>
</body>