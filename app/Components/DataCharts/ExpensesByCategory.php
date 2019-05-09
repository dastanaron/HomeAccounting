<?php

namespace App\Components\DataCharts;

use \App\Models;
use App\Library\Utilities;

/**
 *
 * Класс для сбора данных для графика
 *
 * Принимает в коструктор user_id, выборку дат и указатель расходов.
 *
 * Нужно сборку данных вынести в очередь, поскольку очень сложные пересборки массивов.
 * Потом из сборки выводить на front'end готовый json.
 *
 * Class ExpensesByCategory
 * @package App\Components\DataCharts
 */
class ExpensesByCategory extends AbstractChartData
{

    /**
     * @return \Illuminate\Database\Query\Builder
     */
    public function queryFunds()
    {
       return Models\Funds::select('funds.user_id', 'funds.sum', 'funds.date', 'funds.cause', 'rev_categories.id as category_id', 'rev_categories.name as category_name')
            ->leftJoin('rev_categories', 'funds.category_id', 'rev_categories.id')
            ->where('funds.user_id', '=', $this->userId)
            ->where('funds.rev', '=', $this->fundsRev)
            ->whereBetween('funds.date', [$this->dateStart, $this->dateEnd]);
    }

    /**
     * @return Models\revCategories
     */
    public function queryCategories()
    {
        return Models\revCategories::select('id', 'user_id', 'name')->where('user_id', '=', $this->userId);
    }

    /**
     * @return array
     */
    public function getGroupedByDate()
    {
        return $this->queryFunds()->get()->groupBy('date')->toArray();
    }

    /**
     * @return array
     */
    public function groupByCategory()
    {
        $data = $this->getGroupedByDate();

        $array = array();

        foreach($data as $date => $element) {

            foreach($element as $item) {
                $array[$date][$item['category_id']][] = $item;
            }
        }

        return $array;
    }

    /**
     * @return array
     * @throws \Exception
     */
    public function categorySum()
    {
        $fundsData = $this->groupByCategory();

        $newArray = array();


        foreach($fundsData as $date => $itemsCategory) {

            $formattedDate = $this->dateFormat($date);

            foreach($itemsCategory as $categoryID=>$items) {

                $newArray[$formattedDate][$categoryID]['sum'] = 0;
                $newArray[$formattedDate][$categoryID]['name'] = $items[0]['category_name'];

                foreach($items as $item) {

                    $newArray[$formattedDate][$categoryID]['sum'] += $item['sum'];

                }
            }
        }

        return $newArray;
    }

    /**
     * @param $date
     * @param string $format
     * @return string
     * @throws \Exception
     */
    public function dateFormat($date, $format="Y-m-d")
    {
        return (new \DateTime($date))->format($format);
    }

    /**
     * @return array
     */
    public function addSumToOtherCategories()
    {
        $categories = $this->queryCategories()->get()->toArray();
        $data = $this->categorySum();

        $newArray = array();
        $tmpCategories = array();

        foreach($categories as $category) {

            $tmpCategories[$category['id']]['sum'] = 0;
            $tmpCategories[$category['id']]['name'] = $category['name'];
        }

        foreach($data as $date=>$categorySum) {

            $newArray[$date] = array_replace($tmpCategories, $categorySum);

        }

        return $newArray;
    }

    /**
     * @return array
     */
    public function getData()
    {
        return $this->addSumToOtherCategories();
    }

    /**
     * @return string
     */
    public function getJsonByChart()
    {
        $data = $this->getData();

        $xArray = array();

        $string = 'x, ';

        foreach($data as $date => $items) {
            $xArray['x'][] = $date;
        }

        $string .= implode(', ', $xArray['x']);

        $array = array();

        $x = explode(', ', $string);

        $categoryData = $this->buildRowsToChart();

        $array[] = $x;

        foreach ($categoryData as $item) {
            $array[] = $item;
        }

        return Utilities\Json::encode($array);
    }

    /**
     * @return array
     */
    public function buildRowsToChartData()
    {
        $data = $this->getData();

        $categories = $this->queryCategories()->get()->toArray();


        $array = array();

        $i = 0;

        foreach($categories as $category) {

            $array[$i][$category['name']] = array();

            foreach($data as $date => $items) {

                foreach($items as $categoryId => $item) {
                    if($categoryId === $category['id']) {
                        $array[$i][$category['name']][] = $item['sum'];
                    }
                }

            }

            $i++;
        }

        return $array;
    }


    /**
     * @return array
     */
    public function buildRowsToChart()
    {
        $data = $this->buildRowsToChartData();

        $array = array();

        $string = '';

        foreach($data as $elements) {

            foreach($elements as $key => $value) {

                $string = $key . ', ' . implode(', ', $value);

            }

            $array[] = $string;

        }

        $newArray = array();

        foreach($array as $item) {

            $newArray[] = explode(', ', $item);

        }

        $formattedArray = array();

        foreach($newArray as $items) {

            $tmpArray = array();

            foreach($items as $item) {

                if(preg_match('#\d+#', $item)) {
                    $tmpArray[] = (int) $item;
                }
                else {
                    $tmpArray[] = $item;
                }

            }
            $formattedArray[] = $tmpArray;
        }

        return $formattedArray;
    }
}