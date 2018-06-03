<?php

namespace App\Components\DataCharts;

use \App\Funds;
use \App\revCategories;

class ExpensesByCategory
{

    /**
     * @var string
     */
    public $userId;

    /**
     * @var string
     */
    public $dateStart;

    /**
     * @var string
     */
    public $dateEnd;

    /**
     * @var int
     */
    public $fundsRev;

    /**
     * ExpensesByCategory constructor.
     * @param $userId
     * @param $dateStart
     * @param $dateEnd
     */
    public function __construct($userId, $dateStart, $dateEnd, $fundsRev=2)
    {
        $this->userId = $userId;
        $this->dateStart = $dateStart;
        $this->dateEnd = $dateEnd;
        $this->fundsRev = $fundsRev;
    }

    /**
     * @param $userId
     * @param $dateStart
     * @param $dateEnd
     * @return ExpensesByCategory
     */
    public static function init($userId, $dateStart, $dateEnd)
    {
        return new self($userId, $dateStart, $dateEnd);
    }

    /**
     * @return Funds|\Illuminate\Database\Query\Builder
     */
    public function queryFunds()
    {
       return Funds::select('funds.user_id', 'funds.sum', 'funds.date', 'funds.cause', 'rev_categories.id as category_id', 'rev_categories.name as category_name')
            ->leftJoin('rev_categories', 'funds.category_id', 'rev_categories.id')
            ->where('funds.user_id', '=', $this->userId)
            ->where('funds.rev', '=', $this->fundsRev)
            ->whereBetween('funds.date', [$this->dateStart, $this->dateEnd]);
    }

    public function queryCategories()
    {
        return revCategories::select('id', 'user_id', 'name')->where('user_id', '=', $this->userId);
    }

    /**
     * @return Funds[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Support\Collection
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
     */
    public function categorySum()
    {
        $fundsData = $this->groupByCategory();

        $newArray = array();


        foreach($fundsData as $date => $itemsCategory) {

            foreach($itemsCategory as $categoryID=>$items) {

                $newArray[$date][$categoryID]['sum'] = 0;
                $newArray[$date][$categoryID]['name'] = $items[0]['category_name'];

                foreach($items as $item) {

                    $newArray[$date][$categoryID]['sum'] += $item['sum'];

                }

            }

        }

        return $newArray;

    }

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
    public function getJson()
    {
        return json_encode($this->getData());
    }

    public function getJsonByChart()
    {
        $data = $this->getData();

        $array = array();

        $dataArray = array();

        foreach($data as $date => $items) {
            $array['x'][] = $date;

        }



        return $array;

    }


}