<?php
/**
 * Created by PhpStorm.
 * User: ZKR
 * Date: 28.11.2017
 * Time: 15:05
 * https://api.telegram.org/bot491206841:AAH8wHuoCrTJ7oq5TjrCMHKrPkd7e9z4QSA/getUpdates
 * @ForTestCurrencyBot
 */

use zkr\lib\Currency;
use zkr\lib\TelegramBot;
use zkr\lib\Tools;

require_once __DIR__ . '/vendor/autoload.php';


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
$generalChatId = -1001325237083;
$secondChatID = -218487457;
$intervalSec = 10;
$intervalSecondSec = 30;


$currency = new Currency();
$telegramApi = new TelegramBot();

while (true) {
    $arCurrency = [
        "ACX.IO"                 => $currency->getAcxCurrency("https://acx.io/api/v2/markets"),
        "ABUCOINS.COM"           => $currency->getAbucoinsCurrency("https://api.abucoins.com/products"),
        "BTC-ALPHA.COM"          => $currency->getBtcalphaCurrency("https://btc-alpha.com/api/v1/pairs/"),
        "BIT-Z.COM"              => $currency->getBitzCurrency("https://www.bit-z.com/api_v1/tickerall"),
        "BITTREX.COM"            => $currency->getBittrexCurrency("https://bittrex.com/api/v1.1/public/getcurrencies"),
        "CEX.IO"                 => $currency->getCexioCurrency("https://cex.io/api/currency_limits"),
        "COINEXCHANGE.IO"        => $currency->getCoinexchangeIoCurrency("https://www.coinexchange.io/api/v1/getmarkets"),
        "COINROOM.COM"           => $currency->getCoinroomComCurrency("https://coinroom.com/api/availableCurrencies"),
        "CRYPTOPIA.CO.NZ"        => $currency->getCryptopiaCoNzCurrency("https://www.cryptopia.co.nz/api/GetCurrencies"),
        "DSX.UK"                 => $currency->getDsxUkCurrency("https://dsx.uk/mapi/info"),
        "EXMO.COM"               => $currency->getExmoComCurrency("https://api.exmo.com/v1/currency/"),
        "GATE.IO"                => $currency->getGateIoCurrency("http://data.gate.io/api2/1/pairs"),
        "INDEPENDENTRESERVE.COM" => $currency->getIndependentreserveComCurrency("https://api.independentreserve.com/Public/"),
        "LITEBIT.EU"             => $currency->getLitebitEuCurrency("https://api.litebit.eu/markets"),
        "LIVECOIN.NET"           => $currency->getLivecoinNetCurrency("https://api.livecoin.net/info/coinInfo"),
        "NERAEX.COM"             => $currency->getNeraexComCurrency("https://neraex.com/api/v2/markets"),
        "POLONIEX.COM"           => $currency->getPoloniexCurrency("https://poloniex.com/public?command=returnCurrencies"),
        "QUADRIGACX.COM"         => $currency->getQuadrigacxComCurrency("https://api.quadrigacx.com/public/info"),
        "YOBIT.NET"              => $currency->getYobitNetCurrency("https://yobit.net/api/3/info"),
        "ZB.COM"                 => $currency->getZbComCurrency("http://api.zb.com/data/v1/markets"),
        "ZAIF.JP"                => $currency->getZaifJpCurrency("https://api.zaif.jp/api/1/currencies/all"),
        "GATECOIN.COM"           => $currency->getGatecoinComCurrency("https://api.gatecoin.com/Public/LiveTickers"),
        "KUCOIN.COM"             => $currency->getKucoinComCurrency("https://api.kucoin.com/v1/open/currencies"),
        "LYKKE.COM"              => $currency->getLykkeComCurrency("https://hft-api.lykke.com/api/AssetPairs"),
        "BITSANE.COM"            => $currency->getBitsaneComCurrency("https://bitsane.com/api/public/ticker"),
        "BLEUTRADE.COM"          => $currency->getBleutradeComCurrency("https://bleutrade.com/api/v2/public/getcurrencies"),
        "BRAZILIEX.COM"          => $currency->getBraziliexComCurrency("https://braziliex.com/api/v1/public/currencies"),
        "CRYPTOMATE.CO.UK"       => $currency->getCryptomateCoUkCurrency("https://cryptomate.co.uk/api/all/"),
        "KUNA.IO"                => $currency->getKunaIoCurrency("https://kuna.io/api/v2/tickers"),
        "NOVAEXCHANGE.COM"       => $currency->getNovaexchangeComCurrency("https://novaexchange.com/remote/v2/markets/"),
        "SOUTHXCHANGE.COM"       => $currency->getSouthxchangeComCurrency("https://www.southxchange.com/api/markets"),
        "KRAKEN.COM"             => $currency->getKrakenCurrency("https://api.kraken.com/0/public/Assets"),
        "BINANCE.COM"            => $currency->getBinanceCurrency("https://api.binance.com/api/v1/ticker/allPrices"),
        "LIQUI.IO"               => $currency->getLiquiCurrency("https://api.liqui.io/api/3/info"),
        "BITHUMB.COM"            => $currency->getBithumbCurrency("https://api.bithumb.com/public/ticker/all"),
        "GEMINI.COM"             => $currency->getGeminiCurrency("https://api.gemini.com/v1/symbols"),
        "HITBTC.COM"             => $currency->getHitbtcCurrency("https://api.hitbtc.com/api/2/public/currency"),
        "THEROCKTRADING.COM"     => $currency->getTherocktradingCurrency("https://api.therocktrading.com/v1/funds"),
        "TIDEX.COM"              => $currency->getTidexCurrency("https://api.tidex.com/api/3/info"),
        "WEX.NZ"                 => $currency->getWexCurrency("https://wex.nz/api/3/info"), // + ???
        "QUOINEX.COM"            => $currency->getQuoinexCurrency("https://api.quoine.com/products"), // + ?!?!?! иногда отрабатывает
        "BITFINEX.COM"           => $currency->getBitfinexCurrency("https://api.bitfinex.com/v1/symbols"), // + ???
        "MERCATOX.COM"           => $currency->getMercatoxCurrency("https://mercatox.com/public/json24"), // + ???
        "GDAX.COM"               => $currency->getGdaxCurrency("https://api.gdax.com/currencies"),
        "COINONE.CO.KR"          => $currency->getCoinoneCoKrCurrency("https://api.coinone.co.kr/ticker/?currency=all"),
        "C-CEX.COM"              => $currency->getCcexComCurrency("https://c-cex.com/t/coinnames.json"),
        "EXX.COM"                => $currency->getExxComCurrency("https://api.exx.com/data/v1/markets"),
        "BITSO.COM"              => $currency->getBitsoComCurrency("https://api.bitso.com/v3/available_books/"),
        "MARKETS.BISQ.NETWORK"   => $currency->getMarketsBisqNetworkCurrency("https://markets.bisq.network/api/markets"),
        "VIRCUREX.COM"           => $currency->getVircurexComCurrency("https://api.vircurex.com/api/get_info_for_currency.json"),

//        "BITSTAMP.NET"           => $currency->getBitstampCurrency("https://www.bitstamp.net/api/v2/trading-pairs-info"), // - не фурычит
//        "BITGRAIL.COM"           => $currency->getBitgrailComCurrency("https://bitgrail.com/api/v1/markets"),  // - не фурычит
    ];

//    xprint($arCurrency);
//    $test = $currency->getTestCurrency("https://markets.bisq.network/api/22markets");

    $storageData = Tools::checkStoreData($arCurrency); // получение/проверка на наличие сохранённых данных

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