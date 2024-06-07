<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../css/schedule.css">
    <link rel="stylesheet" href="../css/navbar.css">
    <link rel="stylesheet" href="../css/settings.css">
    <script src="https://kit.fontawesome.com/b99e675b6e.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>

<body>
    <?php
    require_once 'ConnectData.php';

    $stmt = $connect->prepare("SELECT * FROM programs WHERE _id = ?");
    $stmt->bind_param("s", $_SESSION["program_id"]);
    $stmt->execute();
    $result = $stmt->get_result();
    $dataProgram = $result->fetch_assoc(); //Data program
    $stmt->close();

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["addGame"])) {
        $team1 = $_POST["nameTeam1"];
        $team2 = $_POST["nameTeam2"];
        $startDate = $_POST["date"];
        $startTime = $_POST["startTime"];
        $endTime = $_POST["endTime"];
        $location = $_POST["location"];
        $typeGame = $_POST["gameType"];
        $gameNote = $_POST["gameNote"];

        $stmt = $connect->prepare("INSERT INTO games (program_id, team1, team2, startDate, startTime, endTime, team1Score, team2Score, location, typeGame, gameNote) value (?, ?, ?, ?, ?, ?, '-', '-', ?, ?, ?)");
        $stmt->bind_param("sssssssss", $_SESSION["program_id"], $team1, $team2, $startDate, $startTime, $endTime, $location, $typeGame, $gameNote);
        if ($stmt->execute()) {
            header("Location: schedule.php");
            $stmt->close();
            exit();
        } else {
            echo "Error" . $stmt->error;
        }
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["addEvent"])) {
        $typeEvent = $_POST['typeEvent'];
        $date = $_POST['date'];
        $startTime = $_POST['startTime'];
        $endTime = $_POST['endTime'];
        $location = $_POST['location'];
        $eventNote = $_POST['eventNote'];

        $stmt = $connect->prepare("INSERT INTO events (program_id, typeEvent, location, startDate, startTime, endTime, description) VALUES (?, ?, ?, ?, ?, ?, ?)");

        if (!$stmt) {
            echo "Prepare failed: (" . $connect->errno . ") " . $connect->error;
        }

        if (!$stmt->bind_param("sssssss", $_SESSION["program_id"], $typeEvent, $location, $date, $startTime, $endTime, $eventNote)) {
            echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
        }

        if ($stmt->execute()) {
            header("Location: schedule.php");
            $stmt->close();
            exit();
        } else {
            echo "Error: (" . $stmt->errno . ") " . $stmt->error;
        }
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['del_game'])) {
        $game_id = $_POST["game_id"];
        if ($connect->connect_error) {
            die('Cannot connect to database' . $connect_error);
        } else {
            $stmt = $connect->prepare("DELETE FROM games WHERE _id=?");
            $stmt->bind_param("s", $game_id);
        }
        if ($stmt->execute()) {
            header("Location: schedule.php");
            $stmt->close();
            exit;
        } else {
            echo "Error" . $stmt->error;
        }
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['edit_game'])) {
        $_SESSION['game_id'] = $_POST["game_id"];
        header("Location: editGame.php");
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['del_event'])) {
        $event_id = $_POST["event_id"];
        if ($connect->connect_error) {
            die('Cannot connect to database' . $connect_error);
        } else {
            $stmt = $connect->prepare("DELETE FROM events WHERE _id=?");
            $stmt->bind_param("s", $event_id);
        }
        if ($stmt->execute()) {
            header("Location: schedule.php");
            $stmt->close();
            exit;
        } else {
            echo "Error" . $stmt->error;
        }
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['edit_event'])) {
        $_SESSION['event_id'] = $_POST["event_id"];
        header("Location: editEvent.php");
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
        <h3><?php echo $dataProgram['title'] ?></h3>
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
        <div class="header">
            <form action="" method="post">
                <input type="button" value="Add Game" id="addGame" class="addG">
                <input type="button" value="Add Event" id="addEvent" class="addE">

                <!-- Bảng popup -->
                <div id="addGamesPopup" class="popup" method="post">
                    <form action="" method="post">
                        <div class="popup-content">
                            <span class="close" id="close">&times;</span>
                            <h2>Add Games</h2>
                            <div class="inp_editGames">
                                <label for="">Home Team:</label>
                                <select name="nameTeam1" id="nameTeam1">
                                    <?php
                                    $query = "SELECT * from teams_players WHERE program_id = '" . $_SESSION['program_id'] . "'";
                                    $total_row = mysqli_query($connect, $query) or die('error');
                                    if (mysqli_num_rows($total_row) > 0) {
                                        foreach ($total_row as $row) {
                                    ?>
                                            <option value="<?php echo $row['_id']; ?>">
                                                <?php echo $row['name'] ?>
                                            </option>
                                    <?php
                                        }
                                    } else {
                                        echo 'No Data Found!';
                                    }
                                    ?>
                                </select>
                            </div>

                            <div class="inp_editGames">
                                <label for="">Away Team:</label>
                                <select name="nameTeam2" id="nameTeam2">
                                    <?php
                                    $query = "SELECT * FROM teams_players WHERE program_id = '" . $_SESSION['program_id'] . "'";
                                    $total_row = mysqli_query($connect, $query) or die('error');
                                    if (mysqli_num_rows($total_row) > 0) {
                                        foreach ($total_row as $row) {
                                    ?>
                                            <option value="<?php echo $row['_id']; ?>">
                                                <?php echo $row['name'] ?>
                                            </option>
                                    <?php
                                        }
                                    } else {
                                        echo 'No Data Found!';
                                    }
                                    ?>
                                </select>
                            </div>

                            <div class="inp_editGames">
                                <label for="">Date:</label>
                                <input type="date" id="date" name="date">
                            </div>

                            <div class="inp_editGames">
                                <label for="">Group</label>
                                <select name="team" id="group" name="group">
                                    <option value="none">None</option>
                                    <?php
                                    $query = "SELECT * from groups WHERE program_id = '" . $_SESSION['program_id'] . "'";
                                    $total_row = mysqli_query($connect, $query) or die('error');
                                    if (mysqli_num_rows($total_row) > 0) {
                                        foreach ($total_row as $row) {
                                    ?>
                                            <option value=<?php echo $row['name']; ?>>
                                                <?php echo $row['name'] ?>
                                            </option>
                                    <?php
                                        }
                                    } else {
                                        echo 'No Data Found!';
                                    }
                                    ?>
                                </select>
                            </div>

                            <div class="inp_editGames">
                                <label for="">Start Time</label>
                                <input type="time" placeholder="" class="startTime" id="startTime" name="startTime">
                            </div>

                            <div class="inp_editGames">
                                <label for="">End Time</label>
                                <input type="time" placeholder="" id="endTime" name="endTime">
                            </div>
                            <div class="inp_editGames">
                                <label for="">Location</label>
                                <input type="text" placeholder="" id="location" name="location">
                            </div>
                            <div class="inp_editGames">
                                <label for="">Game Type</label>
                                <select name="gameType" id="gameType">
                                    <option value="Group stage">Group stage</option>
                                    <option value="Quarter-finals">Quarter-finals</option>
                                    <option value="Semi-finals">Semi-finals</option>
                                    <option value="Finals">Finals</option>
                                </select>
                            </div>
                            <div class="inp_editGames">
                                <label for="">Game Note</label>
                                <textarea name="gameNote" id="gameNote" cols="30" rows="2"></textarea>
                            </div>


                            <div class="button_container">
                                <button id="add" type="submit" name="addGame">Add</button>
                                <button id="close">Close</button>
                            </div>
                        </div>
                    </form>

                </div>

                <div id="addEventsPopup" class="popup" method="post">
                    <form action="" method="post">
                        <div class="popup-content">
                            <span class="closeE">&times;</span>
                            <h2>Edit Events</h2>
                            <div class="inp_editGames">
                                <label for="">Type event:</label>
                                <select name="typeEvent" id="typeEvent">
                                    <option value="Opening ceremony">Opening ceremony</option>
                                    <option value="Closing ceremony">Closing ceremony</option>
                                    <option value="Award ceremony">Award ceremony</option>
                                    <option value="Press conference">Press conference</option>
                                </select>
                            </div>

                            <div class="inp_editGames">
                                <label for="">Date:</label>
                                <input type="date" id="date" name="date">
                            </div>

                            <div class="inp_editGames">
                                <label for="">Start Time</label>
                                <input type="time" placeholder="" class="startTime" name="startTime">
                            </div>

                            <div class="inp_editGames">
                                <label for="">End Time</label>
                                <input type="time" placeholder="" id="endTime" name="endTime">
                            </div>
                            <div class="inp_editGames">
                                <label for="">Location</label>
                                <input type="text" placeholder="" id="location" name="location">
                            </div>
                            <div class="inp_editGames">
                                <label for="">Event note</label>
                                <textarea id="eventNote" cols="30" rows="2" name="eventNote"></textarea>
                            </div>


                            <div class="button_container">
                                <button id="close">Close</button>
                                <button id="save" type="submit" name="addEvent">Save</button>
                            </div>
                        </div>
                    </form>
                </div>
            </form>
        </div>
        <div class="create_web">
            <!--Schedule-->
            <div id="scheduleContent" class="content">
                <?php
                function getName($team_id)
                {
                    $connect = new mysqli("localhost", "root", "", "web_sport");
                    if ($connect->connect_error) {
                        echo "Connection error : " . $connect->connect_error;
                    }

                    $stmt = $connect->prepare("SELECT name FROM teams_players WHERE _id = ?");
                    $stmt->bind_param("s", $team_id);
                    $stmt->execute();
                    $stmt->bind_result($team_name);
                    $stmt->fetch();
                    $stmt->close();

                    return $team_name;
                }

                $events_and_games = array();

                $query_game = "SELECT * FROM games WHERE program_id= '" . $_SESSION['program_id'] . "'";
                $result_game = mysqli_query($connect, $query_game) or die('error');
                while ($row = mysqli_fetch_assoc($result_game)) {
                    $row['type'] = 'game';
                    $events_and_games[] = $row;
                }

                $query_event = "SELECT * FROM events WHERE program_id = '" . $_SESSION['program_id'] . "'";
                $result_event = mysqli_query($connect, $query_event) or die('error');
                while ($row = mysqli_fetch_assoc($result_event)) {
                    $row['type'] = 'event';
                    $events_and_games[] = $row;
                }

                usort($events_and_games, function ($a, $b) {
                    return strtotime($a['startDate'] . ' ' . $a['startTime']) - strtotime($b['startDate'] . ' ' . $b['startTime']);
                });

                $output = "";
                $prev_start_date = null;

                foreach ($events_and_games as $row) {
                    if ($row['type'] == 'game') {

                        echo '<div class="view_info ">';
                        if ($row['startDate'] != $prev_start_date) {
                            echo '
                <div class="time">
                    <p>' . formatDate($row['startDate']) . '</p>
                </div>
            ';
                        }
                        $prev_start_date = $row['startDate'];

                        echo '

            <div class="match_location showMatch">
                <div class="info_match">
                    <div class="info_time">
                        <p>' . formatTime($row['startTime']) . ' : ' . formatTime($row['endTime']) . '</p>
                    </div>

                    <div class="info_nameclub">
                        <div class="nameClub">
                            <p class="club1">' . getName($row['team1']) . '</p>
                            <p class="vs">vs</p>
                            <p class="club2">' . getName($row['team2']) . '</p>
                        </div>
                        <p class="nameplay">' . $row['typeGame'] . '</p>
                    </div>

                    <div class="info_score">
                        <p>Score: ' . $row['team1Score'] . ' _ ' . $row['team2Score'] . '</p>
                    </div>
                </div>
                <div class="info_loca more">
                    <p class="stadium">Location: ' . $row['location'] . '</p>
                    <form action="" method="post">
                        <input type="hidden" name="game_id" id="game_id" value="'.$row['_id'].'">
                        <button style="background: none; border: none;"  type="submit" name ="edit_game"><i class="fa fa-pencil-square-o"></i></button>
                        <button style="background: none; border: none;" type="submit" name="del_game"><i class="fas fa-trash"></i></button>
                    </form>
                </div>
            </div>
        </div>
        ';
                    } else {
                        echo '
        <div class="view_info">
        <div class="time">
                        <p>' . formatDate($row['startDate']) . '</p>
                    </div>

                    <div class="match_location">
                        <div class="info_match">
                            <div class="info_time">
                                <p>' . formatTime($row['startTime']) . ' : ' . formatTime($row['endTime']) . '</p>
                            </div>
                            <div class="info_status">
                                <p>Event</p>
                            </div>
                            <div class="info_name">
                                <p class="status">' . $row['typeEvent'] . '</p>
                            </div>
                        </div>

                        <div class="info_loca">
                            <p class="stadium">Location: ' . $row['location'] . '</p>
                            <form action="" method="post">
                            <input type="hidden" name="event_id" value="'.$row['_id'].'">
                            <button style="background: none; border: none;" type="submit" name="edit_event"><i class="fa fa-pencil-square-o"></i></button>
                            <button style="background: none; border: none; type="submit" name="del_event"><i class="fas fa-trash"></i></button>
                            </form>

                        </div>
                    </div>
                    </div>';
                    }
                }

                ?>
            </div>
        </div>
        <script>
            document.getElementById('addGame').addEventListener('click', function() {
                document.getElementById('addGamesPopup').style.display = 'block';
            });
            document.getElementById('close').addEventListener('click', function() {
                document.getElementById('addGamesPopup').style.display = 'none';
            });
            document.getElementById('addEvent').addEventListener('click', function() {
                document.getElementById('addEventsPopup').style.display = 'block';
            });
            document.getElementById('closeE').addEventListener('click', function() {
                document.getElementById('addEventsPopup').style.display = 'none';
            });

        </script>
</body>

</html>