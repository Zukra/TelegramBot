<?php
/**
 * Created by PhpStorm.
 * User: ZKR
 * Date: 28.11.2017
 * Time: 15:05
 * http://t.me/ForTestCurrencyBot
 * https://api.telegram.org/bot491206841:AAH8wHuoCrTJ7oq5TjrCMHKrPkd7e9z4QSA/getUpdates
 * testSendMsg
 */

//spl_autoload_extensions(".php");
//spl_autoload_register();

include_once('TelegramBot.php');
include_once('Currency.php');
include_once('Tools.php');

use Currency\Currency;
use Tools\Tools;
use TelegramBot\TelegramBot;

// $intervalSec = 60 * 15; // 15 min
$intervalSec = 30; // 60 sec
$intervalSecondSec = 20; // 30 sec
//$firstChatId = 412846761;
$firstChatId = -218487457;
// $secondChatID = -218487457;
$secondChatID = -1001325237083;
$sendTime = 0;

$currency = new Currency();
$telegramApi = new TelegramBot();
// $chatId = $telegramApi->getChatId();

while (true) {
// массив валют [CODE, NAME]
    $arCurrency = [
        "BITTREX"        => $currency->getBittrexCurrency(),
        "POLONIEX"       => $currency->getPoloniexCurrency(),
        "KRAKEN"         => $currency->getKrakenCurrency(),
        "BINANCE"        => $currency->getBinanceCurrency(),
        "LIQUI"          => $currency->getLiquiCurrency(),
        "BITHUMB"        => $currency->getBithumbCurrency(),
        "GEMINI"         => $currency->getGeminiCurrency(),
        "HITBTC"         => $currency->getHitbtcCurrency(),
        "THEROCKTRADING" => $currency->getTherocktradingCurrency(),
        "EXMO"           => $currency->getExmoCurrency(),
//        "WEX"            => $currency->getWexCurrency(), // ??? упал
//        "QUOINEX"         => $currency->getQuoinexCurrency(), // ?!?!?! иногда отрабатывает
//    "BITFINEX"       => $currency->getBitfinexCurrency(), // -
//    "GDAX"           => $currency->getGdaxCurrency(),  // -
//    "BITSTAMP"       => $currency->getBitstampCurrency(), // -
    ];

    $arExchange = array_keys($arCurrency); // биржи

    Tools::checkStoreData($arCurrency); // проверка на наличие сохранённых данных

    $storageData = Tools::getStoreData($arExchange); // получить сохранённые данные по биржам

    $arrDiff = Tools::compareData($arCurrency, $storageData); // разница сравнения

// получаю сохранённый массив монет
//Tools::ReadArray();
//$storageData = Tools::GetArray();

//$arrDiff = Tools::ArrCompare($arCurrency, $storageData); // разница сравнения

    if (!empty($arrDiff)) {
        $sendTime = time() + $intervalSecondSec;

        $msg = Tools::getMessage($arrDiff);  // формирование сообщения из новых монет

        var_dump($msg);

//    $msg = Tools::getMsg($arCurrency, $arrDiff);  // текущие полученные монеты + новые

        // $telegramApi->sendMessage($chatId, $msg);
        // $telegramApi->sendMessage($firstChatId, $msg, "html");
        $telegramApi->sendMessage($firstChatId, $msg);
    }
    /*
    if ($sendTime > 0 && time() >= $sendTime) {
        $telegramApi->sendMessage($secondChatID, $msg);
        $sendTime = 0;
    }
    */

    sleep($intervalSec);

    /*
    // ответ бота на сообщения в чате
    $updates = $telegramApi->getUpdates();
    foreach ($updates as $update) {
        $telegramApi->sendMessage($update->message->chat->id, 'Kukara4a!');
    }
    */

//    exit();
}