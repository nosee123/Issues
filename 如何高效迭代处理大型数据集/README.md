源码地址：[如何高效迭代处理大型数据集]

## Summary

希望可以迭代处理一个元素列表，不过整个列表会占用大量内存，或者生成整个列表的速度非常慢。

最简单的方法是使用file()函数。这会打开文件，将每一行分别加载到一个数组中的一个元素，然后关闭文件。不过，这样将把整个文件都保存在内存中。

```php
$file = file('log.txt');
foreach ($file as $line) {
    if (preg_match('/^error: /',$line)) print $line.'</br>';
}
```

## Solve

更好的解决办法是，使用一个生成器，如下：

```php
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
```

## Reference 

《PHP经典实例》 David Sklar & Adam Trachtenberg

关于生成器的介绍：https://www.jianshu.com/p/b55a3670ceae

## Author

[nosee123](https://github.com/nosee123)

## License

MIT Public License