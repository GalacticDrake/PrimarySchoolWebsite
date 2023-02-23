<?php
    require('db.php');
    include("auth.php"); //include auth.php file on all secure pages
?>

<head>
    <title>Page not found</title>

    <meta name="viewport" content="width=device-width">
    
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="css/view.css">
    <link rel="stylesheet" href="css/mobile.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Oxygen">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <script src="https://code.jquery.com/jquery-3.6.0.js"></script>
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
                                <li><a href="course/view.php?id='.$_SESSION["quickaccessid"][$count].'">'.$_SESSION["quickaccessname"][$count].'</a></li>
                                ';
                                $count++;
                            }
                        ?>
                    </ul>
                </div>
            </div>
            <div class="container-inner">
                <div class="tabs">                    
                    <div class="file-box">
                        <div>The page is not found, or you do not have permissions to access this page.</div>
                        <a class="blue-link" href="dashboard.php">Click here to return to dashboard</a>
                    </div>
                </div>
            </div>
        </section>
    </div>
    <footer>
        Gryffindor learning management system. Made with <span class="material-icons material-icons-outlined" style="font-size: 16px; color: red;">favorite</span> by FSR GmbH.
    </footer>
    <script src="js/options.js"></script>
    <script src="js/responsive.js"></script>
</body>