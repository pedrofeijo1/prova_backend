<?php

namespace App\Http\Services;

class JsonService extends Service
{
    private $fileName;

    public function setFileName($fileName)
    {
        $this->fileName = $fileName;
    }

    public function getFormmatedJsonContent()
    {
        return json_decode($this->getJsonContent(), true);
    }

    private function getJsonContent()
    {
        return \File::get(
                $this->getJsonFileLocation() . $this->getJsonFileName()
        );
    }

    private function getJsonFileLocation()
    {
        return database_path() . '/';
    }

    private function getJsonFileName()
    {
        return $this->fileName . '.json';
    }
}
