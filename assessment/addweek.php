<?php
    require('../db.php');
    include("../auth.php"); //include auth.php file on all secure pages
    $_SESSION['current_course'] = $_GET['id'];
    $courseid = $_SESSION['current_course']; 
    $ddate = date('Y-m-d');
    $date = new DateTime($ddate);
    $weekno = $date->format("W");
?>

<!DOCTYPE html>

<head>
    <title>Courses</title>

    <meta name="viewport" content="width=device-width">
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/themes/cupertino/jquery-ui.min.css" integrity="sha512-ug/p2fTnYRx/TfVgL8ejTWolaq93X+48/FLS9fKf7AiazbxHkSEENdzWkOxbjJO/X1grUPt9ERfBt21iLh2dxg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="../css/jquery-ui-override.css">
    <link rel="stylesheet" href="../css/main.css">
    <link rel="stylesheet" href="../css/view.css">
    <link rel="stylesheet" href="../css/add_week.css">
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
                    <li><a href="../exam.php">Exam</a></li>
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
                    <div id="quick-access">
                    </div>
                </div>
                <div class="sidetabs">
                    <div class="sidetabs-title">Resources</div>
                </div>
            </div>
            <div class="container-inner">
                <div class="view-header">
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
                    <?php 
                        if($_SESSION['role'] == "parent") {
                            echo 
                            '<div class="view-child">
                                <div class="child-list">
                                    <div class="child-icon"></div>
                                    Child 1
                                </div>
                                <div class="child-list">
                                    <div class="child-icon"></div>
                                    Child 2
                                </div>
                            </div>';
                        }
                    ?>                    
                </div>
                <div class="tabs">
                    <div class="file-title">Add week</div>
                    <div>
                        <?php
                            if(isset($_POST["enter-week"])) {                                
                                // insert file details into the table courseid
                                $query_string = "INSERT INTO {$courseid}(fileid, week, visibility) VALUES (?, ?, ?)";
                                $query_insert = $con->prepare($query_string);                                

                                if(!empty($_POST['check_list'])) {
                                    foreach($_POST['check_list'] as $weeknum) {
                                        $query_insert->bind_param("sss", "temp", $weeknum, 1);
                                        $query_insert->execute();
                                    }
                                }
                                
                            } else {
                        ?>                    
                        <form action="" method="POST">
                            <div class="week-title">Select weeks to enable them.</div>
                            <div class="week-container">                                
                                <?php 
                                    $weekscomp = array_fill(1, 54, 0);

                                    $query_string_find = "SELECT DISTINCT week FROM {$courseid}";
                                    $query_find = $con->prepare($query_string_find);
                                    $query_find->execute();
                                    $find_obj = $query_find->get_result();

                                    while($weeksdone = mysqli_fetch_array($find_obj)) {
                                        $j = $weeksdone[0];
                                        $weekscomp[$j] = $j;
                                    }

                                    $i = 1;
                                    while($i <= 54) {
                                        if($i != $weekscomp[$i]) {
                                            echo
                                            '
                                            <input type="checkbox" name="check_list[]" id="checkbox-'.$i.' value="'.$i.'">
                                            <label class="week-avail" for="checkbox-'.$i.'">
                                                '.strval($i).'
                                            </label>
                                            ';
                                        } else {
                                            echo
                                            '
                                            <div class="week-done">
                                                '.strval($i).'
                                            </div>
                                            ';
                                        }

                                        $i++;
                                    }
                                ?>
                            </div>
                            <div class="file-submission">
                                <div class="submit-right">
                                    <button class="cancel" value="cancel" onclick="goBack()" formnovalidate>Cancel</button>
                                    <button type="submit" value="submit">Save</button>
                                </div>
                            </div>
                        </form>
                        <?php } ?>
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
</body>