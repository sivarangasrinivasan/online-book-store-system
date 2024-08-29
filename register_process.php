<?php
session_start();

if (!empty($_POST)) {
    extract($_POST);
    $_SESSION['error'] = array();

    if (empty($fnm)) {
        $_SESSION['error']['fnm'] = "Please enter Full Name";
    }

    if (empty($unm)) {
        $_SESSION['error']['unm'] = "Please enter User Name";
    }

    if (empty($pwd) || empty($cpwd)) {
        $_SESSION['error']['pwd'] = "Please enter Password";
    } elseif ($pwd != $cpwd) {
        $_SESSION['error']['pwd'] = "Password doesn't match";
    } elseif (strlen($pwd) < 8) {
        $_SESSION['error']['pwd'] = "Please enter a minimum 8 character password";
    }

    if (empty($email)) {
        $_SESSION['error']['email'] = "Please enter E-Mail Address";
    } elseif (!preg_match('/^[a-zA-Z0-9_]+[a-zA-Z0-9_.]*@[a-zA-Z0-9_-]+[a-zA-Z0-9_.-]*\.[a-zA-Z]{2,5}$/', $email)) {
        $_SESSION['error']['email'] = "Please enter a valid E-Mail Address";
    }

    if (empty($answer)) {
        $_SESSION['error']['answer'] = "Please Enter Security Answer";
    }

    if (empty($cno)) {
        $_SESSION['error']['cno'] = "Please enter Contact Number";
    }

    if (!empty($cno) && !is_numeric($cno)) {
        $_SESSION['error']['cno'] = "Please enter Contact Number in Numbers";
    }

    if (!empty($_SESSION['error'])) {
        foreach ($_SESSION['error'] as $er) {
            echo '<font color="red">' . $er . '</font><br>';
        }
        // Redirect back to the registration page if there are errors
        header("Location: register.php");
        exit;
    } else {
        include("includes/connection.php");

        // Create a prepared statement to prevent SQL injection
        $t = time();
        $stmt = $link->prepare("INSERT INTO register (r_fnm, r_unm, r_pwd, r_cno, r_email, r_question, r_answer, r_time) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssssi", $fnm, $unm, $pwd, $cno, $email, $question, $answer, $t);
        $stmt->execute();
        
        // Redirect to registration page with success message
        header("Location: register.php?register");
        exit;
    }
} else {
    // Redirect back to registration page if no POST data
    header("Location: register.php");
    exit;
}
?>