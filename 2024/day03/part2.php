<?php

$input = file_get_contents('input.txt');
$matches = [];
preg_match_all("/mul\(\d{1,3},\d{1,3}\)|do\(\)|don\'t\(\)/", $input, $matches);

// print_r($matches);

$sum = 0;
$valid = TRUE;
foreach ($matches[0] as $match) {
    if ($match === 'do()') {
        $valid = TRUE;
    } elseif ($match === "don't()") {
        $valid = FALSE;
    } else {
        if ($valid) {
            [$a, $b] = explode(',', trim($match, 'mul()'));
            $sum += $a * $b;
        }
    }
}
echo $sum;
