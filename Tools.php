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
}