<?php

use App\Models\MailConfig;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Mail;

function sendUserEmail($user, $password, $template)
{

$mailConfig = MailConfig::find(1);

Config::set('mail.mailers.smtp.host', $mailConfig->host);
Config::set('mail.mailers.smtp.port', $mailConfig->port);
Config::set('mail.mailers.smtp.encryption', $mailConfig->encryption);
Config::set('mail.mailers.smtp.username', $mailConfig->username);
Config::set('mail.mailers.smtp.password', $mailConfig->password);
Config::set('mail.from.address', $mailConfig->email);
Config::set('mail.from.name', $mailConfig->name);

try {
$find = array("{name}", "{email}", "{password}");
$replace = array($user->name, $user->email, $password);

$body = str_replace($find, $replace, $template->body);

Mail::raw($body, function ($message) use ($template, $user) {
$message->to($user->email)
// ->cc([$mailConfig->info_mail])
->subject($template->subject);
});
} catch (\Exception $e) {
print_r($template);
print_r($e->getMessage());
die();
}
return true;
}

