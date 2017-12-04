<?php

namespace zkr\lib;


class Currency {

    public $logFile = __DIR__ . '/../log.txt';
    public $errorFile = __DIR__ . '/../error.txt';
    public $cookieFile = __DIR__ . "/../tmp/cookie.txt";

    private function getResponse($url) {
        $error = "";

        $headers = ["Content-Type: application/json;charset=UTF-8"];

        $ch = curl_init($url);
        $options = [
//            CURLOPT_HEADER => true,
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_USERAGENT  => "Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:57.0) Gecko/20100101 Firefox/57.0",

            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_MAXREDIRS      => 0,

            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false,

            CURLOPT_CONNECTTIMEOUT => 5,
            CURLOPT_TIMEOUT        => 10,

            CURLOPT_COOKIEJAR  => $this->cookieFile,
            CURLOPT_COOKIEFILE => $this->cookieFile,
//            CURLOPT_COOKIESESSION => true,
        ];
        curl_setopt_array($ch, $options);

        $raw_response = curl_exec($ch);
        $response = json_decode($raw_response);

        // If the status is not 200, something is wrong
        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        // If there was no error, this will be an empty string
        $curl_error = curl_error($ch);

        curl_close($ch);

        file_put_contents($this->cookieFile, "");

        if ($status != 200) {
            // If node didn't return a nice error message, we need to make our own
            switch ($status) {
                case 400:
                    $error = 'HTTP_BAD_REQUEST';
                    break;
                case 401:
                    $error = 'HTTP_UNAUTHORIZED';
                    break;
                case 403:
                    $error = 'HTTP_FORBIDDEN';
                    break;
                case 404:
                    $error = 'HTTP_NOT_FOUND';
                    break;
                default:
                    $error = $status;
            }
        }

        if (!empty($curl_error) || $status != 200) {
            $error = date("d-m-Y H:i:s") . " " . $url . " "
                . $error . "  " . $curl_error . PHP_EOL;
            file_put_contents($this->errorFile, $error, FILE_APPEND);

            return false;
        }

        return $response;
    }

    public function getBittrexCurrency($url) {
        $result = [];
        $response = static::getResponse($url);
        if ($response->success) {
            foreach ($response->result as $coin) {
//            if ($coin->IsActive) {
                $result[$coin->Currency] = [
                    "CODE" => $coin->Currency,
                    "NAME" => $coin->CurrencyLong
                ];
//            }
            }
        }

        return $result;
    }

    public function getPoloniexCurrency($url) {
        $result = [];
        $response = static::getResponse($url);
        if (empty($response->error)) {
            foreach ($response as $code => $coin) {
                if ($coin->disabled == 0 || $coin->forzen == 0) {
                    $result[$code] = [
                        "CODE" => $code,
                        "NAME" => $coin->name
                    ];
                }
            }
        }

        return $result;
    }

    public function getKrakenCurrency($url) {
        $result = [];
        $response = static::getResponse($url);
        if (empty($response->error)) {
            foreach ($response->result as $code => $coin) {
                $result[$code] = [
                    "CODE" => $code,
                    "NAME" => $coin->altname
                ];
            }
        }

        return $result;
    }

    public function getBinanceCurrency($url) {
        $result = [];
        $response = static::getResponse($url);
        if (!empty($response)) {
            foreach ($response as $coin) {
                $result[$coin->symbol] = [
                    "CODE" => $coin->symbol,
                    "NAME" => "none"
                ];
            }
        }

        return $result;
    }

    public function getBitfinexCurrency($url) {
        $result = [];
        $response = static::getResponse($url);
        if (!empty($response)) {
            foreach ($response as $coin) {
                $result[strtoupper($coin)] = [
                    "CODE" => strtoupper($coin),
                    "NAME" => "none"
                ];
            }
        }

        return $result;
    }

    public function getLiquiCurrency($url) {
        $result = [];
        $response = static::getResponse($url);
        if (!empty($response)) {
            foreach ($response->pairs as $code => $coin) {
                $result[strtoupper($code)] = [
                    "CODE" => strtoupper($code),
                    "NAME" => "none"
                ];
            }
        }

        return $result;
    }

