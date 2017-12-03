<?php

namespace zkr\lib;


class Tools {
    protected static $arData;

    // not use
    public static function ReadArray() {
        static::$arData = include('storage.php');
    }

    // not use
    public static function GetArray() {
        return static::$arData;
    }

    // not use
    public static function SetArray($data) {
        static::$arData = $data;
    }

    // not use
    public static function Write() {
        $StorageData = '<?php return $arData = ' . var_export(static::GetArray(), true) . '; ?>';
//        echo "file to write\n-------------\n$StorageData\n-------------\n";
        file_put_contents('storage.php', $StorageData);
    }

    // not use
    public static function ArrCompare($arCurrency, $storageData) {
        $arrDiff = [];
        // если в полученном массиве больше валют,
        // чем в сохранённом - вывод и перезапись
        foreach ($arCurrency as $exchange => $coins) {
            foreach ($coins as $code => $coin) {
                if (!isset($storageData[$exchange][$code])) {
                    $coin["EXCHANGE"] = $exchange;
                    $arrDiff[$exchange][$coin["CODE"]] = $coin;
                }
            }
        }

        if (!empty($arrDiff)) {
            static::SetArray($arCurrency);
//            static::Write();
        }

        return $arrDiff;
    }

    // not use
    public static function getMsg($arCurrency, $arrDiff) {
        $msg = "";
        $arAddedCoins = static::getAddedCoins($arrDiff); // добавленные монеты
        // бегу по всем новым монетам
        foreach ($arAddedCoins as $coin) {
            $msg .= $coin["CODE"] . " (" . $coin["NAME"] . ") : " . PHP_EOL;
            // бегу по всем полученным биржам
            foreach ($arCurrency as $exchange => $currency) {
                $msg .= str_pad(" ", 5, " ") . str_pad($exchange, 15, " ");
                // поверка на существование новой монеты на бирже
                if (isset($currency[$coin["CODE"]])) {
                    // если монета была добавлена - "+1", если уже была - "+"
                    $sing = ($exchange == $arrDiff[$exchange][$coin["CODE"]]["EXCHANGE"] ? "+1" : "+");
                    /*
                    if ($exchange == $arrDiff[$exchange][$coin["CODE"]]["EXCHANGE"]) {
                        $msg .= "     " . $exchange . " +1 " . PHP_EOL;
                    } else {
                        $msg .= "     " . $exchange . " + " . PHP_EOL;
                    }
                    */
                    /*
                    "<b>bold</b>, <strong>bold</strong>"
                        . "<i>italic</i>, <em>italic</em>"
                        . "<a href='http://www.example.com/'>inline URL</a>"
                        . "<code>inline fixed-width code</code>"
                        . "<pre>pre-formatted fixed-width code block</pre>"
                        . PHP_EOL;
                    */
                } else {
                    $sing = "-";
                }
                $msg .= str_pad($sing, 4, " ", STR_PAD_BOTH)
                    . PHP_EOL;
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

    // not use
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

    public static function writeExchangeToFile($data) {
        $StorageData = '<?php return ' . var_export($data, true) . '; ?>';
        file_put_contents("data/" . strtolower(key($data)) . '.php', $StorageData);
    }

    /**
     * Проверка сохранённых данных,
     * при отсутствии - сохранение полученных данных
     * @param array $arCurrency - массив полученных данных по биржам
     * @return array массив сохранённых данных по биржам,
     * а если таких данных нет - дополняется полученными данными
     */
    public static function checkStoreData($arCurrency) {
        $result = [];
        foreach ($arCurrency as $nameExch => $coinsExch) {
            $data = static::getData(strtolower($nameExch));
            if (!$data || !isset($data)) {
                static::writeExchangeToFile([$nameExch => $coinsExch]);
                if (!empty($coinsExch)) {
                    $result[$nameExch] = $coinsExch;
                }
            } else {
                $result[$nameExch] = $data[$nameExch];
            }
        }

        return $result;
    }

    /**
     * Получить сохранённые данные
     * @param array $arExchange - массив бирж
     * @return array массив сохранённых данных по биржам
     */
    public static function getStoreData($arExchange) {
        $result = [];
        foreach ($arExchange as $exchange) {
            $data = static::getData(strtolower($exchange));
            $result[$exchange] = $data[$exchange];
        }

        return $result;
    }

    private static function getData($fileName) {
        $file = "data/" . $fileName . ".php";

        return include($file);
    }

    /**
     * Сравнение полученных данных и сохранённых
     * если есть различия - сохранение новых данных
     * @param $arCurrency
     * @param $storageData
     * @return array массив новых монет
     */
    public static function compareData($arCurrency, $storageData) {
        $arDiff = [];
        foreach ($arCurrency as $exchange => $coins) {
            $diff = array_diff_key($coins, $storageData[$exchange]);
            // если есть новые монеты - сохраняем новые данные
            if (!empty($diff)) {
                $arDiff[$exchange] = $diff;
                $data = [$exchange => $coins];
                static::writeExchangeToFile($data);
            }
        }

        return $arDiff;
    }

    public static function getMessage($arrDiff) {
        $msg = "";
        foreach ($arrDiff as $exchange => $coins) {
            $msg .= $exchange . " : " . PHP_EOL;
            foreach ($coins as $coin) {
                $msg .= str_pad(" ", 10, " ")
                    . " + " . $coin["CODE"] . " (" . $coin["NAME"] . ")"
                    . PHP_EOL;
            }
        }

        /*
                // бегу по всем новым монетам
                foreach ($arAddedCoins as $coin) {
                    $msg .= $coin["CODE"] . " (" . $coin["NAME"] . ") : " . PHP_EOL;
                    // бегу по всем полученным биржам
                    foreach ($arCurrency as $exchange => $currency) {
                        $msg .= str_pad(" ", 5, " ") . str_pad($exchange, 15, " ");
                        // поверка на существование новой монеты на бирже
                        if (isset($currency[$coin["CODE"]])) {
                            // если монета была добавлена - "+1", если уже была - "+"
                            $sing = ($exchange == $arrDiff[$exchange][$coin["CODE"]]["EXCHANGE"] ? "+1" : "+");
                        } else {
                            $sing = "-";
                        }
                        $msg .= str_pad($sing, 4, " ", STR_PAD_BOTH)
                            . PHP_EOL;
                    }
                }
        */

        return $msg;
    }
}
