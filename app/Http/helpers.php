<?php

function admin() {
    return \App\Models\Role::json(0);
}

function cashier() {
    return \App\Models\Role::json(1);
}

function cardholder() {
    return \App\Models\Role::json(2);
}

function guest() {
    return \App\Models\Role::json(3);
}

function sendSms($phone_number, $view, $data = null, $online = false) {
    try {
        if ($online) {
            (new \App\Libraries\SmsGatewayMe(config('mail.from.address'),
                config('smsgatewayme.password')))->sendMessageToNumber($phone_number,
                (String)view($view, compact('data')),
                config('smsgatewayme.device'));
        } else {
            (new \App\Libraries\RESTSmsGateway(config('restsmsgateway.host'),
                config('restsmsgateway.port')))->sendMessageToNumber($phone_number,
                (String)view($view, compact('data')));
        }
    } catch (\Exception $e) {
        \Illuminate\Support\Facades\Log::error($e);
        sendEmail(\App\Models\User::where('phone_number', $phone_number)->first()->email, $view, $data);
    } finally {
        \Illuminate\Support\Facades\Log::info((String)view($view, compact('data')));
    }
}

function sendEmail($email, $view, $data = null) {
    try {
        \Illuminate\Support\Facades\Mail::send($view, compact('data'), function ($message) use ($email) {
            $message->from(config('mail.from.address'), config('mail.from.name'));
            $message->to($email);
        });
    } catch (\Exception $e) {
        \Illuminate\Support\Facades\Log::error($e);
        sendSms(\App\Models\User::where('email', $email)->first()->phone_number, $view, $data);
    } finally {
        \Illuminate\Support\Facades\Log::info((String)view($view, compact('data')));
    }
}

function sendSmsAndEmail($phone_number, $email, $view, $data = null) {
    sendSms($phone_number, $view, $data);
    sendEmail($email, $view, $data);
}