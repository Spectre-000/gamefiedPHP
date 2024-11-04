<?php
function getModuleStatus($connectDB, $sessionID) {
    if (!$connectDB || !$sessionID) {
        return null;
    }

    $statusQuery = "SELECT * FROM tbl_moduleprogress WHERE UID = ?";
    $stmt = $connectDB->prepare($statusQuery);
    
    if (!$stmt) {
        return null;
    }

    $stmt->bind_param('i', $sessionID);
    
    if (!$stmt->execute()) {
        return null;
    }

    $result = $stmt->get_result();
    
    if (!$result || $result->num_rows === 0) {
        return array(
            'UID' => $sessionID,
            'module1' => 0, 'module2' => 0, 'module3' => 0, 'module4' => 0,
            'module5' => 0, 'module6' => 0, 'module7' => 0, 'module8' => 0,
            'module9' => 0, 'module10' => 0
        );
    }

    return $result->fetch_assoc();
}

function calculateProgress($moduleData, $category) {
    if (!$moduleData) {
        return [
            'percentage' => 0,
            'completed' => 0,
            'total' => 0
        ];
    }

    $total = 0;
    $completed = 0;
    
    $moduleRanges = [
        'beginner' => range(1, 4),
        'intermediate' => range(5, 8),
        'advanced' => range(9, 10)
    ];

    if (!isset($moduleRanges[$category])) {
        return [
            'percentage' => 0,
            'completed' => 0,
            'total' => 0
        ];
    }

    foreach ($moduleRanges[$category] as $i) {
        $total++;
        if (isset($moduleData["module$i"]) && $moduleData["module$i"] == 1) {
            $completed++;
        }
    }
    
    return [
        'percentage' => $total > 0 ? ($completed / $total) * 100 : 0,
        'completed' => $completed,
        'total' => $total
    ];
}
?>