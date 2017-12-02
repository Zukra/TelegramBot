<?php
/**
 * Created by PhpStorm.
 * User: ZKR
 * Date: 28.11.2017
 * Time: 15:05
 * https://api.telegram.org/bot491206841:AAH8wHuoCrTJ7oq5TjrCMHKrPkd7e9z4QSA/getUpdates
 * @ForTestCurrencyBot
 */

include_once('__autoload.php');

use lib\Currency;
use lib\TelegramBot;
use lib\Tools;


//TODO  выводить удалённые монеты

/**
 *  412846761       Crypto currency (@ForTestCurrencyBot)
 * -1001339615839   AddNewCoins     (@sendnewcoin)
 * -1001325237083   SendNewCoinTest (@shownewcoin) (for testing)
 * -218487457       TestSendCoin    (@testSendMsg) (for test need remove)
 */

$intervalSec = 60; // sec
$intervalSecondSec = 60 * 10; // 10 min
$generalChatId = 412846761;
$secondChatID = -1001339615839;
$msgQueue = []; // очередь сообщений для отправки с задержкой
$sendTime = 0; // время отправки
$timeForMoreSend = 0; // время для отправки сообщения в доп. чат


// for test
$generalChatId = -1001325237083; // for test
$secondChatID = -218487457; // for test
$intervalSec = 10; // for test
$intervalSecondSec = 60; // for test


$currency = new Currency();
$telegramApi = new TelegramBot();


while (true) {
    $arCurrency = [
//        "ACX"                    => $currency->getAcxCurrency("https://acx.io/api/v2/markets"),
//        "ABUCOINS"               => $currency->getAbucoinsCurrency("https://api.abucoins.com/products"),
//        "BTCALPHA"               => $currency->getBtcalphaCurrency("https://btc-alpha.com/api/v1/pairs/"),
//        "BITZ"                   => $currency->getBitzCurrency("https://www.bit-z.com/api_v1/tickerall"),
//        "BITTREX"                => $currency->getBittrexCurrency("https://bittrex.com/api/v1.1/public/getcurrencies"),
//        "CEXIO"                  => $currency->getCexioCurrency("https://cex.io/api/currency_limits"),
//        "COINEXCHANGE_IO"        => $currency->getCoinexchangeIoCurrency("https://www.coinexchange.io/api/v1/getmarkets"),
//        "COINROOM_COM"           => $currency->getCoinroomComCurrency("https://coinroom.com/api/availableCurrencies"),
//        "CRYPTOPIA_CO_NZ"        => $currency->getCryptopiaCoNzCurrency("https://www.cryptopia.co.nz/api/GetCurrencies"),
//        "DSX_UK"                 => $currency->getDsxUkCurrency("https://dsx.uk/mapi/info"),
//        "EXMO_COM"               => $currency->getExmoComCurrency("https://api.exmo.com/v1/currency/"),
//        "GATE_IO"                => $currency->getGateIoCurrency("http://data.gate.io/api2/1/pairs"),
//        "INDEPENDENTRESERVE_COM" => $currency->getIndependentreserveComCurrency("https://api.independentreserve.com/Public/"),
//        "LITEBIT_EU"             => $currency->getLitebitEuCurrency("https://api.litebit.eu/markets"),
//        "LIVECOIN_NET"           => $currency->getLivecoinNetCurrency("https://api.livecoin.net/info/coinInfo"),
//        "NERAEX_COM"             => $currency->getNeraexComCurrency("https://neraex.com/api/v2/markets"),
        "POLONIEX" => $currency->getPoloniexCurrency("https://poloniex.com/public?command=returnCurrencies"),
//        "KRAKEN"                 => $currency->getKrakenCurrency("https://api.kraken.com/0/public/Assets"),
//        "BINANCE"                => $currency->getBinanceCurrency("https://api.binance.com/api/v1/ticker/allPrices"),
//        "LIQUI"                  => $currency->getLiquiCurrency("https://api.liqui.io/api/3/info"),
//        "BITHUMB"                => $currency->getBithumbCurrency("https://api.bithumb.com/public/ticker/all"),
//        "GEMINI"                 => $currency->getGeminiCurrency("https://api.gemini.com/v1/symbols"),
//        "HITBTC"                 => $currency->getHitbtcCurrency("https://api.hitbtc.com/api/2/public/currency"),
//        "THEROCKTRADING"         => $currency->getTherocktradingCurrency("https://api.therocktrading.com/v1/funds"),
//        "TIDEX"                  => $currency->getTidexCurrency("https://api.tidex.com/api/3/info"),
//        "WEX"                    => $currency->getWexCurrency("https://wex.nz/api/3/info"), // + ???
//        "QUOINEX"                => $currency->getQuoinexCurrency("https://api.quoine.com/products"), // + ?!?!?! иногда отрабатывает
//        "BITFINEX"               => $currency->getBitfinexCurrency("https://api.bitfinex.com/v1/symbols"), // + ???
//        "MERCATOX"               => $currency->getMercatoxCurrency("https://mercatox.com/public/json24"), // + ???
//        "GDAX"                   => $currency->getGdaxCurrency("https://api.gdax.com/currencies"),  // - не фурычит
//        "BITSTAMP"               => $currency->getBitstampCurrency("https://www.bitstamp.net/api/v2/trading-pairs-info"), // - не фурычит
//        "HITBTCSYMBOL"           => $currency->getHitbtcCurrencySymbol("https://api.hitbtc.com/api/2/public/symbol"), // double site
    ];

    $arExchange = array_keys($arCurrency); // биржи

    Tools::checkStoreData($arCurrency); // проверка на наличие сохранённых данных

    $storageData = Tools::getStoreData($arExchange); // получить сохранённые данные по биржам

    $arrDiff = Tools::compareData($arCurrency, $storageData); // разница сравнения

    if (!empty($arrDiff)) {
        $msg = Tools::getMessage($arrDiff);  // формирование сообщения из новых монет

        // $telegramApi->sendMessage($generalChatId, $msg, "html");
        $telegramApi->sendMessage($generalChatId, $msg);

        $sendTime = time();  // время отправки
        $timeForMoreSend = $sendTime + $intervalSecondSec; // время отправки в доп. чат

        $msgQueue[] = ["TIME" => $timeForMoreSend, "MSG" => $msg];
    }

    if (!empty($msgQueue[0]) && time() >= $msgQueue[0]["TIME"]) {
        $elem = array_shift($msgQueue);
        $telegramApi->sendMessage($secondChatID, $elem["MSG"]);
    }

    sleep($intervalSec);

//    exit();
}