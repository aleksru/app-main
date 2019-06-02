<?php


namespace App\Services\IMAP;


use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class AvitoGetMessage
{
    /**
     * @return array
     */
    public static function getPost() : array
    {
        $urlHost = env('AVITO_ORDERS_POST_HOST');
        $host = "{" . $urlHost . ":993/ssl/novalidate-cert}INBOX";
        $email = env('AVITO_ORDERS_POST');
        $pass = env('AVITO_ORDERS_POST_PASS');

        $res = [];

        try{
            $connect = imap_open($host, $email, $pass);
        }catch (\Exception $e) {
            Log::error('Сбой подключения к ' . $urlHost . '. e-mail: ' . $email);
            Log::error($e);
            Log::error(imap_last_error());
            return $res;
        }

        $mails = imap_search($connect, 'UNSEEN');

        if(empty($mails)){
            return $res;
        }
        $new_mails = implode(",", $mails);
        $overview = imap_fetch_overview($connect,$new_mails,0);
        foreach ($overview as $ow) {
            $subject = iconv_mime_decode($ow->subject, 0, "UTF-8");
            $body = imap_fetchbody($connect, $ow->msgno,1);
            $res[] = [
                'subject' => $subject,
                'body' => base64_decode($body),
            ];
        }
        imap_close($connect);

        return $res;
    }
}