<?php

namespace App\Http\Controllers;


use App\Events\ChatEvent;
use App\Models\Channel;
use App\Models\Group;
use App\Models\Image;
use App\Models\Product;


use App\Models\Rule;
use App\Models\Shop;
use App\Models\User;
use Carbon\Carbon;
use Carbon\Traits\Date;
use DateTime;
use Helper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Morilog\Jalali\Jalalian;
use PhpParser\Node\Expr\AssignOp\Div;
use PhpParser\Node\Stmt\Else_;


class BotController extends Controller
{

    //user selected  game type and click on find gamer
    //try to find gamer else connect to a bot

    protected $bot;

    public function __construct()
    {
        error_reporting(1);
        set_time_limit(-1);
        header("HTTP/1.0 200 OK");
        date_default_timezone_set('Asia/Tehran');
//--------[Your Config]--------//
        $this->bot = Helper::$bot;
//-----------------------------//
        define('API_KEY', env('TELEGRAM_BOT_TOKEN', 'YOUR-BOT-TOKEN')); // توکن ربات
    }


    public function getupdates(Request $request)
    {
        $update = json_decode(file_get_contents('php://input'));
        if (isset($update->message)) {
            $message = $update->message;
            $chat_id = $message->chat->id;
            $chat_username = '@' . $message->chat->username;
            $text = $message->text;
            $message_id = $message->message_id;
            $from_id = $message->from->id;
            $tc = $message->chat->type;
            $title = isset($message->chat->title) ? $message->chat->title : "";
            $first_name = isset($message->from->first_name) ? $message->from->first_name : "";
            $last_name = isset($message->from->last_name) ? $message->from->last_name : "";
            $username = isset($message->from->username) ? '@' . $message->from->username : "";
            //            $reply = isset($message->reply_to_message->forward_from->id) ? $message->reply_to_message->forward_from->id : "";
//            $reply_id = isset($message->reply_to_message->from->id) ? $message->reply_to_message->from->id : "";
            $reply = isset($message->reply_to_message) ? $message->reply_to_message : "";
            $new_chat_member = $update->message->new_chat_member; #id,is_bot,first_name,last_name,username
            $new_chat_members = $update->message->new_chat_members; #id,is_bot,first_name,last_name,username]
            $left_chat_member = $update->message->left_chat_member; #id,is_bot,first_name,username
            $new_chat_participant = $update->message->new_chat_participant; #id,username

            $animation = $update->message->animation;  #file_name,mime_type,width,height,file_id,file_unique_id,file_size,thumb[file_id,file_unique_id,file_size,width,
            $sticker = $update->message->sticker;  #width,height,emoji,set_name,is_animated,file_id,file_unique_id,file_size,thumb[file_id,file_unique_id,file_size,width,
            $photo = $update->message->photo; #file_id,file_unique_id,file_size,width,height] array of different photo sizes
            $document = $update->message->document; #file_name,mime_type,thumb[file_id,file_unique_id,file_size,width,height]
            $video = $update->message->video; #duration,width,height,mime_type,file_id,file_unique_id,file_size,thumb[file_id,file_unique_id,file_size,width,height]
            $audio = $update->message->audio; #duration,mime_type,title,performer,file_id,file_unique_id,file_size,thumb[file_id,file_unique_id,file_size,width,height]
            $voice = $update->message->voice; #duration,mime_type,file_id,file_unique_id,file_size
            $video_note = $update->message->video_note; #duration,length,file_id,file_unique_id,file_size,thumb[file_id,file_unique_id,file_size,width,height]
            $caption = $message->caption;
            $phone_number = isset($message->contact) ? $message->contact->phone_number : null;

        }
        if (isset($update->callback_query)) {
            $Data = $update->callback_query->data;
            $data_id = $update->callback_query->id;
            $chat_id = $update->callback_query->message->chat->id;
            $caption = $update->callback_query->message->caption;
            $from_id = $update->callback_query->from->id;
            $first_name = $update->callback_query->from->first_name;
            $last_name = $update->callback_query->from->last_name;
            $username = '@' . $update->callback_query->from->username;
            $tc = $update->callback_query->message->chat->type;
            $message_id = $update->callback_query->message->message_id;

        }
        if (isset($update->channel_post)) {
            $tc = $update->channel_post->chat->type;
            $text = $update->channel_post->text;
            $chat_id = $update->channel_post->chat->id;
            $chat_username = '@' . $update->channel_post->chat->username;
            $chat_title = $update->channel_post->chat->title;

            $message_id = $update->channel_post->message_id;


            $caption = $update->channel_post->caption;
            $photo = $update->channel_post->photo;
            $document = $update->channel_post->document;
            $video = $update->channel_post->video;
            $audio = $update->channel_post->audio;
            $voice = $update->channel_post->voice;
            $video_note = $update->channel_post->video_note;

            if (isset($update->channel_post->reply_to_message)) {
                $reply = $update->channel_post->reply_to_message->message_id;

            }
            if (isset($update->channel_post->forward_from_chat)) {
                $from_chat_id = $update->channel_post->forward_from_chat->id;
                $from_message_id = $update->channel_post->forward_from_message_id;
            }
            if (isset($update->channel_post->forward_from)) {
                $from_chat_id = $update->channel_post->forward_from->id;
//                $from_message_id = $update->channel_post->forward_from_id;
            }

        }


//------------------------------------------------------------------------------
//        $rank = $this->user_in_chat($this->channel, $from_id, $tc);// $get['result']['status'];


//        sendTelegramMessage(Helper::$logs[0], json_encode($update), null);

        if ($tc == 'private') {
            $this->getUserOrRegister($first_name, $last_name, $username, $from_id);

            if (!in_array($from_id, Helper::$Devs)) {
                $rank = $this->user_in_chat(Helper::$channel, $from_id, $tc);// $get['result']['status'];
                if ($rank != 'creator' && $rank != 'administrator' && $rank != 'member') {
                    sendTelegramMessage($from_id, "🌟 برای استفاده از امکانات ربات ابتدا در کانال فروشگاه عضو شوید و مجدد /start را بزنید 🌟" . PHP_EOL . PHP_EOL . " 📌  " . Helper::$channel, null, null, null);
                    return;
                }
            }


//            return (string)($USER_REGISTER . "\xE2\x9C\x85" == $text);
//            return (string)(0 == null);
//            return $this->user_in_channel("@lamassaba", $from_id);// == 'administrator' or 'creator'
//            return $this->user_in_channel("@twitterfarsi", $from_id);// Bad Request: user not found
//            return $this->user_in_channel("@twitteddrfarsi", $from_id);// Bad Request: chat not found

//            return json_encode($this->inviteToChat($this->channel));
            $buy_button = json_encode(['inline_keyboard' => [
                [['text' => "📪 ارتباط با ما 📪", 'url' => "telegram.me/" . 'develowper']],
//                [['text' => "📌 دریافت بنر تبلیغاتی 📌", 'callback_data' => "بنر"]],
            ], 'resize_keyboard' => true]);

            $divar_button = json_encode(['keyboard' => [
                [['text' => '📌ثبت کانال در دیوار📌']],
                [['text' => '📍ثبت تبلیغ در دیوار📍']],
                [['text' => '👀 مشاهده دیوار 👀']],
                [['text' => 'سکه های من💰']],
                [['text' => 'منوی اصلی⬅']],
            ], 'resize_keyboard' => true]);
            $button = json_encode(['keyboard' => [
                in_array($from_id, Helper::$Devs) ? [['text' => 'پنل مدیران🚧']] : [],
//                [['text' => '📈 دیوار 📈']],
                [['text' => '🛒 بازار 🛒']],
//                [['text' => '🎯تبادل یاب🎯']],
//                [['text' => 'تگ اتوماتیک🏁'], ['text' => 'تبادل لیستی🔃']],

//                [/*['text' => 'ثبت گروه💥'],*/
//                    ['text' => 'ثبت کانال💥']
//                ],
                /* [['text' => 'مدیریت گروه ها📢'],*/
//                    ['text' => 'مدیریت کانال ها📣']],
                [['text' => "🎴 ساخت دکمه شیشه ای 🎴"], ['text' => "📌 دریافت بنر تبلیغاتی 📌"]],
                [['text' => "📱 خرید شارژ 📱"], ['text' => "📱 خرید اینترنت 📱"], ['text' => "🙏 حمایت از ما 🙏"]],

                [['text' => 'سکه های من💰'], ['text' => $this->user ? "ویرایش اطلاعات✏" : "ثبت نام✅"] /*['text' => 'جریمه افراد لفت داده📛']*/],

                [['text' => 'درباره ربات🤖']],
            ], 'resize_keyboard' => true]);
            $cancelBazarButton = json_encode(['keyboard' => [
                [['text' => "خروج از بازار ❌"]],
            ], 'resize_keyboard' => true]);
            $cancel_button = json_encode(['keyboard' => [
                [['text' => "لغو ❌"]],
            ], 'resize_keyboard' => true]);
            $cancel_register_button = json_encode(['keyboard' => [
                [['text' => "لغو ثبت نام❌"]],
            ], 'resize_keyboard' => true]);
            $return_button = json_encode(['inline_keyboard' => [
                [['text' => "بازگشت⬅", 'callback_data' => "edit_cancel"]],
            ], 'resize_keyboard' => true]);
            $edit_button = json_encode(['inline_keyboard' => [
                [['text' => 'ویرایش نام', 'callback_data' => "edit_name"], ['text' => 'ویرایش نام کاربری', 'callback_data' => "edit_username"], ['text' => 'ویرایش گذرواژه', 'callback_data' => "edit_password"],],
            ], 'resize_keyboard' => true]);
            $admin_button = json_encode(['inline_keyboard' => [
                [['text' => "📬 ارسال همگانی به کاربران", 'callback_data' => 'send_to_users']],
                [['text' => "📬 ارسال همگانی به گروه ها", 'callback_data' => 'send_to_chats']],
                [['text' => "🚶 مشاهده کاربران", 'callback_data' => 'see_users']],
                [['text' => "🚶 مشاهده فالورها", 'callback_data' => 'see_followers']],
                [['text' => "❓ راهنمای دستورات", 'callback_data' => 'admin_help']],
                [['text' => "📊 آمار", 'callback_data' => 'statistics']],
            ], 'resize_keyboard' => true]);
            $send_cancel_button = json_encode(['inline_keyboard' => [
                [['text' => "لغو ارسال⬅", 'callback_data' => "send_cancel"]],
            ], 'resize_keyboard' => true]);

            if (preg_match('/^\/(start)$/i', $text)) {

                if (!$this->user) sendTelegramMessage($chat_id, "■ سلام $first_name خوش آمدید\n\n■ برای ثبت فروشگاه خود  ابتدا در ربات ثبت نام کنید :" . " پشتیبانی: " . Helper::$admin, null, $message_id, $button);
                else  sendTelegramMessage($chat_id, "■ سلام $first_name به ورتا شاپ خوش اومدی✋\n  " . "⚡ توسط این ربات می توانید محصولات بازارچه را مشاهده و یا محصولات خود را در بازارچه ثبت کنید" . PHP_EOL . " 💻 سایت فروشگاه: " . PHP_EOL . Helper::$site . PHP_EOL . PHP_EOL . " پشتیبانی: " . Helper::$admin, null, $message_id, $button);
//                $first_name = $this->MarkDown($first_name);
//                sendTelegramMessage($chat_id, " \n آموزش ربات\n" . $this->tut_link, null, $message_id, null);

                logAdmins("■  کاربر [$first_name](tg://user?id=$from_id) ربات ورتاشاپ را استارت کرد.", 'MarkDown');

            } elseif ($reply) {
//                sendTelegramMessage($from_id, json_encode($reply), null, null);

                $repText = $reply->text;
                if ($repText) {
                    if (str_starts_with($repText, 'ip:')) {
                        $tmp = explode("\n", $repText);
                        if (count($tmp) > 1) {
                            $pusherChannel = $tmp[0];
                            $txt = $tmp[1];
                            if ($pusherChannel && str_contains($pusherChannel, 'ip:')) {
                                $ip = str_replace('ip:', '', $pusherChannel);
                                $t = Carbon::now()->timestamp;
                                event(new ChatEvent('support' . $chat_id, $ip, $text, $ip, $t));

                            }
                        }

                    }
                }
            }
//            elseif ($from_id == Helper::$logs[0]) {
//
//                sendTelegramMessage($from_id, $tags, null, null, null);
//            }
//            elseif ($rank != 'creator' && $rank != 'administrator' && $rank != 'member') {
//                sendTelegramMessage($chat_id, "■ برای استفاده از ربات و همچنین حمایت از ما ابتدا وارد کانال\n● $this->channel\n■ شده سپس به ربات برگشته و /start را بزنید.", null, $message_id, json_encode(['KeyboardRemove' => [], 'remove_keyboard' => true]));
//
//            }
            elseif ($text == 'منوی اصلی⬅' || $Data == "mainmenu$") {
                sendTelegramMessage($chat_id, "منوی اصلی", 'MarkDown', $message_id, $button);


            } elseif ($text == "📱 خرید شارژ 📱" || $text == "📱 خرید اینترنت 📱") {
                sendTelegramMessage($chat_id, "از طریق ربات دیگر ما، به راحتی و به سرعت شارژ و اینترنت تهیه کنید" . PHP_EOL . "@vartastudiobot", "Markdown", $message_id, null);

            } elseif ($text == "🙏 حمایت از ما 🙏") {
                sendTelegramMessage($chat_id, "در صورت رضایت از ربات و در جهت رایگان ماندن خدمات ما، می توانید مبلغی را بعنوان حمایت از ربات پرداخت نماید." . PHP_EOL . "🙏 این کار را کاملا اختیاری و تنها در صورت رضایت انجام دهید 🙏" . "https://idpay.ir/vartastudio", "Markdown", $message_id, null);

            } elseif ($text == '📌ثبت کانال در فروشگاه📌' || $Data == "insert_divar") {

                if (!$this->user) {
                    sendTelegramMessage($chat_id, "ابتدا از قسمت منوی اصلی در ربات ثبت نام نمایید.", "Markdown", $message_id, null);
                    return;
                }
                $groups_channels = array();
                foreach (Channel::orWhere('user_id', $this->user->id)->orWhereIn('shop_id', Rule::where('user_id', $this->user->id)->pluck('shop_id'))->get(['chat_id', 'chat_username']) as $gc) {
//                    $res = $this->user_in_chat($gc, $this->bot_id);
//                    if ($res == 'administrator' || $res == 'creator')
                    array_push($groups_channels, [['text' => $gc->chat_username, 'callback_data' => 'divar$' . $gc->chat_id]]);
                }
//                array_push($groups_channels, [['text' => '➕ثبت کانال/گروه جدید➕', 'callback_data' => 'divar$' . 'new']]);


                $help = json_encode(['inline_keyboard' => [[['text' => 'راهنمای تبدیل کانال به حالت public', 'callback_data' => 'help_public_channel']], [['text' => 'راهنمای اضافه کردن ربات به کانال', 'callback_data' => 'help_add_bot_channel']],], 'resize_keyboard' => true]);
//                    sendTelegramMessage($chat_id, "🔹کانال شما باید در حالت  *public* باشد و با یک نام قابل شناسایی باشد. (مثال:$this->bot)\n🔹ربات را به کانال اضافه کنید.\n    در صورت داشتن هر گونه سوال به قسمت *درباره ربات* مراجعه نمایید. \n $this->bot ", 'Markdown', $message_id, $help);

                $cancelbutton = json_encode(['keyboard' => [
                    [['text' => "لغو ❌"]],
                ], 'resize_keyboard' => true]);

                $this->user->step = 2; // for register channel
                $this->user->save();
                sendTelegramMessage($chat_id, "❓راهنمای ثبت کانال" . PHP_EOL .
                    "گزینه اضافه کردن مدیران را در کانال خود بزنید و نام ربات را جست و جو کنید (" . Helper::$bot . " ) " . PHP_EOL .
                    "پس از نمایش ربات، آن را به کانال خود اضافه کنید" . PHP_EOL .
                    "🚧در صورت هر گونه نیاز به راهنمایی پیام خود را ارسال کنید " . Helper::$admin
                    ,
                    null, $message_id, $cancelbutton);


                //***********

//                if (count($groups_channels) == 0) {
                sendTelegramMessage($chat_id, "نام کانال خود را با @ وارد کنید \n مثال: " . PHP_EOL . "@vartashop", null, $message_id, $cancelbutton);

//                    if ($text) sendTelegramMessage($chat_id, "گروه/کانال ثبت شده ای ندارید\nابتدا از منوی اصلی *ثبت گروه یا کانال* را بزنید", null, $message_id, $divar_button);
//                }
//                else {
//                    $groups_channels = json_encode(['inline_keyboard' => $groups_channels, 'resize_keyboard' => true]);
//                    if ($Data) $this->EditMessageText($chat_id, $message_id, "🔥گزینه مورد نظر خود را برای درج در دیوار انتخاب کنید و یا اگر در دکمه های زیر نیست " . "نام کانال خود را با @ وارد کنید \n مثال: ", "Markdown", $groups_channels);
//                    else sendTelegramMessage($chat_id, "🔥گزینه مورد نظر خود را برای درج در دیوار انتخاب کنید و یا اگر در دکمه های زیر نیست " . "نام کانال خود را با @ وارد کنید \n مثال: " . PHP_EOL . "@vartastudio", "Markdown", $message_id, $groups_channels);
//                }


            } elseif ($text == 'سکه های من💰') {
                $score = $this->user->score;

                sendTelegramMessage($from_id, "💰 سکه فعلی شما:$score \n  برای دریافت سکه می توانید از طریق دکمه ارتباط با ما اقدام به خرید سکه نمایید ", 'Markdown', $message_id, $buy_button);


            } elseif ($text == "منوی اصلی💬") {

                sendTelegramMessage($chat_id, "منوی اصلی", null, $message_id, $button);

            } elseif ($text == "لغو ثبت گروه❌" || $text == "لغو ثبت کانال❌" || $text == "لغو ❌") {
                if ($this->user) {
                    $this->user->step = null; // for register channel
                    $this->user->remember_token = null;
                    $this->user->save();
                }
                deleteTelegramMessage($chat_id, $message_id - 2);
                deleteTelegramMessage($chat_id, $message_id - 1);
                deleteTelegramMessage($chat_id, $message_id);

                sendTelegramMessage($chat_id, "با موفقیت لغو شد!", null, null, $button);

            } elseif ($text == "لغو ثبت تگ ❌") {
                if ($this->user) {
                    $this->user->step = null; // for register channel
                    $this->user->save();
                }
                sendTelegramMessage($chat_id, "با موفقیت لغو شد!", null, null, $button);

            } elseif ($text == "خروج از بازار ❌") {
                if ($this->user) {
                    $this->user->step = null; // for register channel
                    $this->user->remember_token = null;
                    $this->user->save();
                }
                sendTelegramMessage($chat_id, "با موفقیت خارج شدید!", null, null, $button);

            } elseif (strpos($Data, "channel_details$") !== false) {
                if (!$this->user) sendTelegramMessage($chat_id, "■ سلام $first_name خوش آمدید\n\n■ برای ثبت کانال/گروه خود ابتدا در ربات ثبت نام کنید :", null, $message_id, $button);
                else {

                    $chatId = explode("$", $Data)[1];
                    $chat = Channel::where('chat_id', "$chatId")->first();

                    $btns = [];
                    $btns[] = [['text' => " راهنما ", 'callback_data' => 'help$auto_tab'], ['text' => " تب اتوماتیک روزانه " . ($chat->auto_tab_day ? "🟢" : "🔴"), 'callback_data' => 'settings$auto_tab_day$' . "$chatId"],];
                    $btns[] = [['text' => " راهنما ", 'callback_data' => 'help$auto_tab'], ['text' => " تب اتوماتیک شبانه" . ($chat->auto_tab ? "🟢" : "🔴"), 'callback_data' => 'settings$auto_tab$' . "$chatId"],];
                    $btns[] = [['text' => " راهنما ", 'callback_data' => 'help$auto_tag'], ['text' => " تگ اتوماتیک " . ($chat->auto_tag ? "🟢" : "🔴"), 'callback_data' => 'settings$auto_tag$' . "$chatId"],];
                    $btns[] = [['text' => " راهنما ", 'callback_data' => 'help$auto_msg_day'], ['text' => " جمله انگیزشی روزانه " . ($chat->auto_msg_day ? "🟢" : "🔴"), 'callback_data' => 'settings$auto_msg_day$' . "$chatId"],];
                    $btns[] = [['text' => " راهنما ", 'callback_data' => 'help$auto_msg_night'], ['text' => " جمله انگیزشی شبانه " . ($chat->auto_msg_night ? "🟢" : "🔴"), 'callback_data' => 'settings$auto_msg_night$' . "$chatId"],];
                    $btns[] = [['text' => " راهنما ", 'callback_data' => 'help$auto_fun'], ['text' => " پست طنز و سرگرمی " . ($chat->auto_fun ? "🟢" : "🔴"), 'callback_data' => 'settings$auto_fun$' . "$chatId"],];
                    $btns[] = [['text' => "✍️ تغییر تگ (" . Helper::$tag_score . " سکه) ✍️", 'callback_data' => 'settings$change_tag$' . "$chatId"]];
                    $btns[] = [['text' => "بازگشت⬅", 'callback_data' => 'مدیریت کانال ها📣']];

                    $setting_button = json_encode(['inline_keyboard' => $btns, 'resize_keyboard' => true]);

                    $followers = Follower::where('chat_id', "$chatId");

                    $g = Group::where('id', $chat->group_id)->first();


                    $txt = "📣 کانال:";
                    $txt .= $chat->chat_username . PHP_EOL;

                    $txt .= "موضوع:" . " $g->emoji " . "$g->name" . PHP_EOL;
                    $txt .= "تگ کانال (انتهای هر پست):" . PHP_EOL . PHP_EOL;
                    $txt .= $chat->tag ?? "\xD8\x9C" . "➖➖➖➖➖➖➖➖➖➖➖" . PHP_EOL . $chat->chat_username;

                    $this->EditMessageText($chat_id, $message_id, $txt, null, $setting_button);

                }
            } elseif (strpos($Data, 'settings$change_tag$') !== false) {
                $chatId = explode("$", $Data)[2];
                if (!$this->user || $this->user->score < (Helper::$vip_limit + Helper::$tag_score)) {
                    $return = json_encode(['inline_keyboard' => [[['text' => "بازگشت⬅", 'callback_data' => "channel_details$" . $chatId]]], 'resize_keyboard' => false]);
                    $this->popupMessage($data_id, "برای فعالسازی این قابلیت حداقل " . (Helper::$vip_limit + Helper::$tag_score) . " سکه در حساب شما وجود داشته باشد.(سکه فعلی شما: " . $this->user->score . ")" . PHP_EOL);
                } else {
                    $this->user->step = 11;
                    $this->user->remember_token = "$chatId";
                    $this->user->save();
                    $return = json_encode(['inline_keyboard' => [[['text' => "لغو ثبت تگ ❌", 'callback_data' => "channel_details$" . $chatId]]], 'resize_keyboard' => true]);
                    $this->EditMessageText($chat_id, $message_id, "متن تگی که میخواهید زیر پست هایتان درج شود را بنویسید", null, $return);
                }

            } elseif (strpos($Data, "settings$") !== false) {
                if (!$this->user) sendTelegramMessage($chat_id, "\n\n■ برای ثبت کانال/گروه خود ابتدا در ربات ثبت نام کنید :", null, $message_id, $button);
                else {

                    $cmnd = explode("$", $Data)[1];
                    $chatId = explode("$", $Data)[2];
                    $chat = Channel::where('chat_id', "$chatId")->first();

                    if ($from_id != $chat->user_telegram_id) {
                        $this->popupMessage($data_id, "📣" . PHP_EOL . "این کانال متعلق به شما نیست!");
                        return;
                    }
                    $d = Divar::where('chat_id', "$chatId")->first();
                    if ($chat->$cmnd == false) {
                        if (!$d) {
                            $this->popupMessage($data_id, "📛" . PHP_EOL . "ابتدا کانال خود را در دیوار ثبت کنید. (دکمه دیوار -> ثبت کانال در دیوار)");
                            return;
                        }

                        $validate = Helper::botIsAdminAndHasPrivileges("$d->chat_id");
                        $showMessage = $d->validated == false && $validate == true;
                        $d->validated = $validate;
                        $d->save();
                        if (!$validate) {
                            $this->popupMessage($data_id, "📛" . PHP_EOL . "ابتدا ربات را ادمین کانال خود کنید و تمام اجازه دسترسی ها به جز ادمین جدید را به آن بدهید");
                            return;
                        }

                        $count = Helper::getChatMembersCount("$chat->chat_id");
                        $d->members = $count;
                        $d->save();
                        if ($count < 20) {
                            $this->popupMessage($data_id, "📛" . PHP_EOL . "کانال شما حداقل ۲۰ عضو داشته باشد");
                            return;
                        }

                    }
                    if ($showMessage) {
                        if ($cmnd == 'auto_tab')
                            $msg = "❇️ کانال  $chat->chat_username  به لیست تبادل شبانه مگنت گرامی ها اضافه شد!" . PHP_EOL;
                        if ($cmnd == 'auto_tab_day')
                            $msg = "❇️ کانال  $chat->chat_username  به لیست تبادل روزانه مگنت گرامی ها اضافه شد!" . PHP_EOL;

                        sendTelegramMessage(Helper::$divarChannel, $msg, null, null, null);
                    }

                    $chat->$cmnd = !$chat->$cmnd;
                    $chat->save();

                    $faCmnd = ($chat->$cmnd) ? "با موفقیت فعال شد" : "با موفقیت غیر فعال شد";
                    $this->popupMessage($data_id, "📣" . PHP_EOL . $faCmnd);

                    $btns = [];
                    $btns[] = [['text' => " راهنما ", 'callback_data' => 'help$auto_tab'], ['text' => " تب اتوماتیک روزانه " . ($chat->auto_tab_day ? "🟢" : "🔴"), 'callback_data' => 'settings$auto_tab_day$' . "$chatId"],];
                    $btns[] = [['text' => " راهنما ", 'callback_data' => 'help$auto_tab'], ['text' => " تب اتوماتیک شبانه " . ($chat->auto_tab ? "🟢" : "🔴"), 'callback_data' => 'settings$auto_tab$' . "$chatId"],];
                    $btns[] = [['text' => " راهنما ", 'callback_data' => 'help$auto_tag'], ['text' => " تگ اتوماتیک " . ($chat->auto_tag ? "🟢" : "🔴"), 'callback_data' => 'settings$auto_tag$' . "$chatId"],];
                    $btns[] = [['text' => " راهنما ", 'callback_data' => 'help$auto_msg_day'], ['text' => " جمله انگیزشی روزانه " . ($chat->auto_msg_day ? "🟢" : "🔴"), 'callback_data' => 'settings$auto_msg_day$' . "$chatId"],];
                    $btns[] = [['text' => " راهنما ", 'callback_data' => 'help$auto_msg_night'], ['text' => " جمله انگیزشی شبانه " . ($chat->auto_msg_night ? "🟢" : "🔴"), 'callback_data' => 'settings$auto_msg_night$' . "$chatId"],];
                    $btns[] = [['text' => " راهنما ", 'callback_data' => 'help$auto_fun'], ['text' => " پست طنز و سرگرمی " . ($chat->auto_fun ? "🟢" : "🔴"), 'callback_data' => 'settings$auto_fun$' . "$chatId"],];
                    $btns[] = [['text' => "✍️ تغییر تگ (" . Helper::$tag_score . " سکه) ✍️", 'callback_data' => 'settings$change_tag$' . "$chatId"]];
                    $btns[] = [['text' => "بازگشت⬅", 'callback_data' => 'مدیریت کانال ها📣']];

                    $setting_button = json_encode(['inline_keyboard' => $btns, 'resize_keyboard' => true]);

                    $followers = Follower::where('chat_id', "$chatId");
                    $g = Group::where('id', $chat->group_id)->first();


                    $txt = "📣 کانال:";
                    $txt .= $chat->chat_username . PHP_EOL;

                    $txt .= "موضوع:" . " $g->emoji " . "$g->name" . PHP_EOL;
                    $txt .= "تگ کانال (انتهای هر پست):" . PHP_EOL . PHP_EOL;
                    $txt .= $chat->tag ?? "\xD8\x9C" . "➖➖➖➖➖➖➖➖➖➖➖" . PHP_EOL . $chat->chat_username;

                    $this->EditMessageText($chat_id, $message_id, $txt, null, $setting_button);
                }
            } elseif (strpos($Data, "help$") !== false) {
                $cmnd = explode("$", $Data)[1];
                switch ($cmnd) {
                    case "auto_tab"  :
                        sendTelegramMessage($from_id, "📣 تب اتوماتیک" . PHP_EOL . "1⃣ توسط این قابلیت می توانید در لیست تبادل اتوماتیک کانال ها قرار بگیرید. تب روزانه ۲ تا ۳ ظهر و تب شبانه ۱۲ شب تا ۸ صبح است." . PHP_EOL . "2⃣ برای استفاده، این گزینه باید سبز باشد و ربات ادمین کانال شما باشد." . PHP_EOL . "3⃣ قبل از استفاده حتما در صفحه اصلی ربات دکمه تبادل لیستی🔃 را زده و قوانین را بخوانید", null);

                        break;
                    case "auto_tag":
                        sendTelegramMessage($from_id, "📣 تگ اتوماتیک" . PHP_EOL . "1⃣ با فعال سازی این گزینه، بعد از گذاشتن پست در کانال خود یا فوروارد کردن پست از کانال های دیگر، تگ کانال شما انتهای پست گذاشته می شود و تگ کانال مبدا حذف خواهد شد!" . PHP_EOL . "2⃣ برای استفاده، این گزینه باید سبز باشد و ربات ادمین کانال شما باشد.", null);
                        break;
                    case "auto_msg_day":
                        sendTelegramMessage($from_id, "📣 جمله انگیزشی روزانه" . PHP_EOL . "1⃣ با فعال سازی این گزینه، هر روز ساعت 8، یک استیکر و یک جمله انگیزشی به کانال شما ارسال می شود!" . PHP_EOL . "2⃣ برای استفاده، این گزینه باید سبز باشد و ربات ادمین کانال شما باشد.", null);
                        break;
                    case "auto_msg_night":
                        sendTelegramMessage($from_id, "📣 جمله انگیزشی شبانه" . PHP_EOL . "1⃣ با فعال سازی این گزینه، هر شب ساعت 12، یک استیکر و یک جمله انگیزشی به کانال شما ارسال می شود!" . PHP_EOL . "2⃣ برای استفاده، این گزینه باید سبز باشد و ربات ادمین کانال شما باشد.", null);
                        break;
                    case "auto_fun":
                        sendTelegramMessage($from_id, "📣 ارسال پست های طنز" . PHP_EOL . "1⃣ با فعال سازی این گزینه، هر چند ساعت، مطالب فان و طنز به کانال شما ارسال می شود!" . PHP_EOL . "2⃣ برای استفاده، این گزینه باید سبز باشد و ربات ادمین کانال شما باشد.", null);
                        break;
                }
            } elseif ($Data == 'مدیریت کانال ها📣' || $text == 'مدیریت کانال ها📣') {
                if (!$this->user) sendTelegramMessage($chat_id, " $this->bot \n\n برای ثبت کانال خود ابتدا در ربات ثبت نام کنید ", null, $message_id, $button);
                else {
                    $channel_buttons = array();
                    //remove channels that kicked bot

//                    $this->user->channels = $tmp;
//                    $this->user->save();

                    foreach (Channel::where('user_id', $this->user->id)->get() as $ch) {
//                        if ($this->user_in_chat($ch->chat_id, $this->bot_id) == 'administrator')
                        array_push($channel_buttons, [['text' => $ch->chat_username, 'callback_data' => "channel_details$" . "$ch->chat_id"]]);
                    }
                    $buttons = json_encode(['inline_keyboard' => $channel_buttons, 'resize_keyboard' => true]);
                    $msg = count($channel_buttons) > 0 ? "لیست کانال های ثبت شده شما" : "کانال ثبت شده ای ندارید";
                    if ($text) sendTelegramMessage($chat_id, " \n $msg", null, $message_id, $buttons);
                    else if ($Data) $this->EditMessageText($chat_id, $message_id, "$msg \n ", null, $buttons);

                }
            } elseif ($text == 'ثبت کانال💥') {
                return;
                if (!$this->user) sendTelegramMessage($chat_id, "■  $first_name \n\n■ برای ثبت کانال خود ابتدا در ربات ثبت نام کنید :", null, $message_id, $button);
                else if ($this->user->score < $this->install_chat_score) {
                    $score = $this->user->score;
                    sendTelegramMessage($chat_id, "🔹 برای ثبت کانال نیاز به $this->install_chat_score سکه دارید.\n💰 سکه فعلی شما: $score \n  برای دریافت سکه می توانید کانال/گروه های موجود در دیوار را فالو کرده و یا از طریق دکمه ارتباط با ما اقدام به خرید سکه نمایید ", 'Markdown', $message_id, $buy_button);

                } else {
                    $help = json_encode(['inline_keyboard' => [[['text' => 'راهنمای تبدیل کانال به حالت public', 'callback_data' => 'help_public_channel']], [['text' => 'راهنمای اضافه کردن ربات به کانال', 'callback_data' => 'help_add_bot_channel']],], 'resize_keyboard' => true]);
//                    sendTelegramMessage($chat_id, "🔹کانال شما باید در حالت  *public* باشد و با یک نام قابل شناسایی باشد. (مثال:$this->bot)\n🔹ربات را به کانال اضافه کنید.\n    در صورت داشتن هر گونه سوال به قسمت *درباره ربات* مراجعه نمایید. \n $this->bot ", 'Markdown', $message_id, $help);

                    $cancel_button = json_encode(['keyboard' => [
                        [['text' => "لغو ❌"]],
                    ], 'resize_keyboard' => true]);
                    $this->user->step = 2; // for register channel
                    $this->user->save();
                    sendTelegramMessage($chat_id, "❓راهنمای ثبت کانال" . PHP_EOL .
//                        "🚩شما یک بار کانال را ثبت می کنید وبدون ثبت مجدد در درج در دیوار و یا تبادل چرخشی استفاده خواهید کرد" . PHP_EOL .
                        "🚩در صورتی که می خواهید کاربران را تشویق به عضو شدن کنید ربات باید ادمین کانال شما باشد(اختیاری)" . PHP_EOL .
                        "🚩کانال خود را انتخاب کرده و گزینه مدیران (Administrators) را انتخاب کنید" . PHP_EOL .
                        "🚩گزینه جستجو را زده و نام ربات را سرچ کنید ( " . Helper::$bot . " ) و آن را انتخاب کنید تا به کانال اضافه شود" . PHP_EOL .
                        "🚧در صورت هر گونه راهنمایی پیام خود را ارسال کنید " . Helper::$admin
                        ,
                        'MarkDown', $message_id, $cancel_button);
                    sendTelegramMessage($chat_id, "نام کانال خود را با @ وارد کنید \n مثال: " . PHP_EOL . "@vartastudio", 'MarkDown', $message_id, $cancel_button);

                }
//                sendTelegramMessage($chat_id, "\nنصب ربات در کانال :\n ابتدا روی اسم کانال خود کلیک کرده تا اطلاعات آن نمایش داده شود\nدر نسخه دسکتاپ گزینه add member و در نسخه ویندوز روی  subscribers کلیک کنید.\n در این مرحله اسم ربات (magnetgrambot) را جستجو نموده و به گروه اضافه کنید\n ربات در کانال حتما باید به عنوان ادمین اضافه شود.\n سپس در کانال دستور 'نصب' را وارد کنید تا کانال شما ثبت شود🌹", null, $message_id);
            } elseif ($text == 'درباره ربات🤖') {
                sendTelegramMessage($chat_id, "⭐️ بازارچه اینترنتی ورتا مخصوص کسب و کارهای خانگی \n📌 امکانات \n💣 ثبت محصولات \n💣 ثبت سفارش \n💣 مدیریت کانال فروشگاه \n💣 پشتیبانی 24 ساعته \nو...\n ورتا شاپ 👑 بازارچه ایرانیان " . PHP_EOL . PHP_EOL . Helper::$site, null, null, null);

            } elseif ($text == "لغو ثبت نام❌") {
                $button = json_encode(['keyboard' => [
                    [['text' => "ثبت نام✅"]],
                    [['text' => 'درباره ربات🤖']],
                ], 'resize_keyboard' => true]);# user is registering

                if ($this->user) {
                    $this->user->step = null;
                    $this->user->save();
//                        $this->user->destroy();
                }
                sendTelegramMessage($chat_id, "ثبت نام شما لغو شد", 'MarkDown', $message_id, $button);

            } elseif ($text == "ویرایش اطلاعات✏") {

                if (!$this->user) sendTelegramMessage($chat_id, "شما  ثبت نام نکرده اید", 'MarkDown', $message_id, $button);
                else {


                    sendTelegramMessage($chat_id, "■ برای مدیریت تنظیمات از کلید های زیر استفاده کنید :", null, $message_id, $edit_button);
//                    $this->user->step = 0;
//                    $this->user->save();
//                    sendTelegramMessage($chat_id, "نام کاربری را وارد کنید", 'MarkDown', $message_id, $button);
                }
            } elseif (strpos($Data, 'add_channel$') !== false) {
                $channel = explode('$', $Data)[1];
                $group_id = explode('$', $Data)[2];
                $from = explode('$', $Data)[3]; //divar:tab
                if ($this->check('channel', $channel, $chat_id, $message_id, $cancel_button)) {

                    $info = $this->getChatInfo($channel);
                    $this->user->step = null;
                    $this->user->score -= $this->install_chat_score;
                    $this->user->save();
//                    $timestamp = Helper::createChatImage($info->photo, "$info->id");
                    $chat = Channel::create([
//                        'image' => $timestamp,
                        'user_id' => $this->user->id,
                        'group_id' => $group_id,
//                        'user_telegram_id' => $this->user->telegram_id,
                        'chat_id' => "$info->id",
//                        'chat_type' => 'c',
                        'chat_username' => "@" . $info->username,
//                        'chat_title' => $info->title,
//                        'chat_description' => $info->description,
//                        'chat_main_color' => simple_color_thief(storage_path("app/public/chats/$timestamp.jpg"))
                    ]);

                    if ($from == 'divar') {
                        $prices_button = json_encode(['inline_keyboard' => [
                            [['text' => '🕐 ۶ ساعت:  ' . $this->divar_scores['6'] . 'سکه💰', 'callback_data' => "add_divar$6$" . "$info->id"]],
                            [['text' => '🕐 ۱۲ ساعت:  ' . $this->divar_scores['12'] . 'سکه💰', 'callback_data' => "add_divar$12$" . "$info->id"]],
                            [['text' => '🕐 ۲٤ ساعت: ' . $this->divar_scores['24'] . 'سکه💰', 'callback_data' => "add_divar$24$" . "$info->id"]],
                            [['text' => "بازگشت⬅", 'callback_data' => "insert_divar"]],

                        ], 'resize_keyboard' => true]);

                        $this->EditMessageText($chat_id, $message_id, "مدت زمان نمایش را انتخاب کنید:", "Markdown", $prices_button);

                    } elseif ($from == 'tab') {
                        Helper::addChatToTab($info, $first_name, $last_name);
                        sendTelegramMessage($chat_id, "🌹کانال شما با موفقیت در تبادل ثبت شد!" . PHP_EOL . "🚧پشتیبانی: " . Helper::$admin, 'MarkDown', $message_id, $button);
                    }
                }
            } elseif ($Data == "help_public_channel") {
                $txt = "\n*تبدیل کانال به حالت public: *\n 🔸وارد کانال خود شده و روی نام کانال در بالای آن کلیک کنید\n 🔸 در تلگرام موبایل از قسمت بالا *آیکن مداد* را انتخاب کنید.\n 🔸در تلگرام دسکتاپ از گزینه سه نقطه بالا گزینه  *Manage Channel* را انتخاب کنید \n\n 🔸 قسمت  *Channel type*  را به حالت *public*  تغییر دهید.\n 🔸سپس یک نام عمومی (تگ) به کانال خود تخصیص دهید. *ربات کانال شما را توسط این نام شناسایی می کند*. \n ";
                sendTelegramMessage($chat_id, $txt, 'MarkDown', null);

            } elseif ($Data == "help_add_bot_channel") {
                $txt = "\n*اضافه کردن ربات در کانال :*\n🔸 ابتدا وارد کانال خود شده و روی اسم آن کلیک کرده تا اطلاعات آن نمایش داده شود\n🔸 در نسخه دسکتاپ روی گزینه سه نقطه و سپس گزینه *add members* کلیک کنید.\n🔸 در نسخه موبایل روی  *subscribers* و سپس *add subscriber* کلیک کنید . \n در این مرحله اسم ربات($this->bot) را جستجو نموده و به کانال اضافه کنید\n 🔸 *ربات در کانال حتما باید به عنوان ادمین اضافه شود* . \n 🔸سپس در کانال دستور 'نصب' را وارد کنید تا کانال شما ثبت شود🌹";
                sendTelegramMessage($chat_id, $txt, 'MarkDown', null);

            } elseif ($Data == "help_public_group") {
                $txt = "\n  *راهنمای تبدیل گروه به حالت public* \n \n 🔸وارد گروه خود شده و روی نام گروه در بالای آن کلیک کنید\n 🔸 در تلگرام موبایل از قسمت بالا *آیکن مداد* را انتخاب کنید.\n 🔸در تلگرام دسکتاپ از گزینه سه نقطه بالا گزینه  *Manage group* را انتخاب کنید \n\n 🔸 قسمت  *Group type*  را به حالت *public*  تغییر دهید.\n 🔸سپس یک نام عمومی به گروه خود تخصیص دهید. *ربات گروه شما را توسط این نام شناسایی می کند*. \n 🔼 در صورت داشتن هر گونه سوال به قسمت *درباره ربات* مراجعه نمایید. \n $this->bot ";
                sendTelegramMessage($chat_id, $txt, 'MarkDown', null);

            } elseif ($Data == "edit_name") {
                $name = $this->user->name;
                $this->user->step = 3;
                $this->user->save();
                sendTelegramMessage($chat_id, "نام  فعلی: $name \n  نام  جدید را وارد کنید:", null, null, $return_button);

            } elseif ($Data == "edit_username") {
                $name = $this->user->username;
                $this->user->step = 4;
                $this->user->save();
                sendTelegramMessage($chat_id, "نام کاربری  فعلی: $name \n  نام  جدید را وارد کنید:", null, null, $return_button);

            } elseif ($Data == "edit_password") {

                $this->user->step = 5;
                $this->user->save();
                sendTelegramMessage($chat_id, "    \n  گذرواژه جدید را وارد کنید:", null, null, $return_button);

            } elseif ($Data == "edit_cancel") {
                $this->user->step = null;
                $this->user->save();
                sendTelegramMessage($chat_id, "■ برای مدیریت تنظیمات از کلید های زیر استفاده کنید :", null, null, $edit_button);


            } elseif ($text == "پنل مدیران🚧") {
//
                sendTelegramMessage($chat_id, "🚧فقط مدیران ربات به این پنل دسترسی دارند. گزینه مورد نظر خود را انتخاب کنید:", null, null, $admin_button);
            } elseif ($Data == "send_to_users") {
                $this->user->step = 6;
                $this->user->save();
                sendTelegramMessage($chat_id, "■ متن یا فایل ارسالی را وارد کنید :", null, null, $send_cancel_button);

            } elseif ($Data == "send_to_chats") {
                $this->user->step = 7;
                $this->user->save();
                sendTelegramMessage($chat_id, "■ متن یا فایل ارسالی را وارد کنید :", null, null, $send_cancel_button);


            } elseif ($Data == "send_cancel") {
                $this->user->step = null;
                $this->user->save();
                sendTelegramMessage($chat_id, "با موفقیت لغو شد ", null, null, null);


            } elseif ($Data == "see_users") {
                $txt = "";
                $txt .= "\n-------- لیست کاربران-----\n";
                if (in_array($from_id, Helper::$Devs))

                    foreach (User::get(['id', 'name', 'telegram_username', 'telegram_id', 'score']) as $idx => $user) {

                        $txt .= "id: $user->id\n";
                        $txt .= "name: $user->name\n";
                        $txt .= "telegram_username: $user->telegram_username\n";
                        $txt .= "telegram_id: $user->telegram_id\n";
                        $txt .= "channels:" . json_encode($user->channels) . "\n";
                        $txt .= "groups: " . json_encode($user->groups) . "\n";
                        $txt .= "score: $user->score\n";
                        $txt .= "\n-----------------------\n";
                        if ($idx % 3 == 0) {
                            sendTelegramMessage($chat_id, $txt, null, null, null);
                            $txt = "";
                        }
                    }


            } elseif (strpos($Data, "send_to_funs_ok$") !== false || ($reply && strpos($text, "fun") !== false && in_array($from_id, Helper::$logs))) {


                if ($Data) {
                    $id = explode("$", $Data)[1];
                    DB::table('funs')->where('id', $id)->delete();
                    $res = deleteTelegramMessage($chat_id, $message_id);


                } elseif ($reply) {


                    if ($reply->text) {
                        $txt = $reply->text;
                        $txt = preg_replace("/@\w+/", "", $txt);
                        $txt .= PHP_EOL;
                        $txt .= "\xD8\x9C" . "➖➖➖➖➖➖➖➖➖➖➖" . PHP_EOL . "🆅🅰🆁🆃🅰🆂🅷🅾🅿.🅸🆁" . PHP_EOL . Helper::$bot;
                        $reply->text = $txt;
                        $res = $this->sendFile("$chat_id", json_encode($reply), null, false);

                        if (!$res || $res->ok == false) {
                            sendTelegramMessage($from_id, $res->description, null);
                            return;
                        }
                        try {
                            $id = DB::table('funs')->insertGetId(['msg' => json_encode($res->result)]);
                            $send_or_cancel = json_encode(['inline_keyboard' => [
                                [['text' => "حذف شود✨", 'callback_data' => "send_to_funs_ok$" . $id]],

                            ], 'resize_keyboard' => true]);
                            sendTelegramMessage($chat_id, "پست ذخیره شد. در صورت نیاز به اصلاح، آن را حذف کنید", null, $res->result->message_id, $send_or_cancel);
                            if ($from_id == Helper::$logs[1])
                                $res = $this->sendFile(Helper::$logs[0], json_encode($reply), null, false);
                            else
                                $res = $this->sendFile(Helper::$logs[1], json_encode($reply), null, false);
                        } catch (\Exception $e) {
                            sendTelegramMessage($from_id, $e->getMessage(), null);
                            return;
                        }


                    } elseif ($reply->caption || $reply->photo || $reply->document || $reply->video || $reply->audio || $reply->voice || $reply->video_note) {
                        $caption = $reply->caption;
                        $caption = preg_replace("/@\w+/", "", $caption);
                        $caption = $caption . PHP_EOL;
                        $caption .= "\xD8\x9C" . "➖➖➖➖➖➖➖➖➖➖➖" . PHP_EOL . "🆅🅰🆁🆃🅰🆂🅷🅾🅿.🅸🆁" . PHP_EOL . Helper::$bot;
                        $reply->caption = $caption;

                        $res = $this->sendFile("$chat_id", json_encode($reply), null, false);
                        if (!$res || $res->ok == false) {
                            sendTelegramMessage($from_id, $res->description, null);
                            return;
                        }

                        try {
                            $id = DB::table('funs')->insertGetId(['msg' => json_encode($res->result)]);
                            $send_or_cancel = json_encode(['inline_keyboard' => [
                                [['text' => "حذف شود✨", 'callback_data' => "send_to_funs_ok$" . $id]],

                            ], 'resize_keyboard' => true]);
                            sendTelegramMessage($chat_id, "پست ذخیره شد. در صورت نیاز به اصلاح، آن را حذف کنید", null, $res->result->message_id, $send_or_cancel);
                            if ($from_id == Helper::$logs[1])
                                $res = $this->sendFile(Helper::$logs[0], json_encode($reply), null, false);
                            else
                                $res = $this->sendFile(Helper::$logs[1], json_encode($reply), null, false);

                        } catch (\Exception $e) {
                            sendTelegramMessage($from_id, $e->getMessage(), null);
                            return;
                        }
                    }
                }


            } elseif ($Data == "send_to_users_ok" || ($reply && strpos($text, "send") !== false && in_array($from_id, Helper::$logs))) {
                set_time_limit(0);
//                DB::table('queue')->truncate();
                if ($Data) {
                    $group_id = explode("$", $Data)[1];
                    $message_id = $message_id - 1;
                } elseif (strpos($text, "send") !== false) {
                    $group_id = explode("d", $text)[1];
                    $message_id = $reply->message_id;
                }

                $this->user->step = null;
                $this->user->save();
                if (DB::table('queue')->where('message_id', $message_id)->count() == 0) {

                    $ids = User::where('telegram_id', '!=', null)->get('telegram_id AS id', 'app_id');
                    $ids = $ids->map(function ($item) use ($message_id, $from_id) {
                        $item['message_id'] = $message_id;
                        $item['from_id'] = $from_id;
                        return $item;
                    })->toArray();
                    DB::table('queue')->insert($ids);
                    $co = count($ids);
                    sendTelegramMessage($chat_id, "■ تعداد $co پیام به صف اضافه شد!", null, null, null);

                } else {
                    sendTelegramMessage($chat_id, "صف این پیام پر است", null, null, null);
                }

            } elseif ($Data == "statistics") {


                if (!in_array($from_id, Helper::$Devs)) return;


                $txt = "";
                $txt .= "تعداد کاربران" . PHP_EOL;
                $txt .= User::count() . PHP_EOL;
                $txt .= "-------------------" . PHP_EOL;


//                deleteTelegramMessage($chat_id, $message_id);
                sendTelegramMessage($chat_id, $txt, null, null, null);


            } elseif ($Data == "admin_help") {
                $txt = "اضافه کردن امتیاز به کاربر" . "\n";
                $txt .= "<user_id>:score:<score>" . "\n";
                $txt .= "اضافه کردن به دیوار" . "\n";
                $txt .= "<@chat_username>:divar:<hours>" . "\n";
                $txt .= "حذف از دیوار" . "\n";
                $txt .= "<@chat_username>:divar:delete" . "\n";
                $txt .= "ساخت بنر" . "\n";
                $txt .= "banner:<متن پیام>" . "\n";
                $txt .= "ساخت متن با کلید شیشه ای" . "\n";
                $txt .= "inline:<متن پیام>\nمتن1\nلینک1\n ..." . "\n";
                $txt .= 'C:chat_username' . "\n" . "distag" . "\n" . "distab" . "\n" . "block" . "\n" . "unblock" . "\n" . "delete" . "\n" . "alarm";
                $txt .= "تبلیغ انتهای پیام ارسالی" . "\n";
                $txt .= "banner=name=link" . "\n";
                sendTelegramMessage($chat_id, $txt, null, null, null);

            } elseif ((strpos($text, ":score:") !== false)) {


                $id = explode(":", $text)[0];
                $score = explode(":", $text)[2];
                if (in_array($from_id, Helper::$Devs)) {
                    $u = User::where('id', $id)->orWhere('telegram_username', $id)->first();
                    if ($u) {
                        $u->score += $score;
                        $u->save();
                        sendTelegramMessage($u->telegram_id, "🙌 تبریک! \n $score  سکه به شما اضافه شد!  \n  سکه فعلی : $u->score", null, null, null);
                        sendTelegramMessage($chat_id, "$score  سکه به $u->telegram_username  اضافه شد.", null, null, null);
                    }
                }

            } elseif ((strpos($text, "C:") !== false || strpos($text, "c:") !== false)) {
                if (!in_array($from_id, Helper::$Devs)) return;

                $inputs = explode(":", $text);

                $command = $inputs[1];
                if (count($inputs) > 2)
                    $what = $inputs[2];

                switch ($command) {
                    case "phone":

                        $u = User::where('phone', 'like', '%' . $what . '%')->first();
                        if ($u) {
                            $u->phone_verified = true;
                            $u->save();

                            sendTelegramMessage($from_id, $what . "🟢 phone verified successfully !", null, null, null);

                        } else {
                            sendTelegramMessage($from_id, $what . "🔴 user not found !", null, null, null);

                        }
                        break;
                    case "deleteall":

                        $u = User::where('id', $what)->first();
                        if ($u) {
                            $u->delete();
                            foreach (Shop::where('user_id', $u->id)->get() as $shop) {

                                foreach (Product::withoutGlobalScopes()->where('shop_id', $shop->id)->get() as $p) {
                                    foreach (Image::where('type', 'p')->where('for_id', $p->id)->get() as $img) {
                                        if (Storage::exists("public/products/$img->id.jpg")) {
                                            Storage::delete("public/products/$img->id.jpg");
                                        }
                                        $img->delete();
                                    }
                                    $p->delete();
                                }

                                if (Storage::exists("public/shops/$shop->id.jpg")) {
                                    Storage::delete("public/shops/$shop->id.jpg");
                                }
                                $shop->delete();
                            }

                            sendTelegramMessage($from_id, $what . "🟢 all deleted successfully !", null, null, null);

                        } else {
                            sendTelegramMessage($from_id, $what . "🔴 user not found !", null, null, null);

                        }
                        break;
                    case "block":

                        $c = Channel::where('chat_username', $what)->first();
                        if ($c) {
                            $c->active = false;
                            $c->save();
                            Shop::where('id', $c->shop_id)->update(['active' => false]);
                            Product::where('shop_id', $c->shop_id)->update(['active' => false]);

                            sendTelegramMessage($from_id, $what . "🟢 blocked successfully !", null, null, null);

                        } else {
                            sendTelegramMessage($from_id, $what . "🔴 not found !", null, null, null);

                        }
                        break;
                    case "unblock":
                        $c = Channel::where('chat_username', $what)->first();
                        if ($c) {
                            $c->active = true;
                            $c->save();
                            Shop::where('id', $c->shop_id)->update(['active' => true]);
                            Product::withoutGlobalScopes()->where('shop_id', $c->shop_id)->update(['active' => true]);

                            sendTelegramMessage($from_id, $what . "🟢 ublocked successfully !", null, null, null);

                        } else {
                            sendTelegramMessage($from_id, $what . "🔴 not found !", null, null, null);

                        }
                        break;


                    case "distag":

                        $c = Channel::where('chat_username', $what)->first();
                        if ($c) {
                            $c->auto_tag = false;
                            $c->save();
                            sendTelegramMessage($from_id, $what . "🟢 disabled successfully !", null, null, null);

                        } else {
                            sendTelegramMessage($from_id, $what . "🔴 not found !", null, null, null);

                        }
                        break;
                    case "alarm":
                        \Illuminate\Support\Facades\Artisan::call('tab:alarm');
                        break;
                    case "alarmday":
                        \Illuminate\Support\Facades\Artisan::call('tab:alarmday');
                        break;
                    case "validate":
                        \Illuminate\Support\Facades\Artisan::call('tab:validate');
                        break;
                    case "alarmprods":
                        \Illuminate\Support\Facades\Artisan::call('send:productsdaily');
                        break;
                    case "fun":
                        \Illuminate\Support\Facades\Artisan::call('send:messagesfun');
                        break;
                }

            } elseif ((strpos($text, "inline:") !== false)) {
                if (!in_array($from_id, Helper::$Devs)) return;
                $buttons = [];
                $inlines = explode("\n", $text);
                $txt = explode(":", array_shift($inlines))[1]; //remove first (inline)
                $len = count($inlines);
                foreach ($inlines as $idx => $item) {

                    if ($idx % 2 == 0 && $idx + 1 < $len)
                        array_push($buttons, [['text' => $inlines[$idx], 'url' => $inlines[$idx + 1]]]);

                }


                sendTelegramMessage($chat_id, $txt, null, null, json_encode(['inline_keyboard' => $buttons, 'resize_keyboard' => true]));


            } elseif ($text == "🎴 ساخت دکمه شیشه ای 🎴") {
                if (!$this->user) sendTelegramMessage($chat_id, "■  $first_name \n\n■  ابتدا در ربات ثبت نام کنید :", null, $message_id, $button);

                else {
                    $cancel_button = json_encode(['keyboard' => [
                        [['text' => "لغو ❌"]],
                    ], 'resize_keyboard' => true]);
                    $this->user->step = 8;

                    $this->user->save();

                    sendTelegramMessage($chat_id, "متن یا فایل خود را وارد کنید", 'MarkDown', $message_id, $cancel_button);
                }
            } elseif (!$Data && $this->user && $this->user->step !== null && $this->user->step >= 0) {
                # user is registering

                switch ($this->user->step) {
                    case  0:
                        if ($this->check('username', $text, $chat_id, $message_id, $cancel_button)) {
                            $this->user->step++;
                            $this->user->username = $text;
                            $this->user->save();
                            sendTelegramMessage($chat_id, "رمز عبور را وارد کنید\n(حداقل 5 حرف)", 'MarkDown', $message_id);

                        }
                        break;
                    case  1:
                        if ($this->check('password', $text, $chat_id, $message_id, $cancel_button)) {

                            $this->user->password = Hash::make($text);
                            $this->user->step = null;
                            $this->user->save();
//                            $this->createUserImage($this->user->id);
                            sendTelegramMessage($chat_id, "با موفقیت ثبت نام شدید!\n میتوانید با نام کاربری و رمز عبور خود وارد سایت شوید.\n" . Helper::$site, 'MarkDown', $message_id, $button);
                        }
                        break;
//                        case 2 is ثبت کانال
                    case  2:
                        $cancel_button = json_encode(['keyboard' => [
                            [['text' => "لغو ❌"]],
                        ], 'resize_keyboard' => true]);

                        if ($this->check('channel', $text, $chat_id, $message_id, $cancel_button)) {

//                            $tmp = $this->user->channels;
//                            array_push($tmp, $text);
//                            $this->user->channels = $tmp;
//                            $this->user->step = null;


                            $group_id_button = [];
                            foreach (Group::where('id', '<', 20)->get() as $g) {
                                $group_id_button[] = [['text' => "$g->name $g->emoji", 'callback_data' => "add_channel$$text$$g->id$" . 'divar']];
                            }
                            $group_id_button = json_encode(['inline_keyboard' => $group_id_button, 'resize_keyboard' => true]);
                            sendTelegramMessage($chat_id, "موضوع کانال خود را انتخاب کنید", 'MarkDown', $message_id, $group_id_button);


                        }
                        break;

                    case  3:
                        if ($this->check('name', $text, $chat_id, $message_id, $return_button)) {
                            $this->user->step = null;
                            $this->user->name = $text;
                            $this->user->save();
                            sendTelegramMessage($chat_id, "با موفقیت ویرایش شد!", 'MarkDown', $message_id, $edit_button);

                        }
                        break;
                    case  4:
                        if ($this->check('username', $text, $chat_id, $message_id, $return_button)) {
                            $this->user->step = null;
                            $this->user->username = $text;
                            $this->user->save();
                            sendTelegramMessage($chat_id, "با موفقیت ویرایش شد!", 'MarkDown', $message_id, $edit_button);

                        }
                        break;
                    case  5:
                        if ($this->check('password', $text, $chat_id, $message_id, $return_button)) {

                            $this->user->password = Hash::make($text);
                            $this->user->step = null;
                            $this->user->save();
                            sendTelegramMessage($chat_id, "با موفقیت ویرایش شد!", 'MarkDown', $message_id, $edit_button);

                        }
                        break;
                    //send to users
                    case  6:
//                        if (!in_array($from_id, Helper::$Devs))
//                    return;
                        $send_or_cancel = json_encode(['inline_keyboard' => [
                            [['text' => "ارسال شود✨", 'callback_data' => "send_to_users_ok"]],
                            [['text' => "لغو ارسال⬅", 'callback_data' => "send_cancel"]],
                        ], 'resize_keyboard' => true]);
                        $this->user->step = null;
                        $this->user->save();
                        Storage::put('message.txt', json_encode($message));
                        sendTelegramMessage($chat_id, "*از ارسال به کاربران اطمینان دارید؟*", 'MarkDown', $message_id, $send_or_cancel);

                        break;
                    //send to groups
                    case  7:
                        $send_or_cancel = json_encode(['inline_keyboard' => [
                            [['text' => "ارسال شود✨", 'callback_data' => "send_to_chats_ok"]],
                            [['text' => "لغو ارسال⬅", 'callback_data' => "send_cancel"]],
                        ], 'resize_keyboard' => true]);
                        $this->user->step = null;
                        $this->user->save();
                        Storage::put('message.txt', json_encode($message));
                        sendTelegramMessage($chat_id, "*از ارسال به گروه ها اطمینان دارید؟*", 'MarkDown', $message_id, $send_or_cancel);

                        break;
                    //get banner button link
                    case  8:
                        $cancel_button = json_encode(['keyboard' => [
                            [['text' => "لغو ❌"]],
                        ], 'resize_keyboard' => true]);
                        if ($text && strlen($text) > 1000) {
                            sendTelegramMessage($chat_id, "*طول پیام از 1000 حرف کمتر باشد*", 'MarkDown', $message_id, $cancel_button);
                            return;
                        }
                        $this->user->step = 9;
                        $this->user->save();
                        Storage::put("$from_id.txt", json_encode($message));
                        sendTelegramMessage($chat_id, "لینک دکمه را وارد کنید (باید با  //:https شروع شود)", 'MarkDown', $message_id, $cancel_button);

                        break;
                    //get banner button name
                    case  9:
                        $cancel_button = json_encode(['keyboard' => [
                            [['text' => "لغو ❌"]],
                        ], 'resize_keyboard' => true]);
                        if ($text && (strlen($text) > 50 || strpos($text, "https://"))) {
                            sendTelegramMessage($chat_id, "*طول لینک از 50 حرف کمتر باشد و با  //:https شروع شود*", 'MarkDown', $message_id, $cancel_button);
                            return;
                        }
                        $this->user->step = 10;
                        $this->user->save();
                        $txt = Storage::get("$from_id.txt");
                        Storage::put("$from_id.txt", json_encode(['message' => $txt, 'link' => $text]));
                        sendTelegramMessage($chat_id, "متن دکمه را وارد کنید", 'MarkDown', $message_id, $cancel_button);

                        break;
                    //send banner
                    case  10:
                        $cancel_button = json_encode(['keyboard' => [
                            [['text' => "لغو ❌"]],
                        ], 'resize_keyboard' => true]);
                        if ($text && strlen($text) > 50) {
                            sendTelegramMessage($chat_id, "*متن دکمه از 50 حرف کمتر باشد*", 'MarkDown', $message_id, $cancel_button);
                            return;
                        }
                        $this->user->step = null;
                        $this->user->save();
                        $txt = json_decode(Storage::get("$from_id.txt"));
                        Storage::put("$from_id.txt", json_encode(['message' => $txt->message, 'link' => $txt->link, 'name' => $text,]));
                        $this->sendBanner($from_id, Storage::get("$from_id.txt"));
                        sendTelegramMessage($chat_id, "با موفقیت تولید شد!", 'MarkDown', $message_id, $button);
                        break;

                    //change tag
                    case  11:
                        $return = json_encode(['inline_keyboard' => [[['text' => "لغو ثبت تگ ❌", 'callback_data' => "channel_details$" . $this->user->remember_token]]], 'resize_keyboard' => true]);


                        if ($text && strlen($text) > 200) {
                            sendTelegramMessage($chat_id, " متن تگ از 200 حرف کمتر باشد " . PHP_EOL . "تعداد فعلی:" . strlen($text), null, null, $return);

                            deleteTelegramMessage($chat_id, $message_id);
                            return;
                        }
                        if (!$this->user || $this->user->remember_token == null) {
                            sendTelegramMessage($chat_id, " کانال یافت نشد.لطفا مجدد اقدام کنید ", null, null, $return);
                            deleteTelegramMessage($chat_id, $message_id);
                            return;
                        }
//
//
                        Channel::where('chat_id', $this->user->remember_token)->update(['tag' => $text]);
                        $details = json_encode(['inline_keyboard' => [[['text' => "بازگشت⬅", 'callback_data' => "channel_details$" . $this->user->remember_token]]], 'resize_keyboard' => false]);
                        $this->user->remember_token = null;
                        $this->user->step = null;

                        $this->user->save();
                        deleteTelegramMessage($chat_id, $message_id);
                        deleteTelegramMessage($chat_id, $message_id - 1);
                        deleteTelegramMessage($chat_id, $message_id - 2);

                        sendTelegramMessage($from_id, "✅تگ با موفقیت تغییر یافت", null, null, $details);
                        break;


                }

            } elseif ($text == '🛒 بازار 🛒' || $text == '❌لغو ساخت فروشگاه' || $Data == 'bazar$main' || strpos($Data, 'bazar$delete$') !== false) {
//                if (strpos($Data, 'bazar$delete$') !== false && strpos($this->user->remember_token, 'bazar$newShop$') !== false) {
//                    $input = explode('$', $Data);
//                    $id = $input[2];
//                    Shop::where('id', $id)->delete();
//                }
                if (strpos($this->user->remember_token, 'bazar$newShop$') !== false) {
                    $input = explode('$', $this->user->remember_token);
                    $id = $input[2];
                    $shop = Shop::where('id', $id)->first();
                    if ($this->user->role != 'go' && $this->user->id != $shop->user_id && !Rule::where('user_id', $this->user->id)->where('shop_id', $shop->id)->exists())
                        return;
                    $shop->delete();
                }

                $this->user->remember_token = null;
                $this->user->save();

                $bazar_button = [];
                $bazar_button[] = [['text' => "❇️فروشگاه جدید❇️", 'callback_data' => 'bazar$newShop']];
                $bazar_button[] = [['text' => "🔎جست و جوی بازار🔍", 'callback_data' => 'bazar$searchBazar']];
//                $bazar_button[] = [['text' => 'منوی اصلی⬅', 'callback_data' => 'bazar$main']];

                if ($this->user->role == 'go')
                    foreach (Shop::get() as $shop)
                        $bazar_button[] = [['text' => $shop->name, 'callback_data' => 'bazar$getShop$' . $shop->id]];
                else
                    foreach (Shop::orWhere('user_id', $this->user->id)->orWhereIn('id', Rule::where('user_id', $this->user->id)->pluck('shop_id'))->get() as $shop)
                        $bazar_button[] = [['text' => $shop->name, 'callback_data' => 'bazar$getShop$' . $shop->id]];


                $bazar_button = json_encode(['inline_keyboard' => $bazar_button
                    , 'resize_keyboard' => true]);

//                $this->user->step = null; // for register channel
//                $this->user->remember_token = 'bazar$';
//                $this->user->save();
//                sendTelegramMessage($chat_id, "🛍!به بازار مگنت گرام خوش آمدید🛍", null, null, $cancelbutton);
                sendTelegramMessage($chat_id, "🛍به بازارچه ورتا خوش آمدید🛍" . PHP_EOL . "💰 سکه شما: " . $this->user->score . PHP_EOL . "💰 ساخت فروشگاه جدید: " . Helper::$create_shop_score . PHP_EOL .
                    "🏪 تعداد فروشگاه ها: " . Shop::count() . PHP_EOL . "📦 تعداد محصولات: " . Product::count() . PHP_EOL . "💰 ساخت محصول جدید: " . Helper::$create_product_score . PHP_EOL . '', null, null, $bazar_button);

                return;


            } elseif (strpos($Data, "bazar$") !== false || strpos($this->user->remember_token, "bazar$") !== false) {

//                if (!in_array($from_id, Helper::$logs)) {
//                    $this->popupMessage($data_id, "🚧در حال ساخت هستیم!");
//                    return;
//                }


                $input = $Data ? explode('$', $Data) : explode('$', $this->user->remember_token);
                $command = $input[1];
                $param = count($input) > 2 ? $input[2] : null;
                $param2 = count($input) > 3 ? $input[3] : null;
                $param3 = count($input) > 4 ? $input[4] : null;
                $param4 = count($input) > 5 ? $input[5] : null;
                if ($command == 'newShop') {

                    if ($this->user->score < Helper::$create_shop_score) {
                        $this->popupMessage($data_id, "📛 سکه کافی برای این کار ندارید." . PHP_EOL . "💰 حداقل سکه مورد نیاز:" . Helper::$create_shop_score . PHP_EOL . "💰 سکه شما:" . $this->user->score . PHP_EOL . "برای دریافت سکه،به ادمین پیام دهید." . PHP_EOL . Helper::$admin);
                        return;
                    }
                    if ($param == null) {
                        $shop = Shop::create(['user_id' => $this->user->id]);
                        $param = $shop->id;
                    }
                    $cancel_create_button = json_encode(['inline_keyboard' => [[['text' => '❌لغو ساخت فروشگاه', 'callback_data' => 'bazar$delete$' . $param]]]
                        , 'resize_keyboard' => false]);

                    $shop = Shop::where('id', $param)->first();
                    if ($this->user->role != 'go' && $this->user->id != $shop->user_id && !Rule::where('user_id', $this->user->id)->where('shop_id', $shop->id)->exists())
                        return;
                    switch ($param2) {

                        case null:
                            $this->user->remember_token = 'bazar$newShop$' . $shop->id . '$1';
                            $this->user->save();
                            sendTelegramMessage($from_id, ' 🛒 ' . "نام فروشگاه خود را وارد کنید (مثال: لوازم آرایشی ورتا)" . PHP_EOL, null, null, $cancel_create_button);
                            break;
                        //name
                        case '1':
                            if (mb_strlen($text) >= 50) {
                                sendTelegramMessage($from_id, ' 📛 ' . "نام فروشگاه حداکثر 50 کلمه باشد" . PHP_EOL . 'تعداد فعلی: ' . mb_strlen($text), null, null, $cancel_create_button);

                            } else {

                                $shop->name = $text;
                                $this->user->remember_token = 'bazar$newShop$' . $shop->id . '$2';
                                $this->user->save();
                                $shop->save();
                                sendTelegramMessage($from_id, ' 🛒 ' . "ابتدا ربات را ادمین کانال خود کنید. سپس تگ کانال خود را با @ وارد کنید. ( مثال: @vartashop )" . PHP_EOL, null, null, $cancel_create_button);

                            }
                            break;
                        //telegram channel
                        case '2':

                            if (($ch = Channel::where('chat_username', $text)->where('user_id', '!=', $this->user->id)->exists()) && $this->user->role != 'go') {
                                $user = User::where('id', $ch->user_id)->first();
                                $admin = '👤Admin: ' . ($user->telegram_username != "" && $user->telegram_username != "@" ? "$user->telegram_username" :
                                        "[$user->name](tg://user?id=$user->telegram_id)") . PHP_EOL;

                                sendTelegramMessage($from_id, ' 📛 ' . "این کانال به نام شخص دیگری ثبت شده است" . PHP_EOL . $admin . PHP_EOL, 'Markdown', null, $cancel_create_button);

                            } elseif ($this->get_chat_type($text) != 'channel') {
                                sendTelegramMessage($from_id, ' 📛 ' . "ورودی شما از نوع کانال نیست و یا ربات را بلاک کرده اید" . PHP_EOL, 'Markdown', null, $cancel_create_button);

                            } else {
                                $info = $this->getChatInfo($text);
                                $chat = Channel::where('chat_username', $text)->where('user_id', $this->user->id)->first();

                                if ($chat) {
                                    $chat->chat_username = "@$info->username";
//                                    $chat->chat_title = $info->title;
//                                    $chat->chat_description = $info->description;
//                                    $chat->image = $timestamp;
                                    $chat->save();
                                } else {
                                    $chat = Channel::create([
//                                        'image' => $timestamp,
                                        'user_id' => $this->user->id,
                                        'shop_id' => $shop->id,
//                                        'group_id' => null,
                                        'user_telegram_id' => $this->user->telegram_id,
                                        'chat_id' => "$info->id",
//                                        'chat_type' => 'c',
                                        'chat_username' => "@" . $info->username,
//                                        'chat_title' => $info->title,
//                                        'chat_description' => $info->description,
//                                        'chat_main_color' => simple_color_thief(storage_path("app/public/chats/$timestamp.jpg"))
                                    ]);
                                }
                                createChatImage($info->photo, $shop->id);
                                $shop->channel_address = "$info->id";
                                $this->user->remember_token = 'bazar$newShop$' . $shop->id . '$3';
                                $shop->save();
                                $this->user->save();

                                $contact_keyboard = json_encode(['keyboard' => [
                                    [['text' => '☎️ارسال اطلاعات تماس', 'request_contact' => true]],
                                    [['text' => '❌لغو ساخت فروشگاه']],
                                ], 'resize_keyboard' => true]);

                                sendTelegramMessage($from_id, ' 🛒 ' . "دکمه ☎️ارسال اطلاعات تماس را از پایین زده و تایید کنید" . PHP_EOL, null, null, $contact_keyboard);


                            }
                            break;
                        case '3':
                            if ($phone_number == null) {
                                $contact_keyboard = json_encode(['keyboard' => [
                                    [['text' => '☎️ارسال اطلاعات تماس', 'request_contact' => true]],
                                    [['text' => '❌لغو ساخت فروشگاه']],
                                ], 'resize_keyboard' => true]);
                                sendTelegramMessage($from_id, ' 📛 ' . "دکمه ☎️ارسال اطلاعات تماس را از پایین زده و تایید کنید" . PHP_EOL, 'Markdown', null, $contact_keyboard);
                            } else {
                                $group_id_button = [];
                                foreach (Group::whereIn('id', [31, 33, 35, 36, 37, 38, 39, 40])->get() as $g) {
                                    $group_id_button[] = [['text' => "$g->name $g->emoji", 'callback_data' => 'bazar$newShop$' . $shop->id . '$4$' . $g->id]];
                                }
                                $group_id_button[] = [['text' => '❌لغو ساخت فروشگاه', 'callback_data' => 'bazar$delete$' . $param]];
                                $group_id_button = json_encode(['inline_keyboard' => $group_id_button, 'resize_keyboard' => true]);

                                $shop->contact = $phone_number;
                                $this->user->remember_token = 'bazar$newShop$' . $shop->id . '$4';
                                $shop->save();
                                $this->user->save();

                                sendTelegramMessage($from_id, ' 🛒 ' . "شماره تماس دریافت شد!", 'Markdown', null, $button);
                                sendTelegramMessage($from_id, ' 🛒 ' . "دسته بندی فروشگاه خود را انتخاب کنید. در صورت نبودن دسته مربوط به خود، سایر را زده و به ادمین اطلاع دهید." . PHP_EOL . Helper::$admin, 'Markdown', null, $group_id_button);

                            }
                            logAdmins($phone_number, 'Markdown');
                            break;
                        case '4':
                            if ($param3 == null || !in_array($param3, Group::get()->pluck('id')->toArray())) {
                                $group_id_button = [];
                                foreach (Group::where('id', '>=', 20)->get() as $g) {
                                    $group_id_button[] = [['text' => "$g->name $g->emoji", 'callback_data' => 'bazar$newShop$' . $shop->id . '$4$' . $g->id]];
                                }
                                $group_id_button[] = [['text' => '❌لغو ساخت فروشگاه', 'callback_data' => 'bazar$delete$' . $param]];
                                $group_id_button = json_encode(['inline_keyboard' => $group_id_button, 'resize_keyboard' => true]);
                                sendTelegramMessage($from_id, ' 📛 ' . "دسته بندی فروشگاه خود را انتخاب کنید. در صورت نبودن دسته مربوط به خود، سایر را زده و به ادمین اطلاع دهید." . PHP_EOL . Helper::$admin, 'Markdown', null, $group_id_button);

                            } else {
//                                Channel::where('chat_id', "$shop->channel_address")->update(['group_id' => $param3]);
                                $shop->group_id = $param3;
                                $this->user->remember_token = 'bazar$newShop$' . $shop->id . '$5';
                                $shop->save();
                                $this->user->save();

                                sendTelegramMessage($from_id, ' 🛒 ' . "توضیحات فروشگاه خود مانند آدرس،ویژگی ها، شعار،آدرس سایت و اینستاگرام، شماره تماس و ... را وارد کنید (حداکثر 500 کلمه)" . PHP_EOL, null, null, $cancel_create_button);

                            }
                            break;
                        case '5':
                            if (mb_strlen($text) >= 500) {
                                sendTelegramMessage($from_id, ' 📛 ' . "توضیحات فروشگاه حداکثر 500 کلمه باشد" . PHP_EOL . 'تعداد فعلی: ' . mb_strlen($text), null, null, $cancel_create_button);

                            } else {

                                $bazar_button = json_encode(['inline_keyboard' => [[['text' => 'منوی اصلی⬅', 'callback_data' => 'bazar$main']]]
                                    , 'resize_keyboard' => false]);

                                $shop->description = $text;
                                $this->user->remember_token = null;
                                $this->user->score -= Helper::$create_shop_score;
                                $this->user->save();
                                $shop->save();
                                sendTelegramMessage($from_id, ' ✅ ' . 'تبریک' . PHP_EOL . "فروشگاه شما با موفقیت ساخته شد!" . PHP_EOL, null, null, $bazar_button);

                                logAdmins(" ✅🛒 " . " یک فروشگاه اضافه شد " . Channel::where('chat_id', "$shop->channel_address")->first()->chat_username);
                                sendTelegramMessage(Helper::$channel, " ✅🛒 " . " یک فروشگاه اضافه شد " . PHP_EOL . $shop->name . PHP_EOL . Channel::where('chat_id', "$shop->channel_address")->first()->chat_username);

                            }
                            break;
                    }
                } elseif ($command == 'getShop') {
                    $this->user->remember_token = null;
                    $this->user->save();
                    $shop_button = [];

                    $sh = array('🔴', '🟠', '🟡', '🟢', '🔵', '🟣', '⚪');
                    foreach (Product::withoutGlobalScopes()->where('shop_id', $param)->latest()->take(50)->get() as $product) {

                        $shop_button[] = [['text' => $sh[array_rand($sh, 1)] . " " . $product->name, 'callback_data' => 'bazar$getProduct$' . $product->id]];
                    }
                    $shop_button[] = [['text' => "🔎جستجوی محصولات🔍", 'callback_data' => 'bazar$searchProduct$' . $param]];
                    $shop_button[] = [['text' => '🧮مدیریت فروشگاه🧮', 'callback_data' => 'bazar$manageShop$' . $param]];
                    $shop_button[] = [['text' => "❇️محصول جدید❇️", 'callback_data' => 'bazar$newProduct$' . $param]];
                    $shop_button[] = [['text' => 'منوی اصلی⬅', 'callback_data' => 'bazar$main']];
                    $shop_button = json_encode(['inline_keyboard' => $shop_button
                        , 'resize_keyboard' => false]);

                    $shop = Shop::where('id', $param)->first();
                    if ($this->user->role != 'go' && $this->user->id != $shop->user_id && !Rule::where('user_id', $this->user->id)->where('shop_id', $shop->id)->exists())
                        return;
                    $g = Group::where('id', $shop->group_id)->first();
                    $channel = Channel::where('chat_id', "$shop->channel_address")->first();

                    $txt = "🔹نام فروشگاه: " . $shop->name . PHP_EOL;
                    $txt .= "🔻توضیحات: " . PHP_EOL . "\xD8\x9C" . "➖➖➖➖➖➖➖➖➖➖➖" . PHP_EOL . $shop->description . PHP_EOL . "\xD8\x9C" . "➖➖➖➖➖➖➖➖➖➖➖" . PHP_EOL;
                    $txt .= "🔸آدرس کانال: " . $channel->chat_username . PHP_EOL;
                    $txt .= "🔹موضوع: " . " $g->emoji " . "#$g->name" . PHP_EOL;
                    $txt .= "🔻تعداد محصولات: " . Product::withoutGlobalScopes()->where('shop_id', $shop->id)->count() . PHP_EOL;
                    $txt .= '🔸وضعیت: ' . ($shop->active ? "✅فعال" : "📛غیر فعال") . PHP_EOL . " ";
                    sendTelegramMessage($from_id, MarkDown($txt), 'MarkDown', null, $shop_button);
//                    sendTelegramPhoto($from_id, asset("storage/chats/$channel->image.jpg"), MarkDown($txt), null, $shop_button);


                } elseif ($command == 'manageShop') {

                    $shop = Shop::where('id', $param)->first();

                    if ($this->user->role != 'go' && $this->user->id != $shop->user_id && !Rule::where('user_id', $this->user->id)->where('shop_id', $shop->id)->exists()) {
                        if ($Data) $this->popupMessage($data_id, ' 📛 ' . " مجاز نیستید! ");
                        return;
                    }

                    $this->user->remember_token = null;
                    $this->user->save();

                    $return_button = json_encode(['inline_keyboard' => [[['text' => 'بازگشت⬅', 'callback_data' => 'bazar$manageShop$' . $shop->id]]]
                        , 'resize_keyboard' => false]);

                    switch ($param2) {
                        case null:
                            $shop_button = [];
                            $shop_button[] = [['text' => "🔻ویرایش کانال", 'callback_data' => 'bazar$manageShop$' . $shop->id . '$editChannel']];
                            $shop_button[] = [['text' => 'بازگشت⬅', 'callback_data' => 'bazar$getShop$' . $shop->id]];

                            $shop_button = json_encode(['inline_keyboard' => $shop_button
                                , 'resize_keyboard' => false]);

                            $txt = "مدیریت فروشگاه" . PHP_EOL . "گزینه مورد نظر خود را انتخاب کنید";

                            sendTelegramMessage($from_id, $txt, null, null, $shop_button);

                            break;
                        case 'editChannel':
                            $this->user->remember_token = 'bazar$manageShop$' . $shop->id . '$editChannel';
                            $this->user->save();


                            $shop = Shop::where('id', $param)->first();
                            if ($this->user->role != 'go' && $this->user->id != $shop->user_id && !Rule::where('user_id', $this->user->id)->where('shop_id', $shop->id)->exists()) {
                                if ($Data) $this->popupMessage($data_id, ' 📛 ' . " مجاز نیستید! ");
                                return;
                            }
                            if ($text == null) {
                                sendTelegramMessage($from_id, "نام کانال خود را با @ وارد کنید.", null, null, $return_button);
                            } else {
                                $channel = $text;


                                if ($ch = Channel::where('chat_username', $channel)->where('user_id', '!=', $this->user->id)->exists() && $this->user->role != 'go') {
                                    $user = User::where('id', $ch->user_id)->first();
                                    $admin = '👤Admin: ' . ($user->telegram_username != "" && $user->telegram_username != "@" ? "$user->telegram_username" :
                                            "[$user->name](tg://user?id=$user->telegram_id)") . PHP_EOL;

                                    sendTelegramMessage($from_id, ' 📛 ' . "این کانال به نام شخص دیگری ثبت شده است" . PHP_EOL . $admin . PHP_EOL, 'Markdown', null, $return_button);

                                } elseif ($this->get_chat_type($channel) != 'channel') {
                                    sendTelegramMessage($from_id, ' 📛 ' . "ورودی شما از نوع کانال نیست و یا ربات را بلاک کرده اید" . PHP_EOL, 'Markdown', null, $return_button);

                                } else {
                                    $info = $this->getChatInfo($channel);
                                    $chat = Channel::where('chat_username', $channel)->where('user_id', $this->user->id)->first();
                                    if ($chat) {
                                        $chat->chat_username = "@$info->username";
//                                        $chat->chat_title = $info->title;
//                                $chat->chat_description = $info->description;
//                                    $chat->image = $timestamp;
                                        $chat->save();
                                    } else {
                                        $chat = Channel::create([
//                                        'image' => $timestamp,
                                            'user_id' => $this->user->id,
                                            'shop_id' => $shop->id,
//                                        'group_id' => null,
//                                    'user_telegram_id' => $this->user->telegram_id,
                                            'chat_id' => "$info->id",
//                                        'chat_type' => 'c',
                                            'chat_username' => "@" . $info->username,
//                                    'chat_title' => $info->title,
//                                    'chat_description' => $info->description,
//                                        'chat_main_color' => simple_color_thief(storage_path("app/public/chats/$timestamp.jpg"))
                                        ]);
                                    }
                                    createChatImage($info->photo, $shop->id);

                                    $shop->channel_address = "$info->id";
                                    $shop->save();
                                    sendTelegramMessage($from_id, ' ✅ ' . "کانال " . "@" . $info->username . ' به این فروشگاه متصل شد', null, null, $return_button);
                                    $this->user->remember_token = null;
                                    $this->user->save();
                                }
                            }
//                    $shop = Shop::where('id', $product->shop_id)->first();
                            break;
                    }
                } elseif ($command == 'sendProductBanner') {
                    $product = Product::withoutGlobalScopes()->where('id', $param)->first();
                    $shop = Shop::where('id', $product->shop_id)->first();
                    $channel = Channel::where('chat_id', "$shop->channel_address")->first();
                    $tag = ($channel->tag) ?? "\xD8\x9C" . "➖➖➖➖➖➖➖➖➖➖➖" . PHP_EOL . $channel->chat_username;

                    $caption = ($product->discount_price > 0 ? "🔥 #حراج" : "") . PHP_EOL;
                    $caption .= ' 🆔 ' . "کد محصول: #" . $product->id . PHP_EOL;
                    $caption .= ' 🔻 ' . "نام: " . $product->name . PHP_EOL;
//                    $caption .= ' ▪️ ' . "تعداد موجود: " . $product->count . PHP_EOL;
                    $caption .= ' 🔸 ' . "قیمت: " . ($product->price == 0 ? 'پیام دهید' : number_format($product->price) . ' ت ') . PHP_EOL;
                    if ($product->discount_price > 0)
                        $caption .= ' 🔹 ' . "قیمت حراج: " . number_format($product->discount_price) . ' ت ' . PHP_EOL;
                    $caption .= ' 🔻 ' . "توضیحات: " . PHP_EOL . "\xD8\x9C" . "➖➖➖➖➖➖➖➖➖➖➖" . PHP_EOL . $product->description . PHP_EOL . "\xD8\x9C" . "➖➖➖➖➖➖➖➖➖➖➖" . PHP_EOL;
                    $caption .= $product->tags . PHP_EOL;
                    $caption .= $tag . PHP_EOL;
                    $caption .= "🆅🅰🆁🆃🅰🆂🅷🅾🅿" . PHP_EOL . Helper::$site . PHP_EOL;
                    $caption = MarkDown($caption);

                    $images = [];

                    foreach (Image::where('type', 'p')->where('for_id', $product->id)->get() as $item) {
                        $images[] = ['type' => 'photo', 'media' => str_replace('https://', '', asset("storage/products/$item->id.jpg")), 'caption' => $caption, 'parse_mode' => 'Markdown',];
                    }
                    if (count($images) == 0) {
                        sendTelegramMessage($from_id, "برای جذب مخاطب توصیه میشود محصول شما حداقل یک تصویر داشته باشد.");
                        if (Storage::exists("public/shops/$shop->id.jpg")) {
                            sendTelegramPhoto(Helper::$channel, asset("storage/shops/$shop->id.jpg"), $caption, null, null);
                            $res = sendTelegramPhoto($channel->chat_username, asset("storage/shops/$shop->id.jpg"), $caption, null, null);
                            if ($res && $res->ok == false) {
                                sendTelegramMessage($from_id, "🔻 ارسال ناموفق بود." . PHP_EOL . '🔻 ربات باید ادمین کانال شما باشد و اجازه ارسال پیام به کانال شما را داشته باشد' . PHP_EOL . "($res->description)");
                            }
                        } else {
                            sendTelegramMessage(Helper::$channel, $caption, null, null);
                            $res = sendTelegramMessage($channel->chat_username, $caption, null, null);
                            if ($res && $res->ok == false) {
                                sendTelegramMessage($from_id, "🔻 ارسال ناموفق بود." . PHP_EOL . '🔻 ربات باید ادمین کانال شما باشد و اجازه ارسال پیام به کانال شما را داشته باشد' . PHP_EOL . "($res->description)");
                            }
                        }
                    } elseif (!$channel) {
                        sendTelegramMessage($from_id, "برای فروشگاه خود کانالی تعریف نکرده اید.");
//                        sendTelegramPhoto($channel->chat_username, asset("storage/chats/$channel->image.jpg"), $caption, null, null);
                    } elseif (count($images) == 1) {

                        $res = sendTelegramPhoto(Helper::$channel, $images[0]['media'], $caption, null, null);
                        $res = sendTelegramPhoto($channel->chat_id, $images[0]['media'], $caption, null, null);
                        if ($res && $res->ok == false) {
                            sendTelegramMessage($from_id, "🔻 ارسال ناموفق بود." . PHP_EOL . '🔻 ربات باید ادمین کانال شما باشد و اجازه ارسال پیام به کانال شما را داشته باشد' . PHP_EOL . "($res->description)");
                        }
                    } else {
                        $images = [];
                        foreach (Image::where('type', 'p')->where('for_id', $product->id)->get() as $item) {
                            if ($caption) {
                                $images[] = ['type' => 'photo', 'media' => asset("storage/products/$item->id.jpg"), 'caption' => $caption, 'parse_mode' => 'Markdown',];
                                $caption = null;
                            } else {

                                $images[] = ['type' => 'photo', 'media' => asset("storage/products/$item->id.jpg")];
                            }

                        }
                        $res = sendTelegramMediaGroup(Helper::$channel, $images);
                        $res = sendTelegramMediaGroup($channel->chat_id, $images);
                        if ($res && $res->ok == false)
                            sendTelegramMessage($from_id, "🔻 ارسال ناموفق بود." . PHP_EOL . '🔻 ربات باید ادمین کانال شما باشد و اجازه ارسال پیام به کانال شما را داشته باشد' . PHP_EOL . "($res->description)");

                    }

                } elseif ($command == 'getProduct') {
//                    $res = sendTelegramMessage($chat_id, "در حال دریافت ...", null);
//                    if ($res && $res->ok == true)
//                        $messageId = $res->result->message_id;
                    $this->user->remember_token = null;
                    $this->user->save();
                    $product = Product::withoutGlobalScopes()->where('id', $param)->first();
                    $shop = Shop::where('id', $product->shop_id)->first();
                    $channel = Channel::where('chat_id', "$shop->channel_address")->first();

                    if ($this->user->role != 'go' && $this->user->id != $shop->user_id && !Rule::where('user_id', $this->user->id)->where('shop_id', $shop->id)->exists())
                        return;


                    $product_button = [];
                    $product_button[] = [['text' => "🔻ویرایش تعداد موجود", 'callback_data' => 'bazar$editProduct$' . $product->id . '$count'],
                        ['text' => "▪️ویرایش قیمت", 'callback_data' => 'bazar$editProduct$' . $product->id . '$price']];
                    $product_button[] = [['text' => "🔸ویرایش تخفیف", 'callback_data' => 'bazar$editProduct$' . $product->id . '$discount_price'],

                        ['text' => "🔹ویرایش تصویر", 'callback_data' => 'bazar$editProduct$' . $product->id . '$image']];
                    $product_button[] = [['text' => "🔻ویرایش نام", 'callback_data' => 'bazar$editProduct$' . $product->id . '$name'],
                        ['text' => "▪️ویرایش توضیحات", 'callback_data' => 'bazar$editProduct$' . $product->id . '$description']];
                    $product_button[] = [['text' => "🔸ویرایش تگ", 'callback_data' => 'bazar$editProduct$' . $product->id . '$tags'],
                        ['text' => "🔸ویرایش دسته بندی", 'callback_data' => 'bazar$editProduct$' . $product->id . '$group_id']];

                    $product_button[] = [['text' => "📤ارسال بنر به کانال", 'callback_data' => 'bazar$sendProductBanner$' . $product->id]];
//                    $product_button[] = [['text' => "📛📛📛پاک کردن محصول (بدون بازگشت)📛📛📛", 'callback_data' => 'bazar$deleteProduct$' . $product->id . "$" . ($message_id + 1)]];


                    $product_button[] = [['text' => 'منوی اصلی⬅', 'callback_data' => 'bazar$getShop$' . $shop->id]];
                    $product_button = json_encode(['inline_keyboard' => $product_button
                        , 'resize_keyboard' => false]);

                    $caption = "";
                    $caption .= ' 🆔 ' . "کد محصول: #" . $product->id . PHP_EOL;
                    $caption .= ' 🔻 ' . "نام: " . $product->name . PHP_EOL;
                    $caption .= ' 🔹 ' . "دسته بندی: " . ($product->group ? $product->group->name : 'نا مشخص') . PHP_EOL;
                    $caption .= ' ▪️ ' . "تعداد موجود: " . $product->count . PHP_EOL;
                    $caption .= ' 🔸 ' . "قیمت: " . number_format($product->price) . PHP_EOL;
                    $caption .= ' 🔹 ' . "قیمت تخفیف: " . ($product->discount_price == 0 ? 'غیر فعال' : number_format($product->discount_price)) . PHP_EOL;
                    $caption .= ' 🔻 ' . "توضیحات: " . PHP_EOL . "\xD8\x9C" . "➖➖➖➖➖➖➖➖➖➖➖" . PHP_EOL . $product->description . PHP_EOL . "\xD8\x9C" . "➖➖➖➖➖➖➖➖➖➖➖" . PHP_EOL;
                    $caption .= ' ▪️ ' . "تگ ها: " . PHP_EOL . $product->tags . PHP_EOL;
                    $caption .= ' 🔸 ' . "کانال: " . $channel->chat_username . PHP_EOL;
                    $caption = MarkDown($caption);
                    $images = [];

                    foreach (Image::where('type', 'p')->where('for_id', $product->id)->get() as $item) {
                        $images[] = ['type' => 'photo', 'media' => str_replace('https://', '', asset("storage/products/$item->id.jpg")), 'caption' => $caption, 'parse_mode' => 'Markdown',];
                    }
                    if (count($images) == 0) {
                        sendTelegramMessage($from_id, $caption, null, null, $product_button);
                    } elseif (count($images) == 1) {

                        sendTelegramPhoto($chat_id, $images[0]['media'], $caption, null, $product_button);

                    } else {
                        $images = [];
                        foreach (Image::where('type', 'p')->where('for_id', $product->id)->get() as $item) {
                            if ($caption) {
                                $images[] = ['type' => 'photo', 'media' => asset("storage/products/$item->id.jpg"), 'caption' => $caption, 'parse_mode' => 'Markdown',];
                                $caption = null;
                            } else {

                                $images[] = ['type' => 'photo', 'media' => asset("storage/products/$item->id.jpg")];
                            }

                        }
                        sendTelegramMediaGroup($from_id, $images, $product_button);
                        sendTelegramMessage($from_id, "برای ویرایش محصول از دکمه های زیر استفاده کنید", 'Markdown', null, $product_button);
                    }


                } elseif ($command == 'deleteProduct') {
                    $product = Product::withoutGlobalScopes()->where('id', $param)->first();

                    $shop = Shop::where('id', $product->shop_id)->first();

                    if ($this->user->role != 'go' && $this->user->id != $shop->user_id && !Rule::where('user_id', $this->user->id)->where('shop_id', $shop->id)->exists()) {
                        $this->popupMessage($data_id, ' 📛 ' . " مجاز نیستید! ");
                        return;
                    }
                    foreach (Image::where('type', 'p')->where('for_id', $product->id) as $img) {
                        if (Storage::exists("public/products/$img.jpg")) {
                            Storage::delete("public/products/$img.jpg");
                        }
                        $img->delete();
                    }

                    $product->delete();

                    $shop_button = json_encode(['inline_keyboard' => [[['text' => 'منوی اصلی⬅', 'callback_data' => 'bazar$getShop$' . $shop->id]]]
                        , 'resize_keyboard' => false]);
                    sendTelegramMessage($from_id, ' ✅ ' . "با موفقیت لغو شد " . PHP_EOL, null, null, $shop_button);
                    deleteTelegramMessage($chat_id, $message_id);

                } elseif ($command == 'newProduct') { //bazar$newProduct$19$312$1
                    if ($this->user->score < Helper::$create_product_score) {
                        $this->popupMessage($data_id, "📛 سکه کافی برای این کار ندارید." . PHP_EOL . "💰 حداقل سکه مورد نیاز:" . Helper::$create_product_score . PHP_EOL . "💰 سکه شما:" . $this->user->score . PHP_EOL . "برای دریافت سکه، می توانید به پشتیبانی اطلاع دهید." . PHP_EOL . Helper::$admin);
                        return;
                    }
                    $shop = Shop::where('id', $param)->first();
                    if ($this->user->role != 'go' && $this->user->id != $shop->user_id && !Rule::where('user_id', $this->user->id)->where('shop_id', $shop->id)->exists())
                        return;

                    if ($shop != null && $param2 == null) {
                        $product = Product::create(['user_id' => $this->user->id, 'shop_id' => $shop->id,]);
                        $param2 = $product->id;
                    }
                    $cancel_create_button = json_encode(['inline_keyboard' => [[['text' => '❌لغو ساخت محصول', 'callback_data' => 'bazar$deleteProduct$' . $param2]]]
                        , 'resize_keyboard' => false]);

                    $product = Product::withoutGlobalScopes()->where('id', $param2)->first();
                    switch ($param3) {

                        case null:
                            $this->user->remember_token = 'bazar$newProduct$' . $shop->id . '$' . $param2 . '$0';
                            $this->user->save();

                            $group_id_button = [];
                            $groups = Group::orWhere('level', '>', 0)->orWhereIn('id', Group::where('level', '>', 0)->pluck('parent'))->get();
                            foreach ($groups as $g) {
                                $group_id_button[] = [['text' => "$g->name $g->emoji", 'callback_data' => 'bazar$newProduct$' . $shop->id . '$' . $param2 . '$0$' . $g->id]];
                            }
                            $group_id_button[] = [['text' => '❌لغو ساخت محصول', 'callback_data' => 'bazar$deleteProduct$' . $param2]];
                            $group_id_button = json_encode(['inline_keyboard' => $group_id_button, 'resize_keyboard' => true]);


                            sendTelegramMessage($from_id, ' 🛒 ' . "دسته بندی محصول را انتخاب کنید: " . PHP_EOL . '(اگر دسته بندی جدید میخواهید به ادمین اطلاع دهید)' . PHP_EOL . Helper::$admin, null, null, $group_id_button);
                            break;
                        //group
                        case '0':
                            $exists = false;
                            $group_id_button = [];
                            $groups = Group::orWhere('level', '>', 0)->orWhereIn('id', Group::where('level', '>', 0)->pluck('parent'))->get();
                            foreach ($groups as $g) {
                                $group_id_button[] = [['text' => "$g->name $g->emoji", 'callback_data' => 'bazar$newProduct$' . $shop->id . '$' . $param2 . '$0$' . $g->id]];
                                if ($param4 == $g->id)
                                    $exists = true;
                            }
                            $group_id_button[] = [['text' => '❌لغو ساخت محصول', 'callback_data' => 'bazar$deleteProduct$' . $param2]];
                            $group_id_button = json_encode(['inline_keyboard' => $group_id_button, 'resize_keyboard' => true]);

                            if (!$exists) {
                                sendTelegramMessage($from_id, ' 📛 ' . "دسته بندی نامعتبر است" . PHP_EOL . 'مجدد انتخاب کنید: ', null, null, $group_id_button);

                            } else {

                                $product->group_id = $param4;
                                $this->user->remember_token = 'bazar$newProduct$' . $shop->id . '$' . $param2 . '$1';
                                $this->user->save();
                                $product->save();
                                sendTelegramMessage($from_id, ' 🛒 ' . "نام محصول خود را وارد کنید: " . PHP_EOL, null, null, $cancel_create_button);


                            }
                            break;//name
                        case '1':
                            if (mb_strlen($text) >= 100) {
                                sendTelegramMessage($from_id, ' 📛 ' . "نام محصول حداکثر 100 کلمه باشد" . PHP_EOL . 'تعداد فعلی: ' . mb_strlen($text), null, null, $cancel_create_button);

                            } else {

                                $product->name = $text;
                                $this->user->remember_token = 'bazar$newProduct$' . $shop->id . '$' . $param2 . '$2';
                                $this->user->save();
                                $product->save();
                                sendTelegramMessage($from_id, ' 🛒 ' . "توضیحات محصول را حداکثر در 500 کلمه وارد کنید (مثال : اندازه، رنگ، طعم، نحوه استفاده و ...)" . PHP_EOL, null, null, $cancel_create_button);

                            }
                            break;
                        //description
                        case '2':
                            if (mb_strlen($text) >= 1000) {
                                sendTelegramMessage($from_id, ' 📛 ' . "توضیحات محصول حداکثر 1000 کلمه باشد" . PHP_EOL . 'تعداد فعلی: ' . mb_strlen($text), null, null, $cancel_create_button);

                            } else {

                                $product->description = $text;
                                $this->user->remember_token = 'bazar$newProduct$' . $shop->id . '$' . $param2 . '$3';
                                $this->user->save();
                                $product->save();
                                sendTelegramMessage($from_id, ' 🛒 ' . "تعداد فعلی محصول را به صورت عدد انگلیسی وارد کنید" . PHP_EOL, null, null, $cancel_create_button);

                            }
                            break;
                        //count
                        case '3':
                            if ($text != 0 && !preg_match('/^[1-9][0-9]*$/', $text)) {
                                sendTelegramMessage($from_id, ' 📛 ' . "تعداد محصول تنها یک عدد انگلیسی و حداقل 0 باشد ", null, null, $cancel_create_button);

                            } else {

                                $product->count = $text;
                                $this->user->remember_token = 'bazar$newProduct$' . $shop->id . '$' . $param2 . '$4';
                                $this->user->save();
                                $product->save();
                                sendTelegramMessage($from_id, ' 🛒 ' . "قیمت فعلی محصول (به تومان) را به صورت عدد انگلیسی وارد کنید ( در صورت نوسان قیمت می توانید 0 وارد کنید تا 'تماس بگیرید' نمایش داده شود )" . PHP_EOL, null, null, $cancel_create_button);

                            }
                            break;
                        //price
                        case '4':
                            if ($text != 0 && preg_replace('/^[1-9][0-9]*$/', '', $text) != '') {
                                sendTelegramMessage($from_id, ' 📛 ' . "قیمت محصول تنها یک عدد انگلیسی و حداقل 0 باشد " . PHP_EOL, null, null, $cancel_create_button);

                            } else {

                                $product->price = $text;
                                $this->user->remember_token = 'bazar$newProduct$' . $shop->id . '$' . $param2 . '$5';
                                $this->user->save();
                                $product->save();
                                sendTelegramMessage($from_id, ' 🛒 ' . "تگ های محصول را وارد کنید (هر تگ در یک خط و مجموعا 100 کلمه باشد) مثال: کرم \nکرم مرطوب کننده \nمرطوب کننده" . PHP_EOL, null, null, $cancel_create_button);

                            }
                            break;
                        //tag
                        case '5':
                            $text = explode("\n", $text);
                            $tags = "";
                            foreach ($text as $t) {
                                if (!starts_with($t, '#'))
                                    $t = '#' . $t;
                                $t = str_replace(" ", "_", $t);
                                $tags .= $t . "\n";
                            }
                            if (mb_strlen($tags) >= 100) {
                                sendTelegramMessage($from_id, ' 📛 ' . " هر تگ در یک خط و مجموعا 100 کلمه باشد. مثال: کرم \nکرم مرطوب کننده \nمرطوب کننده" . PHP_EOL . 'تعداد فعلی: ' . mb_strlen($tags), null, null, $cancel_create_button);

                            } else {
                                $cancel_create_button = json_encode(['inline_keyboard' => [[['text' => '✅بازگشت به فروشگاه', 'callback_data' => 'bazar$getShop$' . $shop->id]]]
                                    , 'resize_keyboard' => false]);
//                                $text = explode("\n", $text);
//                                $tags = "";
//                                foreach ($text as $t) {
//                                    if (!starts_with($t, '#'))
//                                        $t = '#' . $t;
//                                    $t = str_replace(" ", "_", $t);
//                                    $tags .= $t . "\n";
//                                }

                                $product->tags = $tags;
                                $product->active = true;
                                $product->save();
                                $this->user->remember_token = 'bazar$getShop$' . $shop->id;
                                $this->user->score -= Helper::$create_product_score;
                                $this->user->save();
                                $text = null;
                                sendTelegramMessage($from_id, ' ✅ ' . 'تبریک' . PHP_EOL . "محصول شما با موفقیت ساخته شد!، دکمه بازگشت به فروشگاه را بزنید.\n🔸محصول را انتخاب و سپس  ویرایش تصاویر را بزنید و برای آن تصویر ثبت کنید.\n🔹میتوانید با دکمه ویرایش تخفیف، برای محصول خود تخفیف در نظر بگیرید.\n🔸می توانید بنر محصول را به کانال خود ارسال کنید." . PHP_EOL, null, null, $cancel_create_button);

                                logAdmins(" ✅🛒 " . " یک محصول اضافه شد " . PHP_EOL . "فروشگاه: $shop->name" . PHP_EOL . "محصول: $product->name" . PHP_EOL . Channel::where('chat_id', "$shop->channel_address")->first()->chat_username);

                            }
                            break;
                    }
                } elseif ($command == 'editProduct') {

                    $product = Product::withoutGlobalScopes()->where('id', $param)->first();
                    $shop = Shop::where('id', $product->shop_id)->first();
                    if ($this->user->role != 'go' && $this->user->id != $shop->user_id && !Rule::where('user_id', $this->user->id)->where('shop_id', $shop->id)->exists()) {
                        if ($Data) $this->popupMessage($data_id, ' 📛 ' . " مجاز نیستید! ");
                        return;
                    }
//                    $shop = Shop::where('id', $product->shop_id)->first();

                    $product_button = json_encode(['inline_keyboard' => [[['text' => 'بازگشت⬅', 'callback_data' => 'bazar$getProduct$' . $product->id]]]
                        , 'resize_keyboard' => false]);

                    switch ($param2) {
                        case 'group_id':
                            $this->user->remember_token = 'bazar$editProduct$' . $product->id . '$group_id';
                            $this->user->save();

                            $exists = false;
                            $group_id_button = [];
                            $groups = Group::orWhere('level', '>', 0)->orWhereIn('id', Group::where('level', '>', 0)->pluck('parent'))->get();
                            foreach ($groups as $g) {
                                $group_id_button[] = [['text' => "$g->name $g->emoji", 'callback_data' => 'bazar$editProduct$' . $product->id . '$group_id$' . $g->id]];
                                if ($param3 && $param3 == $g->id)
                                    $exists = true;
                            }
                            $group_id_button[] = [['text' => '❌لغو ویرایش', 'callback_data' => 'bazar$getProduct$' . $product->id]];
                            $group_id_button = json_encode(['inline_keyboard' => $group_id_button, 'resize_keyboard' => true]);


                            if ($Data && !$param3) {
                                deleteTelegramMessage($from_id, $message_id);
                                sendTelegramMessage($from_id, ' 🛒 ' . "دسته بندی محصول را انتخاب کنید: " . PHP_EOL . '(اگر دسته بندی جدید میخواهید به ادمین اطلاع دهید)' . PHP_EOL . Helper::$admin, null, null, $group_id_button);

                            } elseif ($Data && $param3) {
                                if (!$exists) {
                                    sendTelegramMessage($from_id, ' 📛 ' . "دسته بندی نامعتبر است" . PHP_EOL . 'مجدد انتخاب کنید: ', null, null, $group_id_button);

                                } else {
                                    $product->group_id = $param3;
                                    $this->user->remember_token = null;
                                    $this->user->save();
                                    $product->save();
                                    sendTelegramMessage($from_id, ' ✅ ' . 'با موفقیت ویرایش شد.', null, null, $product_button);

                                }
                            }
                            break;
                        case 'name':

                            $this->user->remember_token = 'bazar$editProduct$' . $product->id . '$name';
                            $this->user->save();
                            if ($Data) {
                                deleteTelegramMessage($from_id, $message_id);
                                sendTelegramMessage($from_id, "نام جدید محصول را وارد کنید و یا بازگشت را بزنید" . PHP_EOL . "نام فعلی: " . PHP_EOL . $product->name, null, null, $product_button);
                            } else {
                                if (mb_strlen($text) >= 100) {
                                    sendTelegramMessage($from_id, ' 📛 ' . "نام محصول حداکثر 100 کلمه باشد" . PHP_EOL . "تعداد فعلی: " . mb_strlen($text) . PHP_EOL . 'مجدد وارد کنید و یا بازگشت را بزنید ', null, null, $product_button);

                                } else {
                                    $product->name = $text;
                                    $this->user->remember_token = null;
                                    $this->user->save();
                                    $product->save();
                                    sendTelegramMessage($from_id, ' ✅ ' . 'با موفقیت ویرایش شد.', null, null, $product_button);

                                }
                            }
                            break;
                        case 'description':

                            $this->user->remember_token = 'bazar$editProduct$' . $product->id . '$description';
                            $this->user->save();
                            if ($Data) {
                                deleteTelegramMessage($from_id, $message_id);
                                sendTelegramMessage($from_id, "توضیحات جدید محصول را وارد کنید و یا بازگشت را بزنید" . PHP_EOL . "توضیحات فعلی: " . PHP_EOL . $product->description, null, null, $product_button);
                            } else {
                                if (mb_strlen($text) >= 1000) {
                                    sendTelegramMessage($from_id, ' 📛 ' . "توضیحات محصول حداکثر 1000 کلمه باشد" . PHP_EOL . "تعداد فعلی: " . mb_strlen($text) . PHP_EOL . 'مجدد وارد کنید و یا بازگشت را بزنید ', null, null, $product_button);

                                } else {
                                    $product->description = $text;
                                    $this->user->remember_token = null;
                                    $this->user->save();
                                    $product->save();
                                    sendTelegramMessage($from_id, ' ✅ ' . 'با موفقیت ویرایش شد.', null, null, $product_button);

                                }
                            }
                            break;
                        case 'tags':

                            $this->user->remember_token = 'bazar$editProduct$' . $product->id . '$tags';
                            $this->user->save();
                            if ($Data) {
                                deleteTelegramMessage($from_id, $message_id);
                                sendTelegramMessage($from_id, "تگ های جدید محصول را وارد کنید (هر تگ در یک خط و مجموعا 100 کلمه باشد. مثال: \nکرم \nکرم مرطوب کننده \nمرطوب کننده \n) و یا بازگشت را بزنید" . PHP_EOL . "تگ های فعلی: " . PHP_EOL . $product->tags, null, null, $product_button);
                            } else {
                                $text = explode("\n", $text);
                                $tags = "";
                                foreach ($text as $t) {
                                    if (!starts_with($t, '#'))
                                        $t = '#' . $t;
                                    $t = str_replace(" ", "_", $t);
                                    $tags .= $t . "\n";
                                }
                                if (mb_strlen($tags) >= 100) {
                                    sendTelegramMessage($from_id, ' 📛 ' . "تگ های محصول حداکثر 100 کلمه باشد" . PHP_EOL . "تعداد فعلی: " . mb_strlen($tags) . PHP_EOL . 'مجدد وارد کنید و یا بازگشت را بزنید ', null, null, $product_button);

                                } else {
                                    $product->tags = $tags;
                                    $this->user->remember_token = null;
                                    $this->user->save();
                                    $product->save();
                                    sendTelegramMessage($from_id, ' ✅ ' . 'با موفقیت ویرایش شد.', null, null, $product_button);

                                }
                            }
                            break;
                        case 'price':

                            $this->user->remember_token = 'bazar$editProduct$' . $product->id . '$price';
                            $this->user->save();
                            if ($Data) {
                                deleteTelegramMessage($from_id, $message_id);
                                sendTelegramMessage($from_id, "قیمت محصول (به تومان) را به صورت عدد انگلیسی وارد کنید ( در صورت نوسان قیمت می توانید 0 وارد کنید تا 'تماس بگیرید' نمایش داده شود ) و یا بازگشت را بزنید" . PHP_EOL . "قیمت فعلی: " . $product->price, null, null, $product_button);
                            } else {

                                if ($text != 0 && preg_replace('/^[1-9][0-9]*$/', '', $text) != '') {
                                    sendTelegramMessage($from_id, ' 📛 ' . "قیمت محصول به صورت عدد انگلیسی وارد شود" . PHP_EOL . 'مجدد وارد کنید و یا بازگشت را بزنید ', null, null, $product_button);

                                } elseif ($text != 0 && $text <= $product->discount_price) {
                                    sendTelegramMessage($from_id, ' 📛 ' . "قیمت محصول نباید از قیمت تخفیف کمتر یا برابر باشد" . PHP_EOL . 'قیمت تخفیف: ' . $product->discount_price . PHP_EOL . 'مجدد وارد کنید و یا بازگشت را بزنید ', null, null, $product_button);

                                } else {
                                    $product->price = $text;
                                    $this->user->remember_token = null;
                                    $this->user->save();
                                    $product->save();
                                    sendTelegramMessage($from_id, ' ✅ ' . 'با موفقیت ویرایش شد.', null, null, $product_button);

                                }
                            }
                            break;
                        case 'discount_price':

                            $this->user->remember_token = 'bazar$editProduct$' . $product->id . '$discount_price';
                            $this->user->save();
                            if ($Data) {
                                deleteTelegramMessage($from_id, $message_id);
                                sendTelegramMessage($from_id, "قیمت تخفیف محصول (به تومان) را به صورت عدد انگلیسی وارد کنید ( کمتر از قیمت اصلی باشد ) و یا بازگشت را بزنید" . PHP_EOL . "قیمت تخفیف فعلی: " . $product->discount_price, null, null, $product_button);
                            } else {

                                if ($text != 0 && !preg_match('/^[1-9][0-9]*$/', $text)) {
                                    sendTelegramMessage($from_id, ' 📛 ' . "قیمت تخفیف محصول به صورت عدد انگلیسی وارد شود" . PHP_EOL . 'مجدد وارد کنید و یا بازگشت را بزنید ', null, null, $product_button);

                                } elseif ($text != 0 && $text >= $product->price) {
                                    sendTelegramMessage($from_id, ' 📛 ' . "قیمت تخفیف محصول نباید از قیمت اصلی بیشتر یا برابر باشد" . PHP_EOL . 'قیمت اصلی: ' . $product->price . PHP_EOL . 'مجدد وارد کنید و یا بازگشت را بزنید ', null, null, $product_button);

                                } else {
                                    $product->discount_price = $text;
                                    $this->user->remember_token = null;
                                    $this->user->save();
                                    $product->save();
                                    sendTelegramMessage($from_id, ' ✅ ' . 'با موفقیت ویرایش شد.', null, null, $product_button);

                                }
                            }
                            break;

                        case 'count':
                            $this->user->remember_token = 'bazar$editProduct$' . $product->id . '$count';
                            $this->user->save();
                            if ($param4 == null)
                                $param4 = $product->count;
                            if ($text) {
                                if ($text != 0 && !preg_match('/^[1-9][0-9]*$/', $text)) {
                                    sendTelegramMessage($from_id, ' 📛 ' . "تعداد محصول تنها یک عدد انگلیسی و حداقل 0 باشد " . PHP_EOL, null, null, $product_button);
                                    return;
                                }
                            }
                            if ($param3 == 'accept' || $text) {
                                $this->user->remember_token = null;
                                $this->user->save();


                                $product_button = json_encode(['inline_keyboard' => [[['text' => 'بازگشت⬅', 'callback_data' => 'bazar$getProduct$' . $product->id]]]
                                    , 'resize_keyboard' => false]);
                                if ($Data) {
                                    $product->count = $param4;
                                    $txt = "با موفقیت ویرایش شد." . PHP_EOL . "تعداد فعلی: " . $product->count;
                                    $product->save();
                                    $this->EditMessageText($from_id, $message_id, ' ✅ ' . $txt, null, $product_button);
                                    $this->EditKeyboard($from_id, $message_id, $product_button);
                                }
                                if ($text) {
                                    $product->count = $text;
                                    $txt = "با موفقیت ویرایش شد." . PHP_EOL . "تعداد فعلی: " . $product->count;
                                    $product->save();
                                    sendTelegramMessage($from_id, ' ✅ ' . $txt, null, null, $product_button);

                                }
                                return;
                            }
                            $edit_product_count_button = json_encode(['inline_keyboard' => [
                                [
                                    ['text' => '🔻', 'callback_data' => 'bazar$editProduct$' . $product->id . '$count$d$' . ($param4 - 1)],
                                    ['text' => $param4, 'callback_data' => 'bazar$editProduct$' . $product->id . '$count$_$' . ($param4)],
                                    ['text' => '🔺', 'callback_data' => 'bazar$editProduct$' . $product->id . '$count$u$' . ($param4 + 1)],
                                ], [
                                    ['text' => '✅تایید✅', 'callback_data' => 'bazar$editProduct$' . $product->id . '$count$accept$' . ($param4)],
                                ],
                            ]
                                , 'resize_keyboard' => false]);
                            $txt = "تعداد محصول را با دکمه ها تغییر دهید و یا به صورت عدد انگلیسی ارسال کنید" . PHP_EOL . "تعداد فعلی: " . $product->count;
                            if ($Data && $caption) {
                                $this->EditMessageCaption($from_id, $message_id, $txt, null, $edit_product_count_button);

                            } elseif ($Data) {

//                                deleteTelegramMessage($from_id, $message_id);
                                $this->EditMessageText($from_id, $message_id, $txt, null, $edit_product_count_button);
                            } else
                                sendTelegramMessage($from_id, ' 🛒 ' . $txt, null, null, $edit_product_count_button);

                            break;

                        case 'image':
                            $image_upload_button = [];
                            $imgs = Image::where('type', 'p')->where('for_id', $product->id)->get();
                            for ($i = 0; $i < Helper::$product_image_limit - count($imgs); $i++) {
                                $image_upload_button[] = [['text' => "❇️تصویر جدید❇️", 'callback_data' => 'bazar$editProduct$' . $product->id . '$imageUpload$' . $i]];
                            }

                            $image_upload_button[] = [['text' => 'بازگشت⬅', 'callback_data' => 'bazar$getProduct$' . $product->id]];
                            $image_upload_button = json_encode(['inline_keyboard' => $image_upload_button
                                , 'resize_keyboard' => false]);

//                            $images = [];
//                            foreach ($imgs as $item) {
//                                $images[] = ['type' => 'photo', 'media' => asset("storage/products/$item->id.jpg"), 'caption' => $caption, 'parse_mode' => 'Markdown',];
//                            }
                            $txt = "از طریق دکمه های زیر می توانید تصاویر محصول را حذف یا اضافه کنید." . " (حد اکثر " . Helper::$product_image_limit . " تصویر مجاز است)";
                            $res = sendTelegramMessage($from_id, $txt, null, null, $image_upload_button);

                            if ($res && $res->ok == true) {
                                $messageId = $res->result->message_id;
                                foreach ($imgs as $item) {
                                    $image_button = json_encode(['inline_keyboard' => [[['text' => '⛔️حذف عکس⛔️', 'callback_data' => 'bazar$editProduct$' . $product->id . '$imageDelete$' . $item->id . '$' . ($messageId + 1)]]]
                                        , 'resize_keyboard' => false]);
                                    $images[] = ['type' => 'photo', 'media' => asset("storage/products/$item->id.jpg"), 'caption' => $caption, 'parse_mode' => 'Markdown',];
                                    sendTelegramPhoto($from_id, asset("storage/products/$item->id.jpg"), null, null, $image_button);
                                }

                            }

                            break;
                        case 'imageUpload':
                            if ($Data) {
                                $imgs = Image::where('type', 'p')->where('for_id', $product->id)->get();
                                if (count($imgs) >= Helper::$product_image_limit) {
                                    $this->popupMessage($data_id, ' 📛 ' . "حداکثر عکس مجاز برای یک محصول " . Helper::$product_image_limit . " عدد است. می توانید عکس های قبلی را حذف و جایگزین نمایید.");
                                    return;
                                }
                                sendTelegramMessage($chat_id, "عکس محصول را ارسال کنید", null);
                                $this->user->remember_token = 'bazar$editProduct$' . $product->id . '$imageUpload';
                                $this->user->save();
                            } elseif
                            ($this->user->remember_token) {


                                if ($photo || ($document && strpos($document->mime_type, 'image') !== false)) {
                                    $client = new \GuzzleHttp\Client();
                                    $image = Image::create(['for_id' => $product->id, 'type' => 'p']);

                                    $res = creator('getFile', [
                                        'file_id' => $photo ? $photo[count($photo) - 1]->file_id : $document->file_id,
                                    ]);
                                    if (!$res || $res->ok == false) {

                                        $product_button = json_encode(['inline_keyboard' => [[['text' => 'منوی اصلی⬅', 'callback_data' => 'bazar$getProduct$' . $product->id]]]
                                            , 'resize_keyboard' => false]);
                                        sendTelegramMessage($chat_id, ' 📛 ' . "مشکلی در ثبت عکس به وجود آمد. مجدد ارسال کنید و یا به صفحه محصول برگردید" . PHP_EOL . $res->description, null, null, $product_button);

                                        $image->delete();
                                        return;
                                    }
                                    $link = "https://api.telegram.org/file/bot" . env('TELEGRAM_BOT_TOKEN', 'YOUR-BOT-TOKEN') . "/" . $res->result->file_path;
                                    $r = Storage::put("public/products/$image->id.jpg", $client->get($link)->getBody());
                                    $image_button = json_encode(['inline_keyboard' => [[['text' => '⛔️حذف عکس⛔️', 'callback_data' => 'bazar$editProduct$' . $product->id . '$imageDelete$' . $image->id . '$' . ($message_id + 1)]],
                                        [['text' => 'منوی اصلی⬅', 'callback_data' => 'bazar$getProduct$' . $product->id]],
                                        [['text' => "❇️تصویر جدید❇️", 'callback_data' => 'bazar$editProduct$' . $product->id . '$imageUpload$1']]]
                                        , 'resize_keyboard' => false]);
                                    deleteTelegramMessage($chat_id, $message_id - 1);
                                    deleteTelegramMessage($chat_id, $message_id);
                                    sendTelegramPhoto($chat_id, asset("storage/products/$image->id.jpg"), null, null, $image_button);
                                } else {
                                    $product_button = json_encode(['inline_keyboard' => [[['text' => 'منوی اصلی⬅', 'callback_data' => 'bazar$getProduct$' . $product->id]]]
                                        , 'resize_keyboard' => false]);
                                    sendTelegramMessage($chat_id, ' 📛 ' . "فایل ورودی از نوع عکس نیست. یک عکس دیگر ارسال کنید و یا به صفحه محصول برگردید", null, null, $product_button);

                                }
                            }

                            break;
                        case 'imageDelete':
                            $img = Image::where('id', $param3)->first();
                            $messageId = $param4;
                            $shop = Shop::where('id', $product->shop_id)->first();
                            if ($this->user->role != 'go' && $this->user->id != $shop->user_id && !Rule::where('user_id', $this->user->id)->where('shop_id', $shop->id)->exists()) {
                                $this->popupMessage($data_id, ' 📛 ' . " مجاز نیستید! ");
                                return;
                            }
                            $img->delete();
                            Storage::delete("public/products/$img->id.jpg");
                            $this->popupMessage($data_id, ' ✅ ' . " با موفقیت حذف شد! ");
                            deleteTelegramMessage($chat_id, $messageId);
                            $this->user->remember_token = 'bazar$editProduct$' . $product->id . '$imageUpload';
                            $this->user->save();
                            break;


                    }


                } elseif ($command == 'searchBazar') {

                    if ($Data && $param) {

                        $product = Product::where('id', $param)->first();
                        $shop = Shop::where('id', $product->shop_id)->first();
                        $channel = Channel::where('chat_id', "$shop->channel_address")->first();
                        $tag = ($channel->tag) ?? "\xD8\x9C" . "➖➖➖➖➖➖➖➖➖➖➖" . PHP_EOL . $channel->chat_username;

                        $caption = ($product->discount_price > 0 ? "🔥 #حراج" : "") . PHP_EOL;
                        $caption .= ' 🆔 ' . "کد محصول: #" . $product->id . PHP_EOL;
                        $caption .= ' 🔻 ' . "نام: " . $product->name . PHP_EOL;
//                    $caption .= ' ▪️ ' . "تعداد موجود: " . $product->count . PHP_EOL;
                        $caption .= ' 🔸 ' . "قیمت: " . ($product->price == 0 ? 'پیام دهید' : number_format($product->price) . ' ت ') . PHP_EOL;
                        if ($product->discount_price > 0)
                            $caption .= ' 🔹 ' . "قیمت حراج: " . number_format($product->discount_price) . ' ت ' . PHP_EOL;
                        $caption .= ' 🔻 ' . "توضیحات: " . PHP_EOL . "\xD8\x9C" . "➖➖➖➖➖➖➖➖➖➖➖" . PHP_EOL . $product->description . PHP_EOL . "\xD8\x9C" . "➖➖➖➖➖➖➖➖➖➖➖" . PHP_EOL;
                        $caption .= $product->tags . PHP_EOL;
                        $caption .= $tag . PHP_EOL;
                        $caption = MarkDown($caption);

                        $images = [];

                        foreach (Image::where('type', 'p')->where('for_id', $product->id)->get() as $item) {
                            $images[] = ['type' => 'photo', 'media' => str_replace('https://', '', asset("storage/products/$item->id.jpg")), 'caption' => $caption, 'parse_mode' => 'Markdown',];
                        }
                        if (count($images) == 0) {
                            sendTelegramMessage($from_id, $caption, null, null);
                        } elseif (count($images) == 1) {

                            sendTelegramPhoto($from_id, $images[0]['media'], $caption, null, null);

                        } else {
                            $images = [];
                            foreach (Image::where('type', 'p')->where('for_id', $product->id)->get() as $item) {
                                if ($caption) {
                                    $images[] = ['type' => 'photo', 'media' => asset("storage/products/$item->id.jpg"), 'caption' => $caption, 'parse_mode' => 'Markdown',];
                                    $caption = null;
                                } else {

                                    $images[] = ['type' => 'photo', 'media' => asset("storage/products/$item->id.jpg")];
                                }

                            }
                            sendTelegramMediaGroup($from_id, $images);
                        }


                    } elseif ($Data) {

                        $this->user->remember_token = 'bazar$searchBazar';
                        $this->user->save();
                        sendTelegramSticker($chat_id, 'CAACAgIAAxkBAAEBdR5gw5iQj66hQHloyF2E4pY0OVuRrgACkgcAAkb7rAQh_s6rCTyjxx8E', $cancelBazarButton);
                        $shops_button = [];
                        foreach (Shop::get() as $shop) {
                            $channel = Channel::where('chat_id', "$shop->channel_address")->first();
                            if ($channel)
                                $shops_button[] = [['text' => $shop->name, 'url' => "https://t.me/" . str_replace("@", "", $channel->chat_username)]];
                        }
                        $shops_button = json_encode(['inline_keyboard' => $shops_button, 'resize_keyboard' => false]);
                        sendTelegramMessage($from_id, "قسمتی از نام محصول را بنویسید و یا وارد فروشگاه ها شوید...", null, null, $shops_button);
                    } elseif ($text && mb_strlen($text) < 50) {
                        $products_button = [];
                        foreach (Product::where('name', 'like', '%' . $text . '%')->inRandomOrder()->take(20)->get() as $product) {
                            $products_button[] = [['text' => $product->name, 'callback_data' => 'bazar$searchBazar$' . $product->id]];
                        }
                        if (count($products_button) == 0) {
                            sendTelegramMessage($from_id, "😫 متاسفانه نتیجه ای یافت نشد. می توانید عبارات دیگری را جست و جو کنید 😫", null);
                            return;
                        }
                        $products_button = json_encode(['inline_keyboard' => $products_button, 'resize_keyboard' => false]);

                        sendTelegramMessage($from_id, "🛒 برای نمایش جزییات هر محصول، روی آن کلیک کنید:", null, null, $products_button);
                    }

                }
                return;
            }

        } elseif ($tc == 'channel') {


            //lock tab channel between 0 AM and 8 AM (night tab)
            //lock tab channel between 2 PM and 3 PM (night tab)


//            date_default_timezone_set('Asia/Tehran');
//
//            $now = Carbon::now()->format('H:i:s');
//            $start = '00:10:00';
//            $end = '07:50:00';
//            if ($now >= $start && $now <= $end && Tab::where('chat_id', "$chat_id")->exists()) {
//                deleteTelegramMessage($chat_id, $message_id);
//                return;
//            }
//            //
//            $start = '14:05:00';
//            $end = '15:55:00';
//            if ($now >= $start && $now <= $end && Tab::where('chat_id', "$chat_id")->exists()) {
//                deleteTelegramMessage($chat_id, $message_id);
//                return;
//            }
//
//
//            //remove tag for active auto_tag channels

            if (!$Data && $ch = Channel::where('chat_id', "$chat_id")->where('auto_tag', true)->where('active', true)->first()) {

                if (($text && (strpos($text, Helper::$bot) !== false || strpos($text, $ch->chat_username) !== false)) ||
                    ($caption && (strpos($caption, Helper::$bot) !== false || strpos($caption, $ch->chat_username) !== false)))
                    return;

//                sendTelegramMessage(Helper::$logs[0], $update, null);
                $this->user = User::where('id', "$ch->user_id")->first();
                if (!$this->user || $this->user->score < Helper::$vip_limit)
                    $ch->tag = null;

                if ($text) {
                    $text = preg_replace("/@\w+/", "", $text);

//                    $text .= PHP_EOL . "〰️〰️〰️〰️" . "\xD8\x9C" . "〰️〰️〰️〰️";

                    $text = $text . PHP_EOL;


                    $text .= ($ch->tag) ?? "\xD8\x9C" . "➖➖➖➖➖➖➖➖➖➖➖" . PHP_EOL . $ch->chat_username;

                    $update->channel_post->text = $text;
//                    sendTelegramMessage(Helper::$logs[0], json_encode($update), null);

                    $res = deleteTelegramMessage($chat_id, $message_id);
                    if ($res->ok != true) {
                        sendTelegramMessage(Helper::$logs[0], $ch->chat_username . PHP_EOL . $res->result->description . PHP_EOL . json_encode($update), null);
//                        $ch->auto_tag = false;
                        $ch->save();
                        return;
                    }

                    $this->sendFile("$chat_id", json_encode($update->channel_post), $reply, false);


//                    if ($from_message_id) {
//                        sendTelegramMessage($chat_id, $text, null);
//
//                    } else {
//
//                        $this->EditMessageText($chat_id, $message_id, $text);
//                    }
//                $this->copyMessage($chat_id,);

                } elseif
                ($caption || $photo || $document || $video || $audio || $voice || $video_note || $this->user->remember_token) {

//                    if ($ch->chat_username == '@magnetgramwall') {
//                    if ($ch->chat_username == '@magnetgramwall')
//                        sendTelegramMessage(Helper::$logs[0], json_encode($update->channel_post), null);
                    if ($update->channel_post->media_group_id) {
                        deleteTelegramMessage($chat_id, $message_id);
                        $isNew = false;
                        if ($this->user->remember_token) {
                            $remember = json_decode($this->user->remember_token);
                            if ($remember && $remember->mdgid) {
                                if ($remember->mdgid == $update->channel_post->media_group_id) {

                                    if ($remember->msgid)
                                        deleteTelegramMessage($chat_id, $remember->msgid);
                                    if ($remember->mdmsgid)
                                        deleteTelegramMessage($chat_id, $remember->mdmsgid);
                                    $type = 'document';
                                    $file_id = '';
                                    if ($photo) {
                                        $type = 'photo';

                                        $file_id = is_array($photo) ? $photo[count($photo) - 1]->file_id : $photo->file_id;
                                    } elseif ($video) {
                                        $type = 'video';
                                        $file_id = is_array($video) ? $video[count($video) - 1]->file_id : $video->file_id;


                                    } elseif ($audio) {
                                        $type = 'audio';
                                        $file_id = is_array($audio) ? $audio[count($audio) - 1]->file_id : $audio->file_id;
                                    } elseif ($document) {
                                        $type = 'document';
                                        $file_id = is_array($document) ? $document[count($document) - 1]->file_id : $document->file_id;

                                    }


                                    $media = [];
                                    foreach ($remember->files as $file) {
                                        $media[] = $file;
                                    }
                                    $media[] = ['parse_mode' => 'Markdown', 'type' => $type, 'media' => $file_id];


                                    $res = sendTelegramMediaGroup($chat_id, $media);
//                                        sendTelegramMessage(Helper::$logs[0], json_encode($res), null);
                                    if ($res && $res->ok == true) {
                                        $mediaMsgId = $res->result[0]->message_id;
//                                        sendTelegramMessage(Helper::$logs[0], " $remember->msgid < $mediaMsgId ", null);

                                        while ($remember->msgid < $mediaMsgId) {

                                            deleteTelegramMessage($chat_id, $remember->msgid);
                                            $remember->msgid++;
                                        }
                                    }

                                    $this->user->remember_token = json_encode(['mdgid' => $remember->mdgid, 'msgid' => $update->channel_post->message_id, 'mdmsgid' => $mediaMsgId,
                                        'files' => $media]);
                                    $this->user->save();

                                } else {
                                    $isNew = true;
                                    $this->user->remember_token = null;
                                }
                            } else {
                                $this->user->remember_token = null;
                            }
                        }
                        if ($this->user->remember_token == null || $isNew == true) {

                            $caption = preg_replace("/@\w+/", "", $caption);
                            $caption = $caption . PHP_EOL;
                            $caption .= $ch->tag ?? "\xD8\x9C" . "➖➖➖➖➖➖➖➖➖➖➖" . PHP_EOL . $ch->chat_username;

                            $type = 'document';
                            $file_id = '';
                            if ($photo) {
                                $type = 'photo';

                                $file_id = is_array($photo) ? $photo[count($photo) - 1]->file_id : $photo->file_id;
                            } elseif ($video) {
                                $type = 'video';
                                $file_id = is_array($video) ? $video[count($video) - 1]->file_id : $video->file_id;


                            } elseif ($audio) {
                                $type = 'audio';
                                $file_id = is_array($audio) ? $audio[count($audio) - 1]->file_id : $audio->file_id;
                            } elseif ($document) {
                                $type = 'document';
                                $file_id = is_array($document) ? $document[count($document) - 1]->file_id : $document->file_id;

                            }

                            $this->user->remember_token = json_encode(['mdgid' => $update->channel_post->media_group_id, 'msgid' => $update->channel_post->message_id,
                                'files' => [['parse_mode' => 'Markdown', 'type' => $type, 'caption' => MarkDown($caption), 'media' => $file_id]]]);
                            $this->user->save();
                        }
                        return;
                    }
//                        return;
//                    }


                    $caption = preg_replace("/@\w+/", "", $caption);
//                    if ($caption) {
//                        $caption .= PHP_EOL . "〰️〰️〰️〰️" . "\xD8\x9C" . "〰️〰️〰️〰️";
                    $caption2 = $caption . PHP_EOL;
                    $caption2 .= $ch->tag ?? "\xD8\x9C" . "➖➖➖➖➖➖➖➖➖➖➖" . PHP_EOL . $ch->chat_username;
//                    }

                    $update->channel_post->caption = $caption2;
                    $res = deleteTelegramMessage($chat_id, $message_id);
                    if ($res->ok != true) {
                        sendTelegramMessage(Helper::$logs[0], $ch->chat_username . PHP_EOL . $res->result->description . PHP_EOL . json_encode($update), null);

//                        $ch->auto_tag = false;
//                        $ch->save();
                        return;
                    }
                    $res = $this->sendFile("$chat_id", json_encode($update->channel_post), $reply, false);
                    if ($res == null || $res->ok == false) {
                        $update->channel_post->caption = $caption;
                        $res = $this->sendFile("$chat_id", json_encode($update->channel_post), $reply, false);
                    }

//                    if ($from_message_id) {
//                        $this->copyMessage($chat_id, $from_chat_id, $from_message_id, $caption);
//                        deleteTelegramMessage($chat_id, $message_id);
//                    } else {
//                        $this->EditMessageCaption($chat_id, $message_id, $caption);
//                    }
                }
            }


        }
        if ($text == "/start$this->bot") {
//            deleteTelegramMessage($chat_id, $message_id);
            $buttons = [[['text' => '👈 ورود به ربات 👉', 'url' => "https://t.me/" . str_replace("@", "", $this->bot)]]];
            if (!$this->user) sendTelegramMessage($chat_id, "■ سلام $first_name خوش آمدید\n\n■ برای ثبت فروشگاه خود  ابتدا در ربات ثبت نام کنید :" . " پشتیبانی: " . Helper::$admin, null, $message_id, $button);
            else  sendTelegramMessage($chat_id, "■ سلام $first_name به ورتا شاپ خوش اومدی✋\n  " . "⚡ توسط این ربات می توانید محصولات بازارچه را مشاهده و یا محصولات خود را در بازارچه ثبت کنید" . PHP_EOL . " 💻 سایت فروشگاه: " . PHP_EOL . Helper::$site . PHP_EOL . PHP_EOL . " پشتیبانی: " . Helper::$admin, null, $message_id, $button);

            logAdmins("■  کاربر [$first_name](tg://user?id=$from_id) ربات  ورتا شاپ را استارت کرد.", 'MarkDown');

        }
        if ($text == 'بنر' || $Data == 'بنر' || $text == "📌 دریافت بنر تبلیغاتی 📌") {
            if (!$this->user) {
                sendTelegramMessage($chat_id, "برای دریافت بنر مخصوص خود ابتدا در ربات ثبت نام کنید.", 'MarkDown', $message_id, null);
                return;
            }
            if ($tc == 'private') {
//                sendTelegramMessage($from_id, "بنر زیر را فوروارد کنید و در صورت ورود و ثبت کانال در دیوار توسط افراد دعوت شده, تعداد $this->ref_score سکه دریافت نمایید. ", "Markdown", null, null, true);

            }
            $rLink = "https://t.me/" . str_replace("@", "", $this->bot) . "?start=" . base64_encode("$from_id");
            $buttons = [[['text' => '👈 ورود به ربات 👉', 'url' => $rLink]]];
            sendTelegramMessage($chat_id, "⭐️ بازارچه اینترنتی ورتا مخصوص کسب و کارهای خانگی \n📌 امکانات \n💣 ثبت محصولات \n💣 ثبت سفارش \n💣 مدیریت کانال فروشگاه \n💣 پشتیبانی 24 ساعته \nو...\n ورتا شاپ 👑 بازارچه ایرانیان \n📌 برای ثبت محصولات خود و یا مشاهده محصولات وارد ربات شوید.\n" . PHP_EOL . PHP_EOL . Helper::$site, null, null, json_encode(['inline_keyboard' => $buttons, 'resize_keyboard' => true]), false);

        }
        //referral
        if (strpos($text, "/start ") !== false) {
            return;// agar ebarate /start ersal shod
            $this->user = User::where('telegram_id', $from_id)->first();
//            $button = json_encode(['keyboard' => [
//                in_array($from_id, Helper::$Devs) ? [['text' => 'پنل مدیران🚧']] : [],
//                [['text' => 'دیوار📈']],
//                [['text' => "🎴 ساخت دکمه شیشه ای 🎴"], ['text' => "📌 دریافت بنر تبلیغاتی 📌"]],
//                [['text' => 'سکه های من💰'], ['text' => 'جریمه افراد لفت داده📛']],
//                [['text' => 'ثبت گروه💥'], ['text' => 'ثبت کانال💥']],
//                [['text' => 'مدیریت گروه ها📢'], ['text' => 'مدیریت کانال ها📣']],
//
//                [['text' => $this->user ? "ویرایش اطلاعات✏" : "ثبت نام✅"]],
//                [['text' => 'درباره ربات🤖']],
//            ], 'resize_keyboard' => true]);

//            if ($this->user) return;

            logAdmins("■  کاربر [$first_name](tg://user?id=$from_id) ربات مگنت گرام را استارت کرد.", 'MarkDown');
            $inviter_code = substr($text, 7); // joda kardan id kasi ke rooye linke davatesh click shode

            if (!empty($inviter_code)) {
                $res = explode("$", base64_decode($inviter_code));

                $telegram_id = $res[0];

                if (count($res) > 1) {
                    $chat_id = $res[1];
                    $this->sendChannelBanner($from_id, $telegram_id, $chat_id, false);
                }


                if (Ref::where('new_telegram_id', "$from_id")->exists())
                    return;

                try {
                    Ref::create(['new_telegram_id' => $from_id, 'invited_by' => "$telegram_id"]);
                    sendTelegramMessage($telegram_id, " \n🔔\n " . " هم اکنون " . " [$first_name](tg://user?id=$from_id) " . " با لینک دعوت شما وارد ربات شد. در صورت هر بار ثبت کانال در دیوار توسط او, $this->ref_score سکه به شما اضافه خواهد شد!  " . "\n$this->bot", "Markdown", null, null, false);
                    sendTelegramMessage(Helper::$logs[0], " \n🔔\n " . "  کاربر دعوت شده " . " [$first_name](tg://user?id=$from_id) " . PHP_EOL . " [کاربر دعوت کرده](tg://user?id=$telegram_id) ", "Markdown", null, null, false);

                } catch (\Exception $e) {
                    sendTelegramMessage(Helper::$logs[0], $e->getMessage(), "Markdown", null, null, false);

                }


            }

        }
        // follow a chat from divar


        //advertise a chat from divar

//------------------------------------------------------------------------------

//------------------------------------------------------------------------------
//        unlink("error_log");
    }


