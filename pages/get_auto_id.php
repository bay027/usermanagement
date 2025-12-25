<?php
require_once "../config/database.php";
 
header('Content-Type: application/json');

try {
    $nextMemId = $controller->getNextMemId();
    
    if ($nextMemId) {
        echo json_encode([
            'success' => true,
            'memid' => $nextMemId
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'ไม่สามารถสร้างรหัสพนักงานได้'
        ]);
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'เกิดข้อผิดพลาด: ' . $e->getMessage()
    ]);
}

?>