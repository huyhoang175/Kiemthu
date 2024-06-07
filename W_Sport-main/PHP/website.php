<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../css/website.css">
    <script src="https://kit.fontawesome.com/b99e675b6e.js"></script>
</head>

<body>
    <?php
    require_once 'ConnectData.php';

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $web_name = $_POST["title"];
        $tagline = $_POST["tagline"];
        $description = $_POST["description"];
        $address = $_POST["address"];

        $stmt =$connect->prepare("INSERT INTO website (organization_id, web_name, tagline, description, address) 
        VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $organization_id, $web_name, $tagline, $description, $address);
        if($stmt->execute()) {
            $stmt->close();
            header("location: Dashboard.php");
            exit();
        } else {

        }
        echo "Error: " . $stmt->error;
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

                <!--khi đã đăng nhập, hiển thị profile-->
                <div class="nav_profile">
                    <div class="items">
                        <a href="../PHP/Profile.php" class="sign_up"><img src="../Image/profile.jpg" alt="User Avatar" class="user-avatar"></a>

                        <a href=../PHP/Logout.php class="sign_in">Logout</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="wrapper">
        <div class="sidebar" id="sidebar">
            <ul>
                <li><a href="../PHP/dashboard.php" target="content"><i class="fas fa-home"></i>Dashboard</a></li>
                <li><a href="../php/programs.php" target="content"><i class="fas fa-address-card"></i>Programs</a></li>
                <li><a href="../php/registrations.php" target="content"><i class="fas fa-clipboard"></i>Registrations</a></li>
                <li><a href="../php/member.php" target="content"><i class="fas fa-users"></i>Members</a></li>
                <li><a href="../php/website.php" target="content"><i class="fas fa-globe"></i>Website</a></li>
            </ul>
        </div>
        <div class="main_content" id="content">
            <div class="main">
                <div class="header">Website Information</div>
                <div class="create_web">
                    <form action="" id="new_web" method="post">
                        <div class="input_web">
                            <label for="title">Website name:</label>
                            <input class="inp_program" type="text" id="title" required="required" placeholder="Enter your website Name" name="title">
                        </div>

                        <div class="input_web">
                            <label for="tagline">Tagline </label>
                            <textarea class="inp_program" name="tagline" id="tagline" cols="30" rows="2" placeholder="Enter your Tagline"></textarea>
                        </div>

                        <div class="input_web">
                            <label for="description">Description</label>
                            <textarea class="inp_descrip" name="description" id="description" cols="30" rows="2" placeholder="Enter your Description"></textarea>
                        </div>

                        <div class="input_web">
                            <label for="Address">Address</label>
                            <input class="inp_program" type="text" id="address" required="required" placeholder="Enter your Address" name="address">
                        </div>

                        <!--Submit-->
                        <div class="input_web submit">
                            <input type="submit" name="" id="submit-btn" value="Save">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


</body>

</html>