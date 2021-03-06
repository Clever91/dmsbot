<?php
// make error enable
error_reporting( E_ALL );
ini_set( "display_errors", 1 );
define('TIMEZONE', 'Asia/Tashkent');
date_default_timezone_set(TIMEZONE);
define('TOKEN', '1882359300:AAGEJ2X2FfE-u6Q_QbY8BeYtqgSRUyJKQL8');

//load vendor
require 'vendor/autoload.php';

// use libs 

use App\Helpers\BotHelp;
use Telegram\Bot\Api;

// init telegram api
$telegram = new Api(TOKEN);
$updates = $telegram->getWebhookUpdates();

$message = null; 
$callback = null;
$channel = null;

if ($updates->getMessage() != null) {
    $message = $updates->getMessage();
} else if ($updates->getEditedMessage() != null) {
    $message = $updates->getEditedMessage();
} else if ($updates->getCallbackQuery() != null) {
    $callback = $updates->getCallbackQuery();
} else if ($updates->getChannelPost() !== null) {
    $channel = $updates->getChannelPost();
}

// message is not null
if (!is_null($message) && $message->count()) {
    // get object
    $text = $message->getText();
    $user = $message->getFrom();
    $chat = $message->getChat();
    $location = $message->getLocation();
    $contact = $message->getContact();

    // if user is private so answer to him
    if (strtolower($chat->getType()) === "private") {
        // sqlite connection
        // $connect = new SQLiteHelper();
        // $lang = $connect->getLang($user->getId());

        // if text command exists so answer to him
        if (!empty($text) && !$user->isBot()) {
            // var_dump($have);
            
            try {

                $mysqli = new mysqli("149.154.71.209", "dms_test", "5W7m7U2i", "dms_base_test");

                if ($mysqli -> connect_errno) {
                    echo "Failed to connect to MySQL: " . $mysqli -> connect_error;
                    BotHelp::cLog( $mysqli -> connect_error);
                    exit();
                }

                // Return name of current default database
                if ($result = $mysqli -> query("SELECT DATABASE()")) {
                    $row = $result -> fetch_row();
                    echo "Default database is " . $row[0];
                    BotHelp::cLog( $row);
                    $result -> close();
                }

                // Change db to "test" db
                $mysqli -> select_db("dms_base_test");

                // Return name of current default database
                if ($result = $mysqli -> query("SELECT DATABASE()")) {
                    $row = $result -> fetch_row();
                    echo "Default database is " . $row[0];
                    BotHelp::cLog( $row);
                    $result -> close();
                }

                $mysqli -> close();

                $telegram->sendMessage([
                    'chat_id' => $chat->getId(), 
                    'text' => "Hello",
                    'parse_mode' => "Markdown",
                ]);
            } catch (Exception $e) {
                BotHelp::cLog($e->getMessage());
            }
        }

    } elseif (strtolower($chat->getType()) == ("group" || "supergroup")) {
        if (strtolower($text) === "/mychatid") {
            //
        }

    }

} elseif (!is_null($callback)) {
    $message = $callback->getMessage();
    $from = $callback->getFrom();
    $data = $callback->getData();

    if (!is_null($message) && !empty($data)) {
        $chat = $message->getChat();
        $decode = json_decode($data);
        // 
    }
}

header ( 'Content-Type: application/json' );
echo '{"ok":true,"result":true}';
exit ();


?>
