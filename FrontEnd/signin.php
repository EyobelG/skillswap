<?php
    ini_set('session.cookie_domain', '.joonh.sgedu.site');
    ini_set('session.cookie_secure', 1);
    ini_set('session.cookie_httponly', 1);
    session_start();


    $db_host = 'localhost';
    $db_user = 'utnq9qzvkroxc';
    $db_pass = 'cs20finalproj';
    $db_name = 'dbdtf6cle3tkfo';

    $members_host = 'localhost';
    $members_user = 'utnq9qzvkroxc';
    $members_pass = 'cs20finalproj';
    $members_name = 'dbfxsgcb4otskb';


    $email    = $_POST['email'];
    $password = $_POST['password'];

    $validcredentials = FALSE;
    $error = FALSE;

    $mysqli = new mysqli($db_host, $db_user, $db_pass, $db_name);
    if ($mysqli->connect_error) {
        die("Connection failed: " . $mysqli->connect_error);
    }

    $mysqli_members = new mysqli($members_host, $members_user, $members_pass, $members_name);
    if ($mysqli_members->connect_error) {
        die("Connection failed: " . $mysqli_members->connect_error);
    }

    try {
        if ($email == NULL || $password == NULL) {
            if ($email == NULL) {
                echo "enull";
            }
            if ($password == NULL) {
                echo "pnull";
            }
            throw new Exception("You're probably seeing this message because you pressed the back button on your browser.");
        }

        $user_id = trim($email);
        $email = trim($email);
        $password_hash = password_hash($password, PASSWORD_DEFAULT);

        $unameresult = mysqli_query($mysqli, "SELECT * FROM users WHERE user_id = '$user_id'");
        $emailresult = mysqli_query($mysqli, "SELECT * FROM users WHERE email = '$email'");
        if ($unameresult || $emailresult) {
            if (mysqli_num_rows($unameresult) > 0) { // check if username is in the db
                $datarow = $unameresult->fetch_array();
                if (password_verify($password, $datarow["password"])) {
                    $validcredentials = true;
                }
            } else if (mysqli_num_rows($emailresult) > 0) { // check if email is in the db
                $datarow = $emailresult->fetch_array();
                if (password_verify($password, $datarow["password"])) {
                    $validcredentials = true;
                }
            }
        } else {
            throw new Exception("ERROR: database connection lost");
        }

        if (!$validcredentials) {
            echo "<p>No account matches the information you entered.</p>";
            echo "<p>Please try to <a href=\"signin.html\">sign in</a> again or <a href=\"signup.html\">sign up</a> to make an account.</p>";
            throw new Exception("<p>ERROR: incorrect credentials</p>");
        }
    } catch (Exception $e) {
        echo $e->getMessage();
        $error = TRUE;
    }

    $mysqli->close();
    
    session_start();

    if (!$error) {
        // Store user info in server-side session
        $_SESSION['user_id'] = $user_id;
        $_SESSION['password'] = $password;

        header('Location: index.html');
        echo '<script>window.location.href = "index.html";</script>';
    }

    die();
?>