<?php

function hms2seconds($hhmmss) {
   return strtotime("1970-01-01+00:00 ".$hhmmss);
}

function datetime2date($datetimeString) {
    
}

function seconds2hms($seconds) {
    $t = round($seconds);
    return sprintf('%02d:%02d:%02d', ($t/3600),($t/60%60), $t%60);
}

// function dateDiff2sec($date_diff) {
// return 3600*$date_diff->h + 60*$date_diff->i + $date_diff->s;
// }

?>