<?php
    require('db.php');
    include("auth.php"); //include auth.php file on all secure pages
?>

<!DOCTYPE html>

<head>
    <title>Dashboard</title>

    <meta name="viewport" content="width=device-width">
    
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="css/courses.css">
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
                    <li class="active"><a>Courses</a></li>
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
                <?php
                    switch($_SESSION['role']) {
                        case 'student':
                            $user_course = "SELECT courseid FROM studentcourses WHERE studentid= '".$_SESSION['username']."'";
                            $result = mysqli_query($con, $user_course) or die(mysqli_error($con));
                        
                            while($course_list =  mysqli_fetch_assoc($result)) {
                                $spec_course = "SELECT coursename, courseid FROM courses WHERE courseid='".$course_list['courseid']."'";
                                $result_spec = mysqli_query($con, $spec_course) or die(mysqli_error($con));

                                $display = mysqli_fetch_assoc($result_spec);   
                                echo '<a class="tabs tabs-grid" href="course/view.php?id='.strtolower($display['courseid']).'">
                                <div>
                                    <div class="tab-subtitle">'.$display['courseid'].'</div>
                                    <div class="tab-title">'.$display['coursename'].'</div>
                                </div>
                                </a>';
                            }
                            
                            break;
                        case 'teacher':
                            $user_course = "SELECT courseid FROM teachercourses WHERE teacherid= '".$_SESSION['username']."'";
                            $result = mysqli_query($con, $user_course) or die(mysqli_error($con));
                        
                            while($course_list =  mysqli_fetch_assoc($result)) {
                                $spec_course = "SELECT coursename, courseid FROM courses WHERE courseid='".$course_list['courseid']."'";
                                $result_spec = mysqli_query($con, $spec_course) or die(mysqli_error($con));

                                $display = mysqli_fetch_assoc($result_spec);   
                                echo '<a href="course/view.php?id='.strtolower($display['courseid']).'">
                                <div class="tabs tabs-grid">
                                    <div class="tab-subtitle">'.$display['courseid'].'</div>
                                    <div class="tab-title">'.$display['coursename'].'</div>
                                </div>
                                </a>';
                            }
                            
                            break;
                        case 'parent':
                            $children = "SELECT username FROM students WHERE parentid= '".$_SESSION['username']."'";
                            $result = mysqli_query($con, $children) or die(mysqli_error($con));
                            
                            $i = 0;
                            $dupe_check = [];

                            while($rows = mysqli_fetch_assoc($result)) {
                                $user_course = "SELECT courseid FROM studentcourses WHERE studentid= '".$rows['username']."'";
                                $result_name = mysqli_query($con, $user_course) or die(mysqli_error($con));
                            
                                while($course_list =  mysqli_fetch_assoc($result_name)) {
                                    
                                    $spec_course = "SELECT coursename, courseid FROM courses WHERE courseid='".$course_list['courseid']."'";
                                    $result_spec = mysqli_query($con, $spec_course) or die(mysqli_error($con));

                                    $display = mysqli_fetch_assoc($result_spec);
                                    
                                    for($j = 0; $j < sizeof($dupe_check) + 1; $j++) {
                                        if($j == sizeof($dupe_check)) {
                                            $dupe_check[$j] = $display['coursename'];
                                            echo 
                                            '<a href="course/view.php?id='.strtolower($display['courseid']).'">
                                                <div class="tabs tabs-grid">
                                                    <div class="tab-subtitle">'.$display['courseid'].'</div>
                                                    <div class="tab-title">'.$display['coursename'].'</div>
                                                </div>
                                            </a>';
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