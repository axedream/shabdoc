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
        $input = SELF::getH1($input);
        $input = SELF::getHx($input);
        $input = SELF::getUl($input);
        return $input;
    }




    /**
     * Внедряем параграфы + <br>
     * @param $input
     * @return mixed|string
     */
    public static function getP($input)
    {
        return preg_replace('/(\s*\n){2}/', "</p><p>", '<p>'.$input.'</p>');
    }

    /**
     * Получаем заголовки H1
     * @param $input
     * @return mixed
     */
    public static function getH1($input)
    {
        $reg = "/(<p>.+\n+=+<\/p>|<p>.+\n+-+<\/p>)/";
        if(preg_match($reg,$input,$h1)){
            $replace=str_replace('p>','h1>',$h1[0]);
            $replace=str_replace('-','',$replace);
            $replace=str_replace('=','',$replace);
            return preg_replace($reg, $replace, $input);
        } else {
            return $input;
        }
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

    /**
     * Получаем
     * @param $input
     * @return mixed
     */
    public static function getHx($input)
    {
        //если отловили num его и будем дальше крутить
        if(preg_match_all('/##+(.*?)p>/',$input,$num)) {
            //смотрим количество вхождений и пересобираем строку $i
            for($i=0;$i<sizeof($num[0]);$i++) {
                $count=substr_count($num[0][$i],'#');
                $replace=str_replace('#','',$num[0][$i]);
                $replace=$replace.'</h'.$count.'>';
                $replace='<h'.$count.'>'.$replace;
                $replace=str_replace('</p>', '', $replace);
                $input=str_replace($num[0][$i],$replace,$input);
            }
            return $input;
        } else {
            return $input;
        }
    }

    /**
     * Строим списка
     * @param $input
     * @return mixed
     */
    /*
    public static function getUl($input)
    {
        preg_match_all('/\*\s(.*?)(\n)/',$input,$list);
        file_put_contents("c:\\OpenServer\\domains\\hosting\\my.txt","\nВыводимые данные:\n\n".print_r($list,TRUE), FILE_APPEND | LOCK_EX );
        return $input;


    }
    */

    /**
     * Фомируем список
     * данный алгоритм заимствован и слегка переписан
     * @param $text
     * @return mixed
     */
    public static function getUl($input)
    {
        $regexAst='/\*\s(.*?)(\n|p>)/';
        if(preg_match_all($regexAst,$input,$ast))
        {
            $size=count($ast[0]);
            for($i=0;$i<$size;$i++)
            {
                $replace=str_replace('*','<li>',$ast[0][$i]).'</li>';
                //$replace=str_replace('-','<li>',$ast[0][$i]).'</li>';
                $replace=str_replace('</p>','',$replace);
                if($i==0)
                {
                    $replace='<ul>'.$replace;
                }
                if($i==($size-1))
                {
                    $replace=$replace.'</ul>';
                }
                $input=str_replace($ast[0][$i],$replace,$input);
            }
            return $input;
        }
        else {
            return $input;
        }
    }


}