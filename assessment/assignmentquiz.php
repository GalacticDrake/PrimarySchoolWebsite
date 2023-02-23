<?php
    require('../db.php');
    include("../auth.php"); //include auth.php file on all secure pages
?>

<!DOCTYPE html>

<head>
    <title>Assessments</title>

    <meta name="viewport" content="width=device-width">
    
    <link rel="stylesheet" href="../css/main.css">
    <link rel="stylesheet" href="../css/assignmentquiz.css">
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
                    <li><a>Calendar</a></li>
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
                        <div class="dropdown-option">Account settings</div>
                        <div><a class="dropdown-option" href="../logout.php">Logout</a></div>
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
                        <li class="active"><a>Assignments/Exams</a></li>
                        <li><a href="grading.php">Grades</a></li>
                    </ul>
                </div>
                <div class="sidetabs">
                    <div class="sidetabs-title">Progression</div>
                    <div class="progress-section">
                        <div class="progress-bar">
                            <div class="progress" style="width: calc(100% - 6px);"></div>
                        </div> 
                        <div class="progress-value">100%</div>
                    </div>
                </div>
            </div>
            
            <div class="container-inner">
                <div class="assignment-header">

                    <div class="course-code">
                        <?php
                            echo strtoupper($courseid);
                        ?>
                    </div>
                    <div class="tab-title">
                        <?php
                            $course_name = "SELECT coursename FROM courseassessment WHERE courseid='".$courseid."'";
                            $result = mysqli_query($con, $course_name) or die(mysqli_error($con));

                            $display = mysqli_fetch_assoc($result);
                            echo $display["coursename"];
                        ?>
                    </div>
                </div>
                <?php
                    $week = "SELECT week FROM courseassessment WHERE courseid='".$courseid."'";
                    $result = mysqli_query($con, $week) or die(mysqli_error($con));

                    while($week_list =  mysqli_fetch_assoc($result)) {
                        echo '<div class="tabs">
                                <div class="tabs-header">
                                <div class="course-title">'.$display['week'].'</div>
                            </div>
                            <div class="tabs-container">';
                                
                                $assessments = "SELECT assessment_name FROM courseassessment WHERE courseid='".$courseid."'";
                                $result1 = mysqli_query($con, $assessments) or die(mysqli_error($con));

                                    while($assessment_list =  mysqli_fetch_assoc($result1)) {
                                        $spec_assessment = "SELECT assessment_name FROM courseassessment WHERE courseid='".$assessment_list['courseid']."'";
                                        $result_spec = mysqli_query($con, $spec_assessment) or die(mysqli_error($con));

                                        $display = mysqli_fetch_assoc($result_spec);  
                                        echo '<div class="tabs-option">
                                            <div class="tabs-thumbnail"></div>
                                            <div class="edit-option"><a href="../assessment/edit.php">Edit</a></div>
                                            <div class="tabs-option-title">'.$display['assessment_name'].'</div>
                                        </div>';
                                    }
                                echo '<div class="tabs-option">
                                    <a href="../assessment/add.php"><div class="add-tabs-thumbnail"></div></a>
                                </div>
                            </div>
                        </div>';
                    }
                ?>
            </div>
        </section>
    </div>
    <footer>
        Gryffindor learning management system. Made with <span class="material-icons material-icons-outlined" style="font-size: 16px; color: red;">favorite</span> by FSR GmbH.
    </footer>
    <script src="js/responsive.js"></script>
</body>