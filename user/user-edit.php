<?php
session_start();

//check login status
if (!isset($_SESSION["user_logged_in"]) && $_SESSION["user_logged_in"] !== true) {
    header("location: user-login.php");
    exit();
}

// Include database connection
include '../connection.php';

// Initialize variablesCar
$name = $username = $address = $password = $contact = $email = '';
$newname = $newusername = $newaddress = $newpassword = $newcontact = $newemail = '';
$name_err =$user_err=$address_err = $pass_err = $ph_err = $email_err = '';
$errcnt = 0;

// Check if user ID is provided in the sessuin
$userid=$_SESSION['user_id'];
if (empty($userid)){
    header("location: error.php");
}

else{
    // Execute SQL query to retrieve user details
    $sql = "SELECT * FROM Users WHERE user_id = '$userid'";
    $result = mysqli_query($conn, $sql);

// Check if the query executed successfully
    if ($result) {
        if (mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);

        // Retrieve user details
            $name = $row['name'];
            $username=$row['username'];
            $address = $row['address'];
            $contact = $row['contact'];
            $email = $row['email'];

            // mysqli_free_result($result);
        } else {
        // user not found
            echo "some error occured.";
            exit();
        }
    } else {
    // Error executing query
        echo "Oops! Something went wrong. Please try again later.";
        exit();
    }

}
//update details
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    //check empty fields
    $newname=trim($_POST["name"]);
    if (empty(($newname))) {
        $name_err = "Please enter your name.";
        $errcnt++;
    } else {
        //validating
         if (validate_data($newname, '/^[a-zA-Z\s]+$/' ) === false) {
          $name_err="The name should not contain numbers or special characters";
          $errcnt++;
        }
    }

    // Validate username
    $newusername=trim($_POST["username"]);
    if (empty($newusername)) {
        $username_err = "Please enter your username.";
        $errcnt++;
    } else {
         if (validate_data($newusername, '/^[A-Za-z][A-Za-z0-9]{4,29}$/' ) === false) {
          $username_err="Username invalid must be like 'test', 'test12'";
          $errcnt++;
        }
    }

        // Validate address
    $newaddress=trim($_POST["address"]);
    if (empty($newaddress)) {
        $address_err = "Please enter your address.";
        $errcnt++;
    } else {
        $newaddress = trim($_POST["address"]);
    }

            // Validate contact
    $newcontact=trim($_POST["contact"]);
    if (empty($newcontact)) {
        $contact_err = "Please enter your contact number.";
        $errcnt++;
    } else {
        if (validate_data($newcontact, '/^9[0-9]{9}$/' ) == false) {
          $contact_err="phone number invalid";
          $errcnt++;
      }
    }

            //validate email
    $newemail = $_POST["email"];
    if (empty(trim($newemail))) {
     $email_err = "Please enter your email address.";
     $errcnt++;
    } elseif (!filter_var($newemail, FILTER_VALIDATE_EMAIL)) {
     $email_err = "Invalid email format.";
     $errcnt++;
    }

            // Validate password
    if (empty(trim($_POST["password"]))) {
        $password_err = "Please enter a password.";
        $errcnt++;
    } elseif (strlen(trim($_POST["password"])) < 6) {
        $password_err = "Password must have at least 6 characters.";
    } else {
        $newpassword = trim($_POST["password"]);
    }

    if ($errcnt==0) {
        //update query
    }
}

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User</title>
    <link rel="stylesheet" href="../css/form.css">

    <style>

    </style>
</head>
<body>
    <?php include 'user-nav.php' ?>
    <div class="form-container">
        <h2>Edit user</h2><hr>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" >
            <div class="inp-grp">
                <label for="newname">Name: </label>
                <input type="text" name="newname" value="<?php echo isset($name) ? $name : ''; ?>">
                <span class="inp-err"><?php echo $name_err; ?></span>
            </div>


            <div class="inp-grp">
                <label for="newusername">username: </label>
                <input type="text" name="newusername" value="<?php echo isset($username) ? $username : ''; ?>">
                <span class="inp-err"><?php echo $user_err; ?></span>
            </div>

            <div class="inp-grp">
                <label for="newaddress">address: </label>
                <input type="text" name="newaddress" value="<?php echo isset($address) ? $address : ''; ?>">
                <span class="inp-err"><?php echo $address_err; ?></span>
            </div>
            <div class="inp-grp">
                <label for="newemail">email: </label>
                <input type="text" name="newemail" value="<?php echo isset($email) ? $email : ''; ?>">
                <span class="inp-err"><?php echo $email_err; ?></span>
            </div>
            <div class="inp-grp">
                <label for="newcontact">contact: </label>
                <input type="number" name="newcontact" value="<?php echo isset($contact) ? $contact : ''; ?>">
                <span class="inp-err"><?php echo $ph_err; ?></span>
            </div>
            <div class="inp-grp">
                <label for="newpassword">password: </label>
                <input type="password" name="newpassword" >
                <span class="inp-err"><?php echo $pass_err; ?></span>
            </div>

            <div class="inp-grp">
                <button type="submit" class="loginbtn" name="update">Update</button>
            </div>
        </form>
    </div>

</div>
</body>
</html>