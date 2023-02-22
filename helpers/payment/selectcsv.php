<?php



?>
<div style="width: calc(100% - 20px); border: 1px var(--modColor) solid; background-color: #fff; margin-top: 20px; padding: 10px;">
    <div style="color: var(--modGray); font-size: 18px; border-bottom: 1px var(--modGray) dashed;">Шаг №1: Выберите файл, содержащий CSV-данные (СберБанк)</div>
    <div style="text-align: center; width: 100%; margin-top: 20px;">
        <input type="file" id="orkUploadPayment" onchange="orkUploadCSV()" accept=".txt"/>
    </div>
</div>



<div style="width: calc(100% - 20px); border: 1px var(--modColor) solid; background-color: #fff; margin-top: 20px; padding: 10px;">
    <div style="color: var(--modGray); font-size: 18px; border-bottom: 1px var(--modGray) dashed;">Шаг №1: Выберите файл, содержащий CSV-данные (ForteBank)</div>
    <div style="text-align: center; width: 100%; margin-top: 20px;">
        <input type="file" id="orkUploadPaymentForte" onchange="orkUploadCSVForte()"accept=".csv"/>
    </div>
</div>