## 场景
希望找到地球上两个坐标之间的距离

## 问题 
由于地球不是平的，使用一个标准的勾股定理公式并不能得到两个位置之间的准确距离。

## 解决方案
```
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
```

## 说明
sphere_distance()可以接受另一个球半径作为第五个参数（可选），这允许你计算其它星球上两点间的距离。

## 参考
《PHP经典实例》 David Sklar & Adam Trachtenberg

## Author

[nosee123](https://github.com/nosee123)

