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
            "clientType" => "Тип учреждения",
            "name" => "Наименование",
            "remark" => "Примечание",
            "city" => "Город",
            "street" => "Улица",
            "building" => "Дом",
            "flat" => "Квартира/Офис",
            "emailMain" => "Основной email",
            "clientStatus" => "Статус клиента",
            "filePath" => "Путь к документу, подтверждающему статус",
            "fileName" => "Документ, подтверждающий статус",
            "competitor" => "Конкурент",
            "manager" => "Ответственный менеджер",
            "id" => "Id",
//            "legalAddress" => "юр.адрес",
            "amount" => "сумма тарифа",
            "speed" => "ширина канала",
            "connectSum" => "сумма подключения",
            "hardware" => "оборудование",
            "contractDate" => "дата заключения",
            "bin" => "бин",
            "iban" => "iban",
            "bik" => "бик",
            "kbe" => "кбе",
            "bank" => "банк",
            "nightWork" => "работают ночью",
            "district" => "район",
            "staticIp" => "статический ip",
            "payType" => "тип рассчета",
            "activateDate" => "дата активации",
            "connectType" => "тип подключения",
            "attractType" => "канал привлечения",
            "docPlacement" => "местонахождение документов",
            "emailEavr" => "электронная почта для эавр",
            "cameras" => "Видеонаблюдение",
            "ipPhone" => "IP телефония",
            "kTv" => "Кабельное ТВ",
            "service" => "Сервисное обслуживание",
            "dnum" => "№ Договора",
            "login" => "Учетная запись",
            "createDate" => "Дата создания",
            "changeDate" => "Дата изменения",
            "main" => "Основная",
            "docId" => "Id документа",
            "posId" => "Id позиции в документе",
            "streetType" => "Тип улицы",
            "buildingType" => "Тип строения",
            "flatType" => "Тип помещения",
