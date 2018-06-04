<?php
/**
 * Created by PhpStorm.
 * User: Chan
 * Date: 2018/6/4
 * Time: 17:30
 */
function sphere_distance($lat1, $lon1, $lat2, $lon2, $radius = 6378.135) {
    $rad = floatval(M_PI/180.0);
    $lat1 = floatval($lat1) * $rad;
    $lon1 = floatval($lon1) * $rad;
    $lat2 = floatval($lat2) * $rad;
    $lon2 = floatval($lon2) * $rad;
    $theta = $lon2 - $lon1;
    $dist = acos(sin($lat1) * sin($lat2) + cos($lat1) * cos($lat2) * cos($theta));
    if($dist < 0) {
        $dist += M_PI;
    }
    return $dist = $dist * $radius;
}

// NY,NY(10040)
$lat1 = 40.858704;
$lon1 = -73.928532;
// SF,CA(94114)
$lat2 = 37.758434;
$lon2 = -122.435126;
$dist = sphere_distance($lat1, $lon1, $lat2, $lon2);//$dist为4138.7779393191
$formatted = sprintf("%.2f",$dist * 0.621); //格式化并转换为英里
//从纽约（NYC）到旧金山（SF）约2570英里
//$formatted为2570.18