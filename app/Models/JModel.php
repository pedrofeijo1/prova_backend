<?php

namespace App\Models;

use App\Http\Services\JsonService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Str;

class JModel
{
    private $jsonService;

    public function __construct(JsonService $jsonService)
    {
        $this->jsonService = $jsonService;
        $this->jsonService->setFileName(__('key.' . $this->getFileName()));
    }

    public static function find($index, $fields = [])
    {
        $model = self::all($fields)
            ->get($index);

        if ($model === null) {
            throw new ModelNotFoundException;
        }

        return $model;
    }

    public static function where($key, $value, $fields = [])
    {
        return self::all($fields)
            ->where($key, $value);
    }

    public static function whereIn($key, $values, $fields = [])
    {
        return self::all($fields)
            ->whereIn($key, $values);
    }

    public static function query()
    {
        return new static(new JsonService);
    }

    public static function all($fields = [])
    {
        $model = self::query()->getModel();

        if (!empty($fields)) {
            $model = $model->map(function($values) use ($fields) {
                return collect($values)->only($fields);
            });
        }

        return $model;
    }

    private function getModel()
    {
        return collect(
            $this->jsonService->getFormmatedJsonContent()
        );
    }

    private function getFileName()
    {
        return Str::snake(
            Str::plural(
                class_basename(get_called_class())
            )
        );
    }
}
