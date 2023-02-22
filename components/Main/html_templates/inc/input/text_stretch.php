<input onkeyup="inputStretch(this)" class="inp inputText stretch  <?=isset($class) ? $class : ""?>" type="text" name="name" id="<?=isset($id) ? $id : ""?>" value='<?=isset($value) ? $value : ""?>' style="<?=isset($style) ? makeStyle($style) : ""?>" <?= isset($attribute) ? makeAttribute($attribute) : "" ?>>