//            "serviceType" => "Вид услуги",
            "internet_service" => "Интернет",
            "cou_service" => "ЦОУ",
            "esdi_service" => "ЕШДИ",
            "channel_service" => "Аренда канала",
            "lan_service" => "Обслуживание ЛВС",
            /*--------------------------------------------------*/
            "binType" => "БИН или ИИН",
            "legalCity" => "юр. город",
            "legalStreetType" => "юр. тип улицы",
            "legalStreet" => "юр. улица",
            "legalBuildingType" => "юр. тип строения",
            "legalBuilding" => "юр. дом",
            "legalFlatType" => "юр. тик квартиры",
            "legalFlat" => "юр. квартира",
            "udoNumber" => "номер удостоверения",
            "udoGiver" => "орган выдавший удостоверение",
            "udoDate" => "дата выдачи удостоверения",
            "competitorAmount" => "Тариф конкурента",
            "renewDate" => "Дата переоформления",
            "renewDnum" => "Номер переоформления",
            "renewType" => "Тип переоформления",
            "renewFilePath" => "Путь к файлу переоформления",
            "renewFileName" => "Имя файла переоформления",
            "renewComment" => "Комментарий переоформления",
            "disconnectType" => "Тип отключения",
            "disconnectReason" => "Причина отключения",
            "disconnectDate" => "Дата отключения",
            "disconnectReasonDesc" => "Описание причины отключения",
            "disconnectComment" => "Комментарий к отключению",
            "disconnectFilePath" => "Путь к файлу отключения",
            "disconnectFileName" => "Имя файла отключения",
            "disconnectMethod" => "Метод отключения",
            "oldTarif" => "Прежний тариф",
            "gosDnum" => "Номер на гос. закуп.",
            "gosDate" => "Дата гос. закуп.",
            "gosSum" => "Сумма за год гос. закуп",
            "disconnectRegisterDocPlacement" => "Местоположение документа отключения"
        ];
    }
    
    /*--------------------------------------------------*/
    
    static public function commonParamKeys(){
        return [
            "dnum" => "№ Договора",
            "clientType" => "Тип учреждения",
            "name" => "Наименование",
            "bin" => "бин",
            "iban" => "iban",
            "bik" => "бик",
            "kbe" => "кбе",
            "bank" => "банк",
            "binType" => "БИН или ИИН",
            "legalCity" => "юр. город",
            "legalStreetType" => "юр. тип улицы",
            "legalStreet" => "юр. улица",
            "legalBuildingType" => "юр. тип строения",
            "legalBuilding" => "юр. дом",
            "legalFlatType" => "юр. тик квартиры",
            "legalFlat" => "юр. квартира",
            "udoNumber" => "номер удостоверения",
            "udoGiver" => "орган выдавший удостоверение",
            "udoDate" => "дата выдачи удостоверения",
        ];
    }
    
    /*--------------------------------------------------*/
    
    static function docParamsChange(){
        return [
            "name" => "Наименование",
            "city" => "Город",
            "street" => "Улица",
            "building" => "Дом",
            "flat" => "Квартира/Офис",
            "bin" => "бин",
            "iban" => "iban",
            "bik" => "бик",
            "kbe" => "кбе",
            "bank" => "банк",
            "staticIp" => "статический ip",
            "streetType" => "Тип улицы",
            "buildingType" => "Тип строения",
            "flatType" => "Тип помещения",
            "legalCity" => "юр. город",
            "legalStreetType" => "юр. тип улицы",
            "legalStreet" => "юр. улица",
            "legalBuildingType" => "юр. тип строения",
            "legalBuilding" => "юр. дом",
            "legalFlatType" => "юр. тип квартиры",
            "legalFlat" => "юр. квартира",
            "udoNumber" => "номер удостоверения",
            "udoGiver" => "орган выдавший удостоверение",
            "udoDate" => "дата выдачи удостоверения",
        ];
    }
    
    /*--------------------------------------------------*/
    
    static public function hardwareTemplate(){
        return [
            "Роутер купили у нас" => "Роутер купили у нас",
            "Коммутатор и роутер купили у нас" => "Коммутатор и роутер купили у нас",
            "Коммутатор и роутер свой" => "Коммутатор и роутер свой",
            "Коммутатор общедомовой, роутер купили у нас" => "Коммутатор общедомовой, роутер купили у нас",
            "Коммутатор общедомовой, роутер свой" => "Коммутатор общедомовой, роутер свой",
            "Коммутатор купили у нас, роутер свой" => "Коммутатор купили у нас, роутер свой",
            "Коммутатор свой, роутер купили у нас" => "Коммутатор свой, роутер купили у нас",
            "Коммутатор и роутер в отв. Хр" => "Коммутатор и роутер в отв. Хр",
            "Коммутатор купили у нас, роутер в отв. Хр." => "Коммутатор купили у нас, роутер в отв. Хр.",
            "Без изменений" => "Без изменений"
        ];
    }
    
    /*--------------------------------------------------*/
    
    static public function bikTemplate(){
        return [
            "ATYNKZKA" => "ATYNKZKA - Altyn Bank",
            "TSESKZKA" => "TSESKZKA - First Heartland J?san Bank",
            "IRTYKZKA" => "IRTYKZKA - ForteBank",
            "CASPKZKA" => "CASPKZKA - Kaspi Bank",
            "ALFAKZKA" => "ALFAKZKA - АО ДБ «Есо Center Bank»",
            "KCJBKZKX" => "KCJBKZKX - Банк ЦентрКредит",
            "INLMKZKA" => "INLMKZKA - ДБ АО \"Банк Хоум Кредит\"",
            "BRKEKZKA" => "BRKEKZKA - Филиал АО Bereke Bank",
            "VTBAKZKZ" => "VTBAKZKZ - ДО АО Банк ВТБ (Казахстан)",
            "HSBKKZKX" => "HSBKKZKX - АО «Народный Банк Казахстана»",                      
            "EURIKZKA" => "EURIKZKA - Евразийский Банк"
        ];
    }
    
    /*--------------------------------------------------*/
    
    static public function docType(){
        return [
            "Коммерческое предложение",
            "Блокировка",
            "Отключение",
            "Переоформление"
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
            "Коммерческое предложение" => [
                
            ],
            "Блокировка" =>[
                "startDate",
                "finishDate",
            ],
            "Отключение" => [
                "activateDate",
                "reason"
            ],
            "Переоформление" => [
                
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
            "contains" => "Содержит",
            "notContains" => "Не содержит",
        ];
        $select = [
            "equal" => "Равен",
            "notEqual" => "Не равен"
        ];
        $yesNo = [
            "0" => "Нет",
            "1" => "Да"
        ];
        return [
            "clientType" => [
                "name" => "Тип учреждения",
                "valueType" => "select",
                "values" => \Settings\Main::clientType(),
                "type" => $select
            ],
            "name" => [
                "name" => "Наименование",
                "valueType" => "text",
                "type" => $text
            ],
            "remark" => [
                "name" => "Примечание",
                "valueType" => "text",
                "type" => $text
            ],
            "emailMain" => [
                "name" => "Основной email",
                "valueType" => "text",
                "type" => $text
            ],
            "clientStatus" => [
                "name" => "Статус клиента",
                "valueType" => "select",
                "values" => \Settings\Main::clientStatus(),
                "type" => $select
            ],
            "competitor" => [
                "name" => "Конкурент",
                "valueType" => "select",
                "values" => \Settings\Main::competitor(),
                "type" => $select
            ],
            "manager" => [
                "name" => "Ответственный менеджер",
                "valueType" => "select",
                "values" => \Settings\Main::managerList(),
                "type" => $select
            ],
            "amount" => [
                "name" => "сумма тарифа",
                "valueType" => "text",
                "type" => $number
            ],
            "speed" => [
                "name" => "ширина канала",
                "valueType" => "text",
                "type" => $number
            ],
            "district" => [
                "name" => "район",
                "valueType" => "select",
                "values" => \Settings\Main::district(),
                "type" => $select
            ],
            "emailEavr" => [
                "name" => "электронная почта для эавр",
                "valueType" => "text",
                "type" => $text
            ],
//            "serviceType" => [
//                "name" => "Вид услуги",
//                "valueType" => "select",
//                "values" => \Settings\Main::serviceType(),
//                "type" => $select
//            ],
            "dnum" => [
                "name" => "№ Договора",
                "valueType" => "text",
                "type" => $text
            ],
            "login" => [
                "name" => "Учетная запись",
                "valueType" => "text",
                "type" => $text
            ],
            "main" => [
                "name" => "Основная",
                "valueType" => "select",
                "values" => $yesNo,
                "type" => $text
            ],
            "connectSum" => [
                "name" => "сумма подключения",
                "valueType" => "text",
                "type" => $number
            ],
            "hardware" => [
                "name" => "оборудование",
                "valueType" => "text",
                "type" => $text
            ],
            "nightWork" => [
                "name" => "работают ночью",
                "valueType" => "select",
                "values" => $yesNo,
                "type" => $text
            ],
            "staticIp" => [
                "name" => "статический ip",
                "valueType" => "select",
                "values" => $yesNo,
                "type" => $text
            ],
            "payType" => [
                "name" => "тип рассчета",
                "valueType" => "select",
                "values" => \Settings\Main::payType(),
                "type" => $select
            ],
            "connectType" => [
                "name" => "тип подключения",
                "valueType" => "select",
                "values" => \Settings\Main::connectType(),
                "type" => $select
            ],
            "attractType" => [
                "name" => "канал привлечения",
                "valueType" => "select",
                "values" => \Settings\Main::attractType(),
                "type" => $select
            ],
            "docPlacement" => [
                "name" => "местонахождение документов",
                "valueType" => "select",
                "values" => \Settings\Main::docPlacement(),
                "type" => $select
            ],
            "cameras" => [
                "name" => "Видеонаблюдение",
                "valueType" => "select",
                "values" => $yesNo,
                "type" => $text
            ],
            "ipPhone" => [
                "name" => "IP телефония",
                "valueType" => "select",
                "values" => $yesNo,
                "type" => $text
            ],
            "kTv" => [
                "name" => "Кабельное ТВ",
                "valueType" => "select",
                "values" => $yesNo,
                "type" => $text
            ],
            "service" => [
                "name" => "Сервисное обслуживание",
                "valueType" => "select",
                "values" => $yesNo,
                "type" => $text
            ],
            "activateDate" => [
                "name" => "дата активации",
                "valueType" => "date",
                "type" => $number
            ],
            "contractDate" => [
                "name" => "дата заключения",
                "valueType" => "date",
                "type" => $number
            ],
            "createDate" => [
                "name" => "Дата создания",
                "valueType" => "date",
                "type" => $number
            ],
            "changeDate" => [
                "name" => "Дата изменения",
                "valueType" => "date",
                "type" => $number
            ],
            "bin" => [
                "name" => "бин",
                "valueType" => "text",
                "type" => $text
            ],
            "iban" => [
                "name" => "iban",
                "valueType" => "text",
                "type" => $text
            ],
            "bik" => [
                "name" => "бик",
                "valueType" => "text",
                "type" => $text
            ],
            "kbe" => [
                "name" => "кбе",
                "valueType" => "text",
                "type" => $text
            ],
            "bank" => [
                "name" => "банк",
                "valueType" => "text",
                "type" => $text
            ],
            "udoNumber" => [
                "name" => "номер удостоверения",
                "valueType" => "text",
                "type" => $text
            ],
            "renewDate" => [
                "name" => "Дата переоформления",
                "valueType" => "date",
                "type" => $number
            ],
            "renewDnum" => [
                "name" => "Номер договора переоформления",
                "valueType" => "text",
                "type" => $text
            ],
            "disconnectType" => [
                "name" => "Тип отключения",
                "valueType" => "select",
                "values" => \Settings\Main::disconnectType(),
                "type" => $select
            ],
            "disconnectReason" => [
                "name" => "Причина отключения",
                "valueType" => "select",
                "values" => \Settings\Main::disconnectReason(),
                "type" => $select
            ],
            "disconnectDate" => [
                "name" => "Дата отключения",
                "valueType" => "date",
                "type" => $number
            ],
            "disconnectReasonDesc" => [
                "name" => "Описание причины отключения",
                "valueType" => "text",
                "type" => $text
            ],
            "address" => [
                "name" => "Адрес клиента",
                "valueType" => "text",
                "type" => $text
            ]
        ];
    }
    
    /*--------------------------------------------------*/
    
    static public function clientSupportParams(){
        return [
            "remark" => "Примечание",
            "loginList" => "Логины и пароли"
        ];
    }
    
    /*--------------------------------------------------*/
    
    
    
}







