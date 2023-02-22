<button onclick="<?=isset($onclick) ? $onclick : ""?>" class="buttonNormal <?=isset($class) ? $class : ""?>" style="<?=isset($style) ? makeStyle($style) : ""?>" <?= isset($attribute) ? makeAttribute($attribute) : "" ?>>
    <?=isset($text) ? $text : ""?>
</button>

