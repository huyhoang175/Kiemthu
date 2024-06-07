<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Programs</title>
    <link rel="stylesheet" href="../css/programs.css">
    <script src="https://kit.fontawesome.com/b99e675b6e.js"></script>
</head>

<body>
    <?php
    require_once 'ConnectData.php';
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["edit"])) {
            $_SESSION["program_id"]=$_POST["program_id"];
            header("Location: BasicInfo.php");
            exit();
        }

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["delete"])) {
        $del_id = $_POST["del_id"];

        if ($connect->connect_error) {
            die('Cannot connect to database' . $connect->connect_error);
        } else {
            $stmt = $connect->prepare("SELECT _id FROM registrationRequires WHERE program_id=?");
            $stmt->bind_param("s", $del_id);
            $stmt->execute();
            $stmt->bind_result($registrationRequires_id);
            $stmt->fetch();
            $stmt->close();
        }

        if ($connect->connect_error) {
            die('Cannot connect to database' . $connect_error);
        } else {
            $stmt_priceOption = $connect->prepare("DELETE FROM priceOptions WHERE regisRequire_id=?");
            $stmt_priceOption->bind_param("s", $registrationRequires_id);

            $stmt_registration = $connect->prepare("DELETE FROM registrationRequires WHERE program_id=?");
            $stmt_registration->bind_param("s", $del_id);

            $stmt_games = $connect->prepare("DELETE FROM games WHERE program_id=?");
            $stmt_games->bind_param("s", $del_id);

            $stmt_events = $connect->prepare("DELETE FROM events WHERE program_id=?");
            $stmt_events->bind_param("s", $del_id);

            $stmt_groups = $connect->prepare("DELETE FROM groups WHERE program_id=?");
            $stmt_groups->bind_param("s", $del_id);

            $stmt_tpl = $connect->prepare("DELETE FROM teams_players WHERE program_id=?");
            $stmt_tpl->bind_param("s", $del_id);

            $stmt_programs = $connect->prepare("DELETE FROM programs WHERE _id=?");
            $stmt_programs->bind_param("s", $del_id);
            
        }

        if ($stmt_priceOption->execute() && $stmt_registration->execute() && $stmt_games->execute() && $stmt_events->execute()   && $stmt_groups->execute() && $stmt_tpl->execute() && $stmt_programs->execute()) {
            $stmt_priceOption->close();
            $stmt_registration->close();
            $stmt_games->close();
            $stmt_events->close();
            $stmt_programs->close();
            $stmt_groups->close();
            $stmt_tpl->close();
            header('Location: programs.php');
            exit();
        } else {
            echo "Error" . $stmt_programs->error;
        }
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["createProgram"])) {
        $title = $_POST["title"];
        $sport = $_POST["sport"];
        $program = $_POST["program"];
        if($sport=="Football") {
            $img="../image/football.jpg";
        } elseif ($sport=="Volleyball") {
            $img="../image/volleyball.jpg";
        } elseif ($sport=="Badminton") {
            $img="../image/cau-long-vu.jpg";
        } elseif ($sport="Tennis") {
            $img="../image/tennis.jpg";
        }
    
        $stmt = $connect->prepare("INSERT INTO programs (title, sport, type, organization_id, img) VALUES (?, ?, ?, ?, ?)");

if ($stmt === false) {
    die('Prepare failed: ' . $connect->error);
}

$stmt->bind_param("sssis", $title, $sport, $program, $organization_id, $img);

    
        if ($stmt->execute()) {
            $_SESSION["program_id"] = $stmt->insert_id;
    
            $stmt->close();
    
            $program_id = $_SESSION["program_id"];
    
            $stmt2 = $connect->prepare("INSERT INTO registrationRequires (program_id, name_email, phone, birthday, gender, individualPlayer, teamPlayer, coach, staff, individual) VALUES (?, 0, 0, 0, 0, 0, 0, 0, 0, 0)");
            $stmt2->bind_param("s", $program_id);
            $stmt2->execute();
            $stmt2->close();
    
            $stmt3 = $connect->prepare("INSERT INTO groups (program_id, name) VALUES (?, 'EXAMPLE')");
            $stmt3->bind_param("s", $program_id);
            $stmt3->execute();
            $stmt3->close();
    
            $stmt4 = $connect->prepare("INSERT INTO teams_players (program_id, name) VALUES (?, 'EXAMPLE')");
            $stmt4->bind_param("s", $program_id);
            $stmt4->execute();
            $stmt4->close();
    
            header("Location: programs.php");
            exit();
        } else {
            echo "Error: " . $stmt->error;
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
                <div class="header">Programs</div>
                <div class="items">
                    <form action="" method="post" class="form-item">
                        <input class="search" type="text" placeholder="Search" id="live_search_title">
                        <input type="hidden" name="organizationId" id="organizationId" value="<?php echo $organization_id?>">
                        <select id="sport" name="sportlist" form="sportform" class="inf_program">
                            <option value="">Sport</option>
                            <option value="volleyball">Volleyball</option>
                            <option value="football">Football</option>
                            <option value="badminton">Badminton</option>
                            <option value="tenis">Tenis</option>
                        </select>

                        <select id="all" name="all" form="all" class="inf_program">
                            <option value="">All</option>
                            <option value="tounament">Tounament</option>
                            <option value="league">League</option>
                            <option value="camp">Camp</option>
                            <option value="class">Class</option>
                            <option value="training">Training</option>
                            <option value="event">Event</option>
                            <option value="club">Club</option>
                        </select>

                        <input class="add_member_button" id="btn" type="button" value="Create Program" name="createProgram">
                    </form>
                </div>
            </div>

            <div id="searchresult"></div>

            <div class="overlay" id="overlay"></div>
            <form action="" method="post" class="add_member_popup" id="addMemberPopup">
                <span class="close_popup_button" id="closePopupBtn"><i class="fa fa-times"></i></span>
                <p class="popup_title">New program</p>
                <div class="input_program">
                    <label for="title">Title</label>
                    <input type="text" id="title" required="required" placeholder="Enter your Title" name="title">
                </div>

                <div class="input_program">
                    <label for="sport">Sport</label>
                    <select id="sport" name="sport" class="inf_program">
                        <option value="VolleyBall">VolleyBall</option>
                        <option value="Football">Football</option>
                        <option value="Badminton">Badminton</option>
                        <option value="Tenis">Tenis</option>
                    </select>
                </div>

                <div class="input_program">
                    <label for="program">Type of Program</label>
                    <select id="program" name="program">
                        <option value="Tounament">Tounament</option>
                        <option value="League">League</option>
                        <option value="Camp">Camp</option>
                        <option value="Class">Class</option>
                        <option value="Training">Training</option>
                        <option value="Event">Event</option>
                        <option value="Club">Club</option>
                    </select>
                </div>
                <div class="button_container">
                    <button id="closePopup" class="closeP">Close</button>
                    <input type="submit" name="createProgram" id="addMember" class="addM" value="Create">
                </div>
            </form>
        </div>
    </div>

    <script>
        var overlay = document.getElementById('overlay');
        var popup = document.getElementById('addMemberPopup');
        var iframeOverlay = document.getElementById('iframeOverlay');

        document.getElementById('btn').addEventListener('click', function() {
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
    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script type="text/javascript">
        fetchdata();

        function fetchdata() {
            var action = 'fetchData';
            var organization_id = $('#organizationId').val();
            $.ajax({
                url: "livesearch_program1.php",
                method: "POST",
                data: {
                    action: action,
                    organization_id: organization_id
                },
                success: function(data) {
                    $('#searchresult').html(data);
                }
            });
        }

        $(document).ready(function() {
            $('#live_search_title').keyup(function(event) {
                event.preventDefault();
                var action = 'searchRecord';
                var program_title = $('#live_search_title').val();
                var organization_id = $('#organizationId').val();
                if (program_title != '') {
                    $.ajax({
                        url: "livesearch_program1.php",
                        method: "POST",
                        data: {
                            action: action,
                            program_title: program_title,
                            organization_id: organization_id
                        },
                        success: function(data) {
                            $('#searchresult').html(data);
                        }
                    })
                }
            });
        });

        $('#sport').on('change', function() {
            event.preventDefault();
            var action = 'searchBySport';
            var program_sport = $(this).val();
            var organization_id = $('#organizationId').val();
            $.ajax({
                url: "livesearch_program1.php",
                method: "POST",
                data: {
                    action: action,
                    program_sport: program_sport,
                    organization_id: organization_id
                },
                success: function(data) {
                    $('#searchresult').html(data);

                }
            });
        });

        $('#all').on('change', function() {
            event.preventDefault();
            var action = 'searchByType';
            var program_type = $(this).val();
            var organization_id = $('#organizationId').val();
            $.ajax({
                url: "livesearch_program1.php",
                method: "POST",
                data: {
                    action: action,
                    program_type: program_type,
                    organization_id: organization_id
                },
                success: function(data) {
                    $('#searchresult').html(data);

                }
            });
        });
    </script>
</body>

</html>