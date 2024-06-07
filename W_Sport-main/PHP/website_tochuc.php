<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../css/web_tochuc.css">
    <script src="https://kit.fontawesome.com/b99e675b6e.js"></script>
</head>

<body>
    <?php
    require_once 'ConnectData.php';

    $programId = isset($_GET['programId']) ? $_GET['programId'] : null;
    if ($programId==null) {

    $stmt = $connect->prepare("SELECT * FROM  website WHERE organization_id = ?");
    $stmt->bind_param("s", $organization_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $dataWebsite = $result->fetch_assoc(); //Data website
    $stmt->close();

    $stmt = $connect->prepare("SELECT * FROM  organizations WHERE _id = ?");
    $stmt->bind_param("s", $organization_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $dataOrganization = $result->fetch_assoc(); //Data organization
    $stmt->close();

    $stmt = $connect->prepare("SELECT * FROM  users WHERE _id = ?");
    $stmt->bind_param("s", $_SESSION["user_id"]);
    $stmt->execute();
    $result = $stmt->get_result();
    $dataOwner = $result->fetch_assoc(); //Data owner
    $stmt->close();

    } else {
        $stmt = $connect->prepare("SELECT organization_id FROM programs WHERE _id = ?");
  $stmt->bind_param("s", $programId);
  $stmt->execute();
  $stmt->bind_result($organization_id);
  $stmt->fetch();
  $stmt->close();

  $stmt = $connect->prepare("SELECT owner FROM organizations WHERE _id = ?");
  $stmt->bind_param("s", $organization_id);
  $stmt->execute();
  $stmt->bind_result($owner_id);
  $stmt->fetch();
  $stmt->close();

  $stmt = $connect->prepare("SELECT * FROM  website WHERE organization_id = ?");
  $stmt->bind_param("s", $organization_id);
  $stmt->execute();
  $result = $stmt->get_result();
  $dataWebsite = $result->fetch_assoc(); //Data website
  $stmt->close();

  $stmt = $connect->prepare("SELECT * FROM  organizations WHERE _id = ?");
  $stmt->bind_param("s", $organization_id);
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
                    <div class="dashboard">
                        <p class="goto"><a href="../php/itemmenu.php">Go to Dashboard</a></p>
                        <p><img src="../image/profile.jpg" alt=""></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="main">
        <div class="header">

        <?php if(isset($dataWebsite) && is_array($dataWebsite)) { ?>
        <h2><?php echo isset($dataWebsite['web_name']) ? $dataWebsite['web_name'] : ''; ?></h2>
        <p><?php echo isset($dataWebsite['tagline']) ? $dataWebsite['tagline'] : ''; ?></p>
    <?php } ?>

        </div>

        <div class="content">
            <div class="home">
                <div class="title">
                    <h4>Home</h4>
                </div>
                <?php
                $query = "SELECT * FROM programs WHERE organization_id = '" . $organization_id .  "'";
                $total_row = mysqli_query($connect, $query) or die('error');
                if (mysqli_num_rows($total_row) > 0) {
                    foreach ($total_row as $row) {
                ?>
                        <div class="info">
                            <div class="reg">
                                <div>
                                    <a href=""></a>
                                    <p> <?php echo $row['title']; ?> </p>
                                </div>
                                <div>
                                    <a style="text-decoration: underline;" class="btn-register" href="../PHP/programDetail.php?programId=<?php echo urlencode($row['_id']); ?>">View detail</a>
                                </div>

                            </div>
                            <div class="time_reg">

                                <p><strong>Sport: <?php echo $row['sport']; ?></strong> </p>
                                <p><strong>Type Game: <?php echo $row['type']; ?></strong> </p>

                                <p><strong>Start Date: <?php echo formatDate($row['startDate']); ?></strong> </p>

                                <p><strong>Daily Start: <?php echo formatTime($row['dailyStart']); ?></strong></p>
                                <p><strong>Duration: <?php echo $row['duration'] ?> Minute</strong> </p>
                            </div>

                            <div class="free">
                                <p></p>
                                <p>Free: 0 VND</p>
                            </div>
                        </div>
                <?php
                    }
                } else {
                    echo "No programs available";
                }
                ?>

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
                    <?php
    if(isset($dataOrganization['location'])) {
        echo $dataOrganization['location'];
    } else {
        echo "Location is not available";
    }
    ?>
                </div>
                <div class="item">
                    <p class="item_title">Contact</p>
                    <p class="item_main"><i class="fas fa-envelope"></i><?php echo $dataOwner['email'] ?></p>
                    <p class="item_main"><i class="fas fa-phone"></i><?php echo $dataOwner['phone'] ?></p>
                </div>

            </div>
        </div>

    </div>
</body>

</html>