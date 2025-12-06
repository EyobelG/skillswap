<style>
    * {
        margin: 0;
        width: 100%;
        text-align: center;
        font-family: Helvetica, Arial, sans-serif;
    }

    body {
        height: 100%;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
    }

    h1 {
        color: #6b7280;
        font-weight: 600;
        font-size: 1.5rem;
        margin-bottom: 20px;
    }
</style>

<?php
    echo "<body><h1>Authenticating...</h1><body>";

    $db_host = 'localhost';
    $db_user = 'utnq9qzvkroxc';
    $db_pass = 'cs20finalproj';
    $db_name = 'dbdtf6cle3tkfo';

    $members_host = 'localhost';
    $members_user = 'utnq9qzvkroxc';
    $members_pass = 'cs20finalproj';
    $members_name = 'dbfxsgcb4otskb';


    $fullName = $_POST['fullName'];
    $email    = $_POST['email'];
    $password = $_POST['password'];

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
        if ($fullName == NULL || $email == NULL || $password == NULL) {
            if ($fullName == NULL) {
                echo "nnull";
            }
            if ($email == NULL) {
                echo "enull";
            }
            if ($password == NULL) {
                echo "pnull";
            }
            throw new Exception("You're probably seeing this message because you pressed the back button on your browser.");
        }

        $user_id = implode("-", explode(" ", $fullName));
        $fullName = trim($fullName);
        $email = trim($email);
        $password_hash = password_hash($password, PASSWORD_DEFAULT);

        // check if email is already used
        $result = mysqli_query($mysqli, "SELECT * FROM users WHERE email = '$email'");
        if ($result) {
            if (mysqli_num_rows($result) > 0) {
                echo "<p>An account with this email address has already been created.</p>";
                echo "<p>Please <a href=\"signin.html\">sign in</a> to continue.</p>";
                throw new Exception("<p>ERROR: email already used</p>");
            }
        } else {
            throw new Exception("ERROR: database connection lost");
        }

        if ($mysqli->query("INSERT INTO users (user_id, name, email, password) VALUES ('$user_id', '$fullName', '$email', '$password_hash')") == TRUE
            &&
            $mysqli_members->query("INSERT INTO Members (name, user_id) VALUES ('$fullName', '$user_id')") == TRUE) {
            echo "Success!";
        } else {
            throw new Exception("ERROR: could not insert into database");
        }

    } catch (Exception $e) {
        echo $e->getMessage();
        $error = TRUE;
    }

    $mysqli->close();
    
    if (!$error) {
        header('Location: index.html');
        // Fallback if headers were already sent
        echo '<script>window.location.href = "index.html";</script>';
    }

    die();
?>