<?php
// show potential errors / feedback (from login object)
if (isset($login)) {
    if ($login->errors) {
        foreach ($login->errors as $error) {
            echo $error;
        }
    }
    if ($login->messages) {
        foreach ($login->messages as $message) {
            echo $message;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
        /* This targets the main page body */
        body {
            /* Use Flexbox to easily center content */
            display: flex;
            justify-content: center; /* This centers the form horizontally */
        }

        /* This targets your form */
        form {
            /* This positions the form 50px from the top */
            margin-top: 50px;

            /* The styles below help organize the form elements cleanly */
            display: flex;
            flex-direction: column; /* Stack the labels and inputs vertically */
            width: 250px; /* Give the form a consistent width */
        }

        /* Add some spacing between the inputs */
        .login_input {
            margin-bottom: 15px;
        }

    </style>
</head>
<body>

    <form method="post" action="start.php" name="loginform">
        <label for="login_input_username">Extension</label>
        <input id="login_input_username" class="login_input" type="text" name="user_name" required />

        <label for="login_input_password">Password</label>
        <input id="login_input_password" class="login_input" type="password" name="user_password" autocomplete="off" required />

        <input type="submit" name="login" value="Log in" />
    </form>

</body>
</html>