    public function getBitstampCurrency($url) {
        $result = [];
        $response = static::getResponse($url);
        var_dump($response);
        if (!empty($response)) {
            foreach ($response as $pairs) {
                $result[$pairs->name] = [
                    "CODE" => $pairs->name,
                    "NAME" => $pairs->description
                ];
            }
        }

        return $result;
    }

    public function getBithumbCurrency($url) {
        $result = [];
        $response = static::getResponse($url);
        if ($response->status == "0000") {
            foreach ($response->data as $code => $coin) {
                $result[$code] = [
                    "CODE" => strtoupper($code),
                    "NAME" => "none"
                ];
            }
        }

        return $result;
    }

    public function getGdaxCurrency($url) {
        $result = [];
        $response = static::getResponse($url);
        if (empty($response->message)) {
            foreach ($response as $coin) {
                $result[$coin->id] = [
                    "CODE" => $coin->id,
                    "NAME" => $coin->name
                ];
            }
        }

        return $result;
    }

    public function getGeminiCurrency($url) {
        $result = [];
        $response = static::getResponse($url);
        if ($response->result !== 'error') {
            foreach ($response as $pair) {
                $result[strtoupper($pair)] = [
                    "CODE" => strtoupper($pair),
                    "NAME" => "none"
                ];
            }
        }

        return $result;
    }

    public function getHitbtcCurrency($url) {
        $response = static::getResponse($url);
        $result = [];
        if (!empty($response)) {
            foreach ($response as $coin) {
                $result[$coin->id] = [
                    "CODE" => $coin->id,
                    "NAME" => $coin->fullName
                ];
            }
        }

        return $result;
    }

    public function getHitbtcCurrencySymbol($url) {
        $result = [];
        $response = static::getResponse($url);
        foreach ($response as $pair) {
            $result[$pair->id] = [
                "CODE" => $pair->id,
                "NAME" => $pair->baseCurrency . "/" . $pair->quoteCurrency
            ];
        }

        return $result;
    }

    public function getQuoinexCurrency($url) {
        $result = [];
        $response = static::getResponse($url);
        if (!empty($response)) {
            foreach ($response as $pair) {
                $result[$pair->currency_pair_code] = [
                    "CODE" => $pair->currency_pair_code,
                    "NAME" => $pair->base_currency . "/" . $pair->quoted_currency
                ];
            }
        }

        return $result;
    }

    public function getWexCurrency($url) {
        $result = [];
        $response = static::getResponse($url);
        if ($response->server_time) {
            foreach ($response->pairs as $pair => $coin) {
                $result[strtoupper($pair)] = [
                    "CODE" => strtoupper($pair),
                    "NAME" => "none"
                ];
            }
        }

        return $result;
    }

    public function getExmoComCurrency($url) {
        $result = [];
        $response = static::getResponse($url);
        if (empty($response->error)) {
            foreach ($response as $coin) {
                $result[$coin] = [
                    "CODE" => $coin,
                    "NAME" => "none"
                ];
            }
        }

        return $result;
    }

    public function getTherocktradingCurrency($url) {
        $result = [];
        $response = static::getResponse($url);
        if (!empty($response)) {
            foreach ($response->funds as $pair) {
                $result[$pair->id] = [
                    "CODE" => $pair->id,
                    "NAME" => $pair->base_currency . "/" . $pair->trade_currency
                ];
            }
        }

        return $result;
    }

    public function getMercatoxCurrency($url) {
        $result = [];
        $response = static::getResponse($url);
        if (!empty($response)) {
            foreach ($response->pairs as $pair => $coin) {
                $result[$pair] = [
                    "CODE" => $pair,
                    "NAME" => "none"
                ];
            }
        }

        return $result;
    }

    public function getTidexCurrency($url) {
        $result = [];
        $response = static::getResponse($url);
        if (!empty($response)) {
            foreach ($response->pairs as $pair => $info) {
                $result[strtoupper($pair)] = [
                    "CODE" => strtoupper($pair),
                    "NAME" => "none"
                ];
            }
        }

        return $result;
    }

    public function getAcxCurrency($url) {
        $result = [];
        $response = static::getResponse($url);
        if (!empty($response)) {
            foreach ($response as $pair) {
                $result[strtoupper($pair->id)] = [
                    "CODE" => strtoupper($pair->id),
                    "NAME" => $pair->name
                ];
            }
        }

        return $result;
    }