    private
    function popupMessage2($data_id, $from_id, $message)
    {
        return creator('CallbackQuery', [
            'id' => $data_id,
            'from' => $from_id,
            'message' => $message,

        ]);
    }

    private
    function popupMessage($data_id, $text)
    {
        return creator('answerCallbackQuery', [
            'callback_query_id' => $data_id,
            'text' => $text,

            'show_alert' => true, # popup / notification
            'url' => null,# t.me/your_bot?start=XXXX,
            'cache_time' => null
        ]);
    }

    function sendMessage($chat_id, $text, $mode, $reply = null, $keyboard = null, $disable_notification = false)
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


//    function creator($method, $datas = [])
//    {
//        $url = "https://api.telegram.org/bot" . API_KEY . "/" . $method;
//        $ch = curl_init();
//        curl_setopt($ch, CURLOPT_URL, $url);
//        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//        curl_setopt($ch, CURLOPT_POSTFIELDS, $datas);
//        $res = curl_exec($ch);
//
//        if (curl_error($ch)) {
//            var_dump(curl_error($ch));
//        } else {
//            return json_decode($res);
//        }
//    }

    private
    function inviteToChat($chat_id)
    {

        return creator('exportChatInviteLink', ['chat_id' => $chat_id,]);

    }

    private
    function getChatMembersCount($chat_id)
    {
        $res = creator('getChatMembersCount', ['chat_id' => $chat_id,])->result;
        if ($res)
            return (int)$res; else return 0;
    }

