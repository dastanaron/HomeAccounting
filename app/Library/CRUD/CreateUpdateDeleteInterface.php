<?php


namespace App\Library\CRUD;

interface CreateUpdateDeleteInterface
{
    public function create() : bool;

    public function update() : bool;

    public function delete() : bool;
}