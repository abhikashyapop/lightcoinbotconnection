<?php

$botToken = "7272123353:AAGkYAX8DR-tH3TYRmLy91VEOG7HaYSKi_M";
$website = "https://api.telegram.org/bot" . $botToken;

$update = file_get_contents("php://input");
$updateArray = json_decode($update, TRUE);

$chatId = $updateArray["message"]["chat"]["id"];
$message = $updateArray["message"]["text"];

switch ($message) {
    case "/start":
        sendWelcomeMessage($chatId);
        break;
    default:
        sendMessage($chatId, "I don't understand that command.");
        break;
}

function sendWelcomeMessage($chatId)
{
    global $website;

    $keyboard = [
        'inline_keyboard' => [
            [
                ['text' => 'LIGHTCOIN', 'callback_data' => 'lightcoin'],
            ],
            [
                ['text' => 'Join Community', 'url' => 'https://t.me/LightCoinNetwork'],
            ],
            [
                ['text' => 'Invite Friend', 'url' => 'https://t.me/share/url?url=https://t.me/LightCoinRobot'],
            ],
        ]
    ];

    $replyMarkup = json_encode($keyboard);

    $text = "Welcome to oogincoij bot ! We are delighted to have you join our esteemed mining community. At Light Coin, we are committed to providing you with a seamless and efficient mining experience./n/n/n👛Wallet Feature Coming Soon:- Stay tuned for exciting updates as we enhance our platform./n/n/n⛏️Begin Mining:- Start earning with ease and watch your rewards grow./n/nJoin Our Community:- Connect with fellow miners and stay informed at @LightCoinNetwork./n/nBest regards,/nThe Light Coin Team ✅";
    $url = $website . "/sendMessage?chat_id=" . $chatId . "&text=" . urlencode($text) . "&reply_markup=" . $replyMarkup;

    file_get_contents($url);
}

function sendMessage($chatId, $text, $replyMarkup = null)
{
    global $website;
    $url = $website . "/sendMessage?chat_id=" . $chatId . "&text=" . urlencode($text);
    if ($replyMarkup) {
        $url .= "&reply_markup=" . $replyMarkup;
    }
    file_get_contents($url);
}

function handleCallbackQuery($callbackQuery)
{
    global $website;

    $callbackId = $callbackQuery["id"];
    $chatId = $callbackQuery["message"]["chat"]["id"];
    $data = $callbackQuery["data"];

    switch ($data) {
        case 'lightcoin':
            $keyboard = [
                'inline_keyboard' => [
                    [
                        ['text' => 'Open LIGHTCOIN', 'url' => 'https://lightcoin.com'],
                    ]
                ]
            ];
            $replyMarkup = json_encode($keyboard);
            sendMessage($chatId, "Click the button below to open LIGHTCOIN in Telegram:", $replyMarkup);
            break;
        default:
            sendMessage($chatId, "I don't understand that command.");
            break;
    }

    // Acknowledge the callback query
    $url = $website . "/answerCallbackQuery?callback_query_id=" . $callbackId;
    file_get_contents($url);
}

if (isset($updateArray["callback_query"])) {
    handleCallbackQuery($updateArray["callback_query"]);
}

?>
