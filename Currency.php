<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 28.11.2017
 * Time: 17:39
 */

/*
        $url = $this->arExchange["THEROCKTRADING"];

        $headers = ["Content-Type: application/json"];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $callResult = curl_exec($ch);
        curl_close($ch);

        $result = json_decode($callResult, true);
        var_dump($result);
*/

namespace Currency;


class Currency {
    protected $arExchange = [
        "BITTREX"        => "https://bittrex.com/api/v1.1/public/getcurrencies",
        "POLONIEX"       => "https://poloniex.com/public?command=returnCurrencies",
        "KRAKEN"         => "https://api.kraken.com/0/public/Assets",
        "BINANCE"        => "https://api.binance.com/api/v1/ticker/allPrices",
        "BITFINEX"       => "https://api.bitfinex.com/v1/symbols",
        "LIQUI"          => "https://api.liqui.io/api/3/info",
        "BITSTAMP"       => "https://www.bitstamp.net/api/v2/trading-pairs-info/",
        "BITHUMB"        => "https://api.bithumb.com/public/ticker/all",
        "GDAX"           => "https://api-public.sandbox.gdax.com/products",
        "GEMINI"         => "https://api.gemini.com/v1/symbols",
        "HITBTC"         => "https://api.hitbtc.com/api/2/public/symbol",
        "QUOINEX"         => "https://api.quoine.com/products",
        "THEROCKTRADING" => "https://api.therocktrading.com/v1/funds",
        "WEX"            => "https://wex.nz/api/3/info",
        "EXMO"           => "https://api.exmo.com/v1/currency/",
    ];


    public function getBittrexCurrency() {
        $result = [];
        $response = json_decode(file_get_contents($this->arExchange["BITTREX"]));
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
        $response = json_decode(file_get_contents($this->arExchange["POLONIEX"]));
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
        $response = json_decode(file_get_contents($this->arExchange["KRAKEN"]));
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
        $response = json_decode(file_get_contents($this->arExchange["BINANCE"]));
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
        $response = json_decode(file_get_contents($this->arExchange["BITFINEX"]));
//        foreach ($response as $coin) {
//            $result[$coin->symbol] = [
//                "CODE" => $coin->symbol,
//                "NAME" => "none"
//            ];
//        }

        return $result;
    }

    public function getLiquiCurrency() {
        $result = [];
        $response = json_decode(file_get_contents($this->arExchange["LIQUI"]));
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
        $response = json_decode(file_get_contents($this->arExchange["BITSTAMP"]));
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
        $response = json_decode(file_get_contents($this->arExchange["BITHUMB"]));
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
        $response = json_decode(file_get_contents($this->arExchange["GDAX"]));
        var_dump($response);
        foreach ($response->data as $code => $coin) {
            $result[$code] = [
                "CODE" => strtoupper($code),
                "NAME" => "none"
            ];
        }

//        var_dump($result);

        return $result;
    }

    public function getGeminiCurrency() {
        $result = [];
        $response = json_decode(file_get_contents($this->arExchange["GEMINI"]));
        foreach ($response as $pair) {
            $result[$pair] = [
                "CODE" => strtoupper($pair),
                "NAME" => "none"
            ];
        }

        return $result;
    }

    public function getHitbtcCurrency() {
        $result = [];
        $response = json_decode(file_get_contents($this->arExchange["HITBTC"]));
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
        $response = json_decode(file_get_contents($this->arExchange["QUOINEX"]));
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
        $response = json_decode(file_get_contents($this->arExchange["WEX"]));
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
        $response = json_decode(file_get_contents($this->arExchange["EXMO"]));
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
        $response = json_decode(file_get_contents($this->arExchange["THEROCKTRADING"]));
        foreach ($response->funds as $pair) {
            $result[$pair->id] = [
                "CODE" => $pair->id,
                "NAME" => $pair->base_currency . " / " . $pair->trade_currency
            ];
        }

        return $result;
    }
}