<?php 
header('Content-Type: application/json'); 
$conn = new mysqli("localhost", "root", "", "gpa_db"); 
 
if (isset($_POST['course'], $_POST['student_name'])) { 
    $student = $_POST['student_name']; 
    $semester = $_POST['semester']; 
    $courses = $_POST['course']; 
    $credits = $_POST['credits']; 
    $grades = $_POST['grade']; 
 
    $totalPoints = 0; 
    $totalCredits = 0; 
     
    // الحسابات 
    for ($i = 0; $i < count($courses); $i++) { 
        $cr = floatval($credits[$i]); 
        $g = floatval($grades[$i]); 
        if ($cr <= 0) continue; 
        $totalPoints += ($cr * $g); 
        $totalCredits += $cr; 
    } 
 
    if ($totalCredits > 0) { 
        $gpa = $totalPoints / $totalCredits; 
         
        // تحديد التفسير واللون لشريط التقدم 
        if ($gpa >= 3.7) { $interp = "Distinction"; $color = "bg-success"; } 
        elseif ($gpa >= 3.0) { $interp = "Merit"; $color = "bg-info"; } 
        elseif ($gpa >= 2.0) { $interp = "Pass"; $color = "bg-warning"; } 
        else { $interp = "Fail"; $color = "bg-danger"; } 
 
        // حفظ في قاعدة البيانات 
        $stmt = $conn->prepare("INSERT INTO calculations (student_name, semester, gpa, interpretation) VALUES (?, ?, ?, ?)"); 
        $stmt->bind_param("ssds", $student, $semester, $gpa, $interp); 
        $stmt->execute(); 
        $calc_id = $stmt->insert_id; 
 
        // توليد Progress Bar HTML 
        $percentage = ($gpa / 4.0) * 100; 
        $progressHtml = " 
            <div class='alert alert-light border shadow-sm'> 
                <h5>النتيجة لـ $student ($semester)</h5> 
                <div class='progress mb-2' style='height: 30px;'> 
                    <div class='progress-bar progress-bar-striped $color' role='progressbar'  
                         style='width: $percentage%' aria-valuenow='$gpa' aria-valuemin='0' aria-valuemax='4'> 
                         $gpa / 4.0 
                    </div> 
                </div> 
                <p class='mb-0 text-center font-weight-bold'>التقدير: $interp</p> 
                <a href='export.php?id=$calc_id' class='btn btn-sm btn-outline-dark mt-2'>تصدير CSV لهذه العملية</a> 
            </div>"; 
 
        echo json_encode(['success' => true, 'message' => $progressHtml]); 
    } else { 
        echo json_encode(['success' => false, 'message' => 'بيانات غير صالحة']); 
    } 
} 
exit; 
?>