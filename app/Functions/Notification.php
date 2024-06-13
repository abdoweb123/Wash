<?php

namespace App\Functions;

class Notification
{
    public static function send($title_ar , $title_en , $from, $body_ar , $body_en, $link)
    {
        $notifi = new \App\Models\Notification();
        $notifi->title_ar = $title_ar ?? 'تواصل معنا' ;
        $notifi->title_en = $title_en ?? "Contact us";
        $notifi->from = $from;
        $notifi->body_ar = $body_ar ?? 'هناك رسالة جديدة قادمة من صفحة تواصل معنا';
        $notifi->body_en = $body_en ?? "There is a new message coming from our contact page";
        $notifi->link = $link;
        $notifi->save();
    }
}