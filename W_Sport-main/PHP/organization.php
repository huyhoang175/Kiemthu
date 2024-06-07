<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Profile</title>
    <link rel="stylesheet" href="../css/organization.css">
</head>

<body>
    <?php
    require 'ConnectData.php';

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $name = $_POST["fullname"];
        $description = $_POST["description"];
        $address = $_POST["address"];

        if ($connect->connect_error) {
            die('Cannot connect to data' . $connect_error);
        } else {
            $stmt = $connect->prepare("INSERT INTO organizations (name, tagline , description, owner) VALUES (?, ?, ?, ?)");
            if (!$stmt) {
                die("Prepare failed: " . $connect->error);
            }
            
            $bindResult = $stmt->bind_param("ssss", $name, $address, $description, $_SESSION["user_id"]);
            if (!$bindResult) {
                die("Binding parameters failed: " . $stmt->error);
            }
        }
        if ($stmt->execute()) {
            header("Location: itemmenu.php");
            exit();
        } else {
            echo "Error" . $stmt->error;
        }
        $stmt->close();
    }
    ?>
    <div>
        <!--Navbar-->
        <div class="navbar">
            <div class="navbar-content">
                <div class="navbar-item">
                    <div class="logo">
                        <a href="index.php" class="logo-title">
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
            <div class="organization-content">
                <h3>Create Your Organization</h3>
                <p>Tell us about your new Organization...</p>
                <form action="" id="form-organization" method="post">
                    <div class="input-organ">
                        <label for="fullname">Organization Name</label>
                        <input class="inp_organ" name="fullname" type="text" id="fullname" required="required" placeholder="Enter your organization name">
                    </div>

                    <div class="input-organ">
                        <label for="address">Address</label>
                        <input class="inp_organ" name="address" type="text" id="address" required="required" placeholder="Enter your organization address">
                    </div>

                    <div class="input-organ">
                        <label for="address">Description</label>
                        <input class="inp_organ" name="description" type="text" id="description" required="required" placeholder="Describe your organization">
                    </div>

                    <!--Submit-->
                    <div class="input-organ submit">
                        <input type="submit" name="" id="submit-btn" value="Create">
                    </div>

                </form>
            </div>
        </div>
    </div>
</body>

</html>