    public function getAbucoinsCurrency($url) {
        $result = [];
        $response = static::getResponse($url);
        if ($response->message !== 'Route not found') {
            foreach ($response as $pair) {
                $result[$pair->id] = [
                    "CODE" => $pair->id,
                    "NAME" => $pair->display_name
                ];
            }
        }

        return $result;
    }

    public function getBtcalphaCurrency($url) {
        $result = [];
        $response = static::getResponse($url);
        if (!empty($response)) {
            foreach ($response as $pair) {
                $result[$pair->name] = [
                    "CODE" => $pair->name,
                    "NAME" => "none"
                ];
            }
        }

        return $result;
    }

    public function getBitzCurrency($url) {
        $result = [];
        $response = static::getResponse($url);
        if ($response->msg == "Success") {
            foreach ($response->data as $pair => $info) {
                $result[strtoupper($pair)] = [
                    "CODE" => strtoupper($pair),
                    "NAME" => "none"
                ];
            }
        }

        return $result;
    }

    public function getCexioCurrency($url) {
        $result = [];
        $response = static::getResponse($url);
        if ($response->ok == "ok") {
            foreach ($response->data->pairs as $pair) {
                $result[strtoupper($pair->symbol1 . $pair->symbol2)] = [
                    "CODE" => strtoupper($pair->symbol1 . $pair->symbol2),
                    "NAME" => "none"
                ];
            }
        }

        return $result;
    }

    public function getCoinexchangeIoCurrency($url) {
        $result = [];
        $response = static::getResponse($url);
        if ($response->success == "1") {
            foreach ($response->result as $coin) {
                $result[$coin->MarketAssetCode] = [
                    "CODE" => $coin->MarketAssetCode,
                    "NAME" => $coin->MarketAssetName
                ];
            }
        }

        return $result;
    }

    public function getCoinroomComCurrency($url) {
        $result = [];
        $response = static::getResponse($url);
        if (!empty($response)) {
            foreach ($response->crypto as $coin) {
                $result[$coin] = [
                    "CODE" => $coin,
                    "NAME" => "none"
                ];
            }
        }

        return $result;
    }

    public function getCryptopiaCoNzCurrency($url) {
        $result = [];
        $response = static::getResponse($url);
        if ($response->Success) {
            foreach ($response->Data as $coin) {
                $result[$coin->Symbol] = [
                    "CODE" => $coin->Symbol,
                    "NAME" => $coin->Name
                ];
            }
        }

        return $result;
    }

    public function getDsxUkCurrency($url) {
        $result = [];
        $response = static::getResponse($url);
        if (empty($response->error)) {
            foreach ($response->pairs as $coin => $info) {
                $result[strtoupper($coin)] = [
                    "CODE" => strtoupper($coin),
                    "NAME" => "none"
                ];
            }
        }

        return $result;
    }

    public function getGateIoCurrency($url) {
        $result = [];
        $response = static::getResponse($url);
        if (empty($response->code)) {
            foreach ($response as $pair) {
                $result[strtoupper($pair)] = [
                    "CODE" => strtoupper($pair),
                    "NAME" => "none"
                ];
            }
        }

        return $result;
    }

    public function getIndependentreserveComCurrency($url) {
        $result = [];
        $response = array_merge(
            static::getResponse($url . "GetValidPrimaryCurrencyCodes"),
            static::getResponse($url . "GetValidSecondaryCurrencyCodes")
        );
        if (!empty($response)) {
            foreach ($response as $coin) {
                $result[strtoupper($coin)] = [
                    "CODE" => strtoupper($coin),
                    "NAME" => "none"
                ];
            }
        }

        return $result;
    }

    public function getLitebitEuCurrency($url) {
        $result = [];
        $response = static::getResponse($url);
        if ($response->success) {
            foreach ($response->result as $coin) {
                $result[strtoupper($coin->abbr)] = [
                    "CODE" => strtoupper($coin->abbr),
                    "NAME" => $coin->name
                ];
            }
        }

        return $result;
    }

