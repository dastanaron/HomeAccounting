<?php

namespace App\Http\helpers;

use Illuminate\Http\Request;
use Auth;
use App\revCategories;

class CategoriesHelper
{
    /**
     * @var Request
     */
    protected $request;
    /**
     * @var int
     */
    protected $user_id;


    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->user_id = Auth::user()->id;
    }

    public function getCategories()
    {
        return revCategories::whereUserId($this->user_id)->get();
    }

    public function createCategory()
    {
        $category = new revCategories();

        $category->user_id = $this->user_id;
        $category->name = $this->request->input('name');

        return $category->save();

    }

    public function setCategory()
    {
        $category = revCategories::whereId($this->request->input('category_id'))->first();

        if(empty($category)) {
            return false;
        }

        $category->name = $this->request->input('name');

        return $category->save();

    }

    public function deleteCategory()
    {
        $category = revCategories::whereId($this->request->input('category_id'))->first();

        if(empty($category)) {
            return false;
        }

        return $category->delete();

    }

}