<?php
    $r = [];
    foreach(range(1, 130) as $i) {
        sleep(1);
        $r[] = mt_rand(0, 1);
    }

    $cnt0 = 0;
    $cnt1 = 0;
    foreach($r as $i) {
        if ($i) {
            $cnt1++;
        } else {
            $cnt0++;
        }
    }

    echo "$cnt0 | $cnt1";