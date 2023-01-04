<?php

declare(strict_types=1);

namespace App\Components\PA\CRUD;

use App\Library\BaseInterfaces;
use App\Library\CRUD;
use App\Models;
use Illuminate\Database\Eloquent;

/**
 * Class Categories
 * @package App\Components\PA\CRUD
 */
class Categories extends CRUD\AbstractCUDWithRelatedUser implements BaseInterfaces\CollectionList
{
    /**
     * @return Eloquent\Collection
     */
    public function getList() : Eloquent\Collection
    {
        return Models\revCategories::whereUserId($this->userId)->get();
    }

    public function getById() : Models\revCategories
    {
        return Models\revCategories::whereId($this->request->route('id'))->first();
    }

    /**
     * @return bool
     */
    public function create() : bool
    {
        $category = new Models\revCategories();

        $category->user_id = $this->userId;
        $category->name = $this->request->input('name');

        return (bool) $category->save();
    }

    /**
     * @return bool
     */
    public function update() : bool
    {
        $category = Models\revCategories::whereId($this->request->input('category_id'))->first();

        if(empty($category)) {
            return false;
        }

        $category->name = $this->request->input('name');

        return (bool) $category->save();
    }

    /**
     * @return bool
     * @throws \Exception
     */
    public function delete() : bool
    {
        $category = Models\revCategories::whereId($this->request->input('category_id'))->first();

        if(empty($category)) {
            return false;
        }

        return (bool) $category->delete();

    }

}
