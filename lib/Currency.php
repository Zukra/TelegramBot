<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 28.11.2017
 * Time: 17:39
 */

namespace lib;


class Currency {
    protected $arExchange = [
        "BITTREX"        => "https://bittrex.com/api/v1.1/public/getcurrencies",
        "POLONIEX"       => "https://poloniex.com/public?command=returnCurrencies",
        "KRAKEN"         => "https://api.kraken.com/0/public/Assets",
        "BINANCE"        => "https://api.binance.com/api/v1/ticker/allPrices",
        "BITFINEX"       => "https://api.bitfinex.com/v1/symbols",
        "LIQUI"          => "https://api.liqui.io/api/3/info",
        "BITSTAMP"       => "https://www.bitstamp.net/api/v2/trading-pairs-info",
        "BITHUMB"        => "https://api.bithumb.com/public/ticker/all",
        "GDAX"           => "https://api.gdax.com/currencies",
        "GEMINI"         => "https://api.gemini.com/v1/symbols",
        "HITBTCSYMBOL"   => "https://api.hitbtc.com/api/2/public/symbol",
        "HITBTC"         => "https://api.hitbtc.com/api/2/public/currency",
        "QUOINEX"        => "https://api.quoine.com/products",
        "THEROCKTRADING" => "https://api.therocktrading.com/v1/funds",
        "WEX"            => "https://wex.nz/api/3/info",
        "EXMO"           => "https://api.exmo.com/v1/currency/",
        "MERCATOX"       => "https://mercatox.com/public/json24",
        "TIDEX"          => "https://api.tidex.com/api/3/info",
    ];

    private static function getResponse($url) {
        $headers = ["Content-Type: application/json"];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $callResult = curl_exec($ch);
        curl_close($ch);

        return json_decode($callResult);
    }

    public function getBittrexCurrency() {
        $result = [];
        $response = static::getResponse($this->arExchange["BITTREX"]);
        foreach ($response->result as $coin) {
            if ($coin->IsActive) {
                $result[$coin->Currency] = [
                    "CODE" => $coin->Currency,
                    "NAME" => $coin->CurrencyLong
                ];
            }
        }

        return $result;
    }

    public function getPoloniexCurrency() {
        $result = [];
        $response = static::getResponse($this->arExchange["POLONIEX"]);
        foreach ($response as $code => $coin) {
            if ($coin->disabled == 0 || $coin->forzen == 0) {
                $result[$code] = [
                    "CODE" => $code,
                    "NAME" => $coin->name
                ];
            }
        }

        return $result;
    }

    public function getKrakenCurrency() {
        $result = [];
        $response = static::getResponse($this->arExchange["KRAKEN"]);
        foreach ($response->result as $code => $coin) {
            $result[$code] = [
                "CODE" => $code,
                "NAME" => $coin->altname
            ];
        }

        return $result;
    }

    public function getBinanceCurrency() {
        $result = [];
        $response = static::getResponse($this->arExchange["BINANCE"]);
        foreach ($response as $coin) {
            $result[$coin->symbol] = [
                "CODE" => $coin->symbol,
                "NAME" => "none"
            ];
        }

        return $result;
    }

    public function getBitfinexCurrency() {
        $result = [];
        $response = static::getResponse($this->arExchange["BITFINEX"]);
        foreach ($response as $coin) {
            $result[strtoupper($coin)] = [
                "CODE" => strtoupper($coin),
                "NAME" => "none"
            ];
        }

        return $result;
    }

    public function getLiquiCurrency() {
        $result = [];
        $response = static::getResponse($this->arExchange["LIQUI"]);
        foreach ($response->pairs as $code => $coin) {
            $result[strtoupper($code)] = [
                "CODE" => strtoupper($code),
                "NAME" => "none"
            ];
        }

        return $result;
    }

