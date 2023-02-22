<?php

    error_reporting(E_ALL); 
    ini_set('display_errors', 1);
    mb_internal_encoding('cp1251');
    date_default_timezone_set ('Asia/Almaty');
    require_once ($_SERVER['DOCUMENT_ROOT'].'/_lib/libObjEngine.php');
    require_once ($_SERVER['DOCUMENT_ROOT'].'/_lib/libCity.php');
    require_once ($_SERVER['DOCUMENT_ROOT'].'/_lib/libProfiles.php');
    
    require_once ($_SERVER['DOCUMENT_ROOT'].'/_modules/routers/php/support/getMacByLogin.php');
    
    function tryToGet($mac){
        
        $mac_address = $mac;

        if (tryToLoad($mac)!=''){
            return tryToLoad($mac);
        }
        
        return '';
        $url = "https://api.macvendors.com/" . urlencode($mac_address);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $response = curl_exec($ch);
        if($response) {
            if (strlen($response)<50){
                $response='(?) '.$response;
                $mac=strtolower($mac);
                $mac=substr($mac, 0, 8);            
                $tmp=array();
                $tmp["name"]=$response;
                objSave('/reference/maclist/'.$mac, 'raw', $tmp);
                return $response;
            }
            else
            {
                return '';
            }
        } else {
            return '';
        }
    }
    
    function tryToLoad($mac){
        $mac=strtolower($mac);
        $mac=substr($mac, 0, 8);
        if (objCheckExist('/reference/maclist/'.$mac, 'raw')){
            $obj=objLoad('/reference/maclist/'.$mac, 'raw');
            return $obj["name"];
        }
        return '';
    }
    
    function getGPON($line){
        if (substr_count($line, '[GPON]')==0){
            return '';
        }
        $line=trim(str_replace('[GPON]', '', $line));
        return $line;
    }
    
    function getMacID($mac){
        $macs=array(
            '04:95:e6'=>'Tenda Tech. Co',
            '2c:d4:44'=>'(!) Fujitsu Ltd.',
            'f8:f0:82'=>'NAG SNR CPE-W4N',
            '00:0f:02'=>'NAG SNR CPE-W4G',
            '18:a6:f7'=>'TP-Link Tech Co, Ltd',
            '30:b5:c2'=>'TP-Link Tech Co, Ltd',
            '44:87:fc'=>'(!) Elitegroup CS Co, Ltd',
            '48:e2:44'=>'Hon Hai Precision Ind.',
            '54:a0:50'=>'(!) ASUSTek Computer Inc',
            '84:16:f9'=>'TP-Link Tech Co, Ltd',
            'bc:46:99'=>'TP-Link Tech Co, Ltd',
            'c4:e9:84'=>'TP-Link Tech Co, Ltd',
            'c8:3a:35'=>'Tenda Tech. Co',
            'e8:65:d4'=>'Tenda Tech. Co (Dongguan)',
            'e4:8d:8c'=>'Routerboard Mikrotik',
            '00:1d:7d'=>'(!) Giga-Byte Tech Co, Ltd',
            'b8:88:e3'=>'(!) Compal Information Co',
            'd8:32:14'=>'Tenda Tech. Co (Dongguan)',
            '28:6c:07'=>'Xiaomi Electronics, Co, Ltd',
            '4c:5e:0c'=>'Routerboard Mikrotik',
            '60:e3:27'=>'TP-Link Tech Co, Ltd',
            'b8:97:5a'=>'(!) Biostar Microtech',
            'a0:f3:c1'=>'TP-Link Tech Co, Ltd',
            'f4:f2:6d'=>'TP-Link Tech Co, Ltd',
            'c4:71:54'=>'TP-Link Tech Co, Ltd',
            '98:de:d0'=>'TP-Link Tech Co, Ltd',
            'c0:4a:00'=>'TP-Link Tech Co, Ltd',
            '00:25:22'=>'(!) ASRock Incorporation',
            '18:d6:c7'=>'TP-Link Tech Co, Ltd',
            '00:e0:4c'=>'(!) Realtek Semiconductor Corp',
            '10:78:d2'=>'(!) Elitegroup CS Co, Ltd',
            'e8:94:f6'=>'TP-Link Tech Co, Ltd',
            '00:08:22'=>'(!) InPro Comm (MediaTek)',
            'c0:25:e9'=>'TP-Link Tech Co, Ltd',
            '14:cc:20'=>'TP-Link Tech Co, Ltd',
            'c4:6e:1f'=>'TP-Link Tech Co, Ltd',
            '50:46:5d'=>'(!) ASUSTek Computer Inc',
            '3c:cd:93'=>'(!) LG Electronics Inc',
            '7c:2e:dd'=>'(!) Samsung Electronics Co',
            '00:87:01'=>'(!) Samsung Electronics Co',
            '70:85:c2'=>'(!) ASRock Incorporation',
            'e4:6f:13'=>'D-Link International',
            'f0:76:1c'=>'(!) Compal Information Co',
            'dc:0e:a1'=>'(!) Compal Information Co',
            '40:45:da'=>'(!) Spreadtrum Communications',
            '20:1a:06'=>'(!) Compal Information Co',
            'd4:6e:0e'=>'TP-Link Tech Co, Ltd',
            '54:f2:01'=>'(!) Samsung Electronics Co',
            '20:89:84'=>'(!) Compal Information Co',
            '00:25:11'=>'(!) Elitegroup CS Co, Ltd',
            '7c:8b:ca'=>'(!) Compal Information Co',
            '00:e0:4d'=>'Internet Initiative Japan',
            '10:fe:ed'=>'TP-Link Tech Co, Ltd',
            '50:e5:49'=>'(!) Giga-Byte Tech Co',
            'c0:41:f6'=>'(!) LG Electronics Inc',
            'd0:27:88'=>'(!) Hon Hai Precision Ind.',
            '90:8d:78'=>'D-Link International',
            'bc:5f:f4'=>'(!) ASRock Incorporation',
            'c8:e7:d8'=>'Mercury Communication Co',
            'c0:a5:dd'=>'Mercury-Shenzhen Tech Co',
            'c4:93:d9'=>'(!) Samsung Electronics Co',
            'd8:68:c3'=>'(!) Samsung Electronics Co',
            'f4:71:90'=>'(!) Samsung Electronics Co',
            '78:ab:bb'=>'(!) Samsung Electronics Co',
            'e2:0d:17'=>'TP-Link Tech Co, Ltd',
            '50:64:2b'=>'Xiaomi Electronics, Co, Ltd',
            '30:85:a9'=>'(!) ASUSTek Computer Inc',
            '10:a4:be'=>'(!) Shenzhen Bilian Electronic Co',
            'e8:4e:06'=>'(!) Edup International',
            '40:16:3b'=>'(!) Samsung Electronics Co',
            '94:8b:03'=>'(!) Eaget Innovation and Tech Co',
            '6c:f0:49'=>'(!) Giga-Byte Tech Co, Ltd',
            '24:da:33'=>'(!) Huawei Tech Co, Ltd',
            'fc:aa:b6'=>'(!) Samsung Electronics Co, Ltd'
        );
        if (strlen($mac)>8){
            $wrk=substr($mac,0,8);
            $wrk=strtolower($wrk);
            if (!isset($macs[$wrk])){
                $ttg='';
                $ttg=tryToGet($mac);
                if ($ttg!=''){
                    return str_replace(' ', '&nbsp;', $ttg);
                }
                $tmp=array();
                objSave('/reference/macs/'.$wrk, 'raw', $tmp);
                return 'Неизвестен';
            }
            else
            {
                return str_replace(' ', '&nbsp;', $macs[$wrk]);
            }
        }
        else
        {
            return '';        
        }
    }
    
    function getDelay($sec){
        if ($sec<0){
            return '0 сек.';
        }
        $day=60*60*24;
        $hour=60*60;
        $min=60;
        
        $d_days=floor($sec/$day);
        $sec=$sec-($d_days*$day);

        $d_hours=floor($sec/$hour);
        $sec=$sec-($d_hours*$hour);

        $d_mins=floor($sec/$min);
        $sec=$sec-($d_mins*$min);        
        
        $ret='';
        //$ret=$sec.' => ';
        
        if ($d_days!=0){
            $ret.=$d_days.' дн.';
        }

        if ($d_hours!=0){
            if ($ret!=''){
                $ret.=', ';
            }
            $ret.=$d_hours.' час.';
        }
        
        if ($d_mins!=0){
            if ($ret!=''){
                $ret.=', ';
            }
            $ret.=$d_mins.' мин.';
        }
        
        if ($sec!=0){
            if ($ret!=''){
                $ret.=', ';
            }
            $ret.=$sec.' сек.';
        }
        
        if ($ret==''){
            return '0 сек.';
        }
        
        return $ret;
    }
    
    $id=$_POST["id"];
    
    if (!objCheckExist('/users/'.$id.'/user.vcard', 'vcard')){exit;}

    $uobj=objLoad('/users/'.$id.'/user.vcard', 'vcard');
    
    $cstamp=time();
    $cdate=strftime_c('%Y%m%d', $cstamp);
    $acount=0;
    
    $cmac=getMacByLogin($id);
    
    if (trim($cmac)==''){
        $cmac_read='Отсутствует';
    }
    else
    {
        $cmac_read=$cmac;
    }

    if (!isset($uobj["swname"])){$uobj["swname"]='Неизвестно';}
    if (!isset($uobj["login"])){$uobj["login"]=$id;}
    if (!isset($uobj["swport"])){$uobj["swport"]='Неизвестно';}
    
    $swname=$uobj["swname"];
    $swport=$uobj["swport"];
    
    if (!objCheckExist('/switches/'.$swname.'/'.$swport.'/client.prt', 'prt')){
        $swname='Неизвестно';
        $swport='Неизвестно';
    }
    else
    {
        $pobj=objLoad('/switches/'.$swname.'/'.$swport.'/client.prt', 'prt');
        if (isset($pobj["dnum"])){
            if ($pobj["dnum"]!=$id){
                $swname='Неизвестно';
                $swport='Неизвестно';                
            }
        }
        else
        {
            $swname='Неизвестно';
            $swport='Неизвестно';
        }
    }
    
    $swip='N/A';
    if ($swname!='Неизвестно'){
        $sobj=objLoad('/switches/'.$swname.'/settings.set', 'set');
        if (isset($sobj["ip"])){
            $swip=$sobj["ip"];
        }
    }
    else
    {
        $swname='_regPending';
    }
    
    $delObj=objLoad('/logstamps/auth.dat', 'raw');
    
    ?>
    <input type="hidden" id="switchIP" value="<?=$swip?>"/>
    <input type="hidden" id="switchName" value="<?=$swname?>"/>
    <input type="hidden" id="switchPort" value="<?=$swport?>"/>
    
    <div style="display: table; width: 940px;">
        <div style="display: table-row;">
            <div style="display: table-cell; width: 550px;">
                <div style="display: table; width: 550px;">
                    <div style="display: table-row;">
                        <div style="display: table-cell;">
                            IP абонента:
                        </div>
                        <div style="display: table-cell; font-weight: lighter;" id="sw_<?=$uobj["login"]?>_userip">
                            ...
                        </div>            
                    </div>
                    
                    <div style="display: table-row;">
                        <div style="display: table-cell;">
                            Зарегистрированный MAC:
                        </div>
                        <div style="display: table-cell; font-weight: lighter;">
                            <?=$cmac_read?>
                        </div>            
                    </div>
                    
                    <div style="display: table-row;"><div style="display: table-cell;"></div><div style="display: table-cell; font-weight: lighter; height: 10px;"></div></div>
                    <div style="display: table-row;">
                        <div style="display: table-cell; width: 250px;">
                            Имя коммутатора:
                        </div>
                        <div style="display: table-cell; font-weight: lighter;">
                            <?=$swname?>
                        </div>            
                    </div>
                    <div style="display: table-row;">
                        <div style="display: table-cell;">
                            Номер порта:
                        </div>
                        <div style="display: table-cell; font-weight: lighter;">
                            <?=$swport?>
                        </div>            
                    </div>        
                    <div style="display: table-row;">
                        <div style="display: table-cell;">
                            IP коммутатора:
                        </div>
                        <div style="display: table-cell; font-weight: lighter;">
                            <?=$swip?>
                        </div>            
                    </div>           
                    <div style="display: table-row;">
                        <div style="display: table-cell;">
                            Состояние коммутатора:
                        </div>
                        <div style="display: table-cell; font-weight: lighter;" id="sw_<?=$swname?>_status">
                            ...
                        </div>            
                    </div>
                    <div style="display: table-row;">
                        <div style="display: table-cell;">
                            Состояние порта:
                        </div>
                        <div style="display: table-cell; font-weight: lighter;" id="sw_<?=$swname.'__'.$swport?>_status">
                            ...
                        </div>            
                    </div>           
                    <div style="display: table-row;">
                        <div style="display: table-cell;">
                            Ошибки на порту:
                        </div>
                        <div style="display: table-cell; font-weight: lighter;" id="sw_<?=$swname.'__'.$swport?>_errors">
                            ...
                        </div>            
                    </div>
                    <div style="display: table-row;"><div style="display: table-cell;"></div><div style="display: table-cell; font-weight: lighter; height: 10px;"></div></div>
                    <div style="display: table-row;">
                        <div style="display: table-cell;">
                            Отставание авторизаций:
                        </div>
                        <div style="display: table-cell; font-weight: lighter;">
                            <?=getDelay(time()-$delObj["ltime"])?>
                        </div>            
                    </div>                   
                </div>
            </div>
            <div style="display: table-cell; width: 390px; vertical-align: top;">
                <div id="usersess_<?=$uobj["login"]?>">
                    <div style="width: 100%; text-align: center;">
                        <img style="margin-top: 5px;" src="/_img/blueLoad96.gif"/>
                    </div>
                </div>
            </div>
        </div>
    </div>

    
    
    
    <div style="display: table; width: 940px; border: 1px #2c3e50 solid; margin-top: 20px; font-size: 14px;">
        <div style="display: table-row; background-color: #2c3e50; color: #fff;">
            <div style="display: table-cell; width: 10px;">

            </div> 
            <div style="display: table-cell; width: 160px;">
                Дата и время:
            </div>
            <div style="display: table-cell; width: 10px;">

            </div>                
            <div style="display: table-cell; width: 160px;">
                MAC-адрес:
            </div>        
            <div style="display: table-cell; width: 10px;">

            </div>                
            <div style="display: table-cell; width: 160px;">
                IP-адрес:
            </div>   
            <div style="display: table-cell; width: 10px;">

            </div>                
            <div style="display: table-cell; width: 230px;">
                Тип устройства:
            </div> 
            <div style="display: table-cell; width: 10px;">

            </div>                
            <div style="display: table-cell; width: 230px;">
                &nbsp;&nbsp;Метод авторизации:
            </div>
            <div style="display: table-cell; width: 40px;">
                
            </div>
        </div>        
    <?php
    $lastgpon='';
    while ($cstamp>1483228800){
        if (objCheckExist('/authlog/byLogin/'.$id.'/'.$cdate, 'raw')){
            $tobj=objLoad('/authlog/byLogin/'.$id.'/'.$cdate, 'raw');
            $tkeys=array_keys($tobj);
            for ($i=count($tobj)-1; $i>-1; $i--){
                if (substr_count($tkeys[$i],'isrejected_')!=0){
                    $wstamp=str_replace('isrejected_', '', $tkeys[$i]);
                    $wstamp=trim($wstamp);
                    $acount++;
                    if (($acount/2)==floor($acount/2)){
                        $oadd='background-color: #ddd;';
                    }
                    else
                    {
                        $oadd='';
                    }
                    if ($tobj["isrejected_".$wstamp]=='1'){
                        $cadd=' text-decoration: line-through;';
                    }
                    else
                    {
                        $cadd='';
                    }
                    
                    if (!isset($tobj['ip_'.$wstamp])){
                        $tobj['ip_'.$wstamp]='<span style="color: #f00;">null</span>';
                    }
                    
                    if ($tobj['ip_'.$wstamp]=='init'){
                        $tobj['ip_'.$wstamp]='<span style="font-weight: normal;">Инициализация</span>';
                    }
                    
                    if (!isset($tobj['authtype_'.$wstamp])){
                        $tobj['authtype_'.$wstamp]='N/A';
                    }
                    
                    if (!isset($tobj['mac_'.$wstamp])){
                        $tobj['mac_'.$wstamp]='<span style="color: #f00;">null</span>';
                    }
                    

                    
                    if (!isset($tobj['authtype_'.$wstamp])){
                        $tobj['authtype_'.$wstamp]='<span style="color: #f00;">null</span>';
                    }
                    ?>

    <div style="display: table-row; font-weight: lighter;<?=$cadd?> <?=$oadd?>">
        <div style="display: table-cell; width: 10px;">

        </div>         
        <div style="display: table-cell; width: 230px;">
            <?=strftime_c('%d.%m.%Y %H:%M:%S', $wstamp)?>
        </div>
        <div style="display: table-cell; width: 10px;">

        </div>                
        <div style="display: table-cell; width: 230px;">
            <?=$tobj['mac_'.$wstamp]?>
        </div>        
        <div style="display: table-cell; width: 10px;">

        </div>                
        <div style="display: table-cell; width: 230px;">
            <?=$tobj['ip_'.$wstamp]?>
        </div>   
        <div style="display: table-cell; width: 10px;">

        </div>                
        <div style="display: table-cell; width: 230px; word-break: keep-all; word-wrap: nowrap; white-space: nowrap;">
            <?=getMacID($tobj['mac_'.$wstamp])?>
        </div> 
        <div style="display: table-cell; width: 10px;">

        </div>                
        <div style="display: table-cell; width: 230px;">
            &nbsp;&nbsp;<?=$tobj['authtype_'.$wstamp]?>
        </div> 
        <div style="display: table-cell; text-align: center; vertical-align: middle;">
            
        </div> 
    </div>
                    <?php
                    if ($lastgpon==''){
                        $lastgpon=getGPON($tobj['authtype_'.$wstamp]);
                    }
                }
                if ($acount>49){
                    $cstamp=0;
                    break;
                }
            }
        }
        $cstamp=$cstamp-(60*60*24);
        $cdate=strftime_c('%Y%m%d', $cstamp);
    }
?>
        <input type="hidden" id="lastGPON" value="<?=$lastgpon?>"/>
</div>