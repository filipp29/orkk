<div onclick="<?=isset($onclick) ? $onclick : ""?>" class="buttonRed <?=isset($class) ? $class : ""?>" style="<?=isset($style) ? makeStyle($style) : ""?>" <?= isset($attribute) ? makeAttribute($attribute) : "" ?>>
    <?=isset($text) ? $text : ""?>
</div>

