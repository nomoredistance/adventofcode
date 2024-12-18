<?php

$filename = 'input.txt';
$input = file_get_contents($filename);
$byte_rows = explode("\n", trim($input));

if ($filename === 'input.txt') {
    $ROWS = $COLS = 71;
    $BYTE_TOTAL = 1024;
} else {
    $ROWS = $COLS = 7;
    $BYTE_TOTAL = 12;
}
// 
$grid  = array_fill(0, $ROWS, array_fill(0, $COLS, '.'));
$start = [0, 0];
$end   = [$ROWS-1, $COLS-1];

// fill grid with falling bytes up to specified total
for ($k=0; $k<$BYTE_TOTAL; $k++) {
    [$j, $i] = explode(',', $byte_rows[$k]);
    $grid[$i][$j] = '#';
}

$dirs = [
    '^' => [-1, 0],
    '>' => [ 0, 1],
    'v' => [ 1, 0],
    '<' => [ 0,-1]
];

function make_grid(&$grid) {
    $out = '';
    foreach ($grid as $row) {
        $out .= implode('', $row);
        $out .= "\n";
    }
    return $out;
}


echo make_grid($grid)."\n";

$visited = [];
$visited[$start[0].','.$start[1]] = TRUE;

$heap = new SplMinHeap();
$heap->insert([0, $start[0], $start[1]]); // steps, row, col

while (!$heap->isEmpty()) {

    [$steps, $r, $c] = $heap->extract();

    if ($r === $end[0] && $c === $end[1]) {
        echo "reached end in $steps steps\n";
        break;
    }

    foreach ($dirs as $new_dir => [$dr, $dc]) {
        $r2 = $r + $dr;
        $c2 = $c + $dc;
        if (isset($grid[$r2][$c2]) && $grid[$r2][$c2] === '.' && !isset($visited[$r2.','.$c2])) {
            $heap->insert([$steps+1, $r2, $c2]);
            $visited[$r2.','.$c2] = TRUE;
        }
    }
}

echo "\n\nMemory usage: ".round(memory_get_usage()/1024, 2)." KB\n";
echo "Peak memory usage: ".round(memory_get_peak_usage()/1024/1024, 2). " MB\n";
