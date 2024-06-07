<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <link rel="stylesheet" href="../CSS/SignUp.css">
</head>

<body>
    <?php
    require_once 'ConnectData.php';
    $userNameErr = $nameErr = $emailErr = $passwordErr = "";

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $userName = $_POST["username"];
        $name = $_POST["name"];
        $email = $_POST["email"];
        $password = $_POST["password"];
        $avatar = "../Image/profile.jpg";
        if ($connect->connect_error) {
            die('Cannot connect to database' . $connect_error);
        } else {
            $stmt = $connect->prepare("INSERT INTO users (username, name, email, password, avatar) value (?, ?, ?, ?, ?)");
            $stmt->bind_param("sssss", $userName, $name, $email, $password, $avatar);
        }
        if ($stmt->execute()) {
            $_SESSION["user_id"] = $stmt->insert_id;
            header("Location: Index.php");
            exit();
        } else {
            echo "Error" . $stmt->error;
        }

        $stmt->close();
        $connect->close();
    }
    ?>
    <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <h2>Sign Up</h2>
        <label for="username"><i>User name</i></label>
        <input type="text" id="username" name="username" required placeholder="Enter your user name...">
        <br><span class="error"><?php echo $userNameErr; ?></span>

        <label for="name"><i class="edit">Name</i></label>
        <input type="text" id="name" name="name" required placeholder="Enter your  name...">
        <br><span class="error"><?php echo $nameErr; ?></span>

        <label for="email"><i class="edit">Email</i></label>
        <input type="text" id="email" name="email" required placeholder="Enter your  email...">
        <br><span class="error"><?php echo $emailErr; ?></span>

        <label for="password"><i>Password</i> </label>
        <input type="password" id="password" name="password" required placeholder="Enter your Password...">
        <br><span class="error"><?php echo $passwordErr; ?></span>

        <p>You have already account? <a href="../PHP/Login.php" class="signup">Login</a></p>

        <button type="submit">Sign Up</button>

    </form>

    </div>
    </div>

</body>

</html>