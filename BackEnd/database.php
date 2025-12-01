<html>
    <head>
        <title>Fetch Members Data</title>
    </head>
<body>
<?php
// connect to mysql
$servername = "localhost";
$username = "utnq9qzvkroxc";       
$password = "cs20finalproj";          
$dbname = "dbfxsgcb4otskb";  

$conn = new mysqli($servername, $username, $password, $dbname);

// check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// query the Members table
$sql = "SELECT * FROM Members";
$result = $conn->query($sql);

// store results
$members = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {

        // convert JSON back to arrays (mysql can't store arrays, JSON is a workaround)
        $row["categories"]   = json_decode($row["categories"], true);
        $row["wantsToLearn"] = json_decode($row["wantsToLearn"], true);

        $members[] = $row;
    }
}

// output as JSON (for frontend fetch)
header("Content-Type: application/json");
echo json_encode($members);

$conn->close();
?>
</body>

</html>