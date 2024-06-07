<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Setting</title>
    <link rel="stylesheet" href="../css/setuptime.css">
    <link rel="stylesheet" href="../css/navbar.css">
    <link rel="stylesheet" href="../css/settings.css">
</head>

<body>
    <?php
    require_once 'ConnectData.php';

    if ($connect->connect_error) {
        die('Cannot connect to database' . $connect_error);
    } else {
        $stmt = $connect->prepare("SELECT * FROM programs WHERE _id = ?");
        $stmt->bind_param("s", $_SESSION["program_id"]);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $stmt->close();
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $startDate = $_POST["startDate"];
        $dailyStart = $_POST["dailyTime"];
        $duration = $_POST["perMatch"];
        $dailyMatch = $_POST["perDay"];
        if ($connect->connect_error) {
            die('Cannot connect to database' . $connect_error);
        } else {
            $stmt = $connect->prepare("UPDATE programs SET startDate=?, dailyStart=?, duration=?, dailyMatch=? WHERE _id=?");
            $stmt->bind_param("sssss", $startDate, $dailyStart, $duration, $dailyMatch, $_SESSION["program_id"]);
        }
        if ($stmt->execute()) {
            $stmt->close();
            header("Location: SetUpTime.php");
            exit();
        } else {
            echo "Error" . $stmt->error;
        }
    }
    ?>
    <div class="navbar">
        <div class="navbar-content">
            <div class="navbar-item">
                <div class="logo">
                    <a href="index.php" class="logo-title"> 
                        <h2 title="Sport Management">SportManagement</h2>
                    </a>
                </div>
    
                <!--khi da dang nhap, hien thi profile-->
                <div class="nav_profile">
                    <div class="dashboard">
                        <p class="goto"><a href="../php/Dashboard.php">Go to Dashboard</a></p>
                        <a href="../PHP/itemmenu.php"><img src="../Image/profile.jpg" alt="User Avatar" class="user-avatar"> </a>
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="name_programs">
        <h3><?php echo $row['title']?></h3>
    </div>

    <div class="menu_setting">
        <ul>
            <li><a href="../PHP/BasicInfo.php" target="content">Basic Info</a></li>
            <li><a href="../PHP/SetUpTime.php" target="content">Setup Time</a></li>
            <li><a href="../PHP/registration_setting.php" target="content">Registration</a></li>
            <li><a href="../PHP/teamgroup.php" target="content">Team & Group</a></li>
            <li><a href="../PHP/schedule.php" target="content">Schedule</a></li>
        </ul>
    </div>
    <div class="main">
        <div class="create_web">
            <form action="" id="new_web" method="post">
                <div class="input_web">
                    <label for="startDate">Start Date</label>
                    <input class="inp_program" type="date" id="startDate" required="required" placeholder="" name="startDate" value="<?php echo $row['startDate']; ?>">
                </div>
                <div class="input_web">
                    <label for="dailyTime">Daily Start Time </label>
                    <input class="inp_program" type="time" id="dailyTime" required="required" placeholder="" name="dailyTime" value="<?php echo $row['dailyStart']; ?>">
                </div>
                <div class="input_web">
                    <label for="perMatch">Duration Per Match</label>
                    <input class="inp_program" type="text" id="perMatch" required="required" placeholder="" name="perMatch" value="<?php echo $row['duration']; ?>">
                    <p class="note">Minutes between each match</p>
                </div>

                <div class="input_web">
                    <label for="perDay">Match Per Day</label>
                    <input class="inp_program" type="text" id="perDay" required="required" placeholder="" name="perDay" value="<?php echo $row['dailyMatch']; ?>">
                </div>

                <!--Submit-->
                <div class="input_web submit">
                    <input type="submit" name="" id="submit-btn" value="Save">
                </div>

            </form>
        </div>
    </div>
</body>

</html>