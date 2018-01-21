<?php
/**
 * Created by PhpStorm.
 * User: Giansalex
 * Date: 20/01/2018
 * Time: 22:37
 */

use Composer\Script\Event;

/**
 * Class BinDownloader
 */
class BinDownloader
{
    const CONFIG_PATH = __DIR__.'/../.wkhtmltopdf.dist';

    public static function postInstall(Event $event)
    {
        if (file_exists(self::CONFIG_PATH)) {
            return;
        }

        $url = self::getUrlDownload(self::isWindows(), self::is64Bit());
        $localPath = __DIR__.'/../vendor/bin/'.basename($url);

        if (!file_exists($localPath)) {
            self::downloadBin($url, $localPath);
        }

        echo 'Store config';
        file_put_contents(self::CONFIG_PATH, $localPath);
    }

    private static function isWindows()
    {
        return strtoupper(substr(PHP_OS, 0, 3)) === 'WIN';
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
        echo 'Downloading... ' . $url . PHP_EOL;
        $bin = file_get_contents($url);

        echo 'Writing in ' . $localPath;
        file_put_contents($localPath, $bin);
    }
}