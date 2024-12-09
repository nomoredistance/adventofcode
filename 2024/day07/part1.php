<?php

$input = file_get_contents('input.txt');
$rows  = explode("\n", $input);

$answer = 0;

$row_no = 0;
$ops_arr = [];
$ops_arr[0] = [];
$ops_arr[1] = [['+'], ['*']];

foreach ($rows as $row) {

    $row_no++;
    if (strlen($row) > 0) {
        [$total, $nums_str] = explode(': ', $row);
        $nums = explode(' ', $nums_str);

        /*
        // an incorrect way to optimize!
        $all_add = array_reduce($nums, function ($carry, $el) {
            $carry += $el;
            return $carry;
        }, 0);
        $all_mul = array_reduce($nums, function ($carry, $el) {
            $carry *= $el;
            return $carry;
        }, 1);

        if ($total < $all_add || $total > $all_mul) {
            // cannot reach
            echo "row $row_no: the total $total cannot be reached\n";
            continue;
        }
         */

        // making permutations of operators (+ and *)
        $operands_count = count($nums);
        $operators_count = $operands_count -1;
        if (!isset($ops_arr[$operators_count])) {
            echo "making ops_arr for $operators_count...\n";
            $dec = 0;
            $limit = pow(2, $operators_count)-1;
            while ($dec <= $limit) {
                $arr = [];
                $bin = str_pad(decbin($dec), $operators_count, '0', STR_PAD_LEFT);
                for ($i=0; $i<strlen($bin); $i++) {
                    $arr[] = ($bin[$i] === '1') ? '*' : '+';
                }
                $ops_arr[$operators_count][] = $arr;
                $dec++;
            }
        }

        // try all permutations to reach target
        $tries = 0;
        $can_reach = FALSE;
        foreach ($ops_arr[$operators_count] as $ops_seq) {
            $tries++;
            $temp = $ops_seq;
            $curr = $nums[0];
            for ($i=1; $i<count($nums); $i++) {
                $op = array_shift($ops_seq);
                switch ($op) {
                    case '+': $curr += $nums[$i]; break;
                    case '*': $curr *= $nums[$i]; break;
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
