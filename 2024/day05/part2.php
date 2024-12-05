<?php

$input = file_get_contents('input.txt');

[$rules, $pages] = explode("\n\n", $input);

$rules = explode("\n", $rules);
$pages = explode("\n", $pages);
$rule_map = [];

$graph = [];
foreach ($rules as $rule) {
    [$before, $after] = explode('|', $rule);
    $graph[$before] ??= [];
    $graph[$before][] = $after;
    $graph[$after] ??= [];

    $rule_map[$after] ??= [];
    $rule_map[$after][] = $before;
}


// topo sort
function topo_sort($graph) {
    $in = [];
    $sorted = [];
    foreach ($graph as $node => $edges) {
        $in[$node] ??= 0;
        foreach ($edges as $edge) {
            $in[$edge] ??= 0;
            $in[$edge]++;
        }
    }
    $q  = new SplQueue();
    foreach ($in as $node => $deg) {
        if ($deg === 0) $q->enqueue($node);
    }
    while (!$q->isEmpty()) {
        $node = $q->dequeue();
        if (!isset($graph[$node])) continue;
        $sorted[] = (int)$node;
        foreach ($graph[$node] as $neig) {
            $in[$neig]--;
            if ($in[$neig] === 0) $q->enqueue($neig);
        }
    }
    if (count($sorted) !== count($graph)) {
        throw new Exception('graph contains a cycle!');
    }
    $sorted_map = array_flip($sorted);
    return $sorted_map;
}
// end topo sort

// $sort_func  = function ($a, $b) use (&$sorted_map) {
//     return $sorted_map[$a] - $sorted_map[$b];
// };

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

    if (!$correct) {
        // echo "row $row is incorrect!\n";

        // sort it according to the rules
        $trimmed_graph = [];
        foreach ($arr as $node) {
            $trimmed_graph[$node] = $graph[$node];
        }
        $sorted_map = topo_sort($trimmed_graph);
        usort($arr, function ($a, $b) use (&$sorted_map) {
            return $sorted_map[$a] - $sorted_map[$b];
        });

        $mid = intdiv($N, 2);
        $sum += $arr[$mid];
    }
}

echo $sum;