    private
    function getChatInfo($chat_id)
    {
        $res = creator('getChat', ['chat_id' => $chat_id]);
        if (isset($res->result) && $res->ok == true)
            return $res->result;
        else
            return null;
    }

    private
    function Admin($chat_id, $from_id, $chat_type, $chat_username)
    {
        if ($chat_type == 'supergroup' || $chat_type == 'group') {
            $get = creator('getChatMember', ['chat_id' => $chat_id, 'user_id' => $from_id]);
            $rank = $get->result->status;

            if ($rank == 'creator' || $rank == 'administrator') {
                return true;
            } else {
//                sendTelegramMessage($chat_id, "■  کاربر غیر مجاز \n $this->bot  ", 'MarkDown', null);
                return false;
            }
        } else if ($chat_type == 'channel') {
            return true;
//            $admins = creator('getChatAdministrators', ['chat_id' => $chat_id])->result;
//            $is_admin = false;
//
//            foreach ($admins as $admin) {
//                if ($from_id == $admin->user->id) {
//                    $is_admin = true;
//                }
//            }
//            return $from_id;

//            $this->user = User::whereIn('telegram_id', $admin_ids)->orWhere('channels', 'like', "%[$chat_username,%")
//                ->orWhere('channels', 'like', "%,$chat_username,%")
//                ->orWhere('channels', 'like', "%,$chat_username]%")->first();
//            if (!User::orWhere('channels', 'like', "%[$chat_username,%")
//                ->orWhere('channels', 'like', "%,$chat_username,%")
//                ->orWhere('channels', 'like', "%,$chat_username]%")->exists())
//                sendTelegramMessage($chat_id, "■ ابتدا کانال را در ربات ثبت نمایید  \n📣$this->bot  ", 'MarkDown', null);


//            return $this->user ? true : false;
        }
    }

