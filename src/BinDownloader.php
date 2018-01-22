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
final class BinDownloader
{
    public static function postInstall(Event $event)
    {
        if (getenv('NOT_INSTALL')) {
            return;
        }

        $pathBin = Util::getPathBin();
        echo $pathBin . PHP_EOL;
        if (file_exists($pathBin)) {
            return;
        }

        $url = self::getUrlDownload(Util::isWindows(), self::is64Bit());

        if (!file_exists($pathBin)) {
            if (!is_dir( __DIR__.'/../vendor/bin')) {
                $oldmask = umask(0);
                mkdir(__DIR__.'/../vendor/bin', 0777, true);
                umask($oldmask);
            }
            self::downloadBin($url, $pathBin);
        }
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