<?php

namespace App\Http\Services;

use Symfony\Component\HttpFoundation\Response;

class ResponseService extends Service
{
    public function error($message, $status, $headers = [], $options = 0)
    {
        return response()->json([
            'message' => $message
        ], $status, $headers, $options);
    }

    public function serverError($message, $headers = [], $options = 0)
    {
        return $this->error($message, Response::HTTP_INTERNAL_SERVER_ERROR, $headers, $options);
    }

    public function unauthorized($message, $headers = [], $options = 0)
    {
        return $this->error($message, Response::HTTP_UNAUTHORIZED, $headers, $options);
    }

    public function badRequest($message, $headers = [], $options = 0)
    {
        return $this->error($message, Response::HTTP_BAD_REQUEST, $headers, $options);
    }

    public function forbidden($message, $headers = [], $options = 0)
    {
        return $this->error($message, Response::HTTP_FORBIDDEN, $headers, $options);
    }

    public function notFound($message, $headers = [], $options = 0)
    {
        return $this->error($message, Response::HTTP_NOT_FOUND, $headers, $options);
    }
}
