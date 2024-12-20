<?php

$ans   = 0;
$microtime_start = microtime(TRUE);
$filename = 'test2.txt';
$input = file_get_contents($filename);
$grid_rows = explode("\n", trim($input));

$grid  = [];
$start = [];
$end   = [];
$row_no = 0;
foreach ($grid_rows as $row) {
    $arr = str_split($row);
    $grid[] = $arr;
    if (($start_col = strpos($row, 'S')) !== FALSE) {
        $start = [$row_no, $start_col];
        $grid[$row_no][$start_col] = '.'; // clears mark ('S') from the grid
    }
    if (($end_col = strpos($row, 'E')) !== FALSE) {
        $end = [$row_no, $end_col];
        $grid[$row_no][$end_col] = '.'; // clears mark ('E') from the grid
    }
    $row_no++;
}

$ROWS = count($grid);
$COLS = count($grid[0]);
$dirs = [
    '^' => [-1, 0],
    '>' => [ 0, 1],
    'v' => [ 1, 0],
    '<' => [ 0,-1]
];

function make_grid(&$grid) {
    $out = '';
    foreach ($grid as $row) {
        $row = array_map(function ($el) {
            return (is_integer($el)) ? $el%10 : $el;
        }, $row);
        $out .= implode('', $row);
        $out .= "\n";
    }
    return $out;
}

// returns a set of possible adjacent spaces if wall is cheatable
// note: a wall surrounded by two empty spaces in a 90-deg configuration is not cheatable
function is_wall_cheatable(int $i, int $j) { // row, col

    global $dirs, $grid;
    $empty_spots = 0;
    $adjacent_spaces = [];

    if ($grid[$i][$j] !== '#') {
        throw new Exception("not a wall!");
        return FALSE;
    }
    foreach ($dirs as $dir_name => [$dr, $dc]) {
        $adjacent_spaces[$dir_name] = FALSE;
        $r = $i + $dr;
        $c = $j + $dc;
        if (isset($grid[$r][$c]) && $grid[$r][$c] === '.') {
            $adjacent_spaces[$dir_name] = [$r, $c];
            $empty_spots++;
        }
    }

    if (
        ($empty_spots >= 3)
        || ($empty_spots === 2 && $adjacent_spaces['<'] && $adjacent_spaces['>'])
        || ($empty_spots === 2 && $adjacent_spaces['^'] && $adjacent_spaces['v'])
    ) {
        return array_filter($adjacent_spaces, fn($el) => $el !== FALSE);
    }
    return FALSE;
}

$num_cheatable_walls = 0;
$cheatable_walls = [];
for ($i=0; $i<$ROWS; $i++) {
    for ($j=0; $j<$COLS; $j++) {
        if ($grid[$i][$j] === '#') {
            $adj_sp = is_wall_cheatable($i, $j);
            if ($adj_sp !== FALSE) {
                $cheatable_walls[$i.','.$j] = $adj_sp;
                $num_cheatable_walls++;
            }
        }
    }
}
echo "\nnumber of cheatable walls: $num_cheatable_walls\n";

$steps_grid = $grid;

$visited = [];
$visited[$start[0].','.$start[1]] = TRUE;
$heap = new SplMinHeap();
$heap->insert([0, $start[0], $start[1]]); // picoseconds, row, col

while (!$heap->isEmpty()) { // make a steps_grid (for every '.', note how many time has elapsed)

    [$pico, $r, $c] = $heap->extract();
    $visited[$r.','.$c] = TRUE;
    $steps_grid[$r][$c] = $pico;

    if ($r === $end[0] && $c === $end[1]) {
        echo "reached end in: $pico picoseconds\n";
        break;
    }

    foreach ($dirs as $new_dir => [$dr, $dc]) {
        $r2 = $r + $dr;
        $c2 = $c + $dc;
        if (isset($grid[$r2][$c2]) && $grid[$r2][$c2] === '.' && !isset($visited[$r2.','.$c2])) {
            $heap->insert([$pico+1, $r2, $c2]);
        }
    }
}
// echo make_grid($steps_grid);

$pico_saved_by_cheating = [];
foreach ($cheatable_walls as $hash => $spaces) {
    [$wall_r, $wall_c] = explode(',', $hash);
    $pico_saved_by_cheating[$hash] = 0;

    $min = PHP_INT_MAX;
    $max = 0;
    foreach ($spaces as $dir_name => [$space_r, $space_c]) {
        $min = min($min, $steps_grid[$space_r][$space_c]);
        $max = max($max, $steps_grid[$space_r][$space_c]);
    }
    $saving = $max - $min - 2; // 2 is the time required to traverse wall
    $pico_saved_by_cheating[$hash] = $saving;
    // echo "if wall $hash is cheated, the time saving is $saving\n";
}

$map_saving_freq = [];
$saving_atleast_100pico = 0;
foreach ($pico_saved_by_cheating as $hash => $saving) {
    $map_saving_freq[$saving] ??= 0;
    $map_saving_freq[$saving]++;
    if ($saving >= 100) $saving_atleast_100pico++;
}
ksort($map_saving_freq);
print_r($map_saving_freq);

echo "\n\n===> ANSWER (number of cheats that would save at least 100 picoseconds): $saving_atleast_100pico\n";
echo "\n\nMemory usage: ".round(memory_get_usage()/1024, 2)." KB\n";
echo "Peak memory usage: ".round(memory_get_peak_usage()/1024/1024, 2). " MB\n";
echo "Time spent: ".microtime(TRUE) - $microtime_start."s\n";