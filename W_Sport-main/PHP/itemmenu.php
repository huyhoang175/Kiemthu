<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Side Navigation Bar</title>
    <link rel="stylesheet" href="../css/itemmenu.css">
    <script src="https://kit.fontawesome.com/b99e675b6e.js"></script>
</head>

<body>
    <div class="navbar">
        <div class="navbar-content">
            <div class="navbar-item">
                <div class="logo">
                    <a href="../PHP/Index.php" class="logo-title">
                        <h2 title="Sport Management">SportManagement</h2>
                    </a>
                </div>

                <?php
                require_once 'ConnectData.php';

                if (isset($_SESSION["user_id"])) {
                    echo '<div class="nav_profile">
                                <div class="items">
                                    <a href="../PHP/Profile.php" class="sign_up"><img src="../Image/profile.jpg" alt="User Avatar" class="user-avatar"></a>

                                    <a href=../PHP/Logout.php class="sign_in">Logout</a>
                                </div>
                            </div>';
                } else {
                    echo '<div class="nav_profile">
                                <div class="items">
                                    <p class="goto"><a href="../PHP/itemmenu.php">Go to Dashboard</a></p>
                                    <p><img src="../img/profile.jpg" alt=""></p>
                                </div>
                            </div>';
                }
                ?>
            </div>
        </div>
    </div>

    <div class="wrapper">
        <div class="sidebar" id="sidebar">
            <ul>
                <li><a href="../PHP/dashboard.php"><i class="fas fa-home"></i>Dashboard</a></li>
                <li><a href="../php/programs.php"><i class="fas fa-address-card"></i>Programs</a></li>
                <li><a href="../php/registrations.php"><i class="fas fa-clipboard"></i>Registrations</a></li>
                <li><a href="../php/member.php"><i class="fas fa-users"></i>Members</a></li>
                <li><a href="../php/website.php"><i class="fas fa-globe"></i>Website</a></li>
            </ul>
        </div>
        <div class="main_content" id="content">
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $('#sidebar ul li a').click(function(e) {
                e.preventDefault(); // Ngăn chặn hành vi mặc định của liên kết
                
                // Tải nội dung của URL liên kết vào phần tử #content
                $('#content').load($(this).attr('href'));
            });
        });
    </script>
</body>

</html>