<?php

namespace App\Http\Controllers;

use App\Library\Queue\RabbitMQ;
use App\Models;
use App\Components\DataCharts\DynamicAccumulate;
use App\Modules\Analytics\QueueEntity;
use Illuminate\Http\Request;
use App\Library\Queue;

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

            $rabbit = QueueEntity::getInstance()->getRabbit();
            $message = new Queue\RabbitMQ\Message($data);
            $rabbit->push($message);

            return ['status' => 200, 'controlSum' => $data['controlSum']];
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

            $controlSum = $request->input('control_sum');

            $model = Models\Charts::where([
                ['control_sum', '=', $controlSum],
                ['user_id', '=', $userId]
            ])->first();

            return !empty($model) ? $model->getData() : ['status' => 'try_again'];
        }
        catch(\Exception $e) {

            return ['status' => 400, 'message' => $e->getMessage()];

        }

    }

    /**
     * @param Request $request
     * @return array
     */
    public function getAccumulateDynamic(Request $request)
    {
        $userId = \Auth::user()->id;
        $dynamicAccumulate = new DynamicAccumulate($userId, $request->input('date_start'), $request->input('date_end'));

        return !empty($dynamicAccumulate->getData()) ? $dynamicAccumulate->getJsonByChart() : ['status' => 400, 'message' => 'selection is empty'];
    }

    /**
     * @param Request $request
     * @return array|bool
     */
    private function createChartDataValidator(Request $request)
    {
        $userId = \Auth::user()->id;

        if(!empty($request->input('date_start')) && !empty($request->input('date_end')) && !empty($request->input('rev'))) {

            $controlSum = md5(serialize([
                $request->input('date_start'),
                $request->input('date_end'),
                $request->input('rev'),
                $request->input('chart_type'),
            ]));

            $data = [
                'userId' => $userId,
                'dateStart' => $request->input('date_start'),
                'dateEnd' => $request->input('date_end'),
                'rev' => $request->input('rev'),
                'chartType' => $request->input('chart_type'),
                'controlSum' => $controlSum,
            ];
            return $data;
        }
        else {
            return false;
        }


    }
}
