<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Settings</title>
    <link rel="stylesheet" href="../css/basicInfo.css">
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
        $stmt->bind_param("s", $_SESSION['program_id']);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $stmt->close();
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $subtitle = $_POST["subtitle"];
        $description = $_POST["description"];
        $location = $_POST["location"];
        if ($connect->connect_error) {
            die('Cannot connect to database' . $connect_error);
        } else {
            $stmt = $connect->prepare("UPDATE programs SET subTitle=?, description=?, location=? WHERE _id=?");
            $stmt->bind_param("ssss", $subtitle, $description, $location, $_SESSION['program_id']);
        }
        if ($stmt->execute()) {
            $stmt->close();
            header("Location: BasicInfo.php");
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
                    <label for="title">Title</label>
                    <input class="inp_program" type="text" id="title" required="required" placeholder="" name="title" value="<?php echo $row['title']; ?>" readonly>
                </div>
                <div class="input_web">
                    <label for="subTitle">SubTitle </label>
                    <textarea class="inp_program" name="subtitle" id="subTitle" cols="30" rows="2" placeholder=""><?php echo $row['subTitle']; ?></textarea>
                </div>
                <div class="input_web">
                    <label for="description">Description</label>
                    <textarea class="inp_descrip " name="description" id="description" cols="30" rows="2" placeholder=""><?php echo $row['description']; ?></textarea>
                </div>
                <div class="input_web">
                    <label for="location">Location</label>
                    <input class="inp_program" type="text" id="location" required="required" placeholder="" name="location" value="<?php echo $row['location']; ?>">
                </div>
                <div class="input_web">
                    <label for="sport">Sport</label>
                    <input class="inp_program" type="text" id="sport" required="required" placeholder="" name="sport" value="<?php echo $row['sport']; ?>" readonly>
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