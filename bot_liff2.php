<?php
// กรณีต้องการตรวจสอบการแจ้ง error ให้เปิด 3 บรรทัดล่างนี้ให้ทำงาน กรณีไม่ ให้ comment ปิดไป
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
 
// include composer autoload
require_once 'vendor/autoload.php';
 
// การตั้งเกี่ยวกับ bot
define('LINE_MESSAGE_CHANNEL_ID' , '1655426102');
define('LINE_MESSAGE_CHANNEL_SECRET' , '5c0c3f969eee91ebd85bcc925f48076e');
define('LINE_MESSAGE_ACCESS_TOKEN' , 'OYJH7dN5a1E28fh82jl332smVVeSXk3uP3hj8C0oRvVjQMjCNVDPDyOR1xvZ9ZwWcvaJd15NFt08G94Ydm/FCSyGK9HhjOwXnkkjHGuo5S+9R8LmT8zcqDIz7lkOAmeI7wmkTFD9Ulnoq0/2fBVhMwdB04t89/1O/w1cDnyilFU=');

// กรณีมีการเชื่อมต่อกับฐานข้อมูล
//require_once("dbconnect.php");
 
///////////// ส่วนของการเรียกใช้งาน class ผ่าน namespace
use LINE\LINEBot;
use LINE\LINEBot\HTTPClient;
use LINE\LINEBot\HTTPClient\CurlHTTPClient;
//use LINE\LINEBot\Event;
//use LINE\LINEBot\Event\BaseEvent;
//use LINE\LINEBot\Event\MessageEvent;
use LINE\LINEBot\MessageBuilder;
use LINE\LINEBot\MessageBuilder\TextMessageBuilder;
use LINE\LINEBot\MessageBuilder\StickerMessageBuilder;
use LINE\LINEBot\MessageBuilder\ImageMessageBuilder;
use LINE\LINEBot\MessageBuilder\LocationMessageBuilder;
use LINE\LINEBot\MessageBuilder\AudioMessageBuilder;
use LINE\LINEBot\MessageBuilder\VideoMessageBuilder;
use LINE\LINEBot\ImagemapActionBuilder;
use LINE\LINEBot\ImagemapActionBuilder\AreaBuilder;
use LINE\LINEBot\ImagemapActionBuilder\ImagemapMessageActionBuilder ;
use LINE\LINEBot\ImagemapActionBuilder\ImagemapUriActionBuilder;
use LINE\LINEBot\MessageBuilder\Imagemap\BaseSizeBuilder;
use LINE\LINEBot\MessageBuilder\ImagemapMessageBuilder;
use LINE\LINEBot\MessageBuilder\MultiMessageBuilder;
use LINE\LINEBot\TemplateActionBuilder;
use LINE\LINEBot\TemplateActionBuilder\DatetimePickerTemplateActionBuilder;
use LINE\LINEBot\TemplateActionBuilder\MessageTemplateActionBuilder;
use LINE\LINEBot\TemplateActionBuilder\PostbackTemplateActionBuilder;
use LINE\LINEBot\TemplateActionBuilder\UriTemplateActionBuilder;
use LINE\LINEBot\MessageBuilder\TemplateBuilder;
use LINE\LINEBot\MessageBuilder\TemplateMessageBuilder;
use LINE\LINEBot\MessageBuilder\TemplateBuilder\ButtonTemplateBuilder;
use LINE\LINEBot\MessageBuilder\TemplateBuilder\CarouselTemplateBuilder;
use LINE\LINEBot\MessageBuilder\TemplateBuilder\CarouselColumnTemplateBuilder;
use LINE\LINEBot\MessageBuilder\TemplateBuilder\ConfirmTemplateBuilder;
use LINE\LINEBot\MessageBuilder\TemplateBuilder\ImageCarouselTemplateBuilder;
use LINE\LINEBot\MessageBuilder\TemplateBuilder\ImageCarouselColumnTemplateBuilder;
 
// เชื่อมต่อกับ LINE Messaging API
$httpClient = new CurlHTTPClient(LINE_MESSAGE_ACCESS_TOKEN);
$bot = new LINEBot($httpClient, array('channelSecret' => LINE_MESSAGE_CHANNEL_SECRET));
 