    public function getLivecoinNetCurrency($url) {
        $result = [];
        $response = static::getResponse($url);
        if ($response->success) {
            foreach ($response->info as $coin) {
                $result[$coin->symbol] = [
                    "CODE" => $coin->symbol,
                    "NAME" => $coin->name
                ];
            }
        }

        return $result;
    }

    public function getNeraexComCurrency($url) {
        $result = [];
        $response = static::getResponse($url);
        if (!empty($response)) {
            foreach ($response as $coin) {
                $result[strtoupper($coin->id)] = [
                    "CODE" => strtoupper($coin->id),
                    "NAME" => $coin->name
                ];
            }
        }

        return $result;
    }

    public function getQuadrigacxComCurrency($url) {
        $result = [];
        $response = static::getResponse($url);
        if (!empty($response)) {
            foreach ($response as $coin => $info) {
                $result[strtoupper($coin)] = [
                    "CODE" => strtoupper($coin),
                    "NAME" => "none"
                ];
            }
        }

        return $result;
    }

    public function getYobitNetCurrency($url) {
        $result = [];
        $response = static::getResponse($url);
        if (!empty($response->pairs)) {
            foreach ($response->pairs as $pair => $info) {
                $result[strtoupper($pair)] = [
                    "CODE" => strtoupper($pair),
                    "NAME" => "none"
                ];
            }
        }

        return $result;
    }

    public function getZbComCurrency($url) {
        $result = [];
        $response = static::getResponse($url);
        if (!empty($response)) {
            foreach ($response as $pair => $info) {
                $result[strtoupper($pair)] = [
                    "CODE" => strtoupper($pair),
                    "NAME" => "none"
                ];
            }
        }

        return $result;
    }

    public function getZaifJpCurrency($url) {
        $result = [];
        $response = static::getResponse($url);
        if (!empty($response)) {
            foreach ($response as $coin) {
                $result[strtoupper($coin->name)] = [
                    "CODE" => strtoupper($coin->name),
                    "NAME" => "none"
                ];
            }
        }

        return $result;
    }

    public function getGatecoinComCurrency($url) {
        $result = [];
        $response = static::getResponse($url);
        if (!empty($response->tickers)) {
            foreach ($response->tickers as $pair) {
                $result[$pair->currencyPair] = [
                    "CODE" => $pair->currencyPair,
                    "NAME" => "none"
                ];
            }
        }

        return $result;
    }

    public function getKucoinComCurrency($url) {
        $result = [];
        $response = static::getResponse($url);
        if ($response->success) {
            foreach ($response->data->currencies as $coin) {
                $result[$coin[0]] = [
                    "CODE" => $coin[0],
                    "NAME" => $coin[1]
                ];
            }
        }

        return $result;
    }

    public function getLykkeComCurrency($url) {
        $result = [];
        $response = static::getResponse($url);
        if (!empty($response)) {
            foreach ($response as $pair) {
                $result[$pair->Id] = [
                    "CODE" => $pair->Id,
                    "NAME" => $pair->Name
                ];
            }
        }

        return $result;
    }

    public function getBitsaneComCurrency($url) {
        $result = [];
        $response = static::getResponse($url);
        if (!empty($response)) {
            foreach ($response as $pair => $info) {
                $result[$pair] = [
                    "CODE" => $pair,
                    "NAME" => "none"
                ];
            }
        }

        return $result;
    }

    public function getBleutradeComCurrency($url) {
        $result = [];
        $response = static::getResponse($url);
        if ($response->success == "true") {
            foreach ($response->result as $coin) {
                $result[$coin->Currency] = [
                    "CODE" => $coin->Currency,
                    "NAME" => $coin->CurrencyLong
                ];
            }
        }

        return $result;
    }

    public function getBraziliexComCurrency($url) {
        $result = [];
        $response = static::getResponse($url);
        if (!empty($response)) {
            foreach ($response as $currency => $info) {
                $result[strtoupper($currency)] = [
                    "CODE" => strtoupper($currency),
                    "NAME" => $info->name
                ];
            }
        }

        return $result;
    }

