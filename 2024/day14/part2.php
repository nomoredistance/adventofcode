<?php

$ans   = 0;
$filename = 'input.txt';
$input = file_get_contents($filename);
$rows  = explode("\n", trim($input));

if ($filename === 'input.txt') {
    $ROWS = 103;
    $COLS = 101;
} else {
    $ROWS = 7;
    $COLS = 11;
}
$grid  = array_fill(0, $ROWS, array_fill(0, $COLS, ' '));

/*
$dirs = [
    [-1, 0],
    [ 0, 1],
    [ 1, 0],
    [ 0,-1]
];
 */


$robots = [];
foreach ($rows as $row) {
    if ($row) {
        $bot = [];
        [$p, $v] = explode(' ', $row);
        [$py, $px] = explode(',', ltrim($p, 'p='));
        [$vy, $vx] = explode(',', ltrim($v, 'v='));
        $bot = [
            'p' => [$px, $py],
            'v' => [$vx, $vy]
        ];
        $robots[] = $bot;
    }
}


function make_grid(&$grid) {
    $output = '';
    foreach ($grid as $row) {
        $output .= implode('', $row)."\n";
    }
    $output .= "\n";
    return $output;
}

$SECONDS = 100000;
$clean_grid = $grid;
for ($k=1; $k<=$SECONDS; $k++) {
    $grid = $clean_grid;
    foreach ($robots as $bot) {
        [$px, $py] = $bot['p'];
        [$vx, $vy] = $bot['v'];
        $px = ($px + ($vx*$k) + ($ROWS*$k)) % $ROWS;
        $py = ($py + ($vy*$k) + ($COLS*$k)) % $COLS;
        if ($grid[$px][$py] === ' ') $grid[$px][$py] = '#';
        // $grid[$px][$py]++;
    }
    echo "SECOND ELAPSED: $k\n";
    $out = make_grid($grid);
    if (strpos($out, '#####') !== FALSE) {
        echo $out;
        sleep(1);
    }
}

// echo json_encode($grid);

echo "\n\n===> ANSWER: $ans\n";

echo "\n\nMemory usage: ".round(memory_get_usage()/1024, 2)." KB\n";
echo "Peak memory usage: ".round(memory_get_peak_usage()/1024/1024, 2). " MB\n";
