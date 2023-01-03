<?php


namespace App\Modules\Analytics;


use App\Library\Queue;
use App\Modules;
use App\Models;
use App\Components\DataCharts;

class Consumer extends Modules\Consumers\AbstractRabbitMQConsumer
{
    /**
     * @param Queue\RabbitMQ\Message $message
     */
    public function processEnvelope(Queue\RabbitMQ\Message $message)
    {
        $unpackMessage = $message->getBody();

        $this->messageHandler($unpackMessage);

        $message->getChannel()->basic_ack($message->getDeliveryTag());

        if ($unpackMessage === 'quit') {
            $message->getChannel()->basic_cancel($message->getDeliveryTag());
        }
    }

    /**
     * @param array $unpackMessage
     * @return bool
     */
    public function messageHandler(array $unpackMessage)
    {

        try {
            $chartData = $this->dataForChartType($unpackMessage);

            $controlSum = $unpackMessage['controlSum'];

            $dataToBase = $chartData->getJsonByChart();

        }
        catch (\Exception $e) {
            self::errorLog('Ошибка, не верные данные на входе очереди: ' . var_export($unpackMessage, true));
            return false;
        }


        try {
            $this->recordToBase($controlSum, $unpackMessage['userId'], $dataToBase);
        }
        catch(\Exception $e) {
            self::errorLog('Ошибка, не удалось выполнить запись в БД');
        }

    }

    /**
     * @return Queue\RabbitMQ
     */
    protected function buildRabbitMQObject()
    {
        return QueueEntity::getInstance()->getRabbit();
    }

    /**
     * @return Queue\RabbitMQ\ConsumerParameters
     */
    protected function buildConsumerParameters()
    {
        return QueueEntity::getInstance()->getConsumerParameters();
    }

    /**
     * @param array $unpackMessage
     * @return DataCharts\AbstractChartData
     */
    private function dataForChartType(array $unpackMessage)
    {
        switch ($unpackMessage['chartType'])
        {
            case 'categoryMonth':
                $chartData = DataCharts\ExpensesByMonthCategory::init(
                    $unpackMessage['userId'],
                    $unpackMessage['dateStart'],
                    $unpackMessage['dateEnd']
                );
                return $chartData;

            default:
                $chartData = DataCharts\ExpensesByCategory::init(
                    $unpackMessage['userId'],
                    $unpackMessage['dateStart'],
                    $unpackMessage['dateEnd']
                );
                return $chartData;
        }
    }

    private function recordToBase($controlSum, $userId, $data)
    {
        $chartByControlSum = Models\Charts::whereControlSum($controlSum)->first();

        $chartModel = !empty($chartByControlSum) ? $chartByControlSum : new Models\Charts();

        $chartModel->type = 'line_categories';
        $chartModel->setData($data);
        $chartModel->user_id = $userId;
        $chartModel->control_sum = $controlSum;

        return $chartModel->save();

    }
}
