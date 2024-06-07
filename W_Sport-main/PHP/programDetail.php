<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
  <link rel="stylesheet" href="../css/programDetail.css">
  <script src="https://kit.fontawesome.com/b99e675b6e.js"></script>
</head>

<body>

  <?php
  require_once 'ConnectData.php';

  $programId = isset($_GET['programId']) ? $_GET['programId'] : null;

  $stmt = $connect->prepare("SELECT organization_id FROM programs WHERE _id = ?");
  $stmt->bind_param("s", $programId);
  $stmt->execute();
  $stmt->bind_result($organizationId);
  $stmt->fetch();
  $stmt->close();

  $stmt = $connect->prepare("SELECT owner FROM organizations WHERE _id = ?");
  $stmt->bind_param("s", $organizationId);
  $stmt->execute();
  $stmt->bind_result($owner_id);
  $stmt->fetch();
  $stmt->close();

  $stmt = $connect->prepare("SELECT * FROM  website WHERE organization_id = ?");
  $stmt->bind_param("s", $organizationId);
  $stmt->execute();
  $result = $stmt->get_result();
  $dataWebsite = $result->fetch_assoc(); //Data website
  $stmt->close();

  $stmt = $connect->prepare("SELECT * FROM  organizations WHERE _id = ?");
  $stmt->bind_param("s", $organizationId);
  $stmt->execute();
  $result = $stmt->get_result();
  $dataOrganization = $result->fetch_assoc(); //Data organization
  $stmt->close();

  $stmt = $connect->prepare("SELECT * FROM  users WHERE _id = ?");
  $stmt->bind_param("s", $owner_id);
  $stmt->execute();
  $result = $stmt->get_result();
  $dataOwner = $result->fetch_assoc(); //Data owner
  $stmt->close();

  $stmt = $connect->prepare("SELECT * FROM  registrationrequires WHERE program_id = ?");
  $stmt->bind_param("s", $programId);
  $stmt->execute();
  $result = $stmt->get_result();
  $dataRR = $result->fetch_assoc(); //Data Register Require
  $stmt->close();

  $stmt = $connect->prepare("SELECT * FROM  priceOptions WHERE regisRequire_id = ?");
  $stmt->bind_param("s", $dataRR['_id']);
  $stmt->execute();
  $result = $stmt->get_result();
  $dataPO = $result->fetch_assoc(); //Data Price option
  $stmt->close();

  $stmt = $connect->prepare("SELECT * FROM programs WHERE _id = ?");
  $stmt->bind_param("s", $programId);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows > 0)
    $row = $result->fetch_assoc();
  $stmt->close();
  ?>

  <div class="navbar">
    <div class="navbar-content">
      <div class="navbar-item">
        <div class="logo">
          <a href="Index.php" class="logo-title">
            <h2>Sport Management</h2>
          </a>
        </div>

        <div class="nav_profile">
          <div class="dashboard">
            <p class="goto"><a href="../PHP/itemmenu.php">Go to Dashboard</a></p>
            <p><img src="../img/profile.jpg" alt=""></p>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="main">

    <div class="content">
      <div class="home">
        <div class="info">
          <div class="reg">
            <div>
              <p><?php echo $row['location'] ?></p>
            </div>
            <?php
            function checkEmailExists($table, $user_email)
            {
                $connect = new mysqli("localhost", "root", "", "web_sport");
                if ($connect->connect_error) {
                    echo "Connection error : " . $connect->connect_error;
                }
                $stmt = $connect->prepare("SELECT COUNT(*) AS email_count FROM $table WHERE email = ?");
                $stmt->bind_param("s", $user_email);
                $stmt->execute();
                $stmt->bind_result($result);
                $stmt->fetch();
                $stmt->close();
            
                return $result;
            }
            
            if (isset($row) && is_array($row) && isset($row['openRegister']) && $row['openRegister'] == 1) {
              if (isset($data) && is_array($data) && isset($data['email'])) {
                  $registrations_count = checkEmailExists("registrations", $data['email']);
                  $members_count = checkEmailExists("members", $data['email']);
          
                  if ($registrations_count != 0) {
                      echo '<input type="button" value="Processing" style="background: #FFD700;">';
                  } elseif ($members_count != 0) {
                      echo '<input type="button" value="Accepted" style="background: Green;">';
                  } else {
                      echo '<input id="btn" type="button" value="Register">';
                  }
              } 
          } 
          
           
            ?>
          </div>
          <div class="time_reg">


            <p><strong>Title: </strong><?php echo $row['title']; ?></p>
            <p><strong>Sport: </strong><?php echo $row['sport']; ?> </p>
            <p><strong>Duration: </strong> <?php echo $row['duration']; ?> minutes</p>
            <p><strong>Type: </strong> <?php echo $row['type']; ?></p>


            <p><strong>StartDate: </strong> <?php echo formatDate($row['startDate']); ?></p>
            <p><strong>DailyStart: </strong> <?php echo formatTime($row['dailyStart']); ?></p>
            <p><strong>DailyMatch: </strong> <?php echo $row['dailyMatch']; ?></p>
            <p><strong>Role avaiable:</strong>
            <?php
