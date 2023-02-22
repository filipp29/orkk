 
<div class="createFileForm">
    <form enctype="multipart/form-data" method="POST" class="createFileForm" onsubmit="createFile(this)">
        <input type="hidden" name="MAX_FILE_SIZE" value="10000000" />
        <label>Загрузить файл</label>
        <input type="file" name="file" id="file" onchange="fileOpen(this)">
        <label for="name" >Имя файла</label>
        <input type="text" name="name">
        <input type="hidden" name="path" value="<?=isset($path) ? $path : ""?>">
        <input type="submit" name="submit" id="submit" value="OK" style="">
        <input type="hidden" name="action" value="createFile">
    </form>
</div>





