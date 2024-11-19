<?php
declare(strict_types=1);

//==============Не редактировать
final class DataBase
{
    private bool $isConnected = false;

    public function connect(): bool
    {
        sleep(1);
        $this->isConnected = true;
        return 'connected';
    }

    public function random()
    {
        $this->isConnected = rand(0, 3) ? $this->isConnected : false;
    }

    public function fetch($id): string
    {
        $this->random();
        if (!$this->isConnected) {
            throw new Exception('No connection');
        }
        usleep(100000);
        return 'fetched - ' . $id;
    }

    public function insert($data): string
    {
        $this->random();
        if (!$this->isConnected) {
            throw new Exception('No connection');
        }
        usleep(900000);
        return 'inserted - ' . $data;
    }


    public function batchInsert($data): string
    {
        $this->random();
        if (!$this->isConnected) {
            throw new Exception('No connection');
        }
        usleep(900000);
        return 'batch inserted';
    }
}
//==============

class DataBaseHelper
{
    private DataBase $dataBase;

    public function __construct()
    {
        $this->dataBase = new DataBase();
        $this->connect();
    }

    public function fetch(int $id): string
    {
        while (true) {
            try {
                return $this->dataBase->fetch($id);
            } catch (Exception $e) {
                $this->connect();
            }
        }
    }

    public function insert(int $id): string
    {
        while (true) {
            try {
                return $this->dataBase->insert($id);
            } catch (Exception $e) {
                $this->connect();
            }
        }
    }

    public function batchInsert(array $data): string
    {
        while (true) {
            try {
                return $this->dataBase->batchInsert($data);
            } catch (Exception $e) {
                $this->connect();
            }
        }
    }

    private function connect()
    {
        $onError = function ($level, $message, $file, $line) {
        };
        try {
            set_error_handler($onError);
            $this->dataBase->connect();
        } catch (Throwable $throwable) {

        } finally {
            restore_error_handler();
        }
    }
}

function step1(array $dataToFetch): void
{
    $dataBaseHelper = new DataBaseHelper();

    for ($i = 0; $i < count($dataToFetch); $i++) {
        print($dataBaseHelper->fetch($dataToFetch[$i]));
        print(PHP_EOL);
    }
}

function step2(array $dataToInsert): void
{
    $dataBaseHelper = new DataBaseHelper();
    $dataBaseHelper->batchInsert($dataToInsert);

    for ($i = 0; $i < count($dataToInsert); $i++) {
        print('inserted - ' . $dataToInsert[$i]);
        print(PHP_EOL);
    }
}

//==============Не редактировать
$dataToFetch = [1, 2, 3, 4, 5, 6];
$dataToInsert = [7, 8, 9, 10, 11, 12];

step1($dataToFetch);
step2($dataToInsert);
print("Success");
//==============