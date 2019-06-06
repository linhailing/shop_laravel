<?php
/**
 * Created by PhpStorm.
 * User: henry
 * Date: 2019/4/12
 * Time: 4:08 PM
 * php文件锁
 */

namespace App\Libs;


class Lock{
    //文件锁开始
    public static function lockStart($key = '') {
        global $php_lock, $php_lock_key;
        $php_lock_key = $key;
        if (!$php_lock) $php_lock = new PHPLock(storage_path('framework/cache/'), $key);
        $php_lock->startLock();
        return $php_lock->Lock();
    }
    //文件锁结束
    public static function lockEnd($key = null) {
        global $php_lock, $php_lock_key;
        if (!$key) $key = $php_lock_key;
        if (!$php_lock) $php_lock = new PHPLock(storage_path('framework/cache/'), $key);
        $php_lock->unlock();
        $php_lock->endLock();
    }
    //ＣＡＣＨＥ锁开始
    public static function cacheLockStart($key, $seconds = 10) {
        return RedisManager::cacheLockStart($key, $seconds);
    }
    //ＣＡＣＨＥ锁结束
    public static function cacheLockEnd($key) {
        RedisManager::cacheLockEnd($key);
    }
}