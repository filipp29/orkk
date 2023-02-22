<table class="<?=isset($class) ? $class : ""?>" style="<?=isset($style) ? makeStyle($style) : ""?>" <?= isset($attribute) ? makeAttribute($attribute) : "" ?> >
    <thead>
        <?=isset($thead) ? $thead : ""?>
    </thead>    
    <tbody>
        <?=isset($tbody) ? $tbody : ""?>
    </tbody>    
</table>