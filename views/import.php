<?php
    include("_navbaradmin.php");

    if($_SESSION['ruolo'] != 'superlocale' && $_SESSION['ruolo'] != 'super') {
        header("Location: /utenti/index");
    }

?>

<div class="col-md-12">
<div class="row">

<div class="col-sm-9 col-md-9 col-lg-10 col-main">

    <div class="panel panel-default"  style="height:100%; background:#FFFFFF">
        <div class="panel-heading"><i class="fa fa-database" aria-hidden="true"></i> <strong>IMPORTA DATI PORTOBELLO</strong></div>
            <!-- <div class="panel-body" style="height:86%; overflow-y: auto;"> -->
            <div class="panel-body" style="height:93%; overflow-y: auto;">

                <?php if(isset($importok) && $import == 1) { ?>
                    <div class="alert alert-success">
                        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                        <strong>Inserimento riuscito</strong>
                    </div>
                <?php }
                    else if(isset($cancellato) && $cancellato == 1) {
                ?>
                    <div class="alert alert-success">
                        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                        <strong>Cancellazione effettuata</strong>
                    </div>
                <?php
                    }
                ?>

                <form action="/import/carica" method="POST" enctype="multipart/form-data">
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

        </div>
    </div>

    <div class="col-sm-3 col-md-3 col-lg-2 col-right" style="height:80%">&nbsp;
        <div>&nbsp;</div>
        <button type="submit" class="btn btn-success btn-block">Avanti <i class="fa fa-arrow-right" aria-hidden="true"></i></button>
        </form>
        <a href="" class="btn btn-primary btn-block delete" form-action='/import/cancella' 
            data-toggle="modal" data-target="#myModal"><i class="fa fa-times" aria-hidden="true"></i> Svuota tabelle DB</a>
    </div>

</div>
</div>

<form id="fake-form" method="POST" action="">
    <input type="hidden" id="fake-form-categoria" name="fake-form-categoria" value="">
</form>

<!-- Modal -->
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Elimina categoria</span></h4>
      </div>
      <div class="modal-body">
        <h1 style="text-align:center"><span id="msg-titolo" class="label label-danger"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i> ATTENZIONE!</span></h1><br>
        <p class="msg-warning"></p>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-danger" name="submit_delete" id="submit_delete" data-dismiss="modal">Procedi</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Annulla</button>
      </div>
    </div>

  </div>
</div>

<script type="text/javascript">
$('body').on('hidden.bs.modal', '.modal', function () {
    $(this).removeData('bs.modal');
});
</script>

<script type="text/javascript">
$(document).ready(function(){
    $(".delete").bind('click', function(e) {
        e.preventDefault();
        var preset_name = $(this).attr('name');
        var action = $(this).attr('form-action');
        $("#fam").html(preset_name);
        $('#fake-form').attr('action', action);
        $('.modal-title').html('Svuota tabelle DB');
        $('#msg-titolo').removeClass('label-warning').addClass('label-danger');
        $('.msg-warning').html("Lo svuotamento delle tabelle del database relative ai prodotti, " +
                                "ai clienti e ai famiglari dei clienti pu&ograve; portare alla " +
                                "rimozione dei dati di altre tabelle ad esse correlate!<br>"+
                                "Procedere?");
        $("#submit_delete").removeClass('btn-warning').addClass('btn-danger');
        $("#submit_delete").html('Elimina');
    });

    // Esecuzione della conferma nel modal
    $("#submit_delete").bind('click', function(e) {
        e.preventDefault();
        $("#fake-form").submit();
    });

    return false;
});
</script>
