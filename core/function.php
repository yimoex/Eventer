<?php
function size_convert($size, $round = 2){
	$func = function($_size, $p) use ($size, $round){
		if($size > $_size) return round($size / $_size, $round) . $p;
		return false;
	};
	$arr = [
		'GB' => 1073741800,
		'MB' => 1048576,
		'KB' => 1024,
	];
	foreach($arr as $p => $_size){
		$rec = $func($_size, $p);
		if($rec !== false) return $rec;
	}
	return round($size, $round) . 'B';
}

