<?php

$input = file_get_contents('input.txt');
// $input = preg_replace('/[^0-9\n]/', '', $input);

$rows  = explode("\n", $input);

$grid = [];
$sum = 0;
foreach ($rows as $row) {
    if ($row) {
        $grid[] = str_split($row);
	}
}

$dirs = [
    [ 0,-1],
    [ 0, 1],
    [-1, 0],
    [ 1, 0],
    [ 1,-1],
    [ 1, 1],
    [-1,-1],
    [-1, 1]
];

$ROWS = count($grid);
$COLS = count($grid[0]);
for ($i=0; $i<$ROWS; $i++) {
    for ($j=0; $j<$COLS; $j++) {
        if ($grid[$i][$j] === 'X') {
            foreach ($dirs as [$dr, $dc]) {
                // $c1 = $grid[$i][$j];
                $c2 = $grid[$i+$dr][$j+$dc] ?? '';
                $c3 = $grid[$i+$dr*2][$j+$dc*2] ?? '';
                $c4 = $grid[$i+$dr*3][$j+$dc*3] ?? '';
                if ($c2 === 'M' && $c3 === 'A' && $c4 === 'S') {
                    $sum++;
                }
            }
        }
    }
}

// echo json_encode($grid);
echo $sum;
