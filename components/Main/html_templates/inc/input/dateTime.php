<?php   

if ((isset($value)) &&($value)){
    $value = date("Y-m-d\TH:s",intval($value));
}

?>

<input class="inp inputDate  <?=isset($class) ? $class : ""?>" type="datetime-local" name="name" id="<?=isset($id) ? $id : ""?>" value="<?=isset($value) ? $value : ""?>" style="<?=isset($style) ? makeStyle($style) : ""?>" <?= isset($attribute) ? makeAttribute($attribute) : "" ?>>

