<?php


namespace Tests\Unit\DB;

use Tests;
use Artisan;
use Illuminate\Support\Facades\DB;
use App\Library;

/**
 * Class AbstractDataBaseTest
 * @package Tests\Unit\DB
 *
 * Плохой класс, с точки зрения хорошего кода, но встроенные методы Laravel позволяют только откатывать
 * и накатывать миграции, что является очень долгим процессом. Поэтому здесь мы создаем базу по имени из конфига
 * или если она существует ловим ошибку, и игнорируем ее. Далее накатываем миграции, если они все есть, то все хорошо.
 * Далее выполняется тест, после чистим все таблицы базы данных, кроме таблицы миграций, иначе будет фейл при попытке накатить миграции.
 *
 * Главное, что это работает быстрее чем откат и накат миграций для каждого теста, и для каждого теста у нас чистые таблицы,
 * а соответственно и чистые тесты.
 */
abstract class AbstractDataBaseTest extends Tests\TestCase
{

    protected $dbName = '';

    protected function setUp()
    {
        parent::setUp();
        $this->dbName = config('database.connections.mysql_test.database');
        try {
            DB::getPdo()->query("CREATE DATABASE {$this->dbName};");
        }
        catch (\Doctrine\DBAL\Driver\PDOException $e)
        {
            if($e->getErrorCode() === 1007) {
                //nothing to do
            }
            else {
                throw new Library\Exceptions\BaseException($e->getMessage());
            }
        }

        DB::setDefaultConnection('mysql_test');
        Artisan::call('migrate');
    }


    protected function tearDown()
    {
        $tables = $this->getTables();

        DB::getPdo()->query("SET FOREIGN_KEY_CHECKS = 0;");

        foreach ($tables as $tableName) {
            DB::getPdo()->query("TRUNCATE TABLE `{$tableName}`");
        }

        DB::getPdo()->query("SET FOREIGN_KEY_CHECKS = 1;");

        parent::tearDown();
    }

    /**
     * @return array
     */
    private function getTables()
    {
        $tables = [];
        $pdo = DB::getPdo();
        $stmt = $pdo->query("SHOW TABLES");

        while ($row = $stmt->fetch(\PDO::FETCH_NUM))
        {
            if (array_key_exists(0, $row)) {
                if ($row[0] !== 'migrations') {
                    $tables[] = $row[0];
                }
            }
        }

        return $tables;
    }
}