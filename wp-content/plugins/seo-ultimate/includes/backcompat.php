<?php
if (!function_exists('array_combine')) :

//PHP4 function from:
//http://www.php.net/manual/en/function.array-combine.php#82244
function array_combine($arr1, $arr2) {
    $out = array();
   
    $arr1 = array_values($arr1);
    $arr2 = array_values($arr2);
   
    foreach($arr1 as $key1 => $value1) {
        $out[(string)$value1] = $arr2[$key1];
    }
   
    return $out;
}

endif;