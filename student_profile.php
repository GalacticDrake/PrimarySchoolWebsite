<?php
    require('db.php');
    include("auth.php"); //include auth.php file on all secure pages

    $_SESSION['current_profile'] = $_GET['id'];
    $studentid = $_SESSION['current_profile']; 
?> 

<!DOCTYPE html>

<head>
    <title>Profile</title>

    <meta name="viewport" content="width=device-width">
    
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="css/account.css">
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
                    <div class="sidetabs-title">Profile</div>
                    <ul class="quick-access">
                        <li><a href="profile.php">My Profile</a></li>
                        <?php
                            if($_SESSION['role'] == 'parent') {
                                $child = "SELECT name, username from students WHERE parentid= '".$_SESSION['username']."'";
                                $result_child = mysqli_query($con, $child) or die(mysqli_error($con));
                                
                                while($child_list =  mysqli_fetch_assoc($result_child)) {
                                    if($child_list['username'] == $studentid ) {
                                         echo   '<li class="active"><a>'.$child_list['name'].'</a></li>';
                                    }
                                    else {
                                        echo   '<li><a href="student_profile.php?id='.$child_list['username'].'">'.$child_list['name'].'</a></li>';
                                    }
                                }
                            }
                        ?>
                    </ul>
                </div>
            </div>
            <div class="container-inner">
                <div class="profile-header">
                    <div class="tab-title">Child Information</div>
                    <div class="big-profpic"></div>
                </div>
                <?php
                         $info = "SELECT name, gender, email, phone, username, intake, grade from students WHERE username= '".$studentid."'";
                         $result_info = mysqli_query($con, $info) or die(mysqli_error($con));
                         $display = mysqli_fetch_assoc($result_info); 
                         
                        echo '<div class="tabs">
                            <div class="tabs-header">
                                <div class="info-title">Name</div>
                            </div>
                            <div class="tabs-container">
                                <div class="tabs-info">
                                    '.$display['name'].'
                                </div>
                            </div>
                        </div>
                         <div class="tabs">
                            <div class="tabs-header">
                                <div class="info-title">Gender</div>
                            </div>
                            <div class="tabs-container">
                                <div class="tabs-info">
                                    '.$display['gender'].'
                                </div>
                            </div>
                        </div>
                         <div class="tabs">
                            <div class="tabs-header">
                                <div class="info-title">Email</div>
                            </div>
                            <div class="tabs-container">
                                <div class="tabs-info">
                                     '.$display['email'].'
                                </div>
                            </div>
                        </div>
                         <div class="tabs">
                            <div class="tabs-header">
                                <div class="info-title">Username</div>
                            </div>
                            <div class="tabs-container">
                                <div class="tabs-info">
                                     '.$display['username'].'
                                </div>
                            </div>
                        </div>
                         <div class="tabs">
                            <div class="tabs-header">
                                <div class="info-title">Phone number</div>
                            </div>
                            <div class="tabs-container">
                                <div class="tabs-info">
                                     '.$display['phone'].'
                                </div>
                            </div>
                        </div>
                        <div class="tabs">
                            <div class="tabs-header">
                                <div class="info-title">Intake</div>
                            </div>
                            <div class="tabs-container">
                                <div class="tabs-info">
                                     '.$display['intake'].'
                                </div>
                            </div>
                        </div>
                         <div class="tabs">
                            <div class="tabs-header">
                                <div class="info-title">Grade</div>
                            </div>
                            <div class="tabs-container">
                                <div class="tabs-info">
                                     '.$display['grade'].'
                                </div>
                            </div>
                        </div>';
                ?>
            </div>
        </section>
    </div>
    <footer>
        Gryffindor learning management system. Made with <span class="material-icons material-icons-outlined" style="font-size: 16px; color: red;">favorite</span> by FSR GmbH.
    </footer>
    <script src="js/responsive.js"></script>
</body>