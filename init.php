<?php
/**
 * Created by PhpStorm.
 * User: ZKR
 * Date: 28.11.2017
 * Time: 15:05
 * http://t.me/ForTestCurrencyBot
 * https://api.telegram.org/bot491206841:AAH8wHuoCrTJ7oq5TjrCMHKrPkd7e9z4QSA/getUpdates
 */

include_once('TelegramBot.php');
include_once('Currency.php');
include_once('Tools.php');

use Currency\Currency;
use Tools\Tools;
use TelegramBot\TelegramBot;

$intervalSec = 60 * 15; // 15 min

$currency = new Currency();
$telegramApi = new TelegramBot();
$chatId = $telegramApi->getChatId();

while (true) {
    // массив валют [CODE, NAME]
    $arCurrency = [
        "BITTREX"  => $currency->getBittrexCurrency(),
        "POLONIEX" => $currency->getPoloniexCurrency()
    ];

    // получаю сохранённый массив монет
    Tools::ReadArray();
    $storageData = Tools::GetArray();

    $arrDiff = Tools::ArrCompare($arCurrency, $storageData); // разница сравнения

    if (!empty($arrDiff)) {
        $msg = "";
        foreach ($arrDiff as $exchange => $coins) {
            $msg .= $exchange . "   :   ";
            foreach ($coins as $coin) {
                $msg .= $coin["CODE"] . " = " . $coin["NAME"] . "  ";
            }
            $msg .= PHP_EOL;
        }
        $telegramApi->sendMessage($chatId, $msg);
    }

    /*
    // ответ бота на сообщения в чате
    $updates = $telegramApi->getUpdates();
    foreach ($updates as $update) {
        $telegramApi->sendMessage($update->message->chat->id, 'Kukara4a!');
    }
    */

    sleep($intervalSec);
}