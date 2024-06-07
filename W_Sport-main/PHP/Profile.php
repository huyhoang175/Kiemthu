<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Profile</title>
    <link rel="stylesheet" href="../CSS/Profile.css">
</head>

<body>
    <?php
    require 'ConnectData.php';

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["uploadInf"])) {
        $birthday = $_POST["birthday"];
        $gender = $_POST["gender"];
        $phone = $_POST["phone"];
        if ($connect->connect_error) {
            die('Cannot connect to database' . $connect_error);
        } else {
            $stmt = $connect->prepare("UPDATE users SET birthday=?, gender=?, phone=? WHERE _id=?");
            $stmt->bind_param("ssss", $birthday, $gender, $phone, $user_id);
        }
        if ($stmt->execute()) {
            header("Location: Profile.php");
            $stmt->close();
            exit();
        } else {
            echo "Error" . $stmt->error;
        }
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["uploadAvatar"])) {
        if (isset($_FILES["image"]) && $_FILES["image"]["error"] == 0) {
            $targetDir = "../Image/";
            $targetFile = $targetDir . basename($_FILES["image"]["name"]);
            $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

            if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetFile)) {
                $imagePath = $targetFile;
                $stmt = $connect->prepare("UPDATE users SET avatar=? WHERE _id=?");
                $stmt->bind_param("ss", $imagePath, $user_id);
                if ($stmt->execute()) {
                    header("Location: Profile.php");
                    $stmt->close();
                    exit();
                } else {
                    echo "Error" . $stmt->error;
                }
            }
        } else {
            echo "No file uploaded";
        }
    }
    $connect->close();
    ?>
    <div>
        <!--Navbar-->
        <div class="navbar">
            <div class="navbar-content">
                <div class="navbar-item">
                    <div class="logo">
                        <a href="../PHP/Index.php" class="logo-title">
                            <h2 title="Sport Management">SportManagement</h2>
                        </a>
                    </div>

                    <!--khi da dang nhap, hien thi profile-->
                    <div></div>


                </div>
            </div>
        </div>
        <!--main-->
        <div class="main-content">
            <div class="profile-icon">
                <div class="img_items">
                    <form method="POST" class="img" enctype="multipart/form-data">
                        <img src="<?php echo $data['avatar']; ?>" alt="">
                        <input type="file" name="image" id="image" accept="image/*" required>
                        <br><button type="submit" name="uploadAvatar">Upload</button>
                    </form>
                    <div class="icon-content">
                        <p class="username"><?php echo $data['username']; ?></p>
                        <p class="email"><?php echo $data['email']; ?></p>
                    </div>
                </div>

            </div>
            <div class="profile-content">
                <h3>Your Profile</h3>
                <form action="" id="form-profile" method="POST">
                    <div class="input-profile">
                        <label for="fullname">Full Name</label>
                        <input class="inp_profile" type="text" id="fullname" required="required" placeholder="Enter your Fullname" name="fullName" value="<?php echo $data['name']; ?>" readonly>
                    </div>

                    <div class="input-profile">
                        <label for="email">Email</label>
                        <input class="inp_profile" type="text" id="Email" required="required" placeholder="Enter your Email" name="email" value="<?php echo $data['email']; ?>" readonly>
                    </div>

                    <div class="input-profile">
                        <label for="email">BirthDay</label>
                        <input class="inp_profile" type="date" id="birthday" required="required" placeholder="Enter your BirthDay" name="birthday" value="<?php echo $data['birthday'] ?>">
                    </div>

                    <div class="input-profile pr_gender">
                        <label for="gender">Gender</label>
                        <select id="choose_gender" name="gender">
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                        </select>
                    </div>

                    <div class="input-profile">
                        <label for="phone">Phone</label>
                        <input class="inp_profile" type="text" id="phone" required="required" placeholder="Enter your Phone" name="phone" value="<?php echo $data['phone']; ?>">
                    </div>

                    <!--Submit-->
                    <div class="input-profile submit">
                        <input type="submit" name="uploadInf" id="submit-btn" value="Save">
                    </div>

                </form>
            </div>
        </div>
    </div>
</body>

</html>