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

include_once('TelegramBot.php');
include_once('Currency.php');
include_once('Tools.php');

use Currency\Currency;
use Tools\Tools;
use TelegramBot\TelegramBot;

// $intervalSec = 60 * 15; // 15 min
$intervalSec = 10; // 10 sec
$intervalSecondSec = 30; // 30 sec
$firstChatId = 412846761;
$secondChatID = -218487457;
$sendTime = 0;

$currency = new Currency();
$telegramApi = new TelegramBot();
// $chatId = $telegramApi->getChatId();

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
        $sendTime = time() + $intervalSecondSec;
        $msg = "";
        foreach ($arrDiff as $exchange => $coins) {
            $msg .= $exchange . "   :   ";
            foreach ($coins as $coin) {
                $msg .= $coin["CODE"] . " = " . $coin["NAME"] . "  ";
            }
            $msg .= PHP_EOL;
        }
        // $telegramApi->sendMessage($chatId, $msg);
        $telegramApi->sendMessage($firstChatId, $msg);
    }
    if ($sendTime > 0 && time() >= $sendTime) {
        $telegramApi->sendMessage($secondChatID, $msg);
        $sendTime = 0;
    }
    sleep($intervalSec);

    /*
    // ответ бота на сообщения в чате
    $updates = $telegramApi->getUpdates();
    foreach ($updates as $update) {
        $telegramApi->sendMessage($update->message->chat->id, 'Kukara4a!');
    }
    */
}