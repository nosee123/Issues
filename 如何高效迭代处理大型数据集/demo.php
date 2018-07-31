<?php
/**
 * Created by PhpStorm.
 * User: nosee
 * Date: 2018/7/31
 * Time: 22:05
 */

// The Generator
function FileLineGenerator($file) {
    if(!$fh = fopen($file,'r')) {
        return;
    }
    while (false !== ($line = fgets($fh))) {
        yield $line;
    }
    fclose($fh);
}

// Test
$file = FileLineGenerator('log.txt');
foreach ($file as $line) {
    if (preg_match('/^error: /',$line)) {
        print $line.'</br>';
    }
}