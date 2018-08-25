<?php

namespace App\Modules\Import;


interface ImportFileInterface
{

    public function openFile(string $filePath);

}