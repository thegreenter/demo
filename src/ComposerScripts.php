<?php
/**
 * Created by PhpStorm.
 * User: Giansalex
 * Date: 20/01/2018
 * Time: 22:37
 */

use Composer\Script\Event;

/**
 * Class ComposerScripts
 */
final class ComposerScripts
{
    public static function postInstall(Event $event)
    {
        if (getenv('NOT_INSTALL')) {
            return;
        }

        if (Util::inPath('wkhtmltopdf')) {
            return;
        }

        $pathBin = Util::getPathBin();
        if (file_exists($pathBin)) {
            echo $pathBin . PHP_EOL;
            return;
        }

        $url = self::getUrlDownload(Util::isWindows(), self::is64Bit());

        if (!is_dir( __DIR__.'/../vendor/bin')) {
            $oldmask = umask(0);
            mkdir(__DIR__.'/../vendor/bin', 0777, true);
            umask($oldmask);
        }
        self::downloadBin($url, $pathBin);
    }

    public static function clearCache()
    {
        $dirs = array_filter(glob(__DIR__.'/../cache/*'), 'is_dir');
        foreach ($dirs as $dir) {
            self::rrmdir($dir);
        }

        echo 'Done!';
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

    private static function is64Bit()
    {
        $value = php_uname('m'); // Tipo de máquina. ej. i386

        return strpos($value, '64') !== false;
    }

    private static function getUrlDownload($windows, $x64)
    {
        $domain = 'https://raw.githubusercontent.com/';
        if ($windows) {
            $path = $x64
                ? 'wemersonjanuario/wkhtmltopdf-windows/master/bin/wkhtmltopdf64.exe'
                : 'wemersonjanuario/wkhtmltopdf-windows/master/bin/wkhtmltopdf32.exe';
        } else {
            $path = $x64
                ? 'h4cc/wkhtmltopdf-amd64/master/bin/wkhtmltopdf-amd64'
                : 'h4cc/wkhtmltopdf-i386/master/bin/wkhtmltopdf-i386';
        }

        return $domain.$path;
    }

    private static function downloadBin($url, $localPath)
    {
        echo 'Downloading... '.$url.PHP_EOL;
        $bin = file_get_contents($url);

        echo 'Writing in '.$localPath.PHP_EOL;
        file_put_contents($localPath, $bin);
        chmod($localPath, 0777);
        echo exec("$localPath --version").PHP_EOL;

        echo 'FILE SIZE: '. number_format(filesize($localPath)/1048576, 2).' MB'.PHP_EOL;
    }
}