<?php


namespace App\Library\CRUD;

use Auth;
use Illuminate\Http;

abstract class AbstractCUDWithRelatedUser extends AbstractCreateUpdateDelete
{

    /**
     * @var int
     */
    protected $userId;

    public function __construct(Http\Request $request)
    {
        parent::__construct($request);
        $this->userId = Auth::id();
    }
}