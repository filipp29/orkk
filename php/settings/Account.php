<?php



namespace Settings;

class Account {
    
    static private $keys = [
        "posTypeParamList"
    ];
    
    /*--------------------------------------------------*/
    
    static public function getAll(){
        $result = [];
        foreach(self::$keys as $key){
            $result[$key] = self::$key();
        }
        return $result;
    }
    
    /*--------------------------------------------------*/
    
    
    static public function posTypeParamList(){
        return [
            "Расширение ЛВС" => [
                "contractDate",
                "connectSum",
                "hardware"
            ],
            "Смена реквизитов" => [
                "name",
                "contractDate",
                "iban",
                "bik",
                "bank",
                "legalCity",
                "legalStreetType",
                "legalStreet",
                "legalBuildingType",
                "legalBuilding",
                "legalFlatType",
                "legalFlat",
                "activateDate"
            ],
            "Смена местонахождения" => [
                "contractDate",
                "city",
                "streetType",
                "street",
                "buildingType",
                "building",
                "flatType",
                "flat",
                "connectSum",
                "activateDate",
                "hardware"
            ],
            "Увеличение ширины канала" => [
                "contractDate",
                "connectSum",
                "amount",
                "activateDate",
                "speed",
                "attractType",
                "hardware"
            ],
            "Уменьшение ширины канала" => [
                "contractDate",
                "connectSum",
                "amount",
                "activateDate",
                "speed",
                "hardware"
            ],
            "Аренда серверной стойки" => [
                "contractDate",
                "connectSum",
                "amount",
                "activateDate",
                "attractType",
                "hardware"
            ],
            "Аренда канала" => [
                "contractDate",
                "connectSum",
                "amount",
                "activateDate",
                "attractType",
                "hardware"
            ],
            "Обслуживание ЛВС" => [
                "contractDate",
                "connectSum",
                "amount",
                "activateDate",
                "attractType",
                "hardware"
            ],
            "Подключение к сети" => [
                "contractDate",
                "connectSum",
                "amount",
                "activateDate",
                "attractType",
                "hardware"
            ],
            "Аренда статического IP адреса" => [
                "contractDate",
                "amount",
                "activateDate",
                "attractType"
            ],
        ];
    }
    
    /*--------------------------------------------------*/
    
    static public function commentTemplate(){
        return [
            "Дополнительная точка" => 1,
            "Расширение ЛВС" => 2,
            "Смена реквизитов" => 3,
            "Смена местонахождения" => 4,
            "Увеличение ширины канала" => 5,
            "Уменьшение ширины канала" => 6,
            "Аренда серверной стойки" => 7,
            "Аренда канала" => 8,
            "Обслуживание ЛВС" => 9,
            "Подключение к сети" => 10,
            "Аренда статического IP адреса" => 11,
            "Спецификация" => 12
        ];
    }
    
    /*--------------------------------------------------*/
    
    static public function amountPlus(){
        return [
            "Аренда серверной стойки",
            "Аренда канала",
            "Обслуживание ЛВС",
            "Подключение к сети",
            "Аренда статического IP адреса",
        ];
    }
    
    /*--------------------------------------------------*/
    
    static public function placementKeys(){
        return [
            "docPlacement" => "Документ",
            "safekeepingPlacement" => "Ответ хранения",
            "transferActPlacement" => "Акт приема передачи",
            "disclaimerPlacement" => "Акт отсутствия притензий"
        ];
    }
    
    /*--------------------------------------------------*/
    
    
}
