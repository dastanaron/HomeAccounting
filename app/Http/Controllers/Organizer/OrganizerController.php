<?php
namespace App\Http\Controllers\Organizer;

use App\Http\Controllers\Controller;

class OrganizerController extends Controller
{
    public function index()
    {
        return view('organizer/organizer-index');
    }
}
