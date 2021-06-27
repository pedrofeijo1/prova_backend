<?php


namespace App\Http\Services;

use Illuminate\Http\Request;

class Auth extends Service
{
    private $bearerToken;

    public function __construct(Request $request)
    {
        $this->bearerToken = $request->bearerToken();
    }

    public function check()
    {
        return $this->bearerToken === static::token();
    }

    public static function token()
    {
        return '2WV*EF643!WG@5cd3q3ZS@#LP@$m?8gU';
    }
}
