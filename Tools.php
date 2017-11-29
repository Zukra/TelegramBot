<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 28.11.2017
 * Time: 18:32
 */

namespace Tools;


class Tools {
    protected static $arData;

    public static function ReadArray() {
        static::$arData = include('storage.php');
    }

    public static function GetArray() {
        return static::$arData;
    }

    public static function SetArray($data) {
        static::$arData = $data;
    }

    public static function Write() {
        $StorageData = '<?php return $arData = ' . var_export(static::GetArray(), true) . '; ?>';
//        echo "file to write\n-------------\n$StorageData\n-------------\n";
        file_put_contents('storage.php', $StorageData);
    }

    public static function ArrCompare($arCurrency, $storageData) {
        $arrDiff = [];
        // если в полученном массиве больше валют,
        // чем в сохранённом - вывод и перезапись
        foreach ($arCurrency as $exchange => $coins) {
            foreach ($coins as $code => $coin) {
                if (!isset($storageData[$exchange][$code])) {
                    $arrDiff[$exchange][$coin["CODE"]] = $coin;
                }
            }
        }

        if (!empty($arrDiff)) {
            static::SetArray($arCurrency);
            static::Write();
        }

        return $arrDiff;
    }

    public static function getMsg($arCurrency, $arrDiff) {
        $msg = "";
        $arAddedCoins = static::getAddedCoins($arrDiff); // получить добавленные монеты
        foreach ($arAddedCoins as $coin) {
            $msg .= $coin["CODE"] . " (" . $coin["NAME"] . ") : " . PHP_EOL;
            foreach ($arCurrency as $exchange => $currency) {
                if (isset($currency[$coin["CODE"]])) {
                    $msg .=
                        /*
                        "<b>bold</b>, <strong>bold</strong>"
                            . "<i>italic</i>, <em>italic</em>"
                            . "<a href='http://www.example.com/'>inline URL</a>"
                            . "<code>inline fixed-width code</code>"
                            . "<pre>pre-formatted fixed-width code block</pre>"
                            . PHP_EOL;
                        */
                        "     " . $exchange . " + " . PHP_EOL;
                } else {
                    $msg .= "     " . $exchange . " - " . PHP_EOL;
                }
            }
        }
        /*
        foreach ($arrDiff as $exchange => $coins) {
            $msg .= $exchange . "   :   ";
            foreach ($coins as $coin) {
                $msg .= $coin["CODE"] . " = " . $coin["NAME"] . "  ";
            }
            $msg .= PHP_EOL;
        }
        */
        return $msg;
    }

    private static function getAddedCoins($arrDiff) {
        $result = [];
        foreach ($arrDiff as $coins) {
            foreach ($coins as $coin) {
                $result[$coin["CODE"]] = [
                    "CODE" => $coin["CODE"],
                    "NAME" => $coin["NAME"]
                ];
            }
        }
        return $result;
    }
}