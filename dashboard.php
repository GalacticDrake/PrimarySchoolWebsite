<?php
    require('db.php');
    include("auth.php"); //include auth.php file on all secure pages
?>

<!DOCTYPE html>

<head>
    <title>Dashboard</title>

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="css/dashboard.css">
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
                    <li class="active"><a>Home</a></li>
                    <li><a href="courses.php">Courses</a></li>
                    <li><a href="timetable/view.php">Timetable</a></li>
                    <li><a href="assessments.php">Assessments</a></li>
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
            <div class="container-inner">
                <div class="tabs">
                    <div class="tab-title universal-title">Announcement</div>
                    <div class="inner-tabs">
                    </div>                
                </div>
                <div class="tabs">
                    <div class="tab-title universal-title">Inbox</div>
                    <div class="inner-tabs">
                    </div>                
                </div>
                <div class="tabs">
                    <div class="tab-title universal-title">Courses</div>
                    <div class="inner-tabs">
                        <?php
                            $count = 0; // for quick access counter

                            // when the user logged in, his role is stored in session (student, parent or teacher)
                            // depending on the user's role, contents displayed will differ
                            switch($_SESSION['role']) {
                                // if student, we want to show only his courses (that he was allocated for)
                                case 'student':
                                    // querystring for stdent, where it finds the courseid from studentcourses
                                    // there can be multiple courseids since the student will be enrolled to multiple courses
                                    $user_course = "SELECT courseid FROM studentcourses WHERE studentid= '".$_SESSION['username']."'";
                                    $result = mysqli_query($con, $user_course) or die(mysqli_error($con));

                                    // inefficient way but will do for now
                                    $quick_access_id = array_fill(0, 4, "");
                                    $quick_access_name = array_fill(0, 4, "");
                                
                                    // if there is more than 1, $result will be a set of data objects, which requires fetching
                                    while($course_list =  mysqli_fetch_assoc($result)) {
                                        // find the coursename and courseid for this particular course (the previous does not contain coursename)
                                        $spec_course = "SELECT coursename, courseid FROM courses WHERE courseid='".$course_list['courseid']."'";
                                        $result_spec = mysqli_query($con, $spec_course) or die(mysqli_error($con));

                                        // $display here contains an array of columns (coursename and courseid, depending on the order we listed in $spec_course)
                                        // each column can be accessed with $display['columnname']
                                        $display = mysqli_fetch_assoc($result_spec);
                                        
                                        // display the link and div out in the web browser
                                        // the link and name change depending on what was extracted in $spec_course; i.e. if stem0301 and mathematics 3, they use these values
                                        echo '<a href="course/view.php?id='.$display['courseid'].'"><div class="mini-tabs">'.$display['coursename'].'</div></a>';

                                        // save for quick access
                                        if($count < 4) {
                                            if($quick_access_id[$count] == "" && !isset($_SESSION['quickaccessid'])) {
                                                $quick_access_id[$count] = $display['courseid'];
                                                $quick_access_name[$count] = $display['coursename'];
                                                $count++;
                                            }
                                        }
                                    }
                                    
                                    break;

                                // if teacher, we want to show what courses this teacher is in charge of
                                // code below is similar to student
                                case 'teacher':
                                    $user_course = "SELECT courseid FROM teachercourses WHERE teacherid= '".$_SESSION['username']."'";
                                    $result = mysqli_query($con, $user_course) or die(mysqli_error($con));
                                
                                    // inefficient way but will do for now
                                    $quick_access_id = array_fill(0, 4, "");
                                    $quick_access_name = array_fill(0, 4, "");

                                    while($course_list =  mysqli_fetch_assoc($result)) {
                                        $spec_course = "SELECT coursename, courseid FROM courses WHERE courseid='".$course_list['courseid']."'";
                                        $result_spec = mysqli_query($con, $spec_course) or die(mysqli_error($con));

                                        $display = mysqli_fetch_assoc($result_spec);   
                                        echo '<a href="course/view.php?id='.$display['courseid'].'"><div class="mini-tabs">'.$display['coursename'].'</div></a>';
                                    
                                        // save for quick access
                                        if($count < 4) {
                                            if($quick_access_id[$count] == "" && !isset($_SESSION['quickaccessid'])) {
                                                $quick_access_id[$count] = $display['courseid'];
                                                $quick_access_name[$count] = $display['coursename'];
                                                $count++;
                                            }
                                        }
                                    }
                                    
                                    break;

                                // for parents, this might be more complex
                                // one parent may have multiple children, and we want to show all children's courses
                                case 'parent':
                                    // do the same as in student
                                    $children = "SELECT username FROM students WHERE parentid= '".$_SESSION['username']."'";
                                    $result = mysqli_query($con, $children) or die(mysqli_error($con));
                                    
                                    // $i = 0; no idea what this is, remove if it does not affect
                                    $dupe_check = []; // checks if there are more than 1 student taking the same course

                                    // inefficient way but will do for now
                                    $quick_access_id = array_fill(0, 4, "");
                                    $quick_access_name = array_fill(0, 4, "");

                                    // fetch results
                                    while($rows = mysqli_fetch_assoc($result)) {
                                        // find the courseids for each child
                                        $user_course = "SELECT courseid FROM studentcourses WHERE studentid= '".$rows['username']."'";
                                        $result_name = mysqli_query($con, $user_course) or die(mysqli_error($con));                                                                  
                                    
                                        // identify each courseid properties
                                        while($course_list =  mysqli_fetch_assoc($result_name)) {
                                            
                                            // same as in student
                                            $spec_course = "SELECT coursename, courseid FROM courses WHERE courseid='".$course_list['courseid']."'";
                                            $result_spec = mysqli_query($con, $spec_course) or die(mysqli_error($con));

                                            $display = mysqli_fetch_assoc($result_spec);
                                            
                                            // this code prevents duplicating of courses
                                            // dupe_check stores each unique course
                                            // only courses not found in dupe_check will be displayed
                                            for($j = 0; $j < sizeof($dupe_check) + 1; $j++) {
                                                if($j == sizeof($dupe_check)) {
                                                    $dupe_check[$j] = $display['coursename'];
                                                    echo '<a href="course/view.php?id='.$display['courseid'].'"><div class="mini-tabs">'.$display['coursename'].'</div></a>';
                                                    
                                                    // save for quick access
                                                    if($count < 4) {
                                                        if($quick_access_id[$count] == "" && !isset($_SESSION['quickaccessid'])) {
                                                            $quick_access_id[$count] = $display['courseid'];
                                                            $quick_access_name[$count] = $display['coursename'];
                                                            $count++;                                                            
                                                        }
                                                    }                                                   
                                                    break;
                                                } else if($dupe_check[$j] == $display['coursename']) {
                                                    break;
                                                }                                      
                                            }
                                        }
                                    }                                    

                                    break;
                                default:
                            }

                            if(!isset($_SESSION['quickaccessid'])) {                                                              
                                $_SESSION['quickaccessid'] = $quick_access_id;
                                $_SESSION['quickaccessname'] = $quick_access_name;
                            }
                        ?>
                    </div>                
                </div>
                <div class="tabs">
                    <div class="tab-title universal-title">Assignments and Exams</div>
                    <div class="inner-tabs">
                    <?php
                            $count = 0;
                            switch($_SESSION['role']) {
                                case 'student':
                                   
                                    $user_assessment = "SELECT courseid, assessment_name, coursename FROM upcomingassessment WHERE studentid= '".$_SESSION['username']."'";
                                    $result = mysqli_query($con, $user_assessment) or die(mysqli_error($con));
                                    
                                    $quick_access_id = array_fill(0, 4, "");
                                    $quick_access_name = array_fill(0, 4, "");
                                
                                    while($assessment_list =  mysqli_fetch_assoc($result)) {
                                        
                                        echo '<a href="assessment/view.php?id='.$assessment_list['courseid'].'"><div class="mini-tabs">'.$assessment_list['coursename'].' '.$assessment_list['assessment_name'].'</div></a>';
                                        
                                        if($count < 4) {
                                            if($quick_access_id[$count] == "" && !isset($_SESSION['quickaccessid'])) {
                                                $quick_access_id[$count] = $assessment_list['courseid'];
                                                $quick_access_name[$count] = $assessment_list['coursename'];
                                                $count++;
                                            }
                                        }
                                    }
                                    
                                    break;

                                case 'teacher':
                                    $user_assessment = "SELECT courseid, assessment_name, coursename FROM courseassessment WHERE teacherid= '".$_SESSION['username']."'";
                                    $result = mysqli_query($con, $user_assessment) or die(mysqli_error($con));
                                    
                                    $quick_access_id = array_fill(0, 4, "");
                                    $quick_access_name = array_fill(0, 4, "");
                                
                                    while($assessment_list =  mysqli_fetch_assoc($result)) {
                                       
                                        echo '<a href="assessment/view.php?id='.$assessment_list['courseid'].'"><div class="mini-tabs">'.$assessment_list['coursename'].' '.$assessment_list['assessment_name'].'</div></a>';
                                        
                                        if($count < 4) {
                                            if($quick_access_id[$count] == "" && !isset($_SESSION['quickaccessid'])) {
                                                $quick_access_id[$count] = $assessment_list['courseid'];
                                                $quick_access_name[$count] = $assessment_list['coursename'];
                                                $count++;
                                            }
                                        }
                                    }
                                    
                                    break;

                                 case 'parent':
                                   
                                    $children = "SELECT username FROM students WHERE parentid= '".$_SESSION['username']."'";
                                    $result = mysqli_query($con, $children) or die(mysqli_error($con));
                                    
                                    
                                    $dupe_check = []; // checks if there are more than 1 student taking the same assessment

                                   
                                    $quick_access_id = array_fill(0, 4, "");
                                    $quick_access_name = array_fill(0, 4, "");

                                    // fetch results
                                    while($rows = mysqli_fetch_assoc($result)) {
                                        // find the assessments for each child
                                        $user_assessment = "SELECT courseid, assessment_name, coursename FROM upcomingassessment WHERE studentid= '".$rows['username']."'";
                                        $result_name = mysqli_query($con, $user_assessment) or die(mysqli_error($con));                                                                
                                    
                                        // identify each courseid properties
                                        while($assessment_list =  mysqli_fetch_assoc($result_name)) {
                                            
                                            // same as in student
                                           /* $spec_course = "SELECT coursename, courseid FROM courses WHERE courseid='".$course_list['courseid']."'";
                                            $result_spec = mysqli_query($con, $spec_course) or die(mysqli_error($con));

                                            $display = mysqli_fetch_assoc($result_spec);*/
                                            
                                            // this code prevents duplicating of courses
                                            // dupe_check stores each unique course
                                            // only courses not found in dupe_check will be displayed
                                            for($j = 0; $j < sizeof($dupe_check) + 1; $j++) {
                                                if($j == sizeof($dupe_check)) {
                                                    $dupe_check[$j] = $assessment_list['coursename'].' '.$assessment_list['assessment_name'];
                                                    echo '<a href="assessment/view.php?id='.$assessment_list['courseid'].'"><div class="mini-tabs">'.$assessment_list['coursename'].' '.$assessment_list['assessment_name'].'</div></a>';

                                                    // save for quick access
                                                    if($count < 4) {
                                                        if($quick_access_id[$count] == "" && !isset($_SESSION['quickaccessid'])) {
                                                            $quick_access_id[$count] = $assessment_list['courseid'];
                                                            $quick_access_name[$count] = $assessment_list['coursename'];
                                                            $count++;                                                            
                                                        }
                                                    }                                                   
                                                    break;
                                                } else if($dupe_check[$j] == $assessment_list['coursename'].' '.$assessment_list['assessment_name']) {
                                                    break;
                                                }
                                                
                                            }
                                        }
                                    }                                    

                                    break;
                                default:
                            }
                        ?>
                    </div>                
                </div>
            </div>
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
                    <div class="right-title universal-title">Calendar</div>
                    <div class="mini-calendar">
                        <div class="mini-calendar-container">
                            <div class="calendar-header">
                                <button class="calendar-btn" id="previous" onclick="previous()">
                                    <span class="material-icons">chevron_left</span>
                                </button>
                                <div class="month-title" id="monthAndYear"></div>
                                <button class="calendar-btn" id="next" onclick="next()">
                                    <span class="material-icons">chevron_right</span>
                                </button>
                            </div>
                    
                            <table class="mini-table" id="calendar">
                                <thead>
                                <tr>
                                    <th>Sun</th>
                                    <th>Mon</th>
                                    <th>Tue</th>
                                    <th>Wed</th>
                                    <th>Thu</th>
                                    <th>Fri</th>
                                    <th>Sat</th>
                                </tr>
                                </thead>
                                <tbody id="calendar-body">
                                </tbody>
                            </table>
                        </div>
                        <form class="time-selector-form">
                            <div class="time-heading">Jump To:</div>
                            <div class="time-wrapper">
                                <label class="lead mr-2 ml-2" for="month"></label>
                                <select class="time-selector" name="month" id="month" onchange="jump()">
                                    <option value=0>Jan</option>
                                    <option value=1>Feb</option>
                                    <option value=2>Mar</option>
                                    <option value=3>Apr</option>
                                    <option value=4>May</option>
                                    <option value=5>Jun</option>
                                    <option value=6>Jul</option>
                                    <option value=7>Aug</option>
                                    <option value=8>Sep</option>
                                    <option value=9>Oct</option>
                                    <option value=10>Nov</option>
                                    <option value=11>Dec</option>
                                </select>                    
                    
                                <label for="year"></label>
                                <select class="time-selector" name="year" id="year" onchange="jump()">
                                    <option value=2021>2021</option>
                                    <option value=2022>2022</option>
                                    <option value=2023>2023</option>
                                    <option value=2024>2024</option>
                                    <option value=2025>2025</option>
                                    <option value=2026>2026</option>
                                    <option value=2027>2027</option>
                                    <option value=2028>2028</option>
                                    <option value=2029>2029</option>
                                    <option value=2030>2030</option>
                                </select>
                            </div>
                        </form>
                    </div>
                </div>                
                <div class="sidetabs">
                    <div class="right-title universal-title">Events</div>
                    <div>
                        <ul>
                            <li>To be added</li>
                        </ul>
                    </div>
                </div>                
            </div>
        </section>
    </div>
    <footer>
        Gryffindor learning management system. Made with <span class="material-icons material-icons-outlined" style="font-size: 16px; color: red;">favorite</span> by FSR GmbH.
    </footer>
    <script src="js/responsive.js"></script>
    <script src="js/calendar.js"></script>
</body>