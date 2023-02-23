<!DOCTYPE html>

<head>
    <title>E-learning System</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="css/login.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Oxygen">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">    
</head>
<body>
    <section>
        <div class="outer">
            <div class="title">Gryffindor e-Learning Portal</div>
            <div class="container">
                <?php
                    require('db.php');
                    session_start();

                    // If form submitted, insert values into the database.
                    if (isset($_POST['username'])){

                        $username = stripslashes($_REQUEST['username']); // removes backslashes
                        $username = mysqli_real_escape_string($con, $username); //escapes special characters in a string
                        $password = stripslashes($_REQUEST['password']);
                        $password = mysqli_real_escape_string($con, $password);                       
                        
                        if(!isset($_POST['role']))
                            $role = "student";
                        else
                            $role = $_REQUEST['role'];

                    // Queries database from students to teachers
                        switch($role) {
                            case "student":
                                $query = "SELECT * FROM `students` WHERE username='$username' and password='".md5($password)."'";
                                $result = mysqli_query($con, $query) or die(mysqli_error($con));
                                break;
                            case "parent":
                                $query = "SELECT * FROM `parents` WHERE username='$username' and password='".md5($password)."'";
                                $result = mysqli_query($con, $query) or die(mysqli_error($con));
                                break;
                            case "teacher":
                                $query = "SELECT * FROM `teachers` WHERE username='$username' and password='".md5($password)."'";
                                $result = mysqli_query($con, $query) or die(mysqli_error($con));
                                break;
                            default:
                        }
                        
                        $rows = mysqli_num_rows($result);
                        if($rows == 1) {
                            $_SESSION['username'] = $username; // store username for identity later
                            $_SESSION['role'] = $role; // store role in session for later

                            $display = mysqli_fetch_assoc($result);

                            $_SESSION['display_name'] = $display['name'];

                            header("Location: dashboard.php"); // redirects user to dashboard
                        } else {
                            echo "
                                <div class='form'>
                                    <h3>Username/password is incorrect.</h3>
                                    Please try again.
                                    <a href='login.php' class='err-login'>Login</a>
                                </div>";
                        }
                    } else {
                ?>
                    <form method="post" name="login" action="" autocomplete="off">
                        <div class="outer-nav">
                            <div class="nav-title">Who are you?</div>
                            <nav>
                                <input type="radio" name="role" value="student" id="student">
                                <label id="student-role" class="active" onclick=changeRole(1) for="student">Student</label>
                                <input type="radio" name="role" value="parent" id="parent">
                                <label id="parent-role" onclick=changeRole(2) for="parent">Parent</label>
                                <input type="radio" name="role" value="teacher" id="teacher">
                                <label id="teacher-role" onclick=changeRole(3) for="teacher">Teacher</label>
                            </nav>
                        </div>                
                        <div class="sub-container">
                            <div class="subtitle">
                                <div id="child-subtitle">Hello students! You can find your textbooks, homeworks and other resources here. Login now!</div>
                            </div>
                            <span id="register" class="register" style="display: none;"><a href="register.php">Register account</a></span>
                        </div>
                        <div class="login-info">
                            <input type="text" id="username" name="username">
                            <div class="label label-large" id="userlabel">Username</div>
                        </div>
                        <div style="height: 15px;"></div>
                        <div class="login-info">
                            <input type="password" id="password" name="password">
                            <div class="label label-large" id="pwlabel">Password</div>
                        </div>
                        <div class="submit">
                            <button type="submit" value="submit">
                                <span class="material-icons material-icons-outlined">arrow_forward</span>
                            </button>
                        </div>
                    </form>
                <?php } ?>
                <div class="container-footer">
                    <div id="child-footer">Don't remember? Ask your dad or mom, or consult us by clicking the link below.</div>
                    <a class="inner-footer-link">I have forgotten my ID or password</a>
                </div>
            </div>
        </div>
    </section>
    <footer>
        Gryffindor learning management system. Made with <span class="material-icons material-icons-outlined" style="font-size: 16px; color: red;">favorite</span> by FSR GmbH.
    </footer>
    <script src="js/login.js"></script>
</body>
