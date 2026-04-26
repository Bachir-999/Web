<?php
header('Content-Type: application/json');

$courses = $_POST['course'] ?? [];
$credits = $_POST['credits'] ?? [];
$grades = $_POST['grade'] ?? [];

$totalPoints = 0;
$totalCredits = 0;

$tableHtml = "<table class='table table-bordered mt-3'>
<tr><th>الدورة</th><th>المعامل</th><th>الدرجة</th><th>النقاط</th></tr>";

for ($i = 0; $i < count($courses); $i++) {

    $cr = floatval($credits[$i]);
    $g = floatval($grades[$i]);

    if ($cr <= 0) continue;

    $points = $cr * $g;
    $totalPoints += $points;
    $totalCredits += $cr;

    $tableHtml .= "<tr>
    <td>{$courses[$i]}</td>
    <td>$cr</td>
    <td>$g</td>
    <td>$points</td>
    </tr>";
}

$tableHtml .= "</table>";

if ($totalCredits > 0) {
    $gpa = $totalPoints / $totalCredits;

    $message = "المعدل التراكمي هو: " . number_format($gpa, 2);

    echo json_encode([
        "gpa" => $gpa,
        "message" => $message,
        "tableHtml" => $tableHtml
    ]);
} else {
    echo json_encode([
        "gpa" => 0,
        "message" => "أدخل بيانات صحيحة",
        "tableHtml" => ""
    ]);
}
