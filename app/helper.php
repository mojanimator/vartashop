<?php

use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class Helper
{
    static $create_shop_score = 50;
    static $create_product_score = 5;

    static $lang = 'fa';
    static $admin = '@develowper';
    static $app_version = 1;
    static $initScore = 5;
    static $vip_limit = 20;
    static $product_image_limit = 5;
    static $test = true;
    static $Devs = [72534783, 225594412, 167069519/*225594412, 871016407, 225594412*/]; // آیدی عددی ادمین را از بات @userinfobot بگیرید
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


function orderNameFromId($id, $admin = false)
{

    switch ($id) {
        case 0:
            return 'سبد خرید';
            break;
        case 1:
            return $admin ? 'نیاز به پردازش' : 'در حال پردازش';

            break;
        case 2:
            return $admin ? 'در انتظار پرداخت' : 'نیاز به پرداخت';
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

function sendTelegramPhoto($chat_id, $photo, $caption, $reply = null, $keyboard = null)
{


    return creator('sendPhoto', [
        'chat_id' => $chat_id,
        'photo' => $photo,
        'caption' => $caption,
        'parse_mode' => 'Markdown',
        'reply_to_message_id' => $reply,
        'reply_markup' => $keyboard
    ]);

}

function createChatImage($photo, $shop_id)
{


    if (!isset($photo) || !isset($photo->big_file_id)) return null;

    $timestamp = Carbon::now()->timestamp;

    $client = new \GuzzleHttp\Client();
    $res = creator('getFile', [
        'file_id' => $photo->big_file_id,

    ])->result->file_path;

    $image = "https://api.telegram.org/file/bot" . env('TELEGRAM_BOT_TOKEN', 'YOUR-BOT-TOKEN') . "/" . $res;
    if (Storage::exists("public/shops/$shop_id.jpg")) {
        Storage::delete("public/shops/$shop_id.jpg");
    }
    Storage::put("public/shops/$shop_id.jpg", $client->get($image)->getBody());


//    $img = \Intervention\Image\Facades\Image::make(storage_path("app/public/chats/$timestamp.jpg"));
//    $img2 = \Intervention\Image\Facades\Image::make(storage_path("app/public/magnetgramcover.png"));
//    $img2->resize($img->width(), $img->height());
//    $img->insert($img2, 'center');
//    $img->save(storage_path("app/public/chats/$timestamp.jpg"));

    return $timestamp;

}

function sendTelegramMediaGroup($chat_id, $media, $keyboard = null, $reply = null)
{
//2 to 10 media can be send

    return creator('sendMediaGroup', [
        'chat_id' => $chat_id,
        'media' => json_encode($media),
        'reply_to_message_id' => $reply,

    ]);

}

function sendTelegramSticker($chat_id, $file_id, $keyboard, $reply = null, $set_name = null)
{
    return creator('sendSticker', [
        'chat_id' => $chat_id,
        'sticker' => $file_id,
        "set_name" => $set_name,
        'reply_to_message_id' => $reply,
        'reply_markup' => $keyboard
    ]);
}

function creator2($method, $datas = [])
{

    $datas['method'] = $method;
    $datas['token'] = env('TELEGRAM_BOT_TOKEN');

    $url = "https://qr-image-creator.com/magnetgram_en/api/telegram";
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

function logAdmins($msg, $mode = null)
{
    foreach (Helper::$logs as $log)
        sendTelegramMessage($log, $msg, $mode);

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

function MarkDown($string)
{
    $string = str_replace(["_",], '\_', $string);
    $string = str_replace(["`",], '\`', $string);
    $string = str_replace(["*",], '\*', $string);
    $string = str_replace(["~",], '\~', $string);


    return $string;
}

function validate_base64($base64data, array $allowedMime)
{
    // strip out data uri scheme information (see RFC 2397)
    if (strpos($base64data, ';base64') !== false) {
        list(, $base64data) = explode(';', $base64data);
        list(, $base64data) = explode(',', $base64data);
    }

    // strict mode filters for non-base64 alphabet characters
    if (base64_decode($base64data, true) === false) {
        return false;
    }

    // decoding and then reeconding should not change the data
    if (base64_encode(base64_decode($base64data)) !== $base64data) {
        return false;
    }

    $binaryData = base64_decode($base64data);

    // temporarily store the decoded data on the filesystem to be able to pass it to the fileAdder
    $tmpFile = tempnam(sys_get_temp_dir(), 'medialibrary');
    file_put_contents($tmpFile, $binaryData);

    // guard Against Invalid MimeType
    $allowedMime = array_flatten($allowedMime);

    // no allowedMimeTypes, then any type would be ok
    if (empty($allowedMime)) {
        return true;
    }

    // Check the MimeTypes
    $validation = Illuminate\Support\Facades\Validator::make(
        ['file' => new Illuminate\Http\File($tmpFile)],
        ['file' => 'mimes:' . implode(',', $allowedMime)]
    );

    return !$validation->fails();
}
