<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 28.11.2017
 * Time: 17:39
 */

namespace lib;


class Currency {

    private static function getResponse($url) {
        $headers = ["Content-Type: application/json;charset=UTF-8"];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
//        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
//        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
//        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        $callResult = curl_exec($ch);
        curl_close($ch);

        return json_decode($callResult);
    }

    public function getBittrexCurrency($url) {
        $result = [];
        $response = static::getResponse($url);
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

    public function getPoloniexCurrency($url) {
        $result = [];
        $response = static::getResponse($url);
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

    public function getKrakenCurrency($url) {
        $result = [];
        $response = static::getResponse($url);
        foreach ($response->result as $code => $coin) {
            $result[$code] = [
                "CODE" => $code,
                "NAME" => $coin->altname
            ];
        }

        return $result;
    }

    public function getBinanceCurrency($url) {
        $result = [];
        $response = static::getResponse($url);
        foreach ($response as $coin) {
            $result[$coin->symbol] = [
                "CODE" => $coin->symbol,
                "NAME" => "none"
            ];
        }

        return $result;
    }

    public function getBitfinexCurrency($url) {
        $result = [];
        $response = static::getResponse($url);
        foreach ($response as $coin) {
            $result[strtoupper($coin)] = [
                "CODE" => strtoupper($coin),
                "NAME" => "none"
            ];
        }

        return $result;
    }

    public function getLiquiCurrency($url) {
        $result = [];
        $response = static::getResponse($url);
        foreach ($response->pairs as $code => $coin) {
            $result[strtoupper($code)] = [
                "CODE" => strtoupper($code),
                "NAME" => "none"
            ];
        }

        return $result;
    }

    public function getBitstampCurrency($url) {
        $result = [];
        $response = static::getResponse($url);
        var_dump($response);
//        foreach ($response->pairs as $code => $coin) {
//            $result[strtoupper($code)] = [
//                "CODE" => strtoupper($code),
//                "NAME" => "none"
//            ];
//        }

        return $result;
    }

    public function getBithumbCurrency($url) {
        $result = [];
        $response = static::getResponse($url);
        foreach ($response->data as $code => $coin) {
            $result[$code] = [
                "CODE" => strtoupper($code),
                "NAME" => "none"
            ];
        }

        return $result;
    }

    public function getGdaxCurrency($url) {
        $result = [];
//        $response = static::getResponse($url);
//        foreach ($response->data as $code => $coin) {
//            $result[$code] = [
//                "CODE" => strtoupper($code),
//                "NAME" => "none"
//            ];
//        }

        return $result;
    }

    public function getGeminiCurrency($url) {
        $result = [];
        $response = static::getResponse($url);
        foreach ($response as $pair) {
            $result[$pair] = [
                "CODE" => strtoupper($pair),
                "NAME" => "none"
            ];
        }

        return $result;
    }

    public function getHitbtcCurrency($url) {
        $response = static::getResponse($url);
        $result = [];
        foreach ($response as $coin) {
            $result[$coin->id] = [
                "CODE" => $coin->id,
                "NAME" => $coin->fullName
            ];
        }

        return $result;
    }

    public function getHitbtcCurrencySymbol($url) {
        $result = [];
        $response = static::getResponse($url);
        foreach ($response as $pair) {
            $result[$pair->id] = [
                "CODE" => $pair->id,
                "NAME" => $pair->baseCurrency . " / " . $pair->quoteCurrency
            ];
        }

        return $result;
    }

    public function getQuoinexCurrency($url) {
        $result = [];
        $response = static::getResponse($url);
        foreach ($response as $pair) {
            $result[$pair->currency_pair_code] = [
                "CODE" => $pair->currency_pair_code,
                "NAME" => $pair->base_currency . " / " . $pair->quoted_currency
            ];
        }

        return $result;
    }

    public function getWexCurrency($url) {
        $result = [];
        $response = static::getResponse($url);
        foreach ($response->pairs as $pair => $coin) {
            $result[$pair] = [
                "CODE" => strtoupper($pair),
                "NAME" => "none"
            ];
        }

        return $result;
    }

    public function getExmoComCurrency($url) {
        $result = [];
        $response = static::getResponse($url);
        foreach ($response as $coin) {
            $result[$coin] = [
                "CODE" => $coin,
                "NAME" => "none"
            ];
        }

        return $result;
    }

    public function getTherocktradingCurrency($url) {
        $result = [];
        $response = static::getResponse($url);
        foreach ($response->funds as $pair) {
            $result[$pair->id] = [
                "CODE" => $pair->id,
                "NAME" => $pair->base_currency . " / " . $pair->trade_currency
            ];
        }

        return $result;
    }

    public function getMercatoxCurrency($url) {
        $response = static::getResponse($url);
        $result = [];
        foreach ($response->pairs as $pair => $coin) {
            $result[$pair] = [
                "CODE" => $pair,
                "NAME" => "none"
            ];
        }

        return $result;
    }

    public function getTidexCurrency($url) {
        $result = [];
        $response = static::getResponse($url);
        foreach ($response->pairs as $pair => $info) {
            $result[strtoupper($pair)] = [
                "CODE" => strtoupper($pair),
                "NAME" => "none"
            ];
        }

        return $result;
    }

    public function getAcxCurrency($url) {
        $result = [];
        $response = static::getResponse($url);
        foreach ($response as $pair) {
            $result[strtoupper($pair->id)] = [
                "CODE" => strtoupper($pair->id),
                "NAME" => $pair->name
            ];
        }

        return $result;
    }

    public function getAbucoinsCurrency($url) {
        $result = [];
        $response = static::getResponse($url);
        foreach ($response as $pair) {
            $result[$pair->id] = [
                "CODE" => $pair->id,
                "NAME" => $pair->display_name
            ];
        }

        return $result;
    }

    public function getBtcalphaCurrency($url) {
        $result = [];
        $response = static::getResponse($url);
        foreach ($response as $pair) {
            $result[$pair->name] = [
                "CODE" => $pair->name,
                "NAME" => "none"
            ];
        }

        return $result;
    }

    public function getBitzCurrency($url) {
        $result = [];
        $response = static::getResponse($url);
        foreach ($response->data as $pair => $info) {
            $result[strtoupper($pair)] = [
                "CODE" => strtoupper($pair),
                "NAME" => "none"
            ];
        }

        return $result;
    }

    public function getCexioCurrency($url) {
        $result = [];
        $response = static::getResponse($url);
        foreach ($response->data->pairs as $pair) {
            $result[strtoupper($pair->symbol1 . $pair->symbol2)] = [
                "CODE" => strtoupper($pair->symbol1 . $pair->symbol2),
                "NAME" => "none"
            ];
        }

        return $result;
    }

    public function getCoinexchangeIoCurrency($url) {
        $result = [];
        $response = static::getResponse($url);
        foreach ($response->result as $coin) {
            $result[$coin->MarketAssetCode] = [
                "CODE" => $coin->MarketAssetCode,
                "NAME" => $coin->MarketAssetName
            ];
        }

        return $result;
    }

    public function getCoinroomComCurrency($url) {
        $result = [];
        $response = static::getResponse($url);
        foreach ($response->crypto as $coin) {
            $result[$coin] = [
                "CODE" => $coin,
                "NAME" => "none"
            ];
        }

        return $result;
    }

    public function getCryptopiaCoNzCurrency($url) {
        $result = [];
        $response = static::getResponse($url);
        foreach ($response->Data as $coin) {
            $result[$coin->Symbol] = [
                "CODE" => $coin->Symbol,
                "NAME" => $coin->Name
            ];
        }

        return $result;
    }

    public function getDsxUkCurrency($url) {
        $result = [];
        $response = static::getResponse($url);
        foreach ($response->pairs as $coin => $info) {
            $result[strtoupper($coin)] = [
                "CODE" => strtoupper($coin),
                "NAME" => "none"
            ];
        }

        return $result;
    }

    public function getGateIoCurrency($url) {
        $result = [];
        $response = static::getResponse($url);
        foreach ($response as $pair) {
            $result[strtoupper($pair)] = [
                "CODE" => strtoupper($pair),
                "NAME" => "none"
            ];
        }

        return $result;
    }

    public function getIndependentreserveComCurrency($url) {
        $result = [];
        $response = array_merge(
            static::getResponse($url . "GetValidPrimaryCurrencyCodes"),
            static::getResponse($url . "GetValidSecondaryCurrencyCodes")
        );

        foreach ($response as $coin) {
            $result[strtoupper($coin)] = [
                "CODE" => strtoupper($coin),
                "NAME" => "none"
            ];
        }

        return $result;
    }

    public function getLitebitEuCurrency($url) {
        $result = [];
        $response = static::getResponse($url);
        foreach ($response->result as $coin) {
            $result[strtoupper($coin->abbr)] = [
                "CODE" => strtoupper($coin->abbr),
                "NAME" => $coin->name
            ];
        }

        return $result;
    }

    public function getLivecoinNetCurrency($url) {
        $result = [];
        $response = static::getResponse($url);
        foreach ($response->info as $coin) {
            $result[$coin->symbol] = [
                "CODE" => $coin->symbol,
                "NAME" => $coin->name
            ];
        }

        return $result;
    }

    public function getNeraexComCurrency($url) {
        $result = [];
        $response = static::getResponse($url);
        foreach ($response as $coin) {
            $result[strtoupper($coin->id)] = [
                "CODE" => strtoupper($coin->id),
                "NAME" => $coin->name
            ];
        }

        return $result;
    }
}