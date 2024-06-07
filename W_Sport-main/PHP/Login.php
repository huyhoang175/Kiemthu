<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="../CSS/Login.css">
</head>

<body>
    <?php
    require_once 'ConnectData.php';
    $usernameErr = $passwordErr = "";

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $username = $_POST["username"];
        $password = $_POST["password"];

        $stmt = $connect->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows !== 0) {
            $row = $result->fetch_assoc();
            if ($password == $row['password']) {
                $_SESSION["user_id"] = $row['_id'];
                header("Location: Index.php");
                exit();
            } else {
                $passwordErr = "Wrong password";
            }
        } else {
            $usernameErr = "Does not exist this username";
        }
        $stmt->close();
    }

    $connect->close();
    ?>
    <form method="POST">
        <h2>LOG IN</h2>
        <label for="username"><i>User name</i></label>
        <input type="text" id="username" name="username" required placeholder="Enter your user name...">
        <br><span class="error"><?php echo $usernameErr; ?></span>

        <label for="password"><i>Password</i> </label>
        <input type="password" id="password" name="password" required placeholder="Enter your Password...">
        <br><span class="error"><?php echo $passwordErr; ?></span>
        <p>You haven't account yet? <a href="../PHP/SignUp.php" class="signup"> Sign Up</a></p>
        <button type="submit">Login</button>
    </form>
    </div>
    </div>

</body>

</html>