<?php
    require('../db.php');
    include("../auth.php"); //include auth.php file on all secure pages

    if($_SESSION["role"] == "parent") {
        $query_child_str = "SELECT * FROM students WHERE parentid = ?";
        $query_child = $con->prepare($query_child_str);
        $query_child->bind_param("s", $_SESSION["username"]);
        $query_child->execute();
        $child_obj_data = $query_child->get_result();

        $i = 0;

        while($child_obj = mysqli_fetch_array($child_obj_data)) {
            $children[$i][0] = $child_obj["username"];
            $children[$i][1] = $child_obj["name"];
            $children[$i][2] = $child_obj["grade"];

            $i++;
        }

        if(!isset($_GET["id"])) {
            $temp = $children[0][0];

            header("Location: view.php?id=$temp");
            exit();
        }    

        $max = $i;
    }     
?>

<!DOCTYPE html>

<head>
    <title>Timetable</title>

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="../css/main.css">
    <link rel="stylesheet" href="../css/timetable.css">
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
                    <li class="active"><a>Timetable</a></li>
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
                    <div class="sidetabs-title">Timetable</div>
                    <ul class="quick-access">                        
                        <?php
                            if($_SESSION['role'] == 'parent') {
                                $i = 0;
                                
                                while($i < $max) {
                                    echo   '<li ';

                                    if($_REQUEST["id"] == $children[$i][0]) {
                                        echo 'class="active"';
                                    }
                                    echo '><a href="view.php?id='.$children[$i][0].'">'.$children[$i][1].'</a></li>';
                                    $i++;
                                }
                            } else {
                                echo '<li class="active"><a>My Timetable</a></li>';
                            }
                        ?>
                    </ul>
                </div>
            </div>
            <div class="container-inner">
                <div class="profile-header">
                    <div class="tab-title">Timetable</div>
                </div>
                <?php
                    if($_SESSION['role'] == 'parent') {

                        $childid = $_REQUEST["id"];
                        
                        $student_query_string = 'SELECT grade FROM students WHERE username = ?';
                        $student_query = $con->prepare($student_query_string);
                        $student_query->bind_param("s", $childid);
                        $student_query->execute();
                        $student_obj = $student_query->get_result();
                        $student_obj_data = mysqli_fetch_array($student_obj);
                        $student_grade = $student_obj_data["grade"];

                        $time_query_string = 'SELECT * FROM timetable_s WHERE grade = ?';
                        $time_query = $con->prepare($time_query_string);
                        $time_query->bind_param("i", $student_grade);
                        $time_query->execute();
                        $time_obj = $time_query->get_result();
                        $time = mysqli_fetch_array($time_obj);

                        echo '
                        <div class="img-container">
                            <img src="'.$time["filedir"].'" alt="timetable.png" />
                        </div>
                        ';
                        
                        
                    } else if($_SESSION['role'] == 'teacher') { /* teacher */
                        $time_query_string = 'SELECT * FROM timetable_t WHERE teacherid = ?';
                        $time_query = $con->prepare($time_query_string);
                        $time_query->bind_param("s", $_SESSION["username"]);

                        $time_query->execute();
                        $time_obj = $time_query->get_result();
                        $time = mysqli_fetch_array($time_obj);

                        echo '
                        <div class="img-container">
                            <img src="'.$time["filedir"].'" alt="timetable.png" />
                        </div>
                        ';

                    } else { /* student */
                        $student_query_string = 'SELECT grade FROM students WHERE username = ?';
                        $student_query = $con->prepare($student_query_string);
                        $student_query->bind_param("s", $_SESSION["username"]);
                        $student_query->execute();
                        $student_obj = $student_query->get_result();
                        $student_obj_data = mysqli_fetch_array($student_obj);
                        $student_grade = $student_obj_data["grade"];

                        $time_query_string = 'SELECT * FROM timetable_s WHERE grade = ?';
                        $time_query = $con->prepare($time_query_string);
                        $time_query->bind_param("i", $student_grade);
                        $time_query->execute();
                        $time_obj = $time_query->get_result();
                        $time = mysqli_fetch_array($time_obj);

                        echo '
                        <div class="img-container">
                            <img src="'.$time["filedir"].'" alt="timetable.png" />
                        </div>
                        ';
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

