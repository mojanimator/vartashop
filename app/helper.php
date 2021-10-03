<?php

Class Helper
{


    static $lang = 'fa';
    static $admin = '@develowper';
    static $app_version = 1;
    static $initScore = 5;
    static $test = true;
    static $Dev = [72534783, /*225594412, 871016407, 225594412*/]; // آیدی عددی ادمین را از بات @userinfobot بگیرید
    static $logs = [72534783, /*225594412*/];
    static $admins = [1 => ['username' => '@develowper', 'chat_id' => 72534783], 2 => ['username' => '@fazelbabaeirudsari', 'chat_id' => 225594412],];

    static $chargeProductsLink = 'https://chr724.ir/services/v3/EasyCharge/initializeData';
    static $directChargeLink = 'https://chr724.ir/services/v3/EasyCharge/topup';
    static $directInternetLink = 'https://chr724.ir/services/v3/EasyCharge/internetRecharge';
    static $chargeLink = "https://vartastudio.ir/charge";
    static $donateLink = "https://idpay.ir/vartastudio";
    static $idPayDonateServiceLink = "https://api.idpay.ir/v1.1/payment";
    static $idPayDonateServiceVerifyLink = "https://api.idpay.ir/v1.1/payment/verify";
    static $bot = "@vartashopbot";
    static $site = "https://vartashop.ir";
    static $admin_username = "@develowper";
    static $admin_phone = "09398793845";
    static $bot_id = "2035748168";
    static $app_link = "https://play.google.com/store/apps/developer?id=Varta+Studio";
    static $channel = "@vartashop"; // ربات را ادمین کانال کنید
    static $info = "\n\n👦[Admin 1](instagram.com/develowper)\n👱[Admin 2](tg://user?id=72534783)\n\n🏠[vartastudiobot](https://telegram.me/vartastudiobot) " . "\n📸 *instagram.com/vartastudio*";
    static $info_en = "\n\n👦[Admin 1](instagram.com/develowper)\n👱[Admin 2](tg://user?id=72534783)\n\n🏠[vartastudio_bot](https://telegram.me/vartastudio_bot) " . "\n📸 *instagram.com/vartastudio*";
    static $auth_config_file = 'pc-api-6543717008018024666-973-afa7325affc8.json';

}


function orderNameFromId($id)
{

    switch ($id) {
        case 0:
            return 'سبد خرید';
            break;
        case 1:
            return 'در انتظار پرداخت';
            break;
        case 2:
            return 'در حال پردازش';
            break;
        case 3:
            return 'آماده ارسال';
            break;
        case 4:
            return 'ارسال شده';
            break;

    }
}

function sendTelegramMessage($chat_id, $text, $mode = null, $reply = null, $keyboard = null, $disable_notification = false, $app_id = null)
{

    return creator('sendMessage', [
        'chat_id' => $chat_id,
        'text' => $text,
        'parse_mode' => $mode,
        'reply_to_message_id' => $reply,
        'reply_markup' => $keyboard,
        'disable_notification' => $disable_notification,
    ]);


}

function deleteTelegramMessage($chatid, $massege_id)
{
    return creator('DeleteMessage', [
        'chat_id' => $chatid,
        'message_id' => $massege_id
    ]);
}

function creator($method, $datas = [])
{
    $url = "https://api.telegram.org/bot" . env('TELEGRAM_BOT_TOKEN', 'YOUR-BOT-TOKEN') . "/" . $method;
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $datas);
    $res = curl_exec($ch);
    $res = json_decode($res);
    if ($res && $res->ok == false)
        sendTelegramMessage(Helper::$logs[0], /*"[" . $datas['chat_id'] . "](tg://user?id=" . $datas['chat_id'] . ") \n" .*/
            json_encode($datas) . "\n" . $res->description, null, null, null);

//        Helper::sendMessage(Helper::$logs[0], ..$res->description, null, null, null);
    if (curl_error($ch)) {
        sendTelegramMessage(Helper::$logs[0], 'curl error' . PHP_EOL . json_encode(curl_error($ch)), null, null, null);
        var_dump(curl_error($ch));
        return null;
    } else {
        return $res;
    }
}
