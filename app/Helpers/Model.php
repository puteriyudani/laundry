<?php

use App\Models\{User, notifications_setting};

class Rupiah
{
    public static function getRupiah($value)
    {
        return "Rp " . number_format($value, 2, ',', '.');
    }
}

// Get Email Customer by id
if (! function_exists('email_customer')) {
    function email_customer($id = 0)
    {
        $data = User::where('auth', 'Customer')->where('id', $id)->first();
        return !empty($data) ? $data->email : 'Not Found';
    }
}

// Get Nama Customer by id
if (! function_exists('namaCustomer')) {
    function namaCustomer($id = 0)
    {
        $data = User::where('auth', 'Customer')->where('id', $id)->first();
        return !empty($data) ? $data->name : 'Not Found';
    }
}

// Setting Email Notifications
if (! function_exists('setNotificationEmail')) {
    function setNotificationEmail($id = '')
    {
        $data = notifications_setting::where('email', $id)->first();
        return $data ? $data->email : 'Email Notification Aktif Tidak';
    }
}

// Setting Telegram Order Masuk Notifications
if (! function_exists('setNotificationTelegramIn')) {
    function setNotificationTelegramIn($id = '')
    {
        $data = notifications_setting::where('telegram_order_masuk', $id)->first();
        return $data ? $data->telegram_order_masuk : 'Telegram Notification Order Masuk Tidak Aktif';
    }
}

// Setting Telegram Order Selesai Notifications
if (! function_exists('setNotificationTelegramFinish')) {
    function setNotificationTelegramFinish($id = '')
    {
        $data = notifications_setting::where('telegram_order_selesai', $id)->first();
        return $data ? $data->telegram_order_selesai : 'Telegram Notification Order Selesai Tidak Aktif';
    }
}
