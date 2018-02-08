<?php
use Workerman\Worker;
use Workerman\Lib\Timer;


// 应用程序目录表 watch Applications catalogue
// global $monitor_dir;


#$monitor_dir = realpath(__DIR__.'/..');
#$monitor_dir = realpath(__DIR__.'/..');
#$monitor_dir = realpath(APP_PATH);
// worker
$worker = new Worker();
$worker->name = 'FileMonitor'; // 文件监控
$worker->reloadable = false;
$last_mtime = time();

$worker->onWorkerStart = function() 
{
     #  $monitor_dir;
    // 仅在守护进程模式下监视文件
    if(!Worker::$daemonize)
    {
        Timer::add(1, 'check_files_change', array(FILE_MONITOR));
    }
};
// 检查文件的功能 check files func
function check_files_change($monitor_dir)
{
    global $last_mtime;
    // 递归遍历目录 recursive traversal directory
    $dir_iterator = new RecursiveDirectoryIterator($monitor_dir);
    $iterator = new RecursiveIteratorIterator($dir_iterator);
    foreach ($iterator as $file)
    {
        // 只检查PHP文件 only check php files
        if(pathinfo($file, PATHINFO_EXTENSION) != 'php')
        {
            continue;
        }
        // 检查时间 check mtime
        if($last_mtime < $file->getMTime())
        {
            echo $file." 更新和刷新\n";
            // 掌握进程发送SIGUSR1信号加载 send SIGUSR1 signal to master process for reload
            posix_kill(posix_getppid(), SIGUSR1);
            $last_mtime = $file->getMTime();
            break;
        }
    }
}
