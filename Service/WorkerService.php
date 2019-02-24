<?php

namespace app\Service;


class WorkerService
{
    private $url = 'https://www.quandl.com/api/v3/datasets/WIKI/%symbol%.csv?order=asc&start_date=%start%&end_date=%end%';
    private static $csv = __DIR__.DIRECTORY_SEPARATOR.'companylist.csv';
    private static $symbols = [];

    public function __construct()
    {
        $this->init();
    }

    public function init() : void
    {
    }

    /**
     * @param string $symbol
     * @param string $start
     * @param string $end
     * @param string $email
     * @return array
     * @throws WorkerServiceException
     */
    public function getData(string $symbol, string $start, string $end, string $email) : array
    {
        self::validate($symbol, $start, $end, $email);

        $url = $this->replaceUrl($symbol, $start, $end);
        $data = [];
        $i = 0;
        foreach ( self::getRows($url) as $row) {
            if($i==0){ $i=1; continue; };
            $data[] = $row;
        };
        return $data;
    }

    private function replaceUrl(string $symbol, string $start, string $end) : string
    {
        $url = str_replace('%symbol%', $symbol , $this->url);
        $url = str_replace('%start%', $start , $url);
        $url = str_replace('%end%', $end , $url);
        return $url;
    }

    public static function validate(string $symbol, string $start, string $end, string $email) : void
    {
        if(!self::isDate($start)){
            throw new WorkerServiceException('Start date is not valid');
        };
        if(!self::isDate($end)){
            throw new WorkerServiceException('End date is not valid');
        };
        if(!self::isEmail($email)){
            throw new WorkerServiceException('Email is not valid');
        };
        self::comparisonDate($start, $end);

        self::getSymbols();
        if(!self::isSymbol($symbol)){
            throw new WorkerServiceException('Symbol not found');
        };
    }

    public static function comparisonDate(string $start, string $end) : void
    {
        if( (new \DateTime($start)) > (new \DateTime($end)) ){
            throw new WorkerServiceException('Start date can not be more than the end date');
        };
    }

    public static function isSymbol(string $symbol) : bool
    {
        $symbol = strtolower($symbol);
        if(in_array($symbol, self::$symbols)){
            return true;
        };
        return false;
    }

    public static function isDate(string $date) : bool
    {
        if (preg_match("/^\d{4}-\d{2}-\d{2}$/i", $date)) {
            return true;
        };
        return false;
    }

    public static function isEmail(string $string) : bool
    {
        if (preg_match("/^(\w|\d)+@(\w|\d)+\.(\w|\d)+$/i", $string)) {
            return true;
        };
        return false;
    }

    public static function getRows(string $file) : \Generator
    {
        $handle = fopen($file, 'rb');
        if($handle === false){
            throw new WorkerServiceException('Csv file not open');
        };
        while (feof($handle) === false){
            yield fgetcsv($handle);
        };
        fclose($handle);
    }

    public static function getSymbols() : array
    {
        self::setSymbols();
        return self::$symbols;
    }

    private static function setSymbols() : void
    {
        if(empty(self::$symbols)){
            $i=0;
            foreach ( self::getRows(self::$csv) as $row) {
                if($i==0){ $i=1; continue; };
                self::$symbols[] = strtolower($row[0]);
            };
            self::$symbols = array_unique(self::$symbols);
        };
    }
}