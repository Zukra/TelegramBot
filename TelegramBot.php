<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 28.11.2017
 * Time: 16:19
 */

namespace TelegramBot;


class TelegramBot {
    protected $token = "491206841:AAH8wHuoCrTJ7oq5TjrCMHKrPkd7e9z4QSA";
    protected $urlBasic = "https://api.telegram.org/bot";
    protected $chatId; // 412846761
    protected $updateId;

    public function query($method, $params = []) {
        $url = $this->urlBasic . $this->token . "/" . $method;
        $url .= (!empty($params) ? "?" . http_build_query($params) : "");
        $result = json_decode(file_get_contents($url));

        return $result;
    }

    public function getUpdates() {
        $response = $this->query("getUpdates", ["offset" => $this->updateId + 1]);
        // дабы отвечал только на последнее сообщение
        if (!empty($response->result)) {
            $this->updateId = $response->result[count($response->result) - 1]->update_id;
        }

        return $response->result;
    }

    public function getChatId() {
        $this->chatId = $this->query("getUpdates", ["limit" => 1])
            ->result[0]->message->chat->id;

        return $this->chatId;
    }

    public function sendMessage($chatId, $msg) {

        return $this->query("sendMessage", ["chat_id" => $chatId, "text" => $msg]);
    }
}