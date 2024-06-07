<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Side Navigation Bar</title>
    <link rel="stylesheet" href="../css/dashboard.css">
    <script src="https://kit.fontawesome.com/b99e675b6e.js"></script>
</head>
</head>

<body>
    <?php
        require_once 'ConnectData.php';

        $stmt = $connect->prepare("SELECT * FROM organizations WHERE _id = ?");
        $stmt->bind_param("s", $organization_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $data = $result->fetch_assoc();
        $stmt->close();
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
                <li class="d"><a href="../PHP/dashboard.php" target="content"><i class="fas fa-home"></i>Dashboard</a></li>
                <li><a href="../PHP/programs.php" target="content"><i class="fas fa-address-card"></i>Programs</a></li>
                <li><a href="../PHP/registrations.php" target="content"><i class="fas fa-clipboard"></i>Registrations</a></li>
                <li><a href="../PHP/member.php" target="content"><i class="fas fa-users"></i>Members</a></li>
                <li><a href="../PHP/website.php" target="content"><i class="fas fa-globe"></i>Website</a></li>
            </ul>
        </div>
        <div class="main_content" id="content">
            <div class="main">
                <div class="header">Dash board</div>
                <div class="info">
                    <div class="info-title">Your dashboard</div>
                    <div class="content">You can find everything that you manage from your Dashboard here.</div>
                    <div class="content">This concain all what you need <a href="../php/programs.php">programs</a>, <a href="">registrations</a>,<a href="../php/member.php">members</a>, and your <a href="">page</a></div>
                    <div class="clb">
                        <div class="info-website">Your Website: </div>
                        <div class="football"><a href="../php/website_tochuc.php"><?php echo $data['name']?><i class="fas fa-link"></i></a></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>

</html>