<?php

namespace App\Http\Controllers;

class QrCodeScannerController extends Controller
{
    /**
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('qrcodescanner');
    }
}
