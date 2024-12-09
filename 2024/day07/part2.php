<?php

// [TODO] refactor
// this script uses a lot of RAM! (mainly storing all the permutations)
// increase the memory_limit before running this code

$input = file_get_contents('input.txt');
$rows  = explode("\n", $input);

$answer = 0;

$row_no = 0;
$op_types = [1, 2, 3];
$ops_arr = [];
$ops_arr[0] = [];

function make_permutations($target_len, $curr_arr=[]) {

    global $op_types;
    // base case
    if (count($curr_arr) === $target_len) {
        return [$curr_arr];
    }
    $result = [];
    foreach ($op_types as $o) {
        $temp = $curr_arr;
        $temp[] = $o;
        $result = array_merge($result, make_permutations($target_len, $temp));
    }
    return $result;

}

for ($i=1; $i<12; $i++) {
    $ops_arr[$i] = make_permutations($i);
}

foreach ($rows as $row) {

    $row_no++;
    if (strlen($row) > 0) {
        [$total, $nums_str] = explode(': ', $row);
        $nums = explode(' ', $nums_str);

        // try all permutations to reach target
        $operands_count = count($nums);
        $operators_count = $operands_count -1;

        $tries = 0;
        $can_reach = FALSE;
        foreach ($ops_arr[$operators_count] as $ops_seq) {
            $tries++;
            $temp = $ops_seq;
            $curr = $nums[0];
            for ($i=1; $i<count($nums); $i++) {
                $op = array_shift($ops_seq);
                switch ($op) {
                    case 1:  $curr += $nums[$i]; break;
                    case 2:  $curr *= $nums[$i]; break;
                    case 3: $curr .= $nums[$i]; break;
                    default: throw new Exception("Unsupported operator: $op");
                }
            }
            if ($curr == $total) {
                echo "row $row_no: can reach total $total in $tries tries with ".json_encode($temp)."\n";
                $answer += $total;
                $can_reach = TRUE;
                break;
            }
        }
        if (!$can_reach) echo "row $row_no: cannot reach total $total, tried $tries combinations\n";

    }
}

echo "\n\n\nANSWER ====> $answer\n";


echo "\n\n\n";
echo "Current memory usage: ".round(memory_get_usage()/1024, 2)." KB\n";
echo "Peak memory usage: ".round(memory_get_peak_usage()/1024/1024, 2)." MB\n";


