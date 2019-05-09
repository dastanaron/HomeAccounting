<?php


namespace App\Library\CRUD;

use App\Library;
use Illuminate\Http;

abstract class AbstractCreateUpdateDelete implements CreateUpdateDeleteInterface
{
    /**
     * @var Library\Utilities\Helpers\Helpers
     */
    protected $helpers;

    /**
     * @var Http\Request
     */
    protected $request;

    public function __construct(Http\Request $request)
    {
        $this->helpers = Library\Utilities\Helpers\Helpers::getInstance();
        $this->request = $request;
    }
}