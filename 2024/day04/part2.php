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
    [[-1,-1], [ 1,-1], [-1, 1], [ 1, 1]],
    [[-1,-1], [-1, 1], [ 1,-1], [ 1, 1]],
    [[-1, 1], [ 1, 1], [-1,-1], [ 1,-1]],
    [[ 1,-1], [ 1, 1], [-1,-1], [-1, 1]]
];

$ROWS = count($grid);
$COLS = count($grid[0]);
for ($i=0; $i<$ROWS; $i++) {
    for ($j=0; $j<$COLS; $j++) {
        if ($grid[$i][$j] === 'A') {
            foreach ($dirs as $d) {
                $c1 = $grid[$i+$d[0][0]][$j+$d[0][1]] ?? '';
                $c2 = $grid[$i+$d[1][0]][$j+$d[1][1]] ?? '';
                $c3 = $grid[$i+$d[2][0]][$j+$d[2][1]] ?? '';
                $c4 = $grid[$i+$d[3][0]][$j+$d[3][1]] ?? '';
                if ($c1 === 'M' && $c2 === 'M' && $c3 === 'S' && $c4 === 'S') {
                    $sum++;
                }
            }
        }
    }
}

// echo json_encode($grid);
echo $sum;
