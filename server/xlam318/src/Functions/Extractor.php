<?php

namespace Src\Functions;

use \ZipArchive;



class Extractor
{

    public static function extract(string $file)
    {

        $path = pathinfo(realpath($file), PATHINFO_DIRNAME);

        $zip = new ZipArchive();
        $res = $zip->open($file);
        if ($res === TRUE) {
            $zip->extractTo($path);
            $zip->close();
            return true;
        } else {
            return false;
        }
    }
}
