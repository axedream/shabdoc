<?php
namespace Components;


/**
 * Шаблонизатор
 * Class Shabdoc
 * @package Components
 */
class Shabdoc
{


    /**
     * Точка входа для преобразования
     * @param $input
     * @return mixed
     */
    public static function getCovertContent($input)
    {
        $input = SELF::getUrl($input);
        $input = SELF::getP($input);
        return $input;
    }

    public static function getP($input)
    {
        $input = preg_replace("/\n\n+/", "\n\n", str_replace(["\r\n", "\r"], "\n", $input));
        $inputs = preg_split('/\n\s*\n/', $input, -1, PREG_SPLIT_NO_EMPTY);
        $input = '';
        foreach ($inputs as $inp) {
            $input .= '<p>' . nl2br(trim($inp, "\n")) . "</p>\n";
        }
        $input = preg_replace('|<p>\s*</p>|', '', $input);

        return $input;
    }

    /**
     * Преобразуем URL|EMAIL в ссыдку HTML
     * @param $input
     * @return mixed
     */
    public static function getUrl($input)
    {
        $input = preg_replace("/(^|[\n ])([\w]*?)((ht|f)tp(s)?:\/\/[\w]+[^ \,\"\n\r\t<]*)/is", "$1$2<a href=\"$3\" >$3</a>", $input);
        $input = preg_replace("/(^|[\n ])([\w]*?)((www|ftp)\.[^ \,\"\t\n\r<]*)/is", "$1$2<a href=\"http://$3\" >$3</a>", $input);
        $input = preg_replace("/(^|[\n ])([a-z0-9&\-_\.]+?)@([\w\-]+\.([\w\-\.]+)+)/i", "$1<a href=\"mailto:$2@$3\">$2@$3</a>", $input);
        return $input;
    }

}