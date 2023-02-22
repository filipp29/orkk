<?php



namespace Settings;

class Client {
    
    static private $keys = [
        "paramKeys",
        "hardwareTemplate"
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
    
    static public function paramKeys(){
        return [
            "clientType" => "��� ����������",
            "name" => "������������",
            "remark" => "����������",
            "city" => "�����",
            "street" => "�����",
            "building" => "���",
            "flat" => "��������/����",
            "emailMain" => "�������� email",
            "clientStatus" => "������ �������",
            "filePath" => "���� � ���������, ��������������� ������",
            "fileName" => "��������, �������������� ������",
            "competitor" => "���������",
            "manager" => "������������� ��������",
            "id" => "Id",
//            "legalAddress" => "��.�����",
            "amount" => "����� ������",
            "speed" => "������ ������",
            "connectSum" => "����� �����������",
            "hardware" => "������������",
            "contractDate" => "���� ����������",
            "bin" => "���",
            "iban" => "iban",
            "bik" => "���",
            "kbe" => "���",
            "bank" => "����",
            "nightWork" => "�������� �����",
            "district" => "�����",
            "staticIp" => "����������� ip",
            "payType" => "��� ��������",
            "activateDate" => "���� ���������",
            "connectType" => "��� �����������",
            "attractType" => "����� �����������",
            "docPlacement" => "��������������� ����������",
            "emailEavr" => "����������� ����� ��� ����",
            "cameras" => "���������������",
            "ipPhone" => "IP ���������",
            "kTv" => "��������� ��",
            "service" => "��������� ������������",
            "dnum" => "� ��������",
            "login" => "������� ������",
            "createDate" => "���� ��������",
            "changeDate" => "���� ���������",
            "main" => "��������",
            "docId" => "Id ���������",
            "posId" => "Id ������� � ���������",
            "streetType" => "��� �����",
            "buildingType" => "��� ��������",
            "flatType" => "��� ���������",
//            "serviceType" => "��� ������",
            "internet_service" => "��������",
            "cou_service" => "���",
            "esdi_service" => "����",
            "channel_service" => "������ ������",
            "lan_service" => "������������ ���",
            /*--------------------------------------------------*/
            "binType" => "��� ��� ���",
            "legalCity" => "��. �����",
            "legalStreetType" => "��. ��� �����",
            "legalStreet" => "��. �����",
            "legalBuildingType" => "��. ��� ��������",
            "legalBuilding" => "��. ���",
            "legalFlatType" => "��. ��� ��������",
            "legalFlat" => "��. ��������",
            "udoNumber" => "����� �������������",
            "udoGiver" => "����� �������� �������������",
            "udoDate" => "���� ������ �������������",
            "competitorAmount" => "����� ����������",
            "renewDate" => "���� ��������������",
            "renewDnum" => "����� ��������������",
            "renewType" => "��� ��������������",
            "renewFilePath" => "���� � ����� ��������������",
            "renewFileName" => "��� ����� ��������������",
            "renewComment" => "����������� ��������������",
            "disconnectType" => "��� ����������",
            "disconnectReason" => "������� ����������",
            "disconnectDate" => "���� ����������",
            "disconnectReasonDesc" => "�������� ������� ����������",
            "disconnectComment" => "����������� � ����������",
            "disconnectFilePath" => "���� � ����� ����������",
            "disconnectFileName" => "��� ����� ����������",
            "disconnectMethod" => "����� ����������",
            "oldTarif" => "������� �����",
            "gosDnum" => "����� �� ���. �����.",
            "gosDate" => "���� ���. �����.",
            "gosSum" => "����� �� ��� ���. �����",
            "disconnectRegisterDocPlacement" => "�������������� ��������� ����������"
        ];
    }
    
    /*--------------------------------------------------*/
    
    static public function commonParamKeys(){
        return [
            "dnum" => "� ��������",
            "clientType" => "��� ����������",
            "name" => "������������",
            "bin" => "���",
            "iban" => "iban",
            "bik" => "���",
            "kbe" => "���",
            "bank" => "����",
            "binType" => "��� ��� ���",
            "legalCity" => "��. �����",
            "legalStreetType" => "��. ��� �����",
            "legalStreet" => "��. �����",
            "legalBuildingType" => "��. ��� ��������",
            "legalBuilding" => "��. ���",
            "legalFlatType" => "��. ��� ��������",
            "legalFlat" => "��. ��������",
            "udoNumber" => "����� �������������",
            "udoGiver" => "����� �������� �������������",
            "udoDate" => "���� ������ �������������",
        ];
    }
    
    /*--------------------------------------------------*/
    
    static function docParamsChange(){
        return [
            "name" => "������������",
            "city" => "�����",
            "street" => "�����",
            "building" => "���",
            "flat" => "��������/����",
            "bin" => "���",
            "iban" => "iban",
            "bik" => "���",
            "kbe" => "���",
            "bank" => "����",
            "staticIp" => "����������� ip",
            "streetType" => "��� �����",
            "buildingType" => "��� ��������",
            "flatType" => "��� ���������",
            "legalCity" => "��. �����",
            "legalStreetType" => "��. ��� �����",
            "legalStreet" => "��. �����",
            "legalBuildingType" => "��. ��� ��������",
            "legalBuilding" => "��. ���",
            "legalFlatType" => "��. ��� ��������",
            "legalFlat" => "��. ��������",
            "udoNumber" => "����� �������������",
            "udoGiver" => "����� �������� �������������",
            "udoDate" => "���� ������ �������������",
        ];
    }
    
    /*--------------------------------------------------*/
    
    static public function hardwareTemplate(){
        return [
            "������ ������ � ���" => "������ ������ � ���",
            "���������� � ������ ������ � ���" => "���������� � ������ ������ � ���",
            "���������� � ������ ����" => "���������� � ������ ����",
            "���������� �����������, ������ ������ � ���" => "���������� �����������, ������ ������ � ���",
            "���������� �����������, ������ ����" => "���������� �����������, ������ ����",
            "���������� ������ � ���, ������ ����" => "���������� ������ � ���, ������ ����",
            "���������� ����, ������ ������ � ���" => "���������� ����, ������ ������ � ���",
            "���������� � ������ � ���. ��" => "���������� � ������ � ���. ��",
            "���������� ������ � ���, ������ � ���. ��." => "���������� ������ � ���, ������ � ���. ��.",
            "��� ���������" => "��� ���������"
        ];
    }
    
    /*--------------------------------------------------*/
    
    static public function bikTemplate(){
        return [
            "ATYNKZKA" => "ATYNKZKA - Altyn Bank",
            "TSESKZKA" => "TSESKZKA - First Heartland J?san Bank",
            "IRTYKZKA" => "IRTYKZKA - ForteBank",
            "CASPKZKA" => "CASPKZKA - Kaspi Bank",
            "ALFAKZKA" => "ALFAKZKA - �� �� ���� Center Bank�",
            "KCJBKZKX" => "KCJBKZKX - ���� �����������",
            "INLMKZKA" => "INLMKZKA - �� �� \"���� ���� ������\"",
            "BRKEKZKA" => "BRKEKZKA - ������ �� Bereke Bank",
            "VTBAKZKZ" => "VTBAKZKZ - �� �� ���� ��� (���������)",
            "HSBKKZKX" => "HSBKKZKX - �� ��������� ���� ����������",                      
            "EURIKZKA" => "EURIKZKA - ����������� ����"
        ];
    }
    
    /*--------------------------------------------------*/
    
    static public function docType(){
        return [
            "������������ �����������",
            "����������",
            "����������",
            "��������������"
        ];
    }
    
    /*--------------------------------------------------*/
    
    static public function docParams(){
        return [
            "default" => [
                "clientId",
                "type",
                "timeStamp",
                "docDate",
                "comment",
                "filePath",
                "fileName",
                "commentAuthor",
                "commentTimeStamp"
            ],
            "������������ �����������" => [
                
            ],
            "����������" =>[
                "startDate",
                "finishDate",
            ],
            "����������" => [
                "activateDate",
                "reason"
            ],
            "��������������" => [
                
            ]
        ];
    }
    
    /*--------------------------------------------------*/
    
    static public function tableFilterList(){
        $number = [
            "equal" => "=",
            "notEqual" => "<>",
            "greater" => ">=",
            "less" => "<="
        ];    
        $text = [
            "contains" => "��������",
            "notContains" => "�� ��������",
        ];
        $select = [
            "equal" => "�����",
            "notEqual" => "�� �����"
        ];
        $yesNo = [
            "0" => "���",
            "1" => "��"
        ];
        return [
            "clientType" => [
                "name" => "��� ����������",
                "valueType" => "select",
                "values" => \Settings\Main::clientType(),
                "type" => $select
            ],
            "name" => [
                "name" => "������������",
                "valueType" => "text",
                "type" => $text
            ],
            "remark" => [
                "name" => "����������",
                "valueType" => "text",
                "type" => $text
            ],
            "emailMain" => [
                "name" => "�������� email",
                "valueType" => "text",
                "type" => $text
            ],
            "clientStatus" => [
                "name" => "������ �������",
                "valueType" => "select",
                "values" => \Settings\Main::clientStatus(),
                "type" => $select
            ],
            "competitor" => [
                "name" => "���������",
                "valueType" => "select",
                "values" => \Settings\Main::competitor(),
                "type" => $select
            ],
            "manager" => [
                "name" => "������������� ��������",
                "valueType" => "select",
                "values" => \Settings\Main::managerList(),
                "type" => $select
            ],
            "amount" => [
                "name" => "����� ������",
                "valueType" => "text",
                "type" => $number
            ],
            "speed" => [
                "name" => "������ ������",
                "valueType" => "text",
                "type" => $number
            ],
            "district" => [
                "name" => "�����",
                "valueType" => "select",
                "values" => \Settings\Main::district(),
                "type" => $select
            ],
            "emailEavr" => [
                "name" => "����������� ����� ��� ����",
                "valueType" => "text",
                "type" => $text
            ],
//            "serviceType" => [
//                "name" => "��� ������",
//                "valueType" => "select",
//                "values" => \Settings\Main::serviceType(),
//                "type" => $select
//            ],
            "dnum" => [
                "name" => "� ��������",
                "valueType" => "text",
                "type" => $text
            ],
            "login" => [
                "name" => "������� ������",
                "valueType" => "text",
                "type" => $text
            ],
            "main" => [
                "name" => "��������",
                "valueType" => "select",
                "values" => $yesNo,
                "type" => $text
            ],
            "connectSum" => [
                "name" => "����� �����������",
                "valueType" => "text",
                "type" => $number
            ],
            "hardware" => [
                "name" => "������������",
                "valueType" => "text",
                "type" => $text
            ],
            "nightWork" => [
                "name" => "�������� �����",
                "valueType" => "select",
                "values" => $yesNo,
                "type" => $text
            ],
            "staticIp" => [
                "name" => "����������� ip",
                "valueType" => "select",
                "values" => $yesNo,
                "type" => $text
            ],
            "payType" => [
                "name" => "��� ��������",
                "valueType" => "select",
                "values" => \Settings\Main::payType(),
                "type" => $select
            ],
            "connectType" => [
                "name" => "��� �����������",
                "valueType" => "select",
                "values" => \Settings\Main::connectType(),
                "type" => $select
            ],
            "attractType" => [
                "name" => "����� �����������",
                "valueType" => "select",
                "values" => \Settings\Main::attractType(),
                "type" => $select
            ],
            "docPlacement" => [
                "name" => "��������������� ����������",
                "valueType" => "select",
                "values" => \Settings\Main::docPlacement(),
                "type" => $select
            ],
            "cameras" => [
                "name" => "���������������",
                "valueType" => "select",
                "values" => $yesNo,
                "type" => $text
            ],
            "ipPhone" => [
                "name" => "IP ���������",
                "valueType" => "select",
                "values" => $yesNo,
                "type" => $text
            ],
            "kTv" => [
                "name" => "��������� ��",
                "valueType" => "select",
                "values" => $yesNo,
                "type" => $text
            ],
            "service" => [
                "name" => "��������� ������������",
                "valueType" => "select",
                "values" => $yesNo,
                "type" => $text
            ],
            "activateDate" => [
                "name" => "���� ���������",
                "valueType" => "date",
                "type" => $number
            ],
            "contractDate" => [
                "name" => "���� ����������",
                "valueType" => "date",
                "type" => $number
            ],
            "createDate" => [
                "name" => "���� ��������",
                "valueType" => "date",
                "type" => $number
            ],
            "changeDate" => [
                "name" => "���� ���������",
                "valueType" => "date",
                "type" => $number
            ],
            "bin" => [
                "name" => "���",
                "valueType" => "text",
                "type" => $text
            ],
            "iban" => [
                "name" => "iban",
                "valueType" => "text",
                "type" => $text
            ],
            "bik" => [
                "name" => "���",
                "valueType" => "text",
                "type" => $text
            ],
            "kbe" => [
                "name" => "���",
                "valueType" => "text",
                "type" => $text
            ],
            "bank" => [
                "name" => "����",
                "valueType" => "text",
                "type" => $text
            ],
            "udoNumber" => [
                "name" => "����� �������������",
                "valueType" => "text",
                "type" => $text
            ],
            "renewDate" => [
                "name" => "���� ��������������",
                "valueType" => "date",
                "type" => $number
            ],
            "renewDnum" => [
                "name" => "����� �������� ��������������",
                "valueType" => "text",
                "type" => $text
            ],
            "disconnectType" => [
                "name" => "��� ����������",
                "valueType" => "select",
                "values" => \Settings\Main::disconnectType(),
                "type" => $select
            ],
            "disconnectReason" => [
                "name" => "������� ����������",
                "valueType" => "select",
                "values" => \Settings\Main::disconnectReason(),
                "type" => $select
            ],
            "disconnectDate" => [
                "name" => "���� ����������",
                "valueType" => "date",
                "type" => $number
            ],
            "disconnectReasonDesc" => [
                "name" => "�������� ������� ����������",
                "valueType" => "text",
                "type" => $text
            ],
            "address" => [
                "name" => "����� �������",
                "valueType" => "text",
                "type" => $text
            ]
        ];
    }
    
    /*--------------------------------------------------*/
    
    static public function clientSupportParams(){
        return [
            "remark" => "����������",
            "loginList" => "������ � ������"
        ];
    }
    
    /*--------------------------------------------------*/
    
    
    
}







