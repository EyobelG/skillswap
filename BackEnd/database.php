<html>
    <head>
        <title>Fetch Members Data</title>
    </head>
<body>
<?php
// 1. Connect to MySQL
$servername = "localhost";
$username = "utnq9qzvkroxc";       
$password = "cs20finalproj";          
$dbname = "dbfxsgcb4otskb";  

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// 2. Query the Members table
$sql = "SELECT * FROM Members";
$result = $conn->query($sql);

// 3. Store results
$members = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {

        // Convert JSON-looking strings back to arrays
        $row["categories"]   = json_decode($row["categories"], true);
        $row["wantsToLearn"] = json_decode($row["wantsToLearn"], true);

        $members[] = $row;
    }
}

// 4. Output as JSON (for frontend fetch)
header("Content-Type: application/json");
echo json_encode($members);

$conn->close();
?>
</body>

</html>