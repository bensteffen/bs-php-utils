<?php

function median($x) {
    sort($x);
    $n = count($x);
    if ($n === 0) {
        return null;
    }
    if ($n % 2) { // odd
        return $x[floor(0.5*$n)];
    } else { // even
        return 0.5*($x[0.5*$n - 1] + $x[0.5*$n]);
    }
}

// Interquartile Range
function iqr($x) {
    sort($x);

    $n = count($x);
    $m = floor(0.5*$n);

    $start = $m;
    if ($n % 2) {
        $start = $m+1;
    }
    $q1 = median(array_slice($x, 0, $m));
    $q3 = median(array_slice($x, $start, $m));
    return $q3 - $q1;
}

function binsize($x) {
    return 2*iqr($x)*pow(count($x),-1/3);
}

function hist($x) {
    $binSize = binsize($x);
    $prec = floor(log10($binSize));
    $magn = pow(10,$prec);
    $binSize = $magn*round($binSize/$magn);

    $start = round($magn*round(floor(min($x)/$magn)), $magn+1);
    $end = round($magn*ceil(max($x)/$magn), $magn+1);

    $bins = [];
    $binCenter = $start;
    while($binCenter < $end) {
        $counter = count($bins);
        $binStart = $start + $counter*$binSize;
        $binCenter = $binStart + 0.5*$binSize;
        $binEnd = $binStart + $binSize;
        array_push($bins, [
            'start' => $binStart,
            'center' => $binCenter,
            'end' => $binEnd,
            'values' => [],
            'indices' => [],
            'count' => 0
        ]);
    }

    foreach ($x as $i => $value) {
        foreach ($bins as $b => $bin) {
            if ($value >= $bin['start'] && $value < $bin['end']) {
                array_push($bins[$b]['values'], $value);
                array_push($bins[$b]['indices'], $i);
                $bins[$b]['count'] = $bins[$b]['count'] + 1;
                break;
            }
        }
    }

    return $bins;
}

