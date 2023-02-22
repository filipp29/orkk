<?php
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Expires: " . date("r"));
require_once ($_SERVER['DOCUMENT_ROOT'].'/_lib/libObjEngine.php');
?>

//<script>
<?php

    function checkIIN($iin){
        $nums=array('0', '1', '2', '3', '4', '5', '6', '7', '8', '9');
        $nc=0;
        for ($i=0; $i<strlen($iin); $i++){
            $sym=substr($iin, $i, 1);
            if (in_array($sym, $nums)){$nc++;}else{$nc=0;}
            if ($nc==12){return true;}
        }
        return false;
    }

    $user=$_GET["user"];

    $tmpu=objLoad('/users/'.$user.'/user.vcard', 'raw');

    if (isset($tmpu["login"])){
        $useripid=$tmpu["login"];
    }
    else
    {
        $useripid='NFOUND';
    }
?>
    
if (getById('bldPreviewFrame')!=undefined){
    getById('bldPreviewFrame').style.display='none';
}
    
$out='';
$out=$out+'<input type="hidden" id="userIPID" value=\'<?=$useripid?>\'/>';
$out=$out+'<div style="display: table; font-size: 20px; margin-top: 20px; border-bottom: 1px #eee solid;"><div style="display: table-row;">';
$out=$out+'<div style="display: table-cell;" id="userInfo_common" class="infoSelected" onclick="infoSwitch(\'common\');">Общее</div>';
$out=$out+'<div style="display: table-cell; width: 40px; min-width: 40px; max-width: 40px;"></div>';
$out=$out+'<div style="display: table-cell;" id="userInfo_status" class="infoWaiting" onclick="infoSwitch(\'status\');">Состояние</div>';
$out=$out+'<div style="display: table-cell; width: 40px; min-width: 40px; max-width: 40px;"></div>';
$out=$out+'<div style="display: table-cell;" id="userInfo_payment" class="infoWaiting" onclick="infoSwitch(\'payment\');">Платежи</div>';
$out=$out+'<div style="display: table-cell; width: 40px; min-width: 40px; max-width: 40px;"></div>';
<?php
    if ($_COOKIE["login"]=='izus'){
        ?>
$out=$out+'<div style="display: table-cell; width: 40px; min-width: 40px; max-width: 40px;"></div>';
$out=$out+'<div style="display: table-cell;" id="userInfo_routers" class="infoWaiting"  onclick="infoSwitch(\'routers\');">Роутеры</div>';
        <?php
    }
