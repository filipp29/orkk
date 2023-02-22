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
            "���������� ���" => [
                "contractDate",
                "connectSum",
                "hardware"
            ],
            "����� ����������" => [
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
            "����� ���������������" => [
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
            "���������� ������ ������" => [
                "contractDate",
                "connectSum",
                "amount",
                "activateDate",
                "speed",
                "attractType",
                "hardware"
            ],
            "���������� ������ ������" => [
                "contractDate",
                "connectSum",
                "amount",
                "activateDate",
                "speed",
                "hardware"
            ],
            "������ ��������� ������" => [
                "contractDate",
                "connectSum",
                "amount",
                "activateDate",
                "attractType",
                "hardware"
            ],
            "������ ������" => [
                "contractDate",
                "connectSum",
                "amount",
                "activateDate",
                "attractType",
                "hardware"
            ],
            "������������ ���" => [
                "contractDate",
                "connectSum",
                "amount",
                "activateDate",
                "attractType",
                "hardware"
            ],
            "����������� � ����" => [
                "contractDate",
                "connectSum",
                "amount",
                "activateDate",
                "attractType",
                "hardware"
            ],
            "������ ������������ IP ������" => [
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
            "�������������� �����" => 1,
            "���������� ���" => 2,
            "����� ����������" => 3,
            "����� ���������������" => 4,
            "���������� ������ ������" => 5,
            "���������� ������ ������" => 6,
            "������ ��������� ������" => 7,
            "������ ������" => 8,
            "������������ ���" => 9,
            "����������� � ����" => 10,
            "������ ������������ IP ������" => 11,
            "������������" => 12
        ];
    }
    
    /*--------------------------------------------------*/
    
    static public function amountPlus(){
        return [
            "������ ��������� ������",
            "������ ������",
            "������������ ���",
            "����������� � ����",
            "������ ������������ IP ������",
        ];
    }
    
    /*--------------------------------------------------*/
    
    static public function placementKeys(){
        return [
            "docPlacement" => "��������",
            "safekeepingPlacement" => "����� ��������",
            "transferActPlacement" => "��� ������ ��������",
            "disclaimerPlacement" => "��� ���������� ���������"
        ];
    }
    
    /*--------------------------------------------------*/
    
    
}
