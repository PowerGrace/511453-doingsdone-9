<?php

function numberOfTasks ($tasksList, $projectName) {
    $quantity = 0;
    foreach ($tasksList as $val) {
        if (isset($val['category']) && ($val['category']) === $projectName) {
            $quantity++;
        }
    }
    return $quantity; 
};

function isDateImportant ($val) {
    $finHour = strtotime($val);
    $hoursCount = $finHour - time();
        if ($hoursCount <= 86400 && $val !== null) {
            return true;
        }
    return false;
};

function db_fetch_data($link, $sql, $data = []) {
    $result = [];
    $stmt = db_get_prepare_stmt($link, $sql, $data);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    if ($res) {
        $result = mysqli_fetch_all($res, MYSQLI_ASSOC);
    }
    return $result;
}
?>