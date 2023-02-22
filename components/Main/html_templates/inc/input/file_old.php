<div class="inputFileContainer" style="display: flex; align-items: flex-start; ">
    <label class="divButton" for="<?=isset($id) ? $id : ""?>" style="<?= isset($style) ? makeStyle($style) : "" ?>">
        <img src="/_modules/orkkNew/img/input_file.png" alt="alt" style="height: 100%; width: auto"/>
        <input class="inputFile" onchange="inputFileChange(this)" type="file" name="<?=isset($id) ? $id : ""?>" id="<?=isset($id) ? $id : ""?>" style="display: none">
    </label>
    <div class="textNormal" style="margin: 0px 10px;font-weight: bolder;">
        Файл не выбран
    </div>
</div>


