<?php

declare(strict_types=1);

use App\Mail\InitialPasswordMail;
use Illuminate\Support\Facades\Mail;

\describe('envelop', function () {
    \it('returns correct Envelope', function () {
        $password = 'plainPassword';
        $mail = new InitialPasswordMail($password);

        $result = $mail->envelope();

        \expect($result->subject)->toBe('Initial Password Mail');
    });

});

\describe('content', function () {
    \it('returns correct Content', function () {
        $password = 'plainPassword';
        $mail = new InitialPasswordMail($password);

        $result = $mail->content();

        \expect($result->view)->toBe('mail.initial-password-mail');
        \expect($result->with)->toHaveKey('password', $password);
    });

    \it('renders the subject and send counts', function () {
        $password = 'plainPassword';
        $mail = new InitialPasswordMail($password);

        Mail::fake();

        Mail::to('dummy@example.com')->send($mail);

        Mail::assertSent(InitialPasswordMail::class, 1);
        Mail::assertSent(InitialPasswordMail::class, function ($mail) {
            return $mail->hasSubject('Initial Password Mail')
            && $mail->hasTo('dummy@example.com');
        });
    });
});

\describe('attachments', function () {
    \it('return to attachements', function () {
        $password = 'plainPassword';
        $mail = new InitialPasswordMail($password);

        $result = $mail->attachments();

        \expect($result)->toBe([]);
    });
});
