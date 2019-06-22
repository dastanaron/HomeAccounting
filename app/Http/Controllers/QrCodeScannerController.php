<?php

namespace App\Http\Controllers;

use Illuminate\Http;
use App\Integrations\nalogRu;
use Illuminate\Support\Facades;
use App\Models;
use App\Library\Utilities;
use Illuminate\Database;

class QrCodeScannerController extends Controller
{
    /**
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('qrcodescanner');
    }

    /**
     * Тестовая чешуя, чтобы посмотреть как это вообще будет
     * @param Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendCheck(Http\Request $request)
    {
        $qrcode = $request->input('qrcode');

        $userId = \Auth::id();

        $isValidCode = (new nalogRu\Library\BarcodeParser())->simpleParse($qrcode)->isValid();

        if ($isValidCode) {
            $checkQueueModel = new Models\CheckQueue();

            $checkQueueModel->qrcode = $qrcode;

            $checkQueueModel->user_id = $userId;

            try {
                $isSaved = $checkQueueModel->save();
            }
            catch (Database\QueryException $e) {
                return Facades\Response::json(['status' => 'error', 'message' => 'Cannot save check, may be the check already recorded to base'])->setStatusCode(500);
            }

            if (!$isSaved) {
                return Facades\Response::json(['status' => 'error', 'message' => 'Cannot save check, unknown error'])->setStatusCode(500);
            }

            return Facades\Response::json(['status' => 'saved', 'message' => 'QRcode was saved'])->setStatusCode(200);
        }

        return Facades\Response::json(['status' => 'error', 'message' => 'qrcode is invalid'])->setStatusCode(500);
    }
}
