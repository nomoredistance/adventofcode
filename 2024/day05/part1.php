<?php

$input = file_get_contents('input.txt');

[$rules, $pages] = explode("\n\n", $input);

$rules = explode("\n", $rules);
$pages = explode("\n", $pages);

$rule_map = [];
foreach ($rules as $rule) {
    [$before, $after] = explode('|', $rule);
    $rule_map[$after] ??= [];
    $rule_map[$after][] = $before;
}

// var_dump($rules);
// var_dump($pages);
// var_dump($rule_map);

$sum = 0;
foreach ($pages as $row) {
    if (!$row) continue;

    $arr = explode(',', $row);
    $N = count($arr);
    $pages_after = [];
    $correct = TRUE;
    for ($i=$N-1; $i>=0; $i--) { // loop page numbers
        $curr = $arr[$i];
        $forbidden = $rule_map[$curr] ?? [];
        // var_dump($curr, $forbidden);
        foreach ($forbidden as $f) {
            if (isset($pages_after[$f])) {
                $correct = FALSE;
                break;
            }
        }
        if (!$correct) break;
        $pages_after[$curr] = TRUE;
    }

    if ($correct) {
        echo "row $row is correct!\n";
        $mid = intdiv($N, 2);
        $sum += $arr[$mid];
    }
}

echo $sum;