if (isset($dataRR['individualPlayer']) && $dataRR['individualPlayer'] != 0) {
    echo 'Individual Player, ';
}
if (isset($dataRR['teamPlayer']) && $dataRR['teamPlayer'] != 0) {
    echo 'Team Player, ';
}
if (isset($dataRR['coach']) && $dataRR['coach'] != 0) {
    echo 'Coach, ';
}
if (isset($dataRR['staff']) && $dataRR['staff'] != 0) {
    echo 'Staff ';
}
if (isset($dataRR['individual']) && $dataRR['individual'] != 0) {
    echo 'Individual.';
}
?>

            </p>
          </div>

          <div class="free">
            <?php
            $query = "SELECT * FROM priceoptions WHERE regisRequire_id = '" . $dataRR['_id'] . "'";
            $total_row = mysqli_query($connect, $query) or die('error');
            if (mysqli_num_rows($total_row) > 0) {
              foreach ($total_row as $PO) {
                echo '<p>' . $PO['priceName'] . ': ' . formatPrice($PO['price']) . ' VND<br/></p>';
              }
            }
            ?>
          </div>
        </div>

        <div class="info">
          <h3 style="margin-bottom: 20px">Schedule: </h3>
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

            $query_game = "SELECT * FROM games WHERE program_id= '" . $programId . "'";
            $result_game = mysqli_query($connect, $query_game) or die('error');
            while ($row = mysqli_fetch_assoc($result_game)) {
              $row['type'] = 'game';
              $events_and_games[] = $row;
            }

            $query_event = "SELECT * FROM events WHERE program_id = '" . $programId . "'";
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
                        <p>' . formatTime($row['startTime']) . ' to ' . formatTime($row['endTime']) . '</p>
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
                                <p>' . formatTime($row['startTime']) . ' to ' . formatTime($row['endTime']) . '</p>
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
                        </div>
                    </div>
                    </div>';
              }
            }

            ?>
          </div>
        </div>
      </div>

      <div class="about">
        <div class="title">
          <h4>About</h4>
        </div>
        <div class="item">
          <p class="item_title"><?php echo $dataOrganization['name'] ?></p>
          <p class="item_main"><?php echo $dataOrganization['description'] ?></p>
        </div>

        <div class="item">
    <p class="item_title">Location</p>
    <p class="item_main">
        <?php 
        if (isset($dataOrganization['location'])) {
            echo $dataOrganization['location'];
        } else {
            echo "Location is not available";
        }
        ?>
    </p>
</div>


        <div class="item">
          <p class="item_title">Contact</p>
          <p class="item_main"><i class="fas fa-envelope"></i><?php echo $dataOwner['email'] ?></p>
          <p class="item_main"><i class="fas fa-phone"></i><?php echo $dataOwner['phone'] ?></p>
        </div>

        <div class="item">
          <p class="item_title">Website</p>
          <a class="item_main" href="../PHP/website_tochuc.php?programId=<?php echo urlencode($programId);?>" style="text-decoration: underline;">Visit our website!!</a>
        </div>

      </div>
    </div>

  </div>
  <script>
    document.getElementById("btn").addEventListener("click", function() {
      window.location.href = "../PHP/reg_show.php?programId=<?php echo urlencode($programId); ?>&organizationId=<?php echo urlencode($organizationId); ?>";
    });
  </script>
</body>

</html>