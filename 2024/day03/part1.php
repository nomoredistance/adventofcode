<?php

$input = file_get_contents('input.txt');
$matches = [];
preg_match_all("/mul\(\d{1,3},\d{1,3}\)/", $input, $matches);

$sum = 0;
foreach ($matches[0] as $match) {
    [$a, $b] = explode(',', trim($match, 'mul()'));
    $sum += $a * $b;
}
echo $sum;
