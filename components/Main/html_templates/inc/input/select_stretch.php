
<select onchange="inputStretch(this)"  class="inp inputSelect stretch <?=isset($class) ? $class : ""?>" id="<?=isset($id) ? $id : ""?>" style="<?=isset($style) ? makeStyle($style) : ""?>" <?= isset($attribute) ? makeAttribute($attribute) : "" ?>>
<?php
if (!isset($value)){
    $value = "";
}
foreach($values as $k => $v){
    if ($value == $k){
        $selected = "selected";
    }
    else{
        $selected = "";
    }
?>
    <option  <?=isset($selected) ? $selected : ""?> value="<?=isset($k) ? $k : ""?>" >
        <?=isset($v) ? $v : ""?>
    </option>>
<?php
}

?>

</select>