    public function getBitstampCurrency() {
        $result = [];
        $response = static::getResponse($this->arExchange["BITSTAMP"]);
        var_dump($response);
//        foreach ($response->pairs as $code => $coin) {
//            $result[strtoupper($code)] = [
//                "CODE" => strtoupper($code),
//                "NAME" => "none"
//            ];
//        }

        return $result;
    }

    public function getBithumbCurrency() {
        $result = [];
        $response = static::getResponse($this->arExchange["BITHUMB"]);
        foreach ($response->data as $code => $coin) {
            $result[$code] = [
                "CODE" => strtoupper($code),
                "NAME" => "none"
            ];
        }

        return $result;
    }

    public function getGdaxCurrency() {
        $result = [];
        $response = static::getResponse($this->arExchange["GDAX"]);
//        foreach ($response->data as $code => $coin) {
//            $result[$code] = [
//                "CODE" => strtoupper($code),
//                "NAME" => "none"
//            ];
//        }

        return $result;
    }

    public function getGeminiCurrency() {
        $result = [];
        $response = static::getResponse($this->arExchange["GEMINI"]);
        foreach ($response as $pair) {
            $result[$pair] = [
                "CODE" => strtoupper($pair),
                "NAME" => "none"
            ];
        }

        return $result;
    }

    public function getHitbtcCurrency() {
        $response = static::getResponse($this->arExchange["HITBTC"]);
        $result = [];
        foreach ($response as $coin) {
            $result[$coin->id] = [
                "CODE" => $coin->id,
                "NAME" => $coin->fullName
            ];
        }

        return $result;
    }

    public function getHitbtcCurrencySymbol() {
        $result = [];
        $response = static::getResponse($this->arExchange["HITBTCSYMBOL"]);
        foreach ($response as $pair) {
            $result[$pair->id] = [
                "CODE" => $pair->id,
                "NAME" => $pair->baseCurrency . " / " . $pair->quoteCurrency
            ];
        }

        return $result;
    }

    public function getQuoinexCurrency() {
        $result = [];
        $response = static::getResponse($this->arExchange["QUOINEX"]);
        foreach ($response as $pair) {
            $result[$pair->currency_pair_code] = [
                "CODE" => $pair->currency_pair_code,
                "NAME" => $pair->base_currency . " / " . $pair->quoted_currency
            ];
        }

        return $result;
    }

    public function getWexCurrency() {
        $result = [];
        $response = static::getResponse($this->arExchange["WEX"]);
        foreach ($response->pairs as $pair => $coin) {
            $result[$pair] = [
                "CODE" => strtoupper($pair),
                "NAME" => "none"
            ];
        }

        return $result;
    }

    public function getExmoCurrency() {
        $result = [];
        $response = static::getResponse($this->arExchange["EXMO"]);
        foreach ($response as $coin) {
            $result[$coin] = [
                "CODE" => $coin,
                "NAME" => "none"
            ];
        }

        return $result;
    }

    public function getTherocktradingCurrency() {
        $result = [];
        $response = static::getResponse($this->arExchange["THEROCKTRADING"]);
        foreach ($response->funds as $pair) {
            $result[$pair->id] = [
                "CODE" => $pair->id,
                "NAME" => $pair->base_currency . " / " . $pair->trade_currency
            ];
        }

        return $result;
    }

    public function getMercatoxCurrency() {
        $response = static::getResponse($this->arExchange["MERCATOX"]);
        $result = [];
        foreach ($response->pairs as $pair => $coin) {
            $result[$pair] = [
                "CODE" => $pair,
                "NAME" => "none"
            ];
        }

        return $result;
    }

    public function getTidexCurrency() {
        $result = [];
        $response = static::getResponse($this->arExchange["TIDEX"]);
        foreach ($response->pairs as $pair => $info) {
            $result[strtoupper($pair)] = [
                "CODE" => strtoupper($pair),
                "NAME" => "none"
            ];
        }

        return $result;
    }
}