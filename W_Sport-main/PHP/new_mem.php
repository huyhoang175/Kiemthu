<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../css/new_mem.css">
    <script src="https://kit.fontawesome.com/b99e675b6e.js"></script>
</head>

<body>
    <?php
    require_once 'ConnectData.php';

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $email = $_POST["email"];


        if ($connect->connect_error) {
            die("Connection failed: " . $connect->connect_error);
        }

        $sqlCheckEmail = "SELECT * FROM users WHERE email = '$email'";
        $resultCheckEmail = $connect->query($sqlCheckEmail);

        if ($resultCheckEmail->num_rows > 0) {
            $userData = $resultCheckEmail->fetch_assoc();

            $sqlAddMember = "INSERT INTO members (_id, organization_id, created_at, name, phone, email) 
                             VALUES ('$userData[_id]', '$organization_id', '$userData[birthday]', '$userData[username]', '$userData[phone]', '$userData[email]')";

            if ($connect->query($sqlAddMember) === TRUE) {
                
                    $stmt = $connect->prepare("DELETE FROM registrations WHERE user_id = ?");
                    $stmt->bind_param("s", $userData['_id']);
                    $stmt->execute();
                    $stmt->close();
                    header('location: member.php');
                    exit();
                

                echo '<script>alert("Member added successfully");</script>';
            } else {
                echo '<script>alert("Error");</script>';
                $connect->error;
            }
        } else {
            echo '<script>alert("Email does not exist. Please enter a valid email.");</script>';
        }

        $connect->close();
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
                <div class="header">New Member</div>
                <div class="create_program">
                    <p class="p_enter">Enter member's email to add them to your organization</p>
                    <form action="" id="new_mem" method="post">
                        <div class="input_mem">
                            <label for="email">Email</label>
                            <input class="inp_mem" type="text" id="email" required="required" name="email">
                        </div>

                        <div class="input_mem submit">
                            <input type="submit" name="" id="submit-btn" value="Add">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

</body>

</html>