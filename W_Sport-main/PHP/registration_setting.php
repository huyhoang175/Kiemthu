<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../css/registration_setting.css">
    
    <script src="https://kit.fontawesome.com/b99e675b6e.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>

<body>
    <?php
    require_once 'ConnectData.php';

    $stmt = $connect->prepare("SELECT _id FROM registrationRequires WHERE program_id = ?");
    $stmt->bind_param("s", $_SESSION["program_id"]);
    $stmt->execute();
    $stmt->bind_result($registrationRequire_id);
    $stmt->fetch();
    $stmt->close();

    $stmt = $connect->prepare("SELECT * FROM programs WHERE _id = ?");
    $stmt->bind_param("s", $_SESSION["program_id"]);
    $stmt->execute();
    $result = $stmt->get_result();
    $dataProgram = $result->fetch_assoc(); //Data program
    $stmt->close();

    $stmt = $connect->prepare("SELECT * FROM registrationRequires WHERE program_id = ?");
    $stmt->bind_param("s", $_SESSION["program_id"]);
    $stmt->execute();
    $result = $stmt->get_result();
    $dataRR = $result->fetch_assoc(); // Data registerRequire
    $stmt->close();

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["saveAll"])) {
        $openRegister = isset($_POST["openRegister"]) ? $_POST["openRegister"] : 0;
        $name_email = isset($_POST["requiredName_email"]) ? $_POST["requiredName_email"] : 0;
        $phone = isset($_POST["requiredPhone"]) ? $_POST["requiredPhone"] : 0;
        $birthday = isset($_POST["requiredBirthday"]) ? $_POST["requiredBirthday"] : 0;
        $gender = isset($_POST["requiredGender"]) ? $_POST["requiredGender"] : 0;
        $individualPlayer = isset($_POST["IndividualPlayer"]) ? $_POST["IndividualPlayer"] : 0;
        $teamPlayer = isset($_POST["TeamPlayer"]) ? $_POST["TeamPlayer"] : 0;
        $coach = isset($_POST["Coach"]) ? $_POST["Coach"] : 0;
        $staff = isset($_POST["staff"]) ? $_POST["staff"] : 0;
        $individual = isset($_POST["Individual"]) ? $_POST["Individual"] : 0;
        $startDate = isset($_POST["startDate"]) ? $_POST["startDate"] : 0;
        $startTime = isset($_POST["startTime"]) ? $_POST["startTime"] : 0;
        $endDate = isset($_POST["endDate"]) ? $_POST["endDate"] : 0;
        $endTime = isset($_POST["endTime"]) ? $_POST["endTime"] : 0;

        $stmt = $connect->prepare("UPDATE  registrationRequires SET name_email=?, phone=?, birthday=?, gender=?, individualPlayer=?, teamPlayer=?, coach=?, staff=?, individual=?, startDate=?, startTime=?, endDate=?, endTime=? WHERE program_id=?");
        $stmt->bind_param("ssssssssssssss", $name_email, $phone, $birthday, $gender, $individualPlayer, $teamPlayer, $coach, $staff, $individual, $startDate, $startTime, $endDate, $endTime, $_SESSION["program_id"]);
        $stmt2 = $connect->prepare("UPDATE programs SET openRegister=? WHERE _id=?");
        $stmt2->bind_param("ss", $openRegister, $_SESSION["program_id"]);
        $stmt2->execute();
        $stmt2->close();
        if ($stmt->execute()) {
            header("Location: registration_setting.php");
        } else {
            echo "Error" . $stmt->error;
        }
        $stmt->close();
        $connect->close();
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["addPriceOption"])) {
        $priceName = $_POST["priceName"];
        $price = $_POST["price"];
        $quantity = $_POST["quantity"];

        $stmt = $connect->prepare("INSERT INTO priceOptions (regisRequire_id, priceName, price, quantity, status) VALUES (?, ?, ?, ?, 0)"); // Sửa dòng này
        $stmt->bind_param("dsss", $registrationRequire_id, $priceName, $price, $quantity);

        if ($stmt->execute()) {
            $stmt->close();
        } else {
            echo "Error: " . $stmt->error;
        }
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['deleteOption'])) {
        $priceOption_Id = $_POST["priceOptionid"];
        if ($connect->connect_error) {
            die('Cannot connect to database' . $connect_error);
        } else {
            $stmt = $connect->prepare("DELETE FROM priceOptions WHERE _id=?");
            $stmt->bind_param("s", $priceOption_Id);
        }
        if ($stmt->execute()) {
            header("Location: registration_setting.php");
            $stmt->close();
            exit;
        } else {
            echo "Error" . $stmt->error;
        }
    }

    if ($connect->connect_error) {
        die('Cannot connect to database' . $connect->connect_error);
    } else {
        $stmt = $connect->prepare("SELECT * FROM priceOptions WHERE regisRequire_id = ?");
        $stmt->bind_param("s", $registrationRequire_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $data_priceOption = $result->num_rows;
        $stmt->close();
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
        <h3><?php echo $dataProgram['title']?></h3>
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
    <form action="" method="post">
        <div class="main">
            <div class="enable">
                <label class="switch">
                    <input type="checkbox" name="openRegister" id="openRegister" value="1" <?php if ($dataProgram['openRegister'] == 1) {
                                                                                                echo "checked";
                                                                                            } else {
                                                                                                echo "";
                                                                                            } ?>>
                    <span class="slider round"></span>
                </label>
                <p class="text">Enable & Public Registration Online for this Program</p>
            </div>


            <div class="option">
    <p class="header">Require options: </p>
    <div class="required">
        <label class="required_checkbox">
            <input type="checkbox" name="requiredName_email" id="requiredName_email" value="1" <?php if (isset($dataRR['name_email']) && $dataRR['name_email'] == 1) echo "checked"; ?>>
            <p>Require name, email</p>
        </label>

        <label class="required_checkbox">
            <input type="checkbox" name="requiredPhone" id="requiredPhone" value="1" <?php if (isset($dataRR['phone']) && $dataRR['phone'] == 1) echo "checked"; ?>>
            <span class="checkbox_custom"></span>
            <p>Require phone</p>
        </label>

        <label class="required_checkbox">
            <input type="checkbox" name="requiredGender" id="requiredGender" value="1" <?php if (isset($dataRR['gender']) && $dataRR['gender'] == 1) echo "checked"; ?>>
            <span class="checkbox_custom"></span>
            <p>Require gender</p>
        </label>

        <label class="required_checkbox">
            <input type="checkbox" name="requiredBirthday" id="requiredBirthday" value="1" <?php if (isset($dataRR['birthday']) && $dataRR['birthday'] == 1) echo "checked"; ?>>
            <span class="checkbox_custom"></span>
            <p>Require birthday</p>
        </label>
    </div>
</div>

<div class="option">
    <p class="header">Role options for register: </p>
    <div class="required">
        <label class="required_checkbox">
            <input type="checkbox" name="IndividualPlayer" id="IndividualPlayer" value="1" <?php if (isset($dataRR['individualPlayer']) && $dataRR['individualPlayer'] == 1) echo "checked"; ?>>
            <p>Register as Individual Player</p>
        </label>

        <label class="required_checkbox">
            <input type="checkbox" name="TeamPlayer" id="TeamPlayer" value="1" <?php if (isset($dataRR['teamPlayer']) && $dataRR['teamPlayer'] == 1) echo "checked"; ?>>
            <span class="checkbox_custom"></span>
            <p>Register as Team Player</p>
        </label>

        <label class="required_checkbox">
            <input type="checkbox" name="Coach" id="Coach" value="1" <?php if (isset($dataRR['coach']) && $dataRR['coach'] == 1) echo "checked"; ?>>
            <span class="checkbox_custom"></span>
            <p>Register as Coach</p>
        </label>

        <label class="required_checkbox">
            <input type="checkbox" name="staff" id="staff" value="1" <?php if (isset($dataRR['staff']) && $dataRR['staff'] == 1) echo "checked"; ?>>
            <span class="checkbox_custom"></span>
            <p>Register as Staff</p>
        </label>

        <label class="required_checkbox">
            <input type="checkbox" name="Individual" id="Individual" value="1" <?php if (isset($dataRR['individual']) && $dataRR['individual'] == 1) echo "checked"; ?>>
            <span class="checkbox_custom"></span>
            <p>Register as Individual</p>
        </label>
    </div>
</div>

            <div class="setUp">
                <p class="header">SetUp Start & End Date for Register</p>
                <div class="start">
                    <div class="start_date">
                        <label for="starDate">Start Date*</label>
                        <input type="date" name="startDate" id="starDate" value="<?php echo $dataRR['startDate']; ?>">
                    </div>
                    <div class="start_time">
                        <label for="starTime">Start Time*</label>
                        <input type="time" name="startTime" id="starTime" value="<?php echo $dataRR['startTime']; ?>">
                    </div>
                </div>

                <div class="end">
                    <div class="end_date">
                        <label for="endDate">End Date</label>
                        <input type="date" name="endDate" id="endDate" value="<?php echo $dataRR['endDate']; ?>">
                    </div>
                    <div class="end_time">
                        <label for="endTime">End Time</label>
                        <input type="time" name="endTime" id="endTime" value="<?php echo $dataRR['endTime']; ?>">
                    </div>
                </div>
            </div>

            <div class="price">
                <p class="header">Price Options:</p>
                <p>Press <span><i class="fa fa-plus-circle"></i></span> to add a new Price Option</p>
                <div class="price_option">
                    <div class="name_options">
                        <p class="header_options">Price Name</p>
                        <p class="header_options">Price</p>
                        <p class="header_options">Price Quanlity</p>
                        <p class="header_options">Action</p>
                    </div>
                    <?php
                    if ($data_priceOption > 0) {
                        while ($infor_priceOption = $result->fetch_assoc()) {
                    ?>
                            <span class="space"></span>
                            <div class="price_item" method="post">
                                <p class="pri_name"><?php echo $infor_priceOption['priceName']; ?></p>
                                <p class="pri_num"><?php echo  formatPrice($infor_priceOption['price']); ?></p>
                                <p class="pri_quanl"><?php echo $infor_priceOption['quantity']; ?></p>

                                <form action="" method="post">
                                <input type="hidden" name="priceOptionid" value="<?php echo $infor_priceOption['_id']; ?>">
                                <button type="submit" name="deleteOption" class="actio"><i class="fas fa-trash"></i></button>
                                </form>
                            </div>
                    <?php
                        }
                    }
                    ?>

                    <!--Submit-->
                    <div class="input_web submit">
                        <input type="submit" name="saveAll" id="submit-btn" value="Save All">
                    </div>
                </div>
            </div>
        </div>
    </form>
    <div class="overlay" id="overlay"></div>
    <form action="" method="post" class="add_member_popup" id="addMemberPopup">
        <span class="close_popup_button" id="closePopupBtn"><i class="fa fa-times"></i></span>
        <p class="popup_title">Add New Team/Player Name</p>
        <label for="priceName">Price Name:</label>
        <input class="inputText" type="text" placeholder="" name="priceName" id="priceName">
        <label for="price">Price:</label>
        <input class="inputText" type="text" placeholder="" name="price" id="price">
        <label for="quantity">Price Quantity:</label>
        <input class="inputText" type="text" placeholder="" name="quantity" id="quantity">
        <div class="button_container">
            <button id="closePopup" class="closeP">Close</button>
            <input type="submit" name="addPriceOption" id="addMember" class="addM" value="Add">
        </div>
    </form>

    <div class="add_member_button" id="openPopupBtn"><i class="fa fa-plus"></i></div>
    <script>
        // JavaScript mới
        var overlay = document.getElementById('overlay');
        var popup = document.getElementById('addMemberPopup');
        var iframeOverlay = document.getElementById('iframeOverlay');

        document.getElementById('openPopupBtn').addEventListener('click', function() {
            overlay.classList.add('show');
            popup.classList.add('show');
            iframeOverlay.classList.add('show'); // Hiển thị overlay cho iframe
        });

        document.getElementById('closePopupBtn').addEventListener('click', function() {
            overlay.classList.remove('show');
            popup.classList.remove('show');
            iframeOverlay.classList.remove('show'); // Ẩn overlay cho iframe
        });

        document.getElementById('closePopup').addEventListener('click', function() {
            overlay.classList.remove('show');
            popup.classList.remove('show');
            iframeOverlay.classList.remove('show'); // Ẩn overlay cho iframe
        });
    </script>
</body>

</html>