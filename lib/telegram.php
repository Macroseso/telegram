<?php

namespace macroseso\telegram;

//*telegram bot
//*autor: Macroseso

class Telegram
{
    protected $getMe = "getMe";  // Un método simple para probar el token de autenticación de su bot.
    protected $message = "sendMessage";  // Utilice este método para enviar mensajes de texto.
    protected $forwardMessage = "forwardMessage";  // Utilice este método para reenviar mensajes de cualquier tipo.
    protected $photo = "sendPhoto";   // Utilice este método para enviar fotografías.
    protected $audio = "sendAudio";   // Utilice este método para enviar archivos de audio, si desea que los clientes de Telegram los muestren en el reproductor de música.
    protected $document = "sendDocument";
    protected $sticker = "sendSticker";
    protected $video = "sendVideo";
    protected $voice = "sendVoice";
    protected $location = "sendLocation";
    protected $chatAction = "sendChatAction";
    protected $getUserProfilePhotos = "getUserProfilePhotos";
    protected $getUpdates = "getUpdates";
    protected $setWebhook = "setWebhook";
    protected $removeWebhook = "setWebhook?remove";
    protected $getFile = "getFile";



    protected $baseUrl = 'https://api.telegram.org/bot';

    protected $accessToken = '';
    protected $chanel = '';

    public function __construct($token, $chatid)
    {
        $this->accessToken = $token;
        $this->chanel = $chatid;
    }


    public function sendMessenge($title, $content, $dat = null, $image = null, $url = false,$type='track')
    {
        $buttonText= 'Ir';
        if($type=='track'){
            $buttonText= 'Escuchar canción🎶';
        }elseif($type=='blog'){
            $buttonText= 'Ver contenido📰';
        }
        elseif($type=='video'){
            $buttonText= 'Ver video clip🎞️';
        }elseif($type=='playlist'){
            $buttonText= 'Ver lista de reporducción💽';
        }elseif($type=='moment'){
            $buttonText= 'Ver nueva publicación🌅';
        }
        if($url){
            $contentButton = [
                ['text' => $buttonText, 'url' => $url]
            ];
        }else{
            $contentButton = [];
        }
        $keyboard = [
            'inline_keyboard' => [
                $contentButton,
                [
                    ['text' => 'Visitar Macromusic🎧', 'url' => url()]
                ],
                [
                    ['text' => 'Canal de Telegram', 'url' => config('telegram_link', '')]
                ],
                [
                    ['text' => 'Facebook', 'url' => config('facebook_link', '')]
                ],
                [
                    ['text' => 'Instagram', 'url' => config('instagram_link', '')]
                ]
            ]
        ];
        

        $encodedKeyboard = json_encode($keyboard);
    
        $setedDescriptions =  config('telegram_descriptions', '');

        $autoFollow = explode(',',config('auto-follow-accounts'));
        $autoFollowText = 'Cuentas de seguimiento: ';
        foreach($autoFollow as $accounts){
            if($accounts != ''){
                $autoFollowText .= url($accounts).'  ';
            }
        }
        $body = [
            'chat_id'    => $this->chanel,
            'parse_mode' => 'markdown',
            'reply_markup' => $encodedKeyboard,
            'photo'     => $image, 
            'text' =>  "*$title*[ ]($image)
$content

$setedDescriptions
$autoFollowText
`$dat`"
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->baseUrl . $this->accessToken . '/' . $this->message);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: multipart/form-data']);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $body);

        $result = curl_exec($ch);
        $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        if ($code == 200) {
            return $result;
        } else {
            return false;
        }
    }

    public function sendPhoto($img, $title, $content)
    {
        $body = [
            'chat_id=' => $this->chanel,
            'attach:'    => '/' . $img,
            'caption' => $title,
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->baseUrl . $this->accessToken . '/' . $this->photo);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            "Content-Type:multipart/form-data"
        ));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $body);

        $result = curl_exec($ch);
        $res = json_decode($result);
        $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        return $result;
    }
}