?>
$out=$out+'</div></div>';
<?php
    error_reporting(0);
    ini_set('display_errors', 0);
    mb_internal_encoding('cp1251');
    date_default_timezone_set ('Asia/Almaty');
    require_once ($_SERVER['DOCUMENT_ROOT'].'/_lib/libObjEngine.php');
    require_once ($_SERVER['DOCUMENT_ROOT'].'/_lib/libCity.php');
    require_once ($_SERVER['DOCUMENT_ROOT'].'/_lib/libProfiles.php');
    
    $uiframe=$_GET["obj"];
    $user=$_GET["user"];
    
    function getRateData($id){
        $obj=objLoad('/reference/tvrates/'.$id, 'raw');
        if (isset($obj["name"])){
            return $obj;
        }
        $obj["name"]='N/A';
        $obj["cost"]='0';
        return $obj;
    }
    
    function extActivity($dnum){
        if (!objCheckExist('/_extension/'.$dnum, 'raw')){
            return '<span style="color: #F44336;">Отсутствует</span>.';
        }
        $obj=objLoad('/_extension/'.$dnum, 'raw');
        if (!isset($obj["tstamp"])){
            return '<span style="color: #F44336;">Отсутствует</span>.';
        }
        if ($obj["tstamp"]>time()-(60*60*24)){
            return '<span style="color: #388E3C;">'.strftime_c('%d.%m.%Y %H:%M', $obj["tstamp"]).'</span>.';
        }
        return '<span style="color: #999999;">'.strftime_c('%d.%m.%Y %H:%M', $obj["tstamp"]).'</span>.';
    }
    
    function getPhoneList($dnum){
        $uArr=objLoad('/users/'.$dnum.'/user.vcard', 'vcard');
        if (!isset($uArr["address"])){
            $uArr["address"]='';
        }
        $ret=array();
        if (!isset($uArr["phone"])){
            $uArr["phone"]='';
        }
        if (!isset($uArr["mobile"])){
            $uArr["mobile"]='';
        }
        if ($uArr["phone"]!=''){
            $uArr["phone"] = preg_replace("/[^0-9]/", '', $uArr["phone"]); 
            if (substr_count($uArr["address"], 'Лисаковск')!=0){
                if (strlen($uArr["phone"])==5){
                    array_push($ret, '871433'.$uArr["phone"]);
                }
            }
            if (substr_count($uArr["address"], 'Качар')!=0){
                if (strlen($uArr["phone"])==5){
                    array_push($ret, '871456'.$uArr["phone"]);
                }
            }
            if (substr_count($uArr["address"], 'Костанай')!=0){
                if (strlen($uArr["phone"])==6){
                    array_push($ret, '87142'.$uArr["phone"]);
                }
            }
            if (strlen($uArr["phone"])==10){
                array_push($ret, '8'.$uArr["phone"]);
            }
        }
        if ($uArr["mobile"]!=''){
            $uArr["mobile"] = preg_replace("/[^0-9]/", '', $uArr["mobile"]); 
            if (strlen($uArr["mobile"])==11){
                if ($uArr["mobile"][0]=='9'){
                    $uArr["mobile"]='8'.substr($uArr["mobile"], 1);
                    array_push($ret, $uArr["mobile"]);
                }
            }
        }
        return $ret;
    }
    
    if (objCheckExist('/users/'.$user.'/user.vcard', 'vcard')){
        global $id;
        $id=$user;
        //print '//test_1 '."\n";
        include($_SERVER['DOCUMENT_ROOT'].'/_modules/support/helpers/updatevcard.php');
        $uArr=objLoad('/users/'.$user.'/user.vcard', 'vcard');
        $tvadd='';
        
        if (isset($uArr["state"])){
            switch ($uArr["state"]){
                case '3':
                    $tvadd=' [<span style="color: #f00;">Остановлен менеджером</span>]';
                break;
                case '1':
                    $tvadd=' [<span style="color: #f00;">Блок по балансу</span>]';
                break;            
                case '2':
                    $tvadd=' [<span style="color: #f00;">Остановлен абонентом</span>]';
                break;            
                case '4':
                    $tvadd=' [<span style="color: #f00;">Активная блокировка по балансу</span>]';
                break;            
                case '5':
                    $tvadd=' [<span style="color: #f00;">Превышен лимит трафика</span>]';
                break;            
                case '10':
                    $tvadd=' [<span style="color: #f00;">Отключен</span>]';
                break;            
            }
        }
        if (isset($uArr["tvstate"])){
            switch ($uArr["tvstate"]){
                case '1':
                    $tvadd=' [<span style="color: #f00;">Заявка</span>]';
                break;
                case '2':
                    $tvadd=' [<span style="color: #f00;">Подключен (без договора)</span>]';
                break;
                case '3':
                   $tvadd=' [<span style="color: #f00;">Подключен</span>]';
                break;
                case '4':
                    $tvadd=' [<span style="color: #f00;">Отключен</span>]';
                break;
                case '5':
                    $tvadd=' [<span style="color: #f00;">Отключен (без договора)</span>]';
                break;
                case '6':
                    $tvadd=' [<span style="color: #f00;">Отключен за неуплату</span>]';
                break;
                case '7':
                    $tvadd=' [<span style="color: #f00;">Готов к удалению</span>]';
                break;
            }
            
            $uArr["rate"]=getRateData($uArr["ratecode"])["name"];
        }
        if (!isset($uArr["address"])){
            $uArr["address"]='<i>Не синхронизирован</i>';
        }
        if (!isset($uArr["uname"])){
            $uArr["uname"]='<i>Не синхронизировано</i>';
        }
        if (!isset($uArr["balance"])){
            $uArr["balance"]='<i>Не синхронизирован</i>';
        }
        if (!isset($uArr["pwd"])){
            $uArr["pwd"]='<i>Не синхронизирован</i>';
        }
        if ($uArr["uname"]==''){
            $uArr["uname"]='<i>Безымянный пользователь</i>';
        }
        if ($uArr["balance"]<0){$uArr["balance"]='<span style="color: #f00;">'.$uArr["balance"].' тенге.</span>';}else{$uArr["balance"].=' тенге.';};
        $pArr=getPhoneList($user);
        if (!isset($uArr["rate"])){
            $uArr["rate"]='';
        }
        
        $usrComment='<i>Отсутствует</i>';
        if (objCheckExist('/users/'.$user.'/user.cmnt', 'raw')){
            $uCmnt=objLoad('/users/'.$user.'/user.cmnt', 'raw');
            if (isset($uCmnt["comment"])){
                $usrComment=$uCmnt["comment"];
                $usrComment=htmlentities($usrComment, ENT_COMPAT, 'cp1251');
                $usrComment=str_replace('&lt;br/&gt;', '<br/>', $usrComment);
                $usrComment=str_replace("\n", '<br/>', $usrComment);
                $usrComment=str_replace("\r", '<br/>', $usrComment);
            }
        }
        
        $iinmark='';
        if (substr_count($user, 'tv_')==0){
            if (!isset($uArr["doc_iin"])){
                $iinmark='У пользователя не заполнено поле ИИН.';
            }
            else
            {
                if (!checkIIN($uArr["doc_iin"])){
                    $iinmark='Поле ИИН заполнено некорректно.';
                }
            }
        }
        
        ?>
$out=$out+'<div id="infoDiv_common" style="margin-top: 20px;">';

$out=$out+'<div style="font-size: 24px; font-weight: lighter;"><?=$uArr["uname"]?><?=$tvadd?></div>';

<?php
    if ($iinmark!=''){
        ?>
$out=$out+'<div style="font-size: 14px; font-weight: lighter; color: #f00; margin-left: 10px;"><b><?=$iinmark?></b></div>';
        <?php
    }
?>

$out=$out+'<div style="display: table; width: 100%; margin-left: 10px;">';
$out=$out+'<div style="display: table-row;"><div style="display: table-cell; font-size: 14px; color: #333333; width: 110px;">Адрес:</div><div style="display: table-cell; font-size: 14px; margin-left: 10px; font-weight: lighter;"><?=cityGetStrFromPath(cityGetPathFromAddr($uArr["address"]))?></div></div>';
$out=$out+'<div style="display: table-row;"><div style="display: table-cell; font-size: 14px; color: #333333; width: 110px;">№ договора:</div><div style="display: table-cell; font-size: 14px; margin-left: 10px; font-weight: lighter;"><?=$uArr["dnum"]?></div></div>';
$out=$out+'<div style="display: table-row;"><div style="display: table-cell; font-size: 14px; color: #333333; width: 110px;">Логин:</div><div style="display: table-cell; font-size: 14px; margin-left: 10px; font-weight: lighter;"><?=$uArr["login"]?></div></div>';
$out=$out+'<div style="display: table-row;"><div style="display: table-cell; font-size: 14px; color: #333333; width: 110px;">Пароль:</div><div style="display: table-cell; font-size: 14px; margin-left: 10px; font-weight: lighter;"><?=$uArr["pwd"]?></div></div>';
$out=$out+'<div style="display: table-row;"><div style="display: table-cell; font-size: 14px; color: #333333; width: 110px;">Тариф:</div><div style="display: table-cell; font-size: 14px; margin-left: 10px; font-weight: lighter;"><?=$uArr["rate"]?></div></div>';
$out=$out+'<div style="display: table-row;"><div style="display: table-cell; font-size: 14px; color: #333333; width: 110px;">Баланс:</div><div style="display: table-cell; font-size: 14px; margin-left: 10px; font-weight: lighter;"><?=$uArr["balance"]?></div></div>';
$out=$out+'<div style="display: table-row;"><div style="display: table-cell; font-size: 14px; color: #333333; width: 110px;">Расширение:</div><div style="display: table-cell; font-size: 14px; margin-left: 10px; font-weight: lighter;"><?=extActivity($user)?></div></div>';
$out=$out+'<div style="display: table-row;"><div style="display: table-cell; font-size: 14px; color: #333333; width: 110px;">Комментарий:</div><div style="display: table-cell; font-size: 14px; margin-left: 10px; font-weight: lighter;"><?=$usrComment?></div></div>';

        <?php
        
            if (isset($_COOKIE["login"])){
                    $chkAU=' checked ';
                    if (isset($_COOKIE["lbau"])){
                        if ($_COOKIE["lbau"]=='0'){
                            $chkAU='';
                        }
                    }
                    ?>
$out=$out+'<div style="display: table-row;"><div style="display: table-cell; font-size: 14px; color: #333333; width: 110px;">LANBilling:</div><div style="display: table-cell; font-size: 14px; margin-left: 10px; font-weight: lighter;"><input type="checkbox" id="lbAutoUpdate" <?=$chkAU?> onclick="include_dom(\'/_modules/support/helpers/swapAU.php\');"/></div></div>';
                    <?php
            }
        ?>

$out=$out+'</div>';

        <?php
        $sphone='';
        $mobile='';
        if (count($pArr)!=0){
        ?>
        <?php
            for ($i=0; $i<count($pArr); $i++){
                if (strlen($pArr[$i])==11){
                    if (substr($pArr[$i],0,3)=='871'){
                        if (substr($pArr[$i],0,5)=='87142'){
                            $pArr[$i]=substr($pArr[$i], 5);
                            //$pArr[$i][0]='';
                            //$pArr[$i][1]='';
                            //$pArr[$i][2]='';
                            //$pArr[$i][3]='';
                            //$pArr[$i][4]='';
                            $pArr[$i]=trim($pArr[$i]);
                        }
                        $sphone=$pArr[$i];
                        ?>
                        <?php
                    }
                    else
                    {
                        $mobile=$pArr[$i];
                        ?>
                        <?php
                    }
                }
            }
        ?>
        <?php
        }
        ?>
$out=$out+'<div style="font-size: 18px; margin-top: 20px;">История заявок:</div>';

<?php
    if (!isset($_GET["nonew"])){
?>
<?php
    }
?>
        <?php
        $sArrK=objLoadBranch('/users/'.$user.'/support/', true, false);
        if (is_array($sArrK)){
            $sArr=array_keys($sArrK);
            for ($i=0; $i<count($sArr); $i++){
                $sObj=objLoad('/users/'.$user.'/support/'.$sArr[$i], 'raw');
                $sObj["text"]=str_replace("'", '', $sObj["text"]);
                $sObj["resolution"]=str_replace("'", '', $sObj["resolution"]);
                if (!isset($sObj["phone"])){
                    $sObj["phone"]='';
                }
                if (!isset($sObj["mphone"])){
                    $sObj["mphone"]='';
                }                
                if (isset($sObj["end_time"])){
                    if ($sObj["end_time"]!=''&&$sObj["end_time"]!='0'){
                        $etime=$sObj["end_time"]-$sObj["inc_time"];
                    }
                    else
                    {
                        $etime=0;
                    }
                }
                else {
                    $etime=0;
                }
                
                if ($etime==0){
                    $tmsg='Ожидание';
                }
                else
                {
                    $ehours='0';
                    $edays='0';
                    $esec=$etime-(floor($etime/60)*60);
                    $emin=floor($etime/60);
                    if ($emin>59){
                        $ehours=floor($emin/60);
                        $emin=$emin-$ehours*60;
                    }
                    
                    if ($ehours>23){
                        $edays=floor($ehours/24);
                        $ehours=$ehours-($edays*24);
                    }
                    
                    if ($esec<10){$esec='0'.$esec;}
                    if ($emin<10){$emin='0'.$emin;}
                    if ($ehours<10){$ehours='0'.$ehours;}
                    if ($edays<10){$edays='0'.$edays;}
                    
                    $tmsg='Выполнено за: '.$edays.':'.$ehours.':'.$emin.':'.$esec;
                }
                ?>
                    
$out=$out+'<div style="display: table; margin-top: 10px; width: 100%;"><div style="display: table-row;">';                    
$out=$out+'<div style="display: table-cell; width: 50%;"><hr style="border:none; height:1px; background:#ccc;"/></div>';
$out=$out+'<div style="display: table-cell; width: 180px; min-width: 180px; max-width: 180px; font-size: 12px;" align="center"><?=$tmsg?></div>';
$out=$out+'<div style="display: table-cell; width: 50%;"><hr style="border:none; height:1px; background:#ccc;"/></div>';
$out=$out+'</div></div>';



$out=$out+'<div style="display: table; margin-top: 0px; width: 100%;"><div style="display: table-row;">';

$out=$out+'<div style="display: table-cell; width: 50%; vertical-align: top;">';
$out=$out+'<div style="display: table; margin-top: 10px; width: 100%;"><div style="display: table-row;">';
$out=$out+'<div style="display: table-cell; width: 50px; min-width: 50px; max-width: 50px; vertical-align: top;"><img class="roundimg" src="<?=profileGetAvatar($sObj["operator"])?>"/></div>';
$out=$out+'<div style="display: table-cell; width: 32px; min-width: 32px; max-width: 32px; vertical-align: top;"><img style="margin-top: 5px;" src="/_img/ltriang.png"/></div>';

$out=$out+'<div style="display: table-cell; width: 100%; vertical-align: top;">';
$out=$out+'<div style="border-radius: 5px; min-height: 64px; background-color: #2c3e50; color: #fff; padding: 5px; box-sizing: border-box; font-size: 12px;"><?=profileGetUsername($sObj["operator"])?> [<?=strftime_c('%d.%m.%Y %H:%M',$sObj["inc_time"]) ?>]:<div style="font-weight: lighter;"><?=str_replace("\n", '<br/>', $sObj["text"])?></div><div class="pseudoref" onclick="doCall(\'<?=$sObj["phone"]?>\')">Телефон: <span style="font-weight: lighter;"><?=$sObj["phone"]?></span></div><div class="pseudoref" onclick="doCall(\'<?=$sObj["mphone"]?>\')">Мобильный: <span style="font-weight: lighter;"><?=$sObj["mphone"]?></span></div></div>';
$out=$out+'</div>';
$out=$out+'</div>';
$out=$out+'</div></div>';

$out=$out+'<div style="display: table-cell; width: 20px; min-width: 20px; max-width: 20px;">';
$out=$out+'</div>';
<?php
    if ($tmsg!='Ожидание'){
?>
$out=$out+'<div style="display: table-cell; width: 50%; vertical-align: top;">';
$out=$out+'<div style="display: table; margin-top: 40px; width: 100%;"><div style="display: table-row;">';
$out=$out+'<div style="display: table-cell; width: 100%; vertical-align: top;">';
$out=$out+'<div style="border-radius: 5px; min-height: 64px; background-color: #2c3e50; color: #fff; padding: 5px; box-sizing: border-box; font-size: 12px;"><?=profileGetUsername($sObj["executed"])?> [<?=strftime_c('%d.%m.%Y %H:%M',$sObj["end_time"]) ?>]:<div style="font-weight: lighter;"><?=str_replace("\n", '<br/>', $sObj["resolution"])?></div></div>';
$out=$out+'</div>';
$out=$out+'<div style="display: table-cell; width: 32px; min-width: 32px; max-width: 32px; vertical-align: top;"><img style="margin-top: 5px;" src="/_img/rtriang.png"/></div>';
$out=$out+'<div style="display: table-cell; width: 50px; min-width: 50px; max-width: 50px; vertical-align: top;"><img class="roundimg" src="<?=profileGetAvatar($sObj["executed"])?>"/></div>';
$out=$out+'</div>';
<?php
    }
    else
    {
?>
$out=$out+'<div style="display: table-cell; width: 50%; vertical-align: top;">';
$out=$out+'<div style="display: table; margin-top: 40px; width: 100%;"><div style="display: table-row;">';
$out=$out+'<div style="display: table-cell; width: 100%; vertical-align: top;">';
$out=$out+'<div style="border-radius: 5px; min-height: 64px; padding: 5px; box-sizing: border-box; font-size: 12px;"><div style="font-weight: lighter;">';
<?php
    if (isset($_GET["nonew"])){
        if ($sObj["start_time"]==''||$sObj["start_time"]=='0'){
            ?>
$out=$out+'<button style="width: 150px;" onclick="supRunFromLog(\'<?=str_replace("'", '', $uArr["dnum"])?>\', \'<?=str_replace("'", '', str_replace('"', '`', $uArr["uname"]))?>\',\'<?=str_replace("'", '', cityGetStrFromPath(cityGetPathFromAddr($sObj["address"])))?>\', \'<?=str_replace('\\', '', str_replace('"', '', str_replace("\n", '<br/>', str_replace("'", '', str_replace("\r", '', $sObj["text"])))))?>\' , \'<?=substr($sArr[$i],0, strpos($sArr[$i],'.'))?>\')">Старт заявки</button>';
            <?php
        }
    }


?>
$out=$out+'</div></div>';
$out=$out+'</div>';
$out=$out+'<div style="display: table-cell; width: 32px; min-width: 32px; max-width: 32px; vertical-align: top;"></div>';
$out=$out+'<div style="display: table-cell; width: 50px; min-width: 50px; max-width: 50px; vertical-align: top;"></div>';
$out=$out+'</div>';  
    
    <?php
    }
    ?>
$out=$out+'</div></div>';
$out=$out+'</div></div>';
$out=$out+'<div style="display: table; margin-top: 30px; width: 100%;"><div style="display: table-row;">';                    
$out=$out+'<div style="display: table-cell; width: 100%; height: 20px;"></div>';
$out=$out+'</div></div>';
                <?php
            }
        }
        
        ?>

$out=$out+'</div>';
        <?php
    }
    else
    {
        ?>
$out='Пользователь не найден.';
        <?php
    }
