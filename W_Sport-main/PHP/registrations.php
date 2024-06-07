  <!DOCTYPE html>
  <html lang="en">

  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrations</title>
    <link rel="stylesheet" href="../css/registrations.css">
    <script src="https://kit.fontawesome.com/b99e675b6e.js"></script>
  </head>

  <body>
    <?php
    require_once 'ConnectData.php';

    function getProgramName($program_id)
    {
      $connect = new mysqli("localhost", "root", "", "web_sport");
      if ($connect->connect_error) {
        echo "Connection error : " . $connect->connect_error;
      }

      $stmt = $connect->prepare("SELECT title FROM programs WHERE _id = ?");
      $stmt->bind_param("s", $program_id);
      $stmt->execute();
      $stmt->bind_result($program_name);
      $stmt->fetch();
      $stmt->close();
      $connect->close();

      return $program_name;
    }


    $stmt = $connect->prepare("SELECT * FROM registrations WHERE organization_id='$organization_id'");
    $stmt->execute();
    $result = $stmt->get_result();

    if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['del_registration'])) {
      $del_id = $_POST['del_id'];
      if ($connect->connect_error) {
      } else {
        $stmt = $connect->prepare("DELETE FROM registrations WHERE _id = ?");
        $stmt->bind_param("s", $del_id);
        $stmt->execute();
        $stmt->close();
      }
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
          <div class="header">Registrations</div>
          <div class="items">
            <form action="search_" method="post" class="form-item">
              <input class="search" type="text" placeholder="Search">
              <select id="sport" name="sportlist" form="sportform">
                <option value="">All Program</option>
                <?php
                function getName($program_id)
                {
                    $connect = new mysqli("localhost", "root", "", "web_sport");
                    if ($connect->connect_error) {
                        echo "Connection error : " . $connect->connect_error;
                    }

                    $stmt = $connect->prepare("SELECT title FROM programs WHERE _id = ?");
                    $stmt->bind_param("s", $program_id);
                    $stmt->execute();
                    $stmt->bind_result($program_name);
                    $stmt->fetch();
                    $stmt->close();

                    return $program_name;
                }
                $query = "SELECT * from registrations";
                $total_row = mysqli_query($connect, $query) or die('error');
                if (mysqli_num_rows($total_row) > 0) {
                  foreach ($total_row as $row) {
                ?>
                    <option value="<?php echo $row['program_id']; ?>">
                      <?php echo getName($row['program_id']) ?>
                    </option>
                <?php
                  }
                } else {
                  echo 'No Data Found!';
                }
                ?>
              </select>
              <select id="all" name="all" form="all">
                <option value="all">All</option>
                <option value="a">A</option>
                <option value="b">B</option>
                <option value="c">C</option>

              </select>
            </form>

            <?php
            if ($result->num_rows > 0) {
              while ($row = $result->fetch_assoc()) {
            ?>
                <div class="container">
                  <div class="profile">
                    <img src="../image/profile-icon-design-free-vector.jpg" alt="Profile Picture">
                  </div>
                  <div class="info">
                    <p><strong>Program name:</strong> <?php echo getProgramName($row['program_id']); ?></p>
                    <p><strong>User name:</strong> <?php echo $row['name']; ?></p>
                    <p><strong>User email:</strong> <?php echo $row['email']; ?></p>
                    <p><strong>User phone:</strong> <?php echo $row['phone']; ?></p>
                    <p><strong>Role:</strong> <?php echo $row['role']; ?></p>
                    <p><strong>Team:</strong> <?php echo $row['team']; ?></p>
                    <p><strong>Message:</strong> <?php echo $row['note']; ?></p>
                    <form action="" method="post">
                      <input type="hidden" name="del_id" value="<?php echo $row['_id']; ?>">
                      <input class="del" type="submit" value="Delete" name="del_registration">
                    </form>
                  </div>
                </div>
            <?php
              }
            }
            ?>
          </div>
        </div>
      </div>
    </div>

  </body>

  </html>