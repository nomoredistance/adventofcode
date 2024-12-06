<?php

$input = file_get_contents('input.txt');
$rows  = explode("\n", $input);

$grid  = [];
$pos   = [];
for ($i=0; $i<count($rows); $i++) {
    if (!$rows[$i]) continue;
    $grid[$i] = str_split($rows[$i]);
    if ($j = strpos($rows[$i], '^')) $pos = [$i, $j];
}
$grid[$pos[0]][$pos[1]] = 'X';

$dirs = [
    [-1, 0],
    [ 0, 1],
    [ 1, 0],
    [ 0,-1]
];
$dir = 0;

$ROWS = count($grid);
$COLS = count($grid[0]);
while (TRUE) {
    [$dr, $dc] = $dirs[$dir];
    $r = $pos[0] + $dr;
    $c = $pos[1] + $dc;
    if (!isset($grid[$r][$c])) {
        break;
    } elseif ($grid[$r][$c] === '.') {
        $grid[$r][$c] = 'X';
        $pos = [$r, $c];
    } elseif ($grid[$r][$c] === 'X') {
        $pos = [$r, $c];
    } elseif ($grid[$r][$c] === '#') {
        $dir = ($dir === count($dirs)-1) ? 0 : $dir + 1;
    }
}


$sum = 0;
for ($i=0; $i<$ROWS; $i++) {
    for ($j=0; $j<$COLS; $j++) {
        if ($grid[$i][$j] === 'X') {
            $sum++;
        }
    }
}

echo json_encode($grid)."\n";
echo $sum;
