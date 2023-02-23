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

    // prevent abusing of URL
    if($id_count == 10) {
        $weekno = substr($allid, -2); // get week no for querying

        if($weekno < 0 || $weekno > 54)
            redirectError();

    } else {
        redirectError();
    }

    if(!isset($_SESSION["filestatus"]))
        $_SESSION["filestatus"] = 0;
?>

<!DOCTYPE html>

<head>
    <title>Courses</title>

    <meta name="viewport" content="width=device-width">
    
    <link rel="stylesheet" href="../css/main.css">
    <link rel="stylesheet" href="../css/view.css">
    <link rel="stylesheet" href="../css/mod.css">
    <link rel="stylesheet" href="../css/edit.css">
    <link rel="stylesheet" href="../css/add.css">
    <link rel="stylesheet" href="../css/mobile.css">
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
                    <li><a href="../dashboard.php">Home</a></li>
                    <li><a href="../courses.php">Courses</a></li>
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
                <?php
                    if($_SESSION['role'] != "teacher") {
                        echo
                        '
                        <div class="sidetabs">
                            <div class="sidetabs-title">Assessments done</div>
                            <div class="progress-section">
                                <div class="progress-bar">
                                    <div class="progress" style="width: calc(100% - 6px);"></div>
                                </div> 
                                <div class="progress-value">100%</div>
                            </div>
                        </div>
                        ';
                    } else {
                        echo
                        '
                        <div class="sidetabs">
                            <div class="sidetabs-title">Assessments</div>
                            <div>Check assessments</div>
                        </div>
                        ';

                        echo
                        '
                        <div class="sidetabs">
                            <div class="sidetabs-title">Modify course</div>
                            <div>                                
                                <a href="../course/hideweek.php?id='.$courseid.'">Show/hide weeks</a>
                            </div>
                        </div>
                        ';
                    }
                ?>
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
                            switch($_SESSION["filestatus"]) {
                                case 1:
                                    echo '
                                    <div class="status">
                                        <div>
                                            A file with the same name exists. Please upload with a different name.
                                        </div>
                                        <div class="status-close" onclick="closeStatus()"> 
                                            <span class="material-icons">close</span>
                                        </div>
                                    </div>
                                    ';
                                    break;
                                
                                case 2:
                                    echo '
                                    <div class="status">
                                        <div>
                                            Error finding file. Please contact the IT office to resolve.
                                        </div>
                                        <div class="status-close" onclick="closeStatus()">
                                            <span class="material-icons">close</span>
                                        </div>
                                    </div>
                                    ';
                                    break;
                                
                                case 3:
                                    echo '
                                    <div class="status">
                                        <div>
                                            A file with the same name exists. Please use another name.
                                        </div>
                                        <div class="status-close" onclick="closeStatus()">
                                            <span class="material-icons">close</span>
                                        </div>
                                    </div>
                                    ';
                                    break;
                                
                                case 4:
                                    echo '
                                    <div class="status">
                                        <div>
                                            Error deleting file. File not found.
                                        </div>
                                        <div class="status-close" onclick="closeStatus()">
                                            <span class="material-icons">close</span>
                                        </div>
                                    </div>
                                    ';
                                    break;
                                
                                default:                                
                            }

                            $_SESSION["filestatus"] = 0;
                        ?>
                        <?php
                            // format file size to readable format
                            function countSize($bytes) {
                                // http://www.phpshare.org
                                if ($bytes >= 1073741824) {
                                    $bytes = number_format($bytes / 1073741824, 2) . ' GB';
                                }
                                elseif ($bytes >= 1048576)
                                {
                                    $bytes = number_format($bytes / 1048576, 2) . ' MB';
                                }
                                elseif ($bytes >= 1024)
                                {
                                    $bytes = number_format($bytes / 1024, 2) . ' KB';
                                }
                                elseif ($bytes > 1)
                                {
                                    $bytes = $bytes . ' bytes';
                                }
                                elseif ($bytes == 1)
                                {
                                    $bytes = $bytes . ' byte';
                                }
                                else
                                {
                                    $bytes = '0 bytes';
                                }

                                return $bytes;
                            }

                            // format date to readable format
                            function dateAndTime($fulldate) {
                                $year = substr($fulldate, 0, 4);
                                $month = substr($fulldate, 5, 2);
                                $day = substr($fulldate, 8, 2);
                                $hours = substr($fulldate, 11, 2);
                                $mins = substr($fulldate, 14, 2);

                                if((int)$hours >= 13) {
                                    $hours = $hours - 12;
                                    $period = "p.m.";                            
                                } else {
                                    $period = "a.m.";
                                }

                                return "$day/$month/$year at $hours:$mins $period";
                            }

                            // view files
                            if(isset($_GET['viewfiles'])) {
                                // does not require checking since there must exist files
                                $query_string = "SELECT * FROM {$courseid} WHERE week = ?;";
                                $queryweeks = $con->prepare($query_string);
                                $queryweeks->bind_param("s", $weekno);
                                $queryweeks->execute();
                                $weeklists = $queryweeks->get_result();

                                echo 
                                '<div class="file-header">
                                    <div class="file-status">File description</div>
                                </div>';

                                echo 
                                '<div class="file-nav">
                                    <div class="file-nav-title">Week '.$weekno.'</div> 
                                    <div class="row-of-files">
                                ';

                                while($week = mysqli_fetch_array($weeklists)) {
                                    if($week['fileid'] != $_GET['viewfiles']) {
                                        if($week['filedir'] === NULL) {
                                            echo
                                            '<div class="tabs-option file-error">';
                                        } else {
                                            echo
                                            '<div class="tabs-option">';
                                        }
                                        echo
                                        '
                                            <a class="blue-link" href="view.php?id='.$courseid.$weekno.'&viewfiles='.$week['fileid'].'">
                                                <div class="tabs-thumbnail"></div>
                                                <div class="tabs-option-title"> '. $week['filename'] .'</div>
                                            </a>
                                        </div>';
                                    } else {
                                        echo
                                        '<div class="tabs-option tabs-active">
                                            <a ass="file-options">
                                            <a class="blue-link" href="view.php?id='.$courseid.$weekno.'&viewfiles='.$week['fileid'].'">
                                                <div class="tabs-thumbnail"></div>
                                                <div class="tabs-option-title"> '. $week['filename'] .'</div>
                                            </a>
                                        </div>';

                                        $fileid = $week['fileid'];
                                        $_SESSION['fileid'] = $fileid;
                                        $filename = $week['filename'];

                                        if($week['updatetime'] == NULL)
                                            $modifieddate = dateAndTime($week['uploadtime']);
                                        else
                                            $modifieddate = dateAndTime($week['updatetime']);
                                        
                                        $size = countSize($week['size']);
                                        $filetype = $week['filetype'];
                                        $visible = $week['visibility'];

                                        if($filetype == "link") {
                                            $link = $week['filedir'];
                                        }
                                    }
                                }                               

                                echo '</div></div>';

                                if(isset($fileid)) {
                                    echo '
                                    <div class="file-container">
                                        <div class="file-desc file-flex">
                                            <div class="file-left">
                                                <div class="tabs-thumbnail"></div>
                                            </div>
                                            <div class="file-right">';
                                                if($filename == NULL) {
                                                    echo '
                                                    <div class="file-title">No title</div>';
                                                } else {
                                                    echo '
                                                    <div class="file-title">'.$filename.'</div>
                                                    ';
                                                }
                                                echo '
                                                <div class="file-modify file-desc-space">Modified on
                                                    <span>'.$modifieddate.'</span>
                                                </div>
                                                ';
                                                if($filetype == "file") {
                                                    echo
                                                    '
                                                    <div class="file-name-modify file-desc-space">
                                                        Size:
                                                        <span>'.$size.'</span>
                                                    </div>';
                                                } else if($filetype == "link") {
                                                    echo
                                                    '
                                                    <div class="file-name-modify file-desc-space">
                                                        Link:
                                                        <a class="blue-link" href=".$link.">
                                                            <span>'.$link.'</span>
                                                        </a>
                                                    </div>';
                                                }
                                            echo ' 
                                            </div>
                                        </div>
                                        <div class="file-options-box">
                                            <div class="file-options">
                                                <a class="black-link" href="view.php?id='.$courseid.$weekno.'&addfiles">
                                                    <div class="options-icon">
                                                        <span class="material-icons">
                                                            add
                                                        </span>
                                                    </div>
                                                    <div class="options-label">Add</div>
                                                </a>
                                            </div>
                                            <div class="file-options">
                                                <a class="black-link" href="view.php?id='.$courseid.$weekno.'&editfiles='.$fileid.'">
                                                    <div class="options-icon">
                                                        <span class="material-icons">
                                                            edit
                                                        </span>
                                                    </div>
                                                    <div class="options-label">Edit</div>
                                                </a>
                                            </div>
                                            ';
                                            if($visible == 1) {
                                                echo '
                                                <div class="file-options">
                                                    <form action="visible.php?id='.$courseid.$weekno.'&currfile='.$fileid.'&visible=0" method="POST" class="v-form">
                                                        <button class="options-icon" type="submit">
                                                            <span class="material-icons">
                                                                visibility_off
                                                            </span>
                                                            <div class="options-label">Hide</div>
                                                        </button>                                                
                                                    </form>
                                                </div>';
                                            } else {
                                                echo '
                                                <div class="file-options">
                                                    <form action="visible.php?id='.$courseid.$weekno.'&currfile='.$fileid.'&visible=1" method="POST" class="v-form">
                                                        <button class="options-icon" type="submit">
                                                            <span class="material-icons">
                                                                visibility
                                                            </span>
                                                            <div class="options-label">Show</div>
                                                        </button>
                                                    </form>
                                                </div>';
                                            } 
                                            echo '
                                            <div class="file-options">
                                                <form action="delete.php?id='.$courseid.$weekno.'&currfile='.$fileid.'&type='.$filetype.'" method="POST" class="v-form">
                                                    <button class="options-icon" type="submit">
                                                        <span class="material-icons">
                                                            delete
                                                        </span>
                                                        <div class="options-label">Remove</div>
                                                    </button>                                                
                                                </form>
                                            </div>
                                        </div>
                                    </div>';
                                } else {
                                    echo
                                    '
                                    <div class="file-container">
                                        File does not exist. Click on one of the files above to preview them.
                                    </div>';
                                }


                            }
                        ?>
                        <?php    
                            if(isset($_GET['addfiles'])) {
                                $_SESSION['addtype'] = "file";

                                echo '
                                <div class="file-header">
                                    <div class="file-status">Add Files</div>
                                </div>
                                <div class="file-header-option">
                                    <a class="blue-link" href="view.php?id='.$allid.'&addlinks">
                                        <div class="file-header-link">
                                            <span class="material-icons">add_link</span>
                                            <span>Attach links instead</span>
                                        </div>                                        
                                    </a>
                                    <a class="blue-link" href="../submission/view.php?id='.$allid.'&createsubmission">
                                        <div class="file-header-link">
                                            <span class="material-icons">folder</span>
                                            <span>Create submission</span>
                                        </div>                                        
                                    </a>
                                </div>
                                <div class="file-container">
                                    <form action="addfiles.php?id='.$allid.'&prevfile='.$_SESSION['fileid'].'&type=file" method="POST" enctype="multipart/form-data">
                                        <div class="file-desc">
                                            <div>
                                                <div class="file-edit">
                                                    <label for="selectedFile" class="fileupload">
                                                        <input type="file" id="selectedFile" name="selectedFile" required>
                                                        <span id="file-selected">Click to add a file</span>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        <div id="v-desc" class="v-desc">This document is visible to students</div>
                                        <div class="file-options-box file-flex-column">
                                            <label class="file-options" name="visible" onclick="changeVisible()">
                                                <input type="checkbox" id="visible" name="visible" checked=true>
                                                <span class="material-icons" id="v-icon">
                                                    visibility_off
                                                </span>
                                                <div class="options-label" id="v-text">Hide</div>
                                            </label>
                                        </div>
                                        <div class="file-submission">
                                            <div class="submit-left">
                                                <label class="label">Require submission
                                                    <input type="checkbox" name="submission">
                                                    <span class="checkmark"></span>
                                                </label>
                                            </div>
                                            <div class="submit-right">
                                                <a class="cancel" href="view.php?id='.$allid.'&viewfiles='.$_SESSION['fileid'].'">Cancel</a>
                                                <button type="submit" value="submit">Save</button>
                                            </div>
                                        </div>
                                    </form>                      
                                </div>';
                            } 
                        ?>
                        <?php    
                            if(isset($_GET['addlinks'])) {
                                $_SESSION['addtype'] = "link";

                                echo '
                                <div class="file-header">
                                    <div class="file-status">Add Links</div>
                                </div>
                                <div class="file-header-option">
                                    <a class="blue-link" href="view.php?id='.$allid.'&addfiles">
                                        <div class="file-header-link">
                                            <span class="material-icons">upload_file</span>
                                            <span>Attach files instead</span>
                                        </div>                                        
                                    </a>
                                </div>
                                <div class="file-container">
                                    <form action="addfiles.php?id='.$allid.'&prevfile='.$_SESSION['fileid'].'&type=link" method="POST" enctype="multipart/form-data">
                                        <div class="file-header-desc">
                                            Please enter a link, like a youtube or a wikipedia link. Link title is optional, and takes the link url if empty.
                                        </div>
                                        <div class="file-desc">
                                            <div class="link-edit">
                                                <label for="selectedLinkname" class="linkupload">
                                                    Link title:
                                                </label>
                                                <input type="text" id="selectedLinkname" name="selectedLinkname">
                                            </div>
                                        </div>
                                        <div class="file-desc">
                                            <div class="link-edit">
                                                <label for="selectedLink" class="linkupload">
                                                    Enter link here:
                                                </label>
                                                <input type="text" id="selectedLink" name="selectedLink" required>
                                            </div>
                                        </div>
                                        <div id="v-desc" class="v-desc">This link is visible to students.</div>
                                        <div class="file-options-box file-flex-column">
                                            <label class="file-options" name="visible" onclick="changeVisible()">
                                                <input type="checkbox" id="visible" name="visible" checked=true>
                                                <span class="material-icons" id="v-icon">
                                                    visibility_off
                                                </span>
                                                <div class="options-label" id="v-text">Hide</div>
                                            </label>
                                        </div>
                                        <div class="file-submission">
                                            <div class="submit-left">
                                                <label class="label">Require submission
                                                    <input type="checkbox" name="submission">
                                                    <span class="checkmark"></span>
                                                </label>
                                            </div>
                                            <div class="submit-right">
                                                <a class="cancel" href="view.php?id='.$allid.'&viewfiles='.$_SESSION['fileid'].'">Cancel</a>
                                                <button type="submit" value="submit">Save</button>
                                            </div>
                                        </div>
                                    </form>                      
                                </div>';
                            } 
                        ?>      
                        <?php
                            // edit file
                            if(isset($_GET['editfiles'])) {
                                $fileid = $_REQUEST['editfiles'];

                                // does not require checking since there must exist files
                                $query_string = "SELECT * FROM {$courseid} WHERE fileid = ?;";
                                $queryfile = $con->prepare($query_string);
                                $queryfile->bind_param("s", $fileid);
                                $queryfile->execute();
                                $file_obj = $queryfile->get_result();

                                $filedetails = mysqli_fetch_array($file_obj);

                                echo '
                                    <div class="file-header">
                                        <div class="file-status">Edit File</div>
                                    </div>
                                    <div class="file-container">
                                        <form action="editfiles.php?id='.$allid.'&currfile='.$_SESSION['fileid'].'" method="POST">
                                            <div class="file-desc">
                                                <div>
                                                    <div class="file-edit">
                                                        <label class="edit-label" for="file-title">Title</label>
                                                        <input type="text" name="file-title" id="file-title" value="'.$filedetails['filename'].'">
                                                    </div>
                                                    <div class="file-edit">
                                                        <label class="edit-label" for="file-description">Description</label>
                                                        <textarea id="file-description" name="file-description">';
                                                            if($filedetails['filedesc'] === NULL) {
                                                                echo
                                                                'Enter description.';
                                                            } else {
                                                                echo $filedetails['filedesc'];
                                                            }
                                                        echo '</textarea>
                                                    </div>
                                                </div>
                                            </div>
                                            <div id="v-desc" class="v-desc">This document is visible to students.</div>
                                            <div id="d-desc" class="v-desc d-desc"></div>
                                            <div class="file-options-box">
                                                <label class="file-options" name="visible" onclick="changeVisible()">
                                                    <input type="checkbox" id="visible" name="visible" checked=true>
                                                    <span class="material-icons" id="v-icon">
                                                        visibility_off
                                                    </span>
                                                    <div class="options-label" id="v-text">Hide</div>
                                                </label>
                                                <label class="file-options" name="delete" id="deletelbl" onclick="confirmDelete()">
                                                    <input type="checkbox" id="delete" name="delete">
                                                    <span class="material-icons" id="v-icon">
                                                        delete
                                                    </span>
                                                    <div class="options-label" id="v-text">Remove</div>
                                                </label>
                                            </div>
                                            <div class="file-submission">
                                                <div class="submit-left">
                                                    <label class="label">Require submission';

                                                        if($filedetails['require_submission'] == 1)
                                                            echo '<input type="checkbox" name="submission" checked>';
                                                        else
                                                            echo '<input type="checkbox" name="submission">';
                                                        echo '
                                                        <span class="checkmark"></span>
                                                    </label>
                                                </div>
                                                <div class="submit-right">
                                                    <button type="button" class="cancel" value="cancel" onclick="goBack()" formnovalidate>Cancel</button>
                                                    <button type="submit" value="submit">Save</button>
                                                </div>
                                            </div>
                                        </form>                      
                                    </div>
                                ';
                            }
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
</body>