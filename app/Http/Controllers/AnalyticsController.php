<?php

namespace App\Http\Controllers;

use App\RabbitMQ\DataConstants;
use Illuminate\Http\Request;
use App\RabbitMQ\Analytics\MessagePush;
use Storage;

class AnalyticsController extends Controller
{
    /**
     * PrivateAreaController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * @return array
     */
    public function index()
    {
        return ['status' => 200, 'message' => 'ok'];
    }

    /**
     * @param Request $request
     * @return array
     */
    public function createQueueToAnalyticsData(Request $request)
    {

        $data = $this->createChartDataValidator($request);

        if($data !== false) {
            return ['status' => MessagePush::init()->push($data) ? 200 : 400, 'fileId' => md5(serialize($data))];
        }
        else {
            return ['status' => 400, 'message' => 'Не заполнены обязательные поля'];
        }


    }

    /**
     * @param Request $request
     * @return array|string
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function getDataToChartAnalytics(Request $request)
    {

        try {

            $userId = \Auth::user()->id;

            $fileId = $request->input('file_id');

            $file = DataConstants::ANALYTICS_STORAGE_FOLDER . $userId . '/' . $fileId . '.json';

            $data = Storage::disk()->get($file);

            return $data;
        }
        catch(\Exception $e) {

            return ['status' => 400, 'message' => $e->getMessage()];

        }

    }

    private function createChartDataValidator(Request $request)
    {
        $userId = \Auth::user()->id;

        if(!empty($request->input('date_start')) && !empty($request->input('date_end')) && !empty($request->input('rev'))) {
            $data = [
                'userId' => $userId,
                'dateStart' => $request->input('date_start'),
                'dateEnd' => $request->input('date_end'),
                'rev' => $request->input('rev'),
            ];
            return $data;
        }
        else {
            return false;
        }


    }



}
