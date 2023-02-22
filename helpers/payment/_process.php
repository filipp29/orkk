<?php
    require_once ($_SERVER['DOCUMENT_ROOT'].'/_lib/libObjEngine.php');
    mb_internal_encoding('cp1251');
    date_default_timezone_set ('Asia/Almaty');
    
    function doPayment($id, $dnum, $summ){
        $tmp=array();
        $tmp["id"]=$id;
        $tmp["tstamp"]=time();
        $tmp["dnum"]=$dnum;
        $tmp["sum"]=$summ;
        objSave('/paylog/sber/'.$id, 'raw', $tmp);

        $nbal=getBalance($dnum);
        $nbal=$nbal+$summ;

        $glob_dbname="billing";              //Database name
        $glob_sqlserv="192.168.1.112";          //MySQL Server name
        $glob_sqlname="root";         //MySQL Username
        $glob_sqlpass="Argtc8Sk0";           //MySQL Password   

        $db=mysql_connect($glob_sqlserv,$glob_sqlname,$glob_sqlpass);
        mysql_select_db($glob_dbname,$db);

        $sql = "SET NAMES 'utf8';";
        $result = mysql_query($sql,$db);  
        $sql = 'SET CHARACTER SET "utf8"';
        $result = mysql_query($sql,$db);  

        $sql = "UPDATE `agreements` SET `balance`='".$nbal."' WHERE `number`='".$dnum."';";
        $result = mysql_query($sql,$db);    
        //print $sql."\n";


        $agrmid=getAgrmid($dnum);

        $sql = "INSERT INTO `billing`.`payments` (`agrm_id` ,`amount` ,`comment` ,`receipt` ,`pay_date` ,`local_date` ,`cancel_date` ,`status` ,`mod_person` ,`order_id` ,`class_id` ,`agrm_number` ,`amount_cur_id` ,`from_agrm_id` ,`amount_cur` ,`bso_id` ,`period_date` ,`uuid` ,`script_executed`)VALUES ('".$agrmid."', '".$summ."', '', NULL , '".strftime('%Y-%m-%d %H:%M:%S', time())."', '".strftime('%Y-%m-%d %H:%M:%S', time())."', NULL , '0', '36', NULL , '0', NULL , '2', NULL , '".$summ."', NULL , NULL , NULL , '0');";
        $result = mysql_query($sql,$db);    
        //print $sql."\n"."\n";
    }
    
    function getBalance($num){
        $glob_dbname="billing";              //Database name
        $glob_sqlserv="192.168.1.112";          //MySQL Server name
        $glob_sqlname="root";         //MySQL Username
        $glob_sqlpass="Argtc8Sk0";           //MySQL Password   

        $db=mysql_connect($glob_sqlserv,$glob_sqlname,$glob_sqlpass);
        mysql_select_db($glob_dbname,$db);

        $sql = "SET NAMES 'utf8';";
        $result = mysql_query($sql,$db);  
        $sql = 'SET CHARACTER SET "utf8"';
        $result = mysql_query($sql,$db);  

        $sql = "SELECT * FROM `agreements` WHERE `number`='".$num."';";
        $result = mysql_query($sql,$db);

        if (mysql_num_rows($result)!=0){
            $row=mysql_fetch_array($result);
            return ($row["balance"]);
        }
        else
        {
            return 0;
        }    
    }

    function getAgrmid($num){
        $glob_dbname="billing";              //Database name
        $glob_sqlserv="192.168.1.112";          //MySQL Server name
        $glob_sqlname="root";         //MySQL Username
        $glob_sqlpass="Argtc8Sk0";           //MySQL Password   

        $db=mysql_connect($glob_sqlserv,$glob_sqlname,$glob_sqlpass);
        mysql_select_db($glob_dbname,$db);

        $sql = "SET NAMES 'utf8';";
        $result = mysql_query($sql,$db);  
        $sql = 'SET CHARACTER SET "utf8"';
        $result = mysql_query($sql,$db);  

        $sql = "SELECT * FROM `agreements` WHERE `number`='".$num."';";
        $result = mysql_query($sql,$db);

        if (mysql_num_rows($result)!=0){
            $row=mysql_fetch_array($result);
            return ($row["agrm_id"]);
        }
        else
        {
            return 0;
        }    
    }
    
    //print_r($_POST);
    $pcnt=0;
    $psum=0;
    
    $data=$_POST["list"];
    $list=explode("\n", $data);
    foreach ($list as $item){
        $arr=explode("\t", $item);
        if (count($arr)==3){
            $pcnt++;
            $psum=$psum+$arr[2];
            doPayment($arr[0], $arr[1], $arr[2]);
        }
    }
    
?>
<div style="width: 100%; margin-top: 20px; font-size: 21px; font-weight: lighter;">
    <div style="">
        <b>Обработано платежей: </b><?=$pcnt?>
    </div>
    <div style="">
        <b>На сумму: </b><?=$psum?> тг.
    </div>
</div>