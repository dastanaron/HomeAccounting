<?php

namespace App\Library\BaseInterfaces;

use Illuminate\Database\Eloquent;

interface CollectionList
{
    public function getList() : Eloquent\Collection;
}