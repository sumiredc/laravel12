<?php

declare(strict_types=1);

use App\Http\Responses\ErrorResponse;

\describe('__construct', function () {
    \it('initializes this with the specified values', function ($status, $message) {
        $result = new ErrorResponse($status);

        \expect($result->getStatusCode())->toBe($status);
        \expect($result->getContent())->toBe(\json_encode(['message' => $message]));
    })
        ->with([
            [400, 'Bad Request'],
            [401, 'Unauthorized'],
            [402, 'Payment Required'],
            [403, 'Forbidden'],
            [404, 'Not Found'],
            [405, 'Method Not Allowed'],
            [406, 'Not Acceptable'],
            [407, 'Proxy Authentication Required'],
            [408, 'Request Timeout'],
            [409, 'Conflict'],
            [410, 'Gone'],
            [411, 'Length Required'],
            [412, 'Precondition Failed'],
            [413, 'Content Too Large'],
            [414, 'URI Too Long'],
            [415, 'Unsupported Media Type'],
            [416, 'Range Not Satisfiable'],
            [417, 'Expectation Failed'],
            [418, 'I\'m a teapot'],
            [421, 'Misdirected Request'],
            [422, 'Unprocessable Content'],
            [423, 'Locked'],
            [424, 'Failed Dependency'],
            [425, 'Too Early'],
            [426, 'Upgrade Required'],
            [428, 'Precondition Required'],
            [429, 'Too Many Requests'],
            [431, 'Request Header Fields Too Large'],
            [451, 'Unavailable For Legal Reasons'],
            [500, 'Internal Server Error'],
            [501, 'Not Implemented'],
            [502, 'Bad Gateway'],
            [503, 'Service Unavailable'],
            [504, 'Gateway Timeout'],
            [505, 'HTTP Version Not Supported'],
            [506, 'Variant Also Negotiates'],
            [507, 'Insufficient Storage'],
            [508, 'Loop Detected'],
            [510, 'Not Extended'],
            [511, 'Network Authentication Required'],
            [599, 'An unknown error has occurred'],
        ]);

    \it('throws a InvaidArgumentException when status code invalid', function ($status) {
        new ErrorResponse($status);
    })
        ->with([
            'minimum status value' => 399,
            'maximum status value' => 600,
        ])
        ->throws(InvalidArgumentException::class);
});
