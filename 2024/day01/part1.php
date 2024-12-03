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
sort($list1);
sort($list2);

for ($i=0; $i<count($list1); $i++) {
    $sum += abs($list2[$i] - $list1[$i]);
}

echo $sum;
