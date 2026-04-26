<?php
$conn = new mysqli("localhost", "root", "", "gpa_db");

if ($conn->connect_error) {
    die("Connection failed");
}

$result = $conn->query("SELECT * FROM results ORDER BY id DESC");

if ($result->num_rows > 0) {

    echo "<table border='1'>";
    echo "<tr><th>GPA</th><th>Date</th></tr>";

    while ($row = $result->fetch_assoc()) {
        echo "<tr>
        <td>{$row['gpa']}</td>
        <td>{$row['created_at']}</td>
        </tr>";
    }

    echo "</table>";

} else {
    echo "لا توجد نتائج بعد";
}
?>