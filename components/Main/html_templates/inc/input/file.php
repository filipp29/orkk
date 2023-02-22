<div class="inp inputFileContainer <?=isset($class) ? $class : ""?>" style="display: flex; align-items: flex-start; <?= isset($style) ? makeStyle($style) : "" ?>" id="<?=isset($id) ? $id : ""?>" >
    <div class="divButton" onclick="selectFile(this)" >
        <?=(isset($filePathValue) && ($filePathValue)) ? '<img src="/_modules/orkkNew/img/close_file.png" alt="alt" style="height: 100%; width: auto"/>' : '<img src="/_modules/orkkNew/img/input_file.png" alt="alt" style="height: 100%; width: auto"/>'?>
    </div>
    <div class="hidden filePath" id="<?=isset($filePathId) ? $filePathId : ""?>"><?=(isset($filePathValue) && ($filePathValue)) ? $filePathValue : ""?></div>
    <div class="hidden var fileName" id="<?=isset($fileNameId) ? $fileNameId : ""?>"><?=(isset($fileNameValue) && ($fileNameValue)) ? $fileNameValue : ""?></div>
    <div class="textNormal " style="margin: 0px 10px;font-weight: bolder; color: <?=isset($textColor) ? $textColor : ""?>">
         <?=(isset($filePathValue) && ($filePathValue)) ? 'Файл выбран' : 'Файл не выбран'?>
    </div>
</div>


