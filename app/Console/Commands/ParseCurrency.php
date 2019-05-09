<?php

namespace App\Console\Commands;

use App\Integrations\Currency\Services\CBRFDaily;
use App\Library\Exceptions;
use App\Models;
use Illuminate\Console\Command;

class ParseCurrency extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'currency:parse';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @throws Exceptions\CommandException
     */
    public function handle()
    {
        $currency = new CBRFDaily();

        $currencyList = $currency->getCurrenciesList();

        if(empty($currencyList))
        {
            throw new Exceptions\CommandException('currency list is empty');
        }

        \DB::beginTransaction();

        try {
            \DB::table('currencies')->truncate();


            foreach ($currencyList as $currencyInfo) {
                $currencyEntity = new Models\Currency();

                $currencyEntity->num_code = $currencyInfo->numCode;
                $currencyEntity->external_id = $currencyInfo->CBRFID;
                $currencyEntity->char_code = $currencyInfo->charCode;
                $currencyEntity->nominal = $currencyInfo->nominal;
                $currencyEntity->name = $currencyInfo->name;
                $currencyEntity->value = $currencyInfo->value;

                $currencyEntity->save();

            }

            //Hack: Костыль для рубля, так как его нет в курсах, вот так вот
            $currencyEntity = new Models\Currency();

            $currencyEntity->num_code = 643;
            $currencyEntity->external_id = 'R00643';
            $currencyEntity->char_code = 'RUB';
            $currencyEntity->nominal = 1;
            $currencyEntity->name = 'Российский рубль';
            $currencyEntity->value = 0.00;

            $currencyEntity->save();
        }
        catch (\Exception $e) {
            \DB::rollBack();
            throw new Exceptions\CommandException($e->getMessage());
        }

        \DB::commit();
    }
}
