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

    $reqid = $_GET['deletesubmission']; // used to redirect if there is any issue

    // prevent abusing of URL
    if($id_count != 8)
        if($id_count != 10)
            redirectError();

?>

<head>
    <title>Alert</title>

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
                    <li class="active"><a href="../courses.php">Courses</a></li>
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
                            echo '<div class="warning">Are you sure you want to delete this submission? It cannot be undone!</div>';

                            echo '
                            <form action="deletesubmission.php?id='.$courseid.'&deletesubmission='.$reqid.'" method="POST">
                                <div class="file-submission">    
                                    <div class="submit-left">
                                    </div>
                                    <div class="submit-right">
                                        <button type="button" class="cancel" value="cancel" onclick="goBack()" formnovalidate>Cancel</button>
                                        <button type="submit" value="submit">Yes</button>
                                    </div>
                                </div>                                                   
                            </form>
                            ';
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