?>
$out=$out+'<input type="hidden" id="userCardID" value="<?=$user?>"/>';
$out=$out+'<div id="infoDiv_status" style="margin-top: 20px; display: none;">';
$out=$out+'</div>';

$out=$out+'<div id="infoDiv_diag" style="margin-top: 20px; display: none;">';
$out=$out+'<div style="display: table;">';
$out=$out+'<div style="display: table-row;">';
$out=$out+'<div style="display: table-cell; width: 420px; vertical-align: top;">';
$out=$out+'<div style="width: 400px; border: 1px #ccc solid; height: 250px; background-color: #eee; text-align: center;">';
$out=$out+'<div id="cableTestDiv"><img src="/_img/network-cable-icon.png" style="width: 128px; height: 91px; padding-top: 20px; padding-bottom: 10px;"/><br/>Данная опция позволяет производить замер линии на коммутаторах Eltex и SNR, не покидая WoToM.<br/><button style="width: 200px;" onclick="include_dom(\'/_modules/support/helpers/cableTester.js.php?id=<?=$user?>\');">Начать тест</button><br/></div>';
$out=$out+'</div>';
$out=$out+'</div>';
$out=$out+'<div style="display: table-cell; width: 420px; vertical-align: top;">';
$out=$out+'<div style="width: 400px; border: 1px #ccc solid; height: 250px; background-color: #eee; text-align: center; margin-left: 20px;">';
$out=$out+'<div id="fiberTestDiv"><img src="/_img/network-fiber-icon.png" style="width: 128px; height: 91px; padding-top: 20px; padding-bottom: 10px;"/><br/>Данная опция позволяет получить состояние оптической линии, не покидая WoToM.<br/><button style="width: 200px;" onclick="include_dom(\'/_modules/support/helpers/fiberTester.js.php?id=<?=$user?>\');">Начать тест</button><br/></div>';
$out=$out+'</div>';
$out=$out+'</div>';
$out=$out+'</div>';
$out=$out+'</div>';
$out=$out+'</div>';

$out=$out+'<div id="infoDiv_iptv" style="margin-top: 20px; display: none;">';
$out=$out+'<div id="iptvParam_<?=$user?>">Загрузка...</div>';
$out=$out+'</div>';

$out=$out+'<div id="infoDiv_payment" style="margin-top: 20px; display: none;">Загрузка...';
$out=$out+'</div>';

$out=$out+'<div id="infoDiv_routers" style="margin-top: 20px; display: none;">';
$out=$out+'<div id="rtrParam_<?=$user?>">Загрузка...</div>';
$out=$out+'</div>';

getById('<?=$uiframe?>Frame').style.display='block';
getById('<?=$uiframe?>Frame').style.width='100%';
getById('<?=$uiframe?>').innerHTML=$out;
setTimeout(function() { orkk_supLoadStatus() }, 600);
setTimeout(function() { orkk_supLoadPayment() }, 600);