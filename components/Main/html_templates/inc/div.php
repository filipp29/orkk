<?php
if (!isset($class)){
    $class = "";
}
if ($type == "column"){
    $class .= " divColumn";
}
else{
    $class .= " divRow";
}

?>

<div id="<?=isset($id) ? $id : ""?>" class="<?=isset($class) ? $class : ""?>" style="<?=isset($style) ? makeStyle($style) : ""?>" <?= isset($attribute) ? makeAttribute($attribute) : "" ?> >
    <?=isset($content) ? $content : ""?>
</div>

