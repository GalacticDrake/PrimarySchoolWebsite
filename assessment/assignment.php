<?php
    require('../db.php');
    include("../auth.php"); //include auth.php file on all secure pages 
?>

<!DOCTYPE html>

<head>
    <title>Assessments</title>

    <meta name="viewport" content="width=device-width">
    
    <link rel="stylesheet" href="../css/main.css">
    <link rel="stylesheet" href="../css/assignment_student.css">
    <link rel="stylesheet" href="../css/mini-calendar.css">
    <link rel="stylesheet" href="../css/mobile.css">
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
                    <li><a href="../dashboard.php">Home</a></li>
                    <li><a href="../courses.php">Courses</a></li>
                    <li><a href="../timetable/view.php">Timetable</a></li>
                    <li><a href="../assessments.php">Exam</a></li>
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
                        <div class="dropdown-option">Account settings</div>
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
                    <div class="sidetabs-title">Quick Access</div>
                    <ul class="quick-access">
                        <li class="active"><a>Assignments</a></li>
                        <li><a href="quiz.php">Exams/Quizzes</a></li>
                        <li><a href="maingrades.php?view=gradesgrid">Grades</a></li>
                    </ul>
                </div>
                <div class="sidetabs">
                    <div class="sidetabs-title">Progression</div>
                    <div class="progress-section">
                        <?php 
                            if($_SESSION['role'] == "parent") {
                                $children = "SELECT username FROM students WHERE parentid= '".$_SESSION['username']."'";
                                $result = mysqli_query($con, $children) or die(mysqli_error($con));

                                while($rows = mysqli_fetch_assoc($result)) {
                                    $student_name = "SELECT name FROM students WHERE studentid= '".$_SESSION['username']."'";
                                    $result1 = mysqli_query($con, $student_name) or die(mysqli_error($con));
                                    $display = mysqli_fetch_assoc($result1);
                                    echo '<div class="progress-bar">
                                        <div class="child">'.$display['name'].'</div>
                                        <div class="progress" style="width: calc(100% - 6px);"></div>
                                    </div> 
                                    <div class="progress-value">100%</div>';
                                }
                            } else {
                                echo '<div class="progress-bar">
                                    <div class="progress" style="width: calc(100% - 6px);"></div>
                                </div> 
                                <div class="progress-value">100%</div>';
                            }
                        ?>
                    </div>
                </div>
            </div>
            <?php
                if($_SESSION['role'] == "student") {
                    echo '<div class="container-inner">
                        <div class="assignment-header">
                            <div class="tab-title">Assignments</div>
                        </div>';

                        $user_assessment = "SELECT assessment_type FROM upcomingassessment WHERE studentid= '".$_SESSION['username']."'";
                        $result = mysqli_query($con, $user_assessment) or die(mysqli_error($con));

                        if($result['assessment_type'] == 'Assignment') {
                            $course_name = "SELECT coursename FROM upcomingassessment WHERE assessment_type='".$result['assessment_type']."'";
                            $result_course = mysqli_query($con, $course_name) or die(mysqli_error($con));

                            while($course_list =  mysqli_fetch_assoc($result_course)) {
                                $spec_course = "SELECT coursename FROM upcomingassessment WHERE coursename='".$course_list['coursename']."'";
                                $result_spec = mysqli_query($con, $spec_course) or die(mysqli_error($con));

                                $display = mysqli_fetch_assoc($result_spec); 
                                echo '<div class="tabs">
                                    <div class="tabs-header">
                                        <div class="course-title">
                                            '.$display['coursename'].'
                                        </div>
                                    </div>';
                                    $assignment = "SELECT assessment_name FROM upcomingassessment WHERE coursename='".$result_spec['coursename']."'";
                                    $result_assignment = mysqli_query($con, $assignment) or die(mysqli_error($con));

                                    while($assignment_list =  mysqli_fetch_assoc($result_assignment))
                                        $spec_assignment = "SELECT assessment_name FROM upcomingassessment WHERE assessment_name='".$assignment_list['assessment_name']."'";
                                        $result_spec_assignment = mysqli_query($con, $spec_assignment) or die(mysqli_error($con));
        
                                        $display = mysqli_fetch_assoc($result_spec_assignment); 
                                        echo '<div class="tabs-container">
                                            <div class="tabs-option">
                                                <a href="../submission/addsubmission.php"><div class="tabs-thumbnail"></div></a>
                                                <div class="tabs-option-title">'.$display['assessment_name'].'</div>
                                            </div>
                                        </div>';
                                    }
                                echo '</div>';
                            }
                        }
                    echo '</div>';
                } else {
                    echo '<div class="container-inner">
                        <div class="assignment-header">
                            <div class="tab-title">Assignments</div>
                            <div class="view-child">
                                <div class="child-list">
                                    <div class="child-icon" style="background: var(--dark-pink);"></div>
                                    Child 1
                                </div>
                                <div class="child-list">
                                    <div class="child-icon" style="background: var(--pink);"></div>
                                    Child 2
                                </div>
                            </div>
                        </div>';

                        $user_assessment = "SELECT assessment_type FROM upcomingassessment WHERE studentid= '".$_SESSION['username']."'";
                        $result = mysqli_query($con, $user_assessment) or die(mysqli_error($con));

                        if($result['assessment_type'] == 'Assignment') {
                            $course_name = "SELECT coursename FROM upcomingassessment WHERE assessment_type='".$result['assessment_type']."'";
                            $result_course = mysqli_query($con, $course_name) or die(mysqli_error($con));

                            while($course_list =  mysqli_fetch_assoc($result_course)) {
                                $spec_course = "SELECT coursename FROM upcomingassessment WHERE coursename='".$course_list['coursename']."'";
                                $result_spec = mysqli_query($con, $spec_course) or die(mysqli_error($con));

                                $display = mysqli_fetch_assoc($result_spec); 
                                echo '<div class="tabs">
                                    <div class="tabs-header">
                                        <div class="course-title">
                                            '.$display['coursename'].'
                                        </div>
                                        <div class="tabs-status">
                                            Child done on time.
                                        </div>
                                    </div>';
                                    $assignment = "SELECT assessment_name FROM upcomingassessment WHERE coursename='".$result_spec['coursename']."'";
                                    $result_assignment = mysqli_query($con, $assignment) or die(mysqli_error($con));

                                    while($assignment_list =  mysqli_fetch_assoc($result_assignment))
                                        $spec_assignment = "SELECT assessment_name FROM upcomingassessment WHERE assessment_name='".$assignment_list['assessment_name']."'";
                                        $result_spec_assignment = mysqli_query($con, $spec_assignment) or die(mysqli_error($con));
        
                                        $display = mysqli_fetch_assoc($result_spec_assignment); 
                                        echo '<div class="tabs-container">
                                            <div class="tabs-option">
                                                <div class="tabs-thumbnail">
                                                    <div class="child-icon" style="background: var(--dark-pink);"></div>
                                                    <div class="child-icon" style="background: var(--pink);"></div>
                                                </div>
                                                <div class="tabs-option-title">'.$display['assessment_name'].'</div>
                                            </div>
                                        </div>';
                                    }
                                echo '</div>';
                            }
                        }
                    echo '</div>';
                }
            ?>
        </section>
    </div>
    <footer>
        Gryffindor learning management system. Made with <span class="material-icons material-icons-outlined" style="font-size: 16px; color: red;">favorite</span> by FSR GmbH.
    </footer>
    <script src="js/responsive.js"></script>
</body>