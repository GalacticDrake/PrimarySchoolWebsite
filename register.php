<!DOCTYPE html>

<head>
    <title>E-learning System</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="css/login.css">
    <link rel="stylesheet" href="css/register.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Oxygen">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">    
</head>
<body>
    <section>
        <div class="outer">
            <div class="title">Gryffindor e-Learning Portal</div>
            <div class="container">
                <div class="nav-title">
                    Register
                </div>
                <?php
                    require('db.php');
                    include("auth.php");
                    // update the database for new registrants
                    // check if registered key is in the database for verification

                    if (isset($_REQUEST['username'])) {
                        $uid = stripslashes($_REQUEST['uid']);
                        $uid = mysqli_real_escape_string($con,$uid);
                        $key = "SELECT uniqueid FROM `parents` WHERE uniqueid='$uid'";
                        $result = mysqli_query($con, $key) or die(mysqli_error($con));
                        $rows = mysqli_num_rows($result);

                        // if unique id is found, proceed with registration
                        if($rows == 1) {
                            $username = stripslashes($_REQUEST['username']); // removes backslashes
                            $username = mysqli_real_escape_string($con,$username); //escapes special characters in a string
                            
                            // prevent duplicate of usernames
                            $check = "SELECT username FROM `parents` WHERE username='$username'";
                            $cresult = mysqli_query($con, $check) or die(mysqli_error($con));
                            $crows = mysqli_num_rows($cresult);

                            // if duplicates not found, proceed with registration
                            if($crows == 0) {
                                $email = stripslashes($_REQUEST['email']);
                                $email = mysqli_real_escape_string($con,$email);
                                $password = stripslashes($_REQUEST['password']);
                                $password = mysqli_real_escape_string($con,$password);
                                $gender = stripslashes($_REQUEST['gender']);
                                $gender = mysqli_real_escape_string($con,$gender);
                                $phone = stripslashes($_REQUEST['pnumber']);
                                $phone = mysqli_real_escape_string($con,$phone);

                                $trn_date = date("Y-m-d H:i:s");
                                // default names as usernames; can be changed later
                                $query = "UPDATE parents SET username = '$username', password = '".md5($password)."', email = '$email', trn_date = '$trn_date', phone = '$phone', gender = '$gender', name = '$username' WHERE uniqueid = '$uid'";
                                $queryresult = mysqli_query($con, $query);

                                if($queryresult) {
                                    // for more secure verification, a password check should be used due to how typical usernames are
                                    // this can be listed as recommendations
                                    echo "<div class='form'><h3>You are registered successfully.</h3><br/>Click here to <a href='login.php'>Login</a></div>";
                                    $query = "UPDATE parents SET uniqueid = NULL WHERE username = '$username'";
                                    $queryresult = mysqli_query($con, $query);
                                } else {
                                    echo "<div class='form'>An error has occurred. Please try again.<a href='login.php'>Login</a></div>";
                                }
                            } else {
                                echo "<div class='form'>Looks like you have already registered. Click here to <a href='login.php'>Login</a></div>";
                            }                                
                        } else {
                            echo "<div class='form'>Unique id is invalid. Please try again. <a href='register.php'>Register again</a></div>";
                        }
                    } else {                    
                ?>
                <form method="post" name="register" action="" autocomplete="off">
                    <div class="sub-container">
                        <div class="subtitle">
                            <div id="child-subtitle">You will need a special key given by the school. If you do not have, please request by email to the IT department.</div>
                        </div>
                        <div id="register" class="register"><a href="login.php">Return to login</a></div>
                    </div>
                    <div class="login-row">
                        <div class="login-info">
                            <input type="text" id="username" name="username" required>
                            <div class="label label-large" id="userlabel">Username</div>
                        </div>
                        <div class="break" style="height: 15px;"></div>
                        <div class="login-info">
                            <input type="uid" id="uid" name="uid" required>
                            <div class="label label-large" id="uidlabel">Unique ID</div>
                        </div>
                    </div>
                    <div style="height: 15px;"></div>
                    <div class="login-info">
                        <input type="password" id="password" name="password" required>
                        <div class="label label-large" id="pwlabel">Password</div>
                    </div>
                    <div style="height: 15px;"></div>
                    <div class="login-info">
                        <input type="email" id="email" name="email" required>
                        <div class="label label-large" id="elabel">Email</div>
                    </div>
                    <div style="height: 15px;"></div>
                    <div class="login-info">
                        <input type="tel" id="pnumber" name="pnumber" required>
                        <div class="label label-large" id="plabel">Phone number</div>
                    </div>
                    <div style="height: 15px;"></div>
                    <div class="login-info register-info">
                        <div class="label label-large label-block">Gender: </div>
                        <input type="radio" name="gender" value="Male" id="Male" required>
                        <label for="Male">Male</label>
                        <input type="radio" name="gender" value="Female" id="Female">
                        <label for="Female">Female</label>
                        <input type="radio" name="gender" value="Others" id="Others">
                        <label for="Others">Others</label>
                    </div>
                    <div class="submit">
                        <button type="submit" value="submit">
                            <span class="material-icons material-icons-outlined">arrow_forward</span>
                        </button>
                    </div>
                </form>
                <?php } ?>
            </div>
        </div>
    </section>
    <footer>
        Gryffindor learning management system. Made with <span class="material-icons material-icons-outlined" style="font-size: 16px; color: red;">favorite</span> by FSR GmbH.
    </footer>
    <script src="js/login.js"></script>
</body>
