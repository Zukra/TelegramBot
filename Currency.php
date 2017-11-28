<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 28.11.2017
 * Time: 17:39
 */

namespace Currency;


class Currency {
    protected $bittrexUrl = "https://bittrex.com/api/v1.1/public/getcurrencies";
    protected $poloniexUrl = "https://poloniex.com/public?command=returnCurrencies";


    public function getBittrexCurrency() {
        $result = [];
        $response = json_decode(file_get_contents($this->bittrexUrl));

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
        $response = json_decode(file_get_contents($this->poloniexUrl));
        foreach ($response as $code => $coin) {
            if ($coin->disabled > 0 || $coin->forzen > 0) {
                $result[$code] = [
                    "CODE" => $code,
                    "NAME" => $coin->name
                ];
            }
        }

        return $result;
    }
}