    public function getCryptomateCoUkCurrency($url) {
        $result = [];
        $response = static::getResponse($url);
        if (!empty($response)) {
            foreach ($response as $currency => $info) {
                $result[$currency] = [
                    "CODE" => $currency,
                    "NAME" => "none"
                ];
            }
        }

        return $result;
    }

    public function getKunaIoCurrency($url) {
        $result = [];
        $response = static::getResponse($url);
        if (!empty($response)) {
            foreach ($response as $pair => $info) {
                $result[strtoupper($pair)] = [
                    "CODE" => strtoupper($pair),
                    "NAME" => "none"
                ];
            }
        }

        return $result;
    }

    public function getNovaexchangeComCurrency($url) {
        $result = [];
        $response = static::getResponse($url);
        if (!empty($response->status)) {
            foreach ($response->markets as $pair) {
                $result[$pair->marketname] = [
                    "CODE" => $pair->marketname,
                    "NAME" => "none"
                ];
            }
        }

        return $result;
    }

    public function getCcexComCurrency($url) {
        $result = [];
        $response = static::getResponse($url);
        if (!empty($response)) {
            foreach ($response as $code => $name) {
                $result[strtoupper($code)] = [
                    "CODE" => strtoupper($code),
                    "NAME" => $name
                ];
            }
        }

        return $result;
    }

    public function getExxComCurrency($url) {
        $result = [];
        $response = static::getResponse($url);
        if (!empty($response)) {
            foreach ($response as $pair => $info) {
                $result[strtoupper($pair)] = [
                    "CODE" => strtoupper($pair),
                    "NAME" => "none"
                ];
            }
        }

        return $result;
    }

    public function getBitsoComCurrency($url) {
        $result = [];
        $response = static::getResponse($url);
        if ($response->success) {
            foreach ($response->payload as $pair) {
                $result[strtoupper($pair->book)] = [
                    "CODE" => strtoupper($pair->book),
                    "NAME" => "none"
                ];
            }
        }

        return $result;
    }

    public function getMarketsBisqNetworkCurrency($url) {
        $result = [];
        $response = static::getResponse($url);
        if (!empty($response)) {
            foreach ($response as $pair) {
                $result[strtoupper($pair->pair)] = [
                    "CODE" => strtoupper($pair->pair),
                    "NAME" => $pair->name
                ];
            }
        }

        return $result;
    }

    public function getVircurexComCurrency($url) {
        $result = [];
        $response = static::getResponse($url);
        if ($response->status == 0) {
            foreach ($response as $pair => $info) {
                if ($pair != 'status') {
                    $result[strtoupper($pair)] = [
                        "CODE" => strtoupper($pair),
                        "NAME" => "none"
                    ];
                }
            }
        }

        return $result;
    }

    public function getSouthxchangeComCurrency($url) {
        $result = [];
        $response = static::getResponse($url);
        if (!empty($response)) {
            foreach ($response as $pair) {
                $result[$pair[0] . $pair[1]] = [
                    "CODE" => $pair[0] . $pair[1],
                    "NAME" => $pair[0] . "/" . $pair[1]
                ];
            }
        }

        return $result;
    }

    public function getBitgrailComCurrency($url) {
        $result = [];
        $response = static::getResponse($url);
        var_dump($response);
        if ($response->success == 1) {
            foreach ($response->response as $pairs) {
                foreach ($pairs as $pair) {
                    $result[$pair->market] = [
                        "CODE" => $pair->market,
                        "NAME" => "none"
                    ];
                }
            }
        }

        return $result;
    }

    public function getCoinoneCoKrCurrency($url) {
        $result = [];
        $response = static::getResponse($url);
        if ($response->result == "success") {
            foreach ($response as $info) {
                if ($info->currency) {
                    $result[strtoupper($info->currency)] = [
                        "CODE" => strtoupper($info->currency),
                        "NAME" => "none"
                    ];
                }
            }
        }

        return $result;
    }

    public function getTestCurrency($url) {
        $result = [];
        $response = static::getResponse($url);
        var_dump($response);

        /*
        if ($response->success == 1) {
            foreach ($response->response as $pairs) {
                foreach ($pairs as $pair) {
                    $result[$pair->market] = [
                        "CODE" => $pair->market,
                        "NAME" => "none"
                    ];
                }
            }
        }
        */

        return $result;
    }
}