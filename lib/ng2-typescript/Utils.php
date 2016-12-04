<?php

class Utils
{
    public static function upperCaseFirstLetter($str)
    {
        return ucwords($str);
    }






    public static function indent($text,$n){
        if(is_string($text) && is_int($n)){
            $indent = "";
            $i = 0;
            while($i < $n){
                $i++;
                $indent.= "\t";
            }
            return str_replace("\n", "\n".$indent, str_replace(array("\r\n","\r"), "\n", $text));
        }
    }

    public static function startsWithNumber($string) {
        return strlen($string) > 0 && ctype_digit(substr($string, 0, 1));
    }

    public static function toLispCase($input)
    {
        preg_match_all('!([A-Z][A-Z0-9]*(?=$|[A-Z][a-z0-9])|[A-Za-z][a-z0-9]+)!', $input, $matches);
        $ret = $matches[0];
        foreach ($ret as &$match) {
            $match = $match == strtoupper($match) ? strtolower($match) : lcfirst($match);
        }
        return implode('-', $ret);
    }

    public static function buildExpression($items, $lineSuffix, $textIndent = 0)
    {
        if (is_array($items)) {
            return count($items) != 0 ? Utils::indent(join($lineSuffix, $items),$textIndent) : "";
        }else{
            return "";
        }
    }


    public static function startsWith($str, $prefix)
    {
        return (substr($str, 0, strlen($prefix)) === $prefix);
    }

    public static function validateType($type, $typeClassName, $availableTypes, $target)
    {

        $errors = array();

        if (!isset($type))
        {
            $errors[] = "missing type for {$target}";
        }else
        {
            switch ($type)
            {
                case KalturaServerTypes::Simple:
                    $supportedTypes = array('int','bool','float','bigint','string');

                    if (!in_array($typeClassName,$supportedTypes))
                    {
                        $errors[] = "Unknown type '{$typeClassName}' for {$target}";

                    }
                    break;
                case KalturaServerTypes::Unknown:
                    $errors[] = "Unknown type for {$target}";
                    break;
                case KalturaServerTypes::Object:
                case KalturaServerTypes::ArrayObject:
                case KalturaServerTypes::EnumOfString:
                case KalturaServerTypes::EnumOfInt:
                    if (!in_array($typeClassName, $availableTypes))
                    {
                        $errors[] = "Unknown type '{$typeClassName}' for {$target}";

                    }
                    break;
            }
        }

        return $errors;
    }

    public static function findInArrayByName($searchedValue, $array)
    {
        $neededObject = array_filter(
            $array,
            function ($e) use (&$searchedValue) {
                return $e->name == $searchedValue;
            }
        );

        if (count($neededObject) == "1")
        {
            return $neededObject[0];
        }else
        {
            return null;
        }
    }

    public static function fromSnakeCaseToCamelCase($str)
    {
        $startWithSnake = Utils::startsWith($str,'_');
        $newName =  str_replace(' ', '', ucwords(str_replace(['-', '_'], ' ', strtolower($str))));

        return ($startWithSnake ? '_' : '') . $newName;
    }

    public static function ifExp($condition, $truthlyValue, $falsyValue = "")
    {
        return $condition ? $truthlyValue : $falsyValue;
    }

    public static function toSnakeCase($input)
    {
        preg_match_all('!([A-Z][A-Z0-9]*(?=$|[A-Z][a-z0-9])|[A-Za-z][a-z0-9]+)!', $input, $matches);
        $ret = $matches[0];
        foreach ($ret as &$match) {
            $match = $match == strtoupper($match) ? strtolower($match) : lcfirst($match);
        }
        return implode('_', $ret);
    }

    public static function createDocumentationExp($spacer, $documentation)
    {
        if ($documentation) {
            return "/** " . NewLine . "* " . wordwrap(str_replace(array("\t", "\n", "\r"), " ", $documentation), 80, NewLine . "* ") . NewLine . "**/";
        }
        return "";
    }


}