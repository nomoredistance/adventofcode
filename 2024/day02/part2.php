<?php

$input = file_get_contents('input.txt');
// $input = preg_replace('/[^0-9\n]/', '', $input);

$rows  = explode("\n", $input);

$sum = 0;
$max_diff = 3;

function check_safety ($levels) {

    global $max_diff;
    $is_increasing = FALSE;
    $valid = TRUE;
    for ($i=1; $i<count($levels); $i++) {
        $diff = abs($levels[$i]-$levels[$i-1]);
        if ($diff <= 0 || $diff > $max_diff) {
            $valid = FALSE;
            break;
        }
        if ($i===1) {
            $is_decreasing = ($levels[$i] < $levels[$i-1]) ? TRUE:FALSE;
        } else {
            if (
                ($is_decreasing  && ($levels[$i] > $levels[$i-1]))
                || (!$is_decreasing && ($levels[$i] < $levels[$i-1]))
            ) {
                $valid = FALSE;
                break;
            }
        }
    }
    return $valid;

};

foreach ($rows as $row) {
    if ($row) {
        $levels = explode(' ', $row);

        $valid = FALSE;
        for ($i=0; $i<count($levels); $i++) {
            $valid = check_safety(
                array_values(array_filter($levels, function ($k) use ($i) {
                    return $k !== $i;
                }, ARRAY_FILTER_USE_KEY))
            );
            if ($valid) {
                $sum++;
                break;
            }
        }
	}
}

echo $sum;
