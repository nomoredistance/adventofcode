<?php

$input = file_get_contents('input.txt');
// $input = preg_replace('/[^0-9\n]/', '', $input);

$rows  = explode("\n", $input);

$sum = 0;
$list1 = $list2 = [];
foreach ($rows as $row) {
    if ($row) {
        [$x1, $x2] = explode('   ', $row);
        $list1[] = $x1;
        $list2[] = $x2;
	}
}
// sort($list1);
// sort($list2);
$ct_values = array_count_values($list2);

for ($i=0; $i<count($list1); $i++) {
    $score = $list1[$i] * ($ct_values[$list1[$i]] ?? 0); 
    $sum += $score;
}

// print_r($ct_values);
echo $sum;
