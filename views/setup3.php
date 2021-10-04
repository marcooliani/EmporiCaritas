<?php
?>
<div class="col-md-12">

<h2>Configurazione emporio - Importazione dati Portobello</h2>
<div>&nbsp;</div>

<div class="row">

<div class="col-sm-9 col-md-9 col-lg-10">
<form action="/setup/config/3" method="POST" enctype="multipart/form-data">
    <input type="hidden" name="fileupload" id="fileupload" value="fileupload">

    <div class="well well-sm" style="padding-left:20px;">
        <label>Importa prodotti (solo barcodes)</label>
        <input type="file" accept=".csv" style="visibility:hidden; width: 1px;" 
            id='prodotti' name='prodotti'  
            onchange="$(this).parent().find('span').html($(this).val().replace('C:\\fakepath\\', ''))"  /> <!-- Chrome security returns 'C:\fakepath\'  -->
        <input class="btn btn-primary" type="button" value="Scegli file" onclick="$(this).parent().find('input[type=file]').click();"/> <!-- on button click fire the file click event -->
        &nbsp;
        <span  class="badge badge-important" ></span>
    </div>

    <div class="well well-sm" style="padding-left:20px;">
        <label>Importa clienti (solo capifamiglia)</label>
        <input type="file" accept=".csv" style="visibility:hidden; width: 1px;" 
            id='famiglie' name='famiglie'  
            onchange="$(this).parent().find('span').html($(this).val().replace('C:\\fakepath\\', ''))"  /> <!-- Chrome security returns 'C:\fakepath\'  -->
        <input class="btn btn-primary" type="button" value="Scegli file" onclick="$(this).parent().find('input[type=file]').click();"/> <!-- on button click fire the file click event -->
        &nbsp;
        <span  class="badge badge-important" ></span>
    </div>

    <div class="well well-sm" style="padding-left:20px;">
        <label>Importa famigliari</label>
        <input type="file" accept=".csv" style="visibility:hidden; width: 1px;" 
            id='famigliari' name='famigliari'  
            onchange="$(this).parent().find('span').html($(this).val().replace('C:\\fakepath\\', ''))"  /> <!-- Chrome security returns 'C:\fakepath\'  -->
        <input class="btn btn-primary" type="button" value="Scegli file" onclick="$(this).parent().find('input[type=file]').click();"/> <!-- on button click fire the file click event -->
        &nbsp;
        <span  class="badge badge-important" ></span>
    </div>

    <div class="well well-sm" style="padding-left:20px;">
        <label>Importa fornitori</label>
        <input type="file" accept=".csv" style="visibility:hidden; width: 1px;" 
            id='fornitori' name='fornitori'  
            onchange="$(this).parent().find('span').html($(this).val().replace('C:\\fakepath\\', ''))"  /> <!-- Chrome security returns 'C:\fakepath\'  -->
        <input class="btn btn-primary" type="button" value="Scegli file" onclick="$(this).parent().find('input[type=file]').click();"/> <!-- on button click fire the file click event -->
        &nbsp;
        <span  class="badge badge-important" ></span>
    </div>


</div>

<div class="col-sm-3 col-md-3 col-lg-2 col-right" style="height:80%">&nbsp;
	<div>&nbsp;</div>
    <button type="submit" class="btn btn-success btn-block">Avanti <i class="fa fa-arrow-right" aria-hidden="true"></i></button>
    </form>
    <a href="/setup/config/4" class="btn btn-primary btn-block">Salta import <i class="fa fa-arrow-right" aria-hidden="true"></i></a>
</div>

</div>
</div>

