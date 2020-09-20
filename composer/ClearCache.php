<?php

class ClearCache
{
    public static function run()
    {
        $dirs = array_filter(glob(__DIR__.'/../cache/*'), 'is_dir');
        foreach ($dirs as $dir) {
            self::rrmdir($dir);
        }

        echo 'Done!'.PHP_EOL;
    }

    private static function rrmdir($src) {
        $dir = opendir($src);
        while(false !== ( $file = readdir($dir)) ) {
            if (( $file != '.' ) && ( $file != '..' )) {
                $full = $src . '/' . $file;
                if ( is_dir($full) ) {
                    self::rrmdir($full);
                }
                else {
                    unlink($full);
                }
            }
        }
        closedir($dir);
        rmdir($src);
    }
}

ClearCache::run();