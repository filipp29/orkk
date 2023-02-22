<div class="inp inputFileContainer" style="display: flex; align-items: flex-start; ">
    <div class="divButton" onclick="selectFile(this,true)" style="<?= isset($style) ? makeStyle($style) : "" ?>">
        <?=(isset($filePathValue) && ($filePathValue)) ? '<img src="/_modules/orkkNew/img/close_file_background.png" alt="alt" style="height: 100%; width: auto"/>' : '<img src="/_modules/orkkNew/img/input_file_background.png" alt="alt" style="height: 100%; width: auto"/>'?>
    </div>
    <div class="hidden filePath" id="<?=isset($filePathId) ? $filePathId : ""?>"><?=(isset($filePathValue) && ($filePathValue)) ? $filePathValue : ""?></div>
    <div class="textNormal fileName" id="<?=isset($fileNameId) ? $fileNameId : ""?>" style="margin: 0px 10px;font-weight: bolder;color: <?=isset($textColor) ? $textColor : ""?>">
        <?=(isset($fileNameValue) && ($fileNameValue)) ? $fileNameValue : "Файл не выбран"?>
    </div>
</div>