$response = '';
$replyToken = '';
$replyData = '';
// คำสั่งรอรับการส่งค่ามาของ LINE Messaging API
$content = file_get_contents('php://input');
 
// แปลงข้อความรูปแบบ JSON  ให้อยู่ในโครงสร้างตัวแปร array
$events = json_decode($content, true);

//testtt
// $events['events'][0]['message']['text'] = 'testtesttest';
// $events['events'][0]['message']['type'] = 'text' ;
// $events['events'][0]['replyToken'] = '9ef84c22ffd44768b1292cbc8c2f9cac';
// $events['events'][0]['source']['userId'] = '9ef84c22ffd44768b1292cbc8c2f9cac';

if(!is_null($events)){

    $replyToken = $events['events'][0]['replyToken'];
    $typeMessage = $events['events'][0]['type'];
    $userMessage = $events['events'][0]['message']['text'];
    $userMessage = strtolower($userMessage);

    $message_ar = explode("@",$userMessage);
    if($message_ar == false){

        $theMess = $events['events'][0]['message']['text'];

    }else{

        $theMess = $message_ar[0];
    }



    switch ($typeMessage){
        case "message": // ส่งแบบเป็นข้อความ
            switch ($theMess) {  // เช็ตข้อความก่อนวรรค
                case "_t":
                    $textReplyMessage = "Bot ตอบกลับคุณเป็นข้อความ";
                    $replyData = new TextMessageBuilder($textReplyMessage);
                    break;
                case "json":
      

                    $response = json_encode($events);
                    // $replyData = new TextMessageBuilder($textReplyMessage);

                    

                    break;
                    
                case "คู่มือ":
                    $picFullSize = 'https://med.nu.ac.th/qqline/test/user-line5.jpg';
                    $picThumbnail = 'https://med.nu.ac.th/qqline/test/user-line5.jpg';
                    $replyData = new ImageMessageBuilder($picFullSize,$picThumbnail);
                break;                                                                           
                default:
                    // $textReplyMessage = " คุณไม่ได้พิมพ์ ค่า ตามที่กำหนด";
                    // $textReplyMessage = json_encode($events);
                    $userId_get = $events['events'][0]['source']['userId'];

                    $response = "";

                    $response = json_encode($events);

                    $textReplyMessage = $response ;
                    $replyData = new TextMessageBuilder($textReplyMessage);  
                    // ส่งรูป สมัคร
                    // $picFullSize = 'https://med.nu.ac.th/qqline/test/user-line5.jpg';
                    // $picThumbnail = 'https://med.nu.ac.th/qqline/test/user-line5.jpg';
                    // $replyData = new ImageMessageBuilder($picFullSize,$picThumbnail);
                      
                break;                                      
            }
        break;

        case "beacon":
            // น่าจะส่งมาตอนเริ่ม add 
            // $textReplyMessage = json_encode($events);
            $response = "สวัสดี  บีค่อน";
            $textReplyMessage = $response ;
            $replyData = new TextMessageBuilder($textReplyMessage);  
        break;   

        default:

        // น่าจะส่งมาตอนเริ่ม add 
        // $response = json_encode($events);
        $response = " ลงล่าง ๆ ".$typeMessage;
        $textReplyMessage = $response ;
        $replyData = new TextMessageBuilder($textReplyMessage);  
        // $textReplyMessage = 'ลงทะเบียนได้เลยครับ !';
        // $replyData = new TextMessageBuilder($textReplyMessage);         
        break;  

                                                                        

    }
}



    // $textReplyMessage = $response ;
    // $replyData = new TextMessageBuilder($textReplyMessage);    

    sendMess($bot , $replyToken  ,$replyData );

    // ส่วนของคำสั่งจัดเตียมรูปแบบข้อความสำหรับส่ง
    // $textMessageBuilder = new TextMessageBuilder(json_encode($events));
    // $textMessageBuilder = new TextMessageBuilder($textReplyMessage);






    function sendMess($bot , $replyToken  ,$replyData )
    {

        $response = $bot->replyMessage($replyToken,$replyData);
        if ($response->isSucceeded()) {
            echo 'Succeeded!';
            return;
        }else{
            echo $response->getHTTPStatus() . ' ' . $response->getRawBody();

        }

    }



?>