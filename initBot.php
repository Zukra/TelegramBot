<?php
/**
 * Created by PhpStorm.
 * User: ZKR
 * Date: 28.11.2017
 * Time: 15:05
 * http://t.me/ForTestCurrencyBot
 * https://api.telegram.org/bot491206841:AAH8wHuoCrTJ7oq5TjrCMHKrPkd7e9z4QSA/getUpdates
 * testSendMsg
 * @ForTestCurrencyBot
 */

include_once('__autoload.php');

use lib\Currency;
use lib\TelegramBot;
use lib\Tools;


// $intervalSec = 60 * 15; // 15 min
$intervalSec = 30; // 60 sec
$intervalSecondSec = 20; // 30 sec
$generalChatId = -218487457; // -1001339615839  412846761
// $secondChatID = -218487457;
$secondChatID = -1001325237083;
$sendTime = 0;

$currency = new Currency();
$telegramApi = new TelegramBot();

while (true) {
    $arCurrency = [
        "BITTREX" => $currency->getBittrexCurrency(),
//        "POLONIEX"       => $currency->getPoloniexCurrency(),
//        "KRAKEN"         => $currency->getKrakenCurrency(),
//        "BINANCE"        => $currency->getBinanceCurrency(),
//        "LIQUI"          => $currency->getLiquiCurrency(),
//        "BITHUMB"        => $currency->getBithumbCurrency(),
//        "GEMINI"         => $currency->getGeminiCurrency(),
//        "HITBTCSYMBOL"   => $currency->getHitbtcCurrencySymbol(),
//        "HITBTC"         => $currency->getHitbtcCurrency(),
//        "THEROCKTRADING" => $currency->getTherocktradingCurrency(),
//        "EXMO"           => $currency->getExmoCurrency(),
//        "TIDEX"          => $currency->getTidexCurrency(),
//        "WEX"            => $currency->getWexCurrency(), // + ???
//        "QUOINEX"        => $currency->getQuoinexCurrency(), // + ?!?!?! иногда отрабатывает
//        "BITFINEX"       => $currency->getBitfinexCurrency(), // + ???
//        "MERCATOX"       => $currency->getMercatoxCurrency(), // + ???
//        "GDAX"           => $currency->getGdaxCurrency(),  // - не фурычит
//        "BITSTAMP"       => $currency->getBitstampCurrency(), // - не фурычит
    ];

    $arExchange = array_keys($arCurrency); // биржи

    Tools::checkStoreData($arCurrency); // проверка на наличие сохранённых данных

    $storageData = Tools::getStoreData($arExchange); // получить сохранённые данные по биржам

    $arrDiff = Tools::compareData($arCurrency, $storageData); // разница сравнения

    if (!empty($arrDiff)) {
        $sendTime = time() + $intervalSecondSec;

        $msg = Tools::getMessage($arrDiff);  // формирование сообщения из новых монет

        var_dump($msg);

        // $telegramApi->sendMessage($generalChatId, $msg, "html");
//        $telegramApi->sendMessage($generalChatId, $msg);
    }
    /*
    if ($sendTime > 0 && time() >= $sendTime) {
        $telegramApi->sendMessage($secondChatID, $msg);
        $sendTime = 0;
    }
    */

//    sleep($intervalSec);

    exit();
}