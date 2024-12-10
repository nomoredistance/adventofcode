<?php

$input = file_get_contents('input.txt');
$rows  = explode("\n", $input);

$grid  = [];
for ($i=0; $i<count($rows); $i++) {
    if (!$rows[$i]) continue;
    $grid[$i] = str_split($rows[$i]);
}

$ROWS = count($grid);
$COLS = count($grid[0]);
$map_antennas = [];

for ($i=0; $i<$ROWS; $i++) {
    for ($j=0; $j<$COLS; $j++) {
        if ($grid[$i][$j] === '.') continue;
        $map_antennas[$grid[$i][$j]] ??= [];
        $map_antennas[$grid[$i][$j]][] = [$i, $j];
    }
}

$antinodes = [];
foreach ($map_antennas as $freq => $locations) {
    if (count($locations) <= 1) continue;
    for ($i=0; $i<count($locations); $i++) {
        for ($j=$i+1; $j<count($locations); $j++) {
            [$r1, $c1] = $locations[$i];
            [$r2, $c2] = $locations[$j];
            $dr = $r2 - $r1;
            $dc = $c2 - $c1;
            $an1 = [$r1-$dr, $c1-$dc]; // antinode 1
            $an2 = [$r2+$dr, $c2+$dc]; // antinode 2
            if (isset($grid[$an1[0]][$an1[1]])) 
                $antinodes[] = $an1[0].','.$an1[1];
            if (isset($grid[$an2[0]][$an2[1]]))
                $antinodes[] = $an2[0].','.$an2[1];
        }
    }
}


// print_r($map_antennas);
echo "\n";
echo "==> ANSWER: ".count(array_unique($antinodes));

echo "\n\nMemory usage: ".round(memory_get_usage()/1024, 2)." KB\n";
echo "Peak memory usage: ".round(memory_get_peak_usage()/1024/1024, 2). " MB\n";
