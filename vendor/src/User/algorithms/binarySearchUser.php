<?php
function binarySearch($list, $email) {
    $lowIndex = 0;
    $highIndex = sizeof($list) - 1;

    while ($lowIndex <= $highIndex) {
        $middleIndex = round(($lowIndex + $highIndex) / 2);
        $test = $list[$middleIndex];

        if ($test['email'] == $email) {
            return $middleIndex;
        } elseif ($test['email'] > $email) {
            $highIndex = $middleIndex - 1;
        } else {
            $lowIndex = $middleIndex + 1;
        }
    }
    return 'Не найден!';
}