    private
    function get_chat_type($chat_id)
    {
        $res = creator('getChat', [
            'chat_id' => $chat_id,

        ]);
        if ($res->ok == false)
            return $res->description;
        return $res->result->type;
    }

    private
    function user_in_chat($chat_id, $user_id, $chat_type = null)
    {


        $res = creator('getChatMember', [
            'chat_id' => $chat_id,
            'user_id' => $user_id
        ]);
        if ($res->ok == false)
            return $res->description;
        return $res->result->status;
    }

    private
    function copyMessage($chat_id, $from_chat_id, $message_id, $text, $mode = null, $keyboard = null)
    {
        creator('copyMessage', [
            'chat_id' => $chat_id,
            'from_chat_id' => $from_chat_id,
            'message_id' => $message_id,
            'caption' => $text,
            'parse_mode' => $mode,
            'reply_markup' => $keyboard
        ]);
    }

    private
    function EditMessageCaption($chat_id, $message_id, $text, $mode = null, $keyboard = null)
    {
        return creator('editMessageCaption', [
            'chat_id' => $chat_id,
            'message_id' => $message_id,
            'caption' => $text,
            'parse_mode' => $mode,
            'reply_markup' => $keyboard
        ]);
    }

    private
    function EditMessageText($chat_id, $message_id, $text, $mode = null, $keyboard = null)
    {
        return creator('EditMessageText', [
            'chat_id' => $chat_id,
            'message_id' => $message_id,
            'text' => $text,
            'parse_mode' => $mode,
            'reply_markup' => $keyboard
        ]);
    }

    private
    function EditKeyboard($chat_id, $message_id, $keyboard)
    {
        creator('EditMessageReplyMarkup', [
            'chat_id' => $chat_id,
            'message_id' => $message_id,
            'reply_markup' => $keyboard
        ]);
    }

    private
    function DeleteMessage($chatid, $massege_id)
    {
        creator('DeleteMessage', [
            'chat_id' => $chatid,
            'message_id' => $massege_id
        ]);
    }

    private
    function Kick($chatid, $fromid)
    {
        creator('KickChatMember', [
            'chat_id' => $chatid,
            'user_id' => $fromid
        ]);
    }

    private
    function Forward($chatid, $from_id, $massege_id)
    {
        creator('ForwardMessage', [
            'chat_id' => $chatid,
            'from_chat_id' => $from_id,
            'message_id' => $massege_id
        ]);
    }

    function MarkDown($string)
    {
        return str_replace(["`", "_", "*", "[", "]"], "\t", $string);
    }


    private
    function check($what, $text, $chat_id, $message_id, $cancel_button)
    {
        $message = null;
        if ($what == 'name') {
            if (strlen($text) < 5)
                $message = "نام  حداقل 5 حرف باشد";
            elseif (strlen($text) > 50)
                $message = "نام  حداکثر 50 حرف باشد";
            elseif (User::where("name", $text)->exists())
                $message = "نام  تکراری است";
        }
        if ($what == 'username') {
            if (strlen($text) < 5)
                $message = "نام کاربری  حداقل 5 حرف باشد";
            elseif (strlen($text) > 50)
                $message = "نام کاربری حداکثر 30 حرف باشد";
            elseif (User::where("username", $text)->exists())
                $message = "نام کاربری تکراری است";
        } else if ($what == 'password') {
            if (strlen($text) < 5)
                $message = "طول گذرواژه حداقل 5";
            elseif (strlen($text) > 50)
                $message = "طول گذرواژه حداکثر 50";

        } else if ($what == 'channel') {

            if (Channel::where('chat_username', $text)->exists())
                $message = "این کانال از قبل ثبت شده است!";

            elseif ($this->get_chat_type($text) != 'channel')
                $message = "ورودی شما از نوع کانال نیست و یا ربات را بلاک کرده اید";

            //temporary disable admin check
//            else {
//                $result = $this->user_in_chat($text, $this->user->telegram_id);
//                if ($result == "Bad Request: user not found")
//                    $message = "شما عضو این کانال نیستید!";
//                elseif ($result == "Bad Request: chat not found")
//                    $message = "کانال وجود ندارد!";
//                elseif ($result != "creator" && $result != "administrator")
//                    $message = "شما مدیر کانال نیستید !";
//            }
        }

        if ($message) {
            sendTelegramMessage($chat_id, $message, 'MarkDown', $message_id, $cancel_button);
            return false;
        } else {
            return true;
        }

    }

    public
    function request($request)
    {


        $http = new \GuzzleHttp\Client(['base_uri' => $request['url'],
        ]);

        try {
            $response = $http->post(/*route('passport.token'*/
                ''
                , [

                'headers' => ['cache-control' => 'no-cache',
                    'Content-Type' => 'application/x-www-form-urlencoded'
                ],
                'form_params' => $request['params']
            ]);

            return json_decode($response->getBody()->getContents(), true)["result"]["status"];
        } catch (\Guzzlehttp\Exception\BadResponseException $e) {
            if ($e->getCode() == 400) {
                return json_decode($e->getResponse()->getBody()->getContents(), true)["description"];
            } else if ($e->getCode() == 401) {
                return response()->json($e->getMessage(), $e->getCode());
            }
            return response()->json($e->getMessage(), $e->getCode());

        }
    }

    private
    function sendFile($chat_id, $storage, $reply = null, $tag = true)
    {


        $message = json_decode($storage);
        $poll = $message->poll;
        $text = $message->text;
        $sticker = $message->sticker;  #width,height,emoji,set_name,is_animated,file_id,file_unique_id,file_size,thumb[file_id,file_unique_id,file_size,width,
        $animation = $message->animation;  #file_name,mime_type,width,height,file_id,file_unique_id,file_size,thumb[file_id,file_unique_id,file_size,width,

        $photo = $message->photo; #file_id,file_unique_id,file_size,width,height] array of different photo sizes
        $document = $message->document; #file_name,mime_type,thumb[file_id,file_unique_id,file_size,width,height]
        $video = $message->video; #duration,width,height,mime_type,file_id,file_unique_id,file_size,thumb[file_id,file_unique_id,file_size,width,height]
        $audio = $message->audio; #duration,mime_type,title,performer,file_id,file_unique_id,file_size,thumb[file_id,file_unique_id,file_size,width,height]
        $voice = $message->voice; #duration,mime_type,file_id,file_unique_id,file_size
        $video_note = $message->video_note; #duration,length,file_id,file_unique_id,file_size,thumb[file_id,file_unique_id,file_size,width,height]
        $caption = $message->caption;
        $media_group_id = $message->media_group_id;

        $media = $photo ? $photo : ($document ? $document : ($video ? $video : ($audio ? $audio : ($voice ? $voice : null))));

//        $media = [];
//        if ($photo) {
//            foreach ($photo as $item) {
//                $media[] = ['type' => 'photo', 'media' => $item->file_id, 'caption' => $caption];
//
//            }
//        }

        if ($text) {
            $adv_section = explode('banner=', $text); //banner=name=@id
            $text = $adv_section[0];
        } else if ($caption) {
            $adv_section = explode('banner=', $caption);
            $caption = $adv_section[0];
        }
        if (count($adv_section) == 2) {

            $link = explode('=', $adv_section[1]);
            $trueLink = $link[1];
            foreach ($link as $idx => $li) {
                if ($idx > 1)
                    $trueLink .= ('=' . $li);
            }
            $buttons = [[['text' => "👈 $link[0] 👉", 'url' => $trueLink]], [['text' => '👈 محل تبلیغ کانال و گروه شما 👉', 'url' => "https://t.me/" . str_replace("@", "", $this->bot)]]];
        } else {
            if ($text && $tag) $text = $text . "\n\n" . $this->bot;
            else if ($caption && $tag) $caption = $caption . "\n\n" . $this->bot;
            $buttons = [[['text' => '👈 محل تبلیغ کانال و گروه شما 👉', 'url' => "https://t.me/" . str_replace("@", "", $this->bot)]]];
        }

        $caption = MarkDown($caption);

        if ($tag)
            $keyboard = json_encode(['inline_keyboard' => $buttons, 'resize_keyboard' => true]);


        if ($text)
            return creator('SendMessage', [
                'chat_id' => $chat_id,
                'text' => MarkDown($text),
                'parse_mode' => 'Markdown',
                'reply_to_message_id' => $reply,
                'reply_markup' => $keyboard
            ]);
//        elseif ($media_group_id && $media)
//            creator('sendMediaGroup', [
//                'chat_id' => $chat_id,
//                'media' => $media[count($media) - 1]->file_id /*[count($photo) - 1]->file_id*/,
//
//
//                'reply_to_message_id' => $reply,
//                'reply_markup' => $keyboard
//            ]);
        elseif ($photo)
            return creator('sendPhoto', [
                'chat_id' => $chat_id,
                'photo' => $photo[count($photo) - 1]->file_id,
                'caption' => $caption,
                'parse_mode' => 'Markdown',
                'reply_to_message_id' => $reply,
                'reply_markup' => $keyboard
            ]);
        elseif ($audio)
            return creator('sendAudio', [
                'chat_id' => $chat_id,
                'audio' => $audio->file_id,
                'caption' => $caption,
                'parse_mode' => 'Markdown',
                'duration' => $audio->duration,
                'performer' => $audio->performer,
                'title' => $audio->title,
                'thumb' => $audio->thumb->file_id,
                'reply_to_message_id' => $reply,
                'reply_markup' => $keyboard
            ]);
        elseif ($document)
            return creator('sendDocument', [
                'chat_id' => $chat_id,
                'document' => $document->file_id,
                'caption' => $caption,
                'parse_mode' => 'Markdown',
                'thumb' => $document->thumb->file_id,
                'reply_to_message_id' => $reply,
                'reply_markup' => $keyboard
            ]);
        elseif ($video)
            return creator('sendVideo', [
                'chat_id' => $chat_id,
                'video' => $video->file_id,
                'duration' => $video->duration,
                'width' => $video->width,
                'height' => $video->height,
                'caption' => $caption,
                'parse_mode' => 'Markdown',
                'thumb' => $video->thumb->file_id,
                'reply_to_message_id' => $reply,
                'reply_markup' => $keyboard
            ]);
        elseif ($animation)
            return creator('sendAnimation', [
                'chat_id' => $chat_id,
                'animation' => $animation->file_id,
                'duration' => $animation->duration,
                'width' => $animation->width,
                'height' => $animation->height,
                'caption' => $caption,
                'parse_mode' => 'Markdown',
                'thumb' => $animation->thumb->file_id,
                'reply_to_message_id' => $reply,
                'reply_markup' => $keyboard
            ]);
        elseif ($voice)
            return creator('sendVoice', [
                'chat_id' => $chat_id,
                'voice' => $voice->file_id,
                'duration' => $voice->duration,
                'caption' => $caption,
                'parse_mode' => 'Markdown',
                'reply_to_message_id' => $reply,
                'reply_markup' => $keyboard
            ]);
        elseif ($video_note)
            return creator('sendVideoNote', [
                'chat_id' => $chat_id,
                'video_note' => $video_note->file_id,
                'duration' => $video_note->duration,
                'length' => $video_note->length,
                'thumb' => $video_note->thumb->file_id,
                'caption' => $caption,
                'parse_mode' => 'Markdown',
                'reply_to_message_id' => $reply,
                'reply_markup' => $keyboard
            ]);
        elseif ($sticker)
            return creator('sendSticker', [
                'chat_id' => $chat_id,
                'sticker' => $sticker->file_id,
                "set_name" => "DaisyRomashka",
                'reply_to_message_id' => $reply,
                'reply_markup' => $keyboard
            ]);
        elseif ($poll)
            return creator('sendPoll', [
                'chat_id' => $chat_id,
                'question' => $poll->question,
                'options' => json_encode(array_column($poll->options, 'text')),//  ,
                'type' => $poll->type,//quiz
                'allows_multiple_answers' => $poll->allows_multiple_answers,
                'is_anonymous' => $poll->is_anonymous,
                'correct_option_id' => $poll->correct_option_id, // index of correct answer for quiz
// //            'open_period' => 5-600,   this or close_date
// //            'close_date' => 5, 5 - 600,
                'reply_to_message_id' => $reply,
                'reply_markup' => $keyboard
            ]);
        return null;
    }

    private
    function sendBanner($chat_id, $storage)
    {


        $storage = json_decode($storage);
        $message = json_decode($storage->message);
        $link = $storage->link;
        $name = $storage->name;
        $poll = $message->poll;
        $text = $message->text;
        $sticker = $message->sticker;  #width,height,emoji,set_name,is_animated,file_id,file_unique_id,file_size,thumb[file_id,file_unique_id,file_size,width,
        $animation = $message->animation;  #file_name,mime_type,width,height,file_id,file_unique_id,file_size,thumb[file_id,file_unique_id,file_size,width,

        $photo = $message->photo; #file_id,file_unique_id,file_size,width,height] array of different photo sizes
        $document = $message->document; #file_name,mime_type,thumb[file_id,file_unique_id,file_size,width,height]
        $video = $message->video; #duration,width,height,mime_type,file_id,file_unique_id,file_size,thumb[file_id,file_unique_id,file_size,width,height]
        $audio = $message->audio; #duration,mime_type,title,performer,file_id,file_unique_id,file_size,thumb[file_id,file_unique_id,file_size,width,height]
        $voice = $message->voice; #duration,mime_type,file_id,file_unique_id,file_size
        $video_note = $message->video_note; #duration,length,file_id,file_unique_id,file_size,thumb[file_id,file_unique_id,file_size,width,height]
        $caption = $message->caption;


        $buttons = [[['text' => "👈 $name 👉", 'url' => $link]]];

        $keyboard = json_encode(['inline_keyboard' => $buttons, 'resize_keyboard' => true]);
        Storage::put("log.txt", $text);

        if ($text)
            creator('SendMessage', [
                'chat_id' => $chat_id,
                'text' => $text /*. "\n $this->bot"*/,
//                'parse_mode' => 'Markdown',
                'reply_to_message_id' => null,
                'reply_markup' => $keyboard
            ]);
        else if ($photo)
            creator('sendPhoto', [
                'chat_id' => $chat_id,
                'photo' => $photo[count($photo) - 1]->file_id,
                'caption' => $caption,
//                'parse_mode' => 'Markdown',
                'reply_to_message_id' => null,
                'reply_markup' => $keyboard
            ]);
        else if ($audio)
            creator('sendAudio', [
                'chat_id' => $chat_id,
                'audio' => $audio->file_id,
                'caption' => $caption,
//                'parse_mode' => 'Markdown',
                'duration' => $audio->duration,
                'performer' => $audio->performer,
                'title' => $audio->title,
                'thumb' => $audio->thumb,
                'reply_to_message_id' => null,
                'reply_markup' => $keyboard
            ]);
        else if ($document)
            creator('sendDocument', [
                'chat_id' => $chat_id,
                'document' => $document->file_id,
                'caption' => $caption,
//                'parse_mode' => 'Markdown',
                'thumb' => $document->thumb,
                'reply_to_message_id' => null,
                'reply_markup' => $keyboard
            ]);
        else if ($video)
            creator('sendVideo', [
                'chat_id' => $chat_id,
                'video' => $video->file_id,
                'duration' => $video->duration,
                'width' => $video->width,
                'height' => $video->height,
                'caption' => $caption,
//                'parse_mode' => 'Markdown',
                'thumb' => $video->thumb,
                'reply_to_message_id' => null,
                'reply_markup' => $keyboard
            ]);
        else if ($animation)
            creator('sendAnimation', [
                'chat_id' => $chat_id,
                'animation' => $animation->file_id,
                'duration' => $animation->duration,
                'width' => $animation->width,
                'height' => $animation->height,
                'caption' => $caption,
//                'parse_mode' => 'Markdown',
                'thumb' => $animation->thumb,
                'reply_to_message_id' => null,
                'reply_markup' => $keyboard
            ]);
        else if ($voice)
            creator('sendVoice', [
                'chat_id' => $chat_id,
                'voice' => $voice->file_id,
                'duration' => $voice->duration,
                'caption' => $caption,
//                'parse_mode' => 'Markdown',
                'reply_to_message_id' => null,
                'reply_markup' => $keyboard
            ]);
        else if ($video_note)
            creator('sendVideoNote', [
                'chat_id' => $chat_id,
                'video_note' => $video_note->file_id,
                'duration' => $video_note->duration,
                'length' => $video_note->length,
                'thumb' => $video_note->thumb,
                'caption' => $caption,
//                'parse_mode' => 'Markdown',
                'reply_to_message_id' => null,
                'reply_markup' => $keyboard
            ]);
        else if ($sticker)
            creator('sendSticker', [
                'chat_id' => $chat_id,
                'sticker' => $sticker->file_id,
                "set_name" => "DaisyRomashka",
                'reply_to_message_id' => null,
                'reply_markup' => $keyboard
            ]);
        else if ($poll)
            creator('sendPoll', [
                'chat_id' => $chat_id,
                'question' => $poll->question,
                'options' => json_encode(array_column($poll->options, 'text')),//  ,
                'type' => $poll->type,//quiz
                'allows_multiple_answers' => $poll->allows_multiple_answers,
                'is_anonymous' => $poll->is_anonymous,
                'correct_option_id' => $poll->correct_option_id, // index of correct answer for quiz
// //            'open_period' => 5-600,   this or close_date
// //            'close_date' => 5, 5 - 600,
//                'reply_to_message_id' => $reply,
                'reply_markup' => $keyboard
            ]);

//        Storage::delete("$chat_id.txt");
    }


    private
    function createUserImage($user_id)
    {

        $client = new \GuzzleHttp\Client();
        $res = creator('getUserProfilePhotos', [
            'user_id' => $user_id,

        ])->result->photos;
        // return json_encode($res);
        if (!isset($res) || count($res) == 0) return;
        $res = creator('getFile', [
            'file_id' => $res[0][count($res[0]) - 1]->file_id,

        ])->result->file_path;

        $image = "https://api.telegram.org/file/bot" . env('TELEGRAM_BOT_TOKEN', 'YOUR-BOT-TOKEN') . "/" . $res;
        Storage::put("public/users/$user_id.jpg", $client->get($image)->getBody());

    }

    private
    function getUserOrRegister($first_name, $last_name, $username, $from_id)
    {
        $this->user = null;
        if ($from_id == null) {
            return;
        }
        $this->user = User::where('telegram_id', "$from_id")->first();
        if (!$this->user) {
            $name = "";
            if ($first_name != "") {
                if (mb_strlen($first_name) > 50)
                    $name = mb_substr($first_name, 0, 49);
                else $name = $first_name;
            } elseif ($last_name != "") {
                if (mb_strlen($last_name) > 50)
                    $name = mb_substr($last_name, 0, 49);

            } elseif ($username != "" && $username != "@") {
                if (mb_strlen($username) > 50)
                    $name = mb_substr($username, 1, 49);
            } else
                $name = "$from_id";

            $username = $username === '' ? null : $username;
//            if (!User::where('telegram_id', $from_id)->exists()) {
            $this->user = User::create(['telegram_id' => "$from_id", 'telegram_username' => $username ?? null, 'score' => Helper::$initScore, 'step' => null, 'name' => $name]);
//            }
        } else {
            if ($this->user->telegram_username != $username && $username != null && $username != "" && $username != "@") {
                $this->user->telegram_username = $username;
                $this->user->save();
            }
            return;
        }
    }

    private
    function sendChannelBanner($send_to_id, $advertiser_id, $chat_id, $is_advertise)
    {

        $divar = Divar::where('chat_id', "$chat_id")->first();
        $user = User::where('id', $divar->user_id)->first();

        $rLink = "https://t.me/" . str_replace("@", "", $this->bot) . "?start=" . base64_encode("$advertiser_id$$chat_id");


        $address = "🔗 " . ($is_advertise ? (" ورود و دریافت سکه " . PHP_EOL . $rLink) : "$divar->chat_username");


        $line = array(

            "➖➖➖➖➖➖➖➖➖➖➖",
//            "➿➿➿➿➿➿➿➿➿➿➿",
            "🕳️🕳️🕳️🕳️🕳️🕳️🕳️🕳️🕳️🕳️🕳️",
            "〰️〰️〰️〰️〰️〰️〰️〰️〰️〰️〰️",
            "🔸🔸🔸🔸🔸🔸🔸🔸🔸🔸",
            "🕶🕶🕶🕶🕶🕶🕶🕶🕶🕶",
            "🚥🚥🚥🚥🚥🚥🚥🚥🚥🚥",
            "▪️▪️▪️▪️▪️▪️▪️▪️▪️▪️",
        );
        $sLine = array(

            "➖➖➖",
//            "➿➿➿",
            "🕳️🕳️🕳️",
            "〰️〰️〰️",
            "🔸🔸🔸",
            "🕶🕶🕶",
            "🚥🚥🚥",
            "▪️▪️▪️",
        );
        $idx = array_rand($line);
        $line = $line[$idx];
        $sLine = $sLine[$idx];

        $g = Group::where('id', $divar->group_id)->first();
        $caption = (" $g->emoji " . "#$g->name") . PHP_EOL;
        $caption .= /*PHP_EOL .*/
            "\xD8\x9C" . "$line" . PHP_EOL /*. PHP_EOL*/
        ;
        $caption .= "🌍 " . $divar->chat_title . PHP_EOL;
        $caption .= $address . PHP_EOL;
        $caption .= '👤Admin: ' . ($user->telegram_username != "" && $user->telegram_username != "@" ? "$user->telegram_username" :
                "[$user->name](tg://user?id=$user->telegram_id)") . PHP_EOL;
        $caption .= /*PHP_EOL .*/
            "$line" . PHP_EOL /*. PHP_EOL*/
        ;
        $caption .= "💬 " . (mb_strlen($divar->chat_description) < 150 ? $divar->chat_description : mb_substr($divar->chat_description, 0, 150)) . " ... " . PHP_EOL;
        $caption .= /*PHP_EOL .*/
            "\xD8\x9C" . "$line" . PHP_EOL /*. PHP_EOL*/
        ;
        if ($divar->follow_score > 0)
            $caption .= "✅جایزه عضویت: " . $divar->follow_score . PHP_EOL;
        if ($divar->ref_score > 0)
            $caption .= "🔗جایزه عضویابی: " . $divar->ref_score . PHP_EOL;
        $r = Helper::$remain_member_day_limit;
        if ($divar->follow_score > 0)
            $caption .= "⛔جریمه لفت دادن ($r روز): " . $divar->follow_score * 2 . PHP_EOL;
        $caption .= "$line" . PHP_EOL;
        $caption .= ($is_advertise ? $rLink : "$divar->chat_username") . PHP_EOL;
        $caption .= "$line" . PHP_EOL;


        $caption .= "💫ربات دیوار و تبادل شبانه💫" . PHP_EOL;
        $caption .= Helper::$bot . PHP_EOL;
        $caption .= PHP_EOL . "🆅🅰🆁🆃🅰🆂🅷🅾🅿.🅸🆁" . PHP_EOL;

        $follow_score = Helper::$follow_score;

        if ($is_advertise)
            $cell_button = json_encode(['inline_keyboard' => [
                [['text' => "👈 ورود و عضویت (  $follow_score سکه ) 👉", 'url' => $rLink]],

            ], 'resize_keyboard' => true]);
        else
            $cell_button = json_encode(['inline_keyboard' => [
                [['text' => "👈 ورود و عضویت (  $follow_score سکه ) 👉", 'url' => "https://t.me/" . str_replace('@', '', $divar->chat_username)]],
                [['text' => "✅ عضو شدم (دریافت سکه) ✅", 'callback_data' => "divar_i_followed$$chat_id$$advertiser_id"]],

            ], 'resize_keyboard' => true]);


        $message = sendTelegramMessage($send_to_id, MarkDown($caption), 'markdown', null, $cell_button);
//        $message = sendTelegramPhoto($send_to_id, asset("storage/chats/$chat_id.jpg"), self::MarkDown($caption), null, $cell_button);


        return true;
    }


}
