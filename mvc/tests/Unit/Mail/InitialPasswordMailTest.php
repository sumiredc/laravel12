<?php

declare(strict_types=1);

use App\Mail\InitialPasswordMail;

describe('InitialPasswordMailTest', function () {
    it('envelope', function () {
        $password = 'plainPassword';
        $mail = new InitialPasswordMail($password);

        $result = $mail->envelope();

        expect($result->subject)->toBe('Initial Password Mail');
    });

    it('content', function () {
        $password = 'plainPassword';
        $mail = new InitialPasswordMail($password);

        $result = $mail->content();

        expect($result->view)->toBe('mail.initial-password-mail');
        expect($result->with)->toBe(['password' => $password]);
    });

    it('attachments', function () {
        $password = 'plainPassword';
        $mail = new InitialPasswordMail($password);

        $result = $mail->attachments();

        expect($result)->toBe([]);
    });
});
