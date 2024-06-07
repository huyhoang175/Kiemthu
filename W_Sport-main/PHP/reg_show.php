<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="../css/reg_show.css">
</head>

<body>

    <?php
    require_once 'ConnectData.php';
    $programId = isset($_GET['programId']) ? $_GET['programId'] : null;
    $organizationId = isset($_GET['organizationId']) ? $_GET['organizationId'] : null;

    $stmt = $connect->prepare("SELECT * FROM  registrationrequires WHERE program_id = ?");
    $stmt->bind_param("s", $programId);
    $stmt->execute();
    $result = $stmt->get_result();
    $dataRR = $result->fetch_assoc(); //Data Register Require
    $stmt->close();

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $name = $_POST['name'];
        $email = $_POST['email'];
        $phone = $_POST['phone'];
        $role = $_POST['role'];
        $teamName = $_POST['teamName'];
        $price = $_POST['price'];
        $note = $_POST['note'];

        $stmt = $connect->prepare("INSERT INTO registrations (program_id, user_id, organization_id, name, email, phone, role, team, priceOption, note) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssssssss", $programId, $user_id, $organizationId, $name, $email, $phone, $role, $teamName, $price, $note);

        if ($stmt->execute()) {
            echo '<script>alert("Registration successful!");</script>';
            echo '<script>window.location.href = "programDetail.php?programId=' . urlencode($programId) . '";</script>';
            exit();
        } else {
            echo '<script>alert("Error: ' . $stmt->error . '");</script>';
        }
        

        $stmt->close();
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
                    <div class="dashboard">
                        <p class="goto"><a href="../PHP/itemmenu.php">Go to Dashboard</a></p>
                        <p><img src="../img/profile.jpg" alt=""></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="main">
        <div class="header">Register</div>
        <p class="header_title">Summer Fooball 2024</p>
        <div class="create_web">
            <form action="" id="new_web" method="post">
                <div class="info">
                    <p class="info_title">Your info</p>
                    <p class="info_name"></p>
                </div>
                <div class="input_web">
                    <p class="name">Name</p>
                    <input class="inp_program" type="text" id="name" required="required" placeholder="Enter your Name" name="name" value="<?php echo $data['name']; ?>">
                </div>
                <div class="input_web">
                    <p class="name">Email</p>
                    <input class="inp_program" type="text" id="email" required="required" placeholder="Enter your email" name="email" value="<?php echo $data['email']; ?>">
                </div>
                <div class="input_web">
                    <label for="phone">Phone:</label>
                    <input class="inp_program" type="text" id="phone" required="required" placeholder="Enter your Phone" name="phone" value="<?php echo $data['phone']; ?>">
                </div>

                <div class="input_web">
                    <label for="role">Select role:</label>
                    <select class="inp_program" name="role" id="role" required="required">
                        <?php

                        if ($dataRR['individualPlayer'] != 0) {
                            echo '<option style ="height: 10px;" value="individualPlayer">Individual player</option>';
                        }
                        if ($dataRR['teamPlayer'] != 0) {
                            echo '<option style ="height: 10px;" value="teamPlayer">Team player Player</option>';
                        }
                        if ($dataRR['coach'] != 0) {
                            echo '<option style ="height: 10px;" value="coach">Coach</option>';
                        }
                        if ($dataRR['individual'] != 0) {
                            echo '<option style ="height: 10px;" value="individual">Individual</option>';
                        }
                        if ($dataRR['staff'] != 0) {
                            echo '<option style ="height: 10px;" value="staff">Staff</option>';
                        }
                        ?>
                    </select>
                </div>

                <div class="input_web">
                    <label for="teamName">Team Name:</label>
                    <input class="inp_program" type="text" id="teamName" required="required" placeholder="Enter your Team Name" name="teamName">
                    <p class="none">*Enter team name if you join as coach, team player, staff, else you can type 'None'</p>
                </div>

                <div class="input_web">
                    <label for="price">Select a Price Option:</label>
                    <select name="price" id="price" class="inp_program" required="required">
                        <?php
                        $query = "SELECT * FROM priceoptions WHERE regisRequire_id = '" . $dataRR['_id'] . "'";
                        $total_row = mysqli_query($connect, $query) or die('error');
                        if (mysqli_num_rows($total_row) > 0) {
                            foreach ($total_row as $PO) {
                                echo '<option value="'.$PO['priceName'].'"> '.$PO['priceName'].'</option>';
                            }
                        }
                        ?>
                    </select>
                </div>

                <div class="input_web">
                    <label for="note">Note:</label>
                    <textarea class="inp_descrip " name="note" id="note" cols="30" rows="2" placeholder="Enter your Note"></textarea>
                </div>

                <div class="input_web submit">
                    <input type="submit" name="" id="submit-btn" value="Register">
                </div>

            </form>
        </div>
    </div>
</body>

</html>