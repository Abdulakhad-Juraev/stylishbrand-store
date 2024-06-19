<?php
return [
    'adminEmail' => 'admin@example1.com',
    'supportEmail' => 'support@example.com',
    'senderEmail' => 'noreply@example.com',
    'senderName' => 'Example.com mailer',
    'user.passwordResetTokenExpire' => 3600,
    'user.passwordMinLength' => 8,
    'languageParam' => 'lang',
    'languages' => [
        'uz' => "O'zbekcha",
        'ru' => "Русский",
//        'en' => "English",
    ],
    'defaultLanguage' => 'uz',
    'is_sms_test' => true,
    // ffmpeg begin
    'is_linux' => false,
    'localhost' => true,
    'ffprobeBin' => 'ffprobe', //path to ffprobe binary
    'ffmpegBin' => 'ffmpegBin', //path to ffmpeg binary
    // ffmpeg end
];
