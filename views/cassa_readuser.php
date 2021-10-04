<?php include("_navbarcassa.php"); ?>

<div class="col-md-12">
<div class="row">
    <div class="col-sm-12 col-main col-lg-12">

	<div class="login-block">
    	<h1><i class="fa fa-shopping-cart"></i> Nuova operazione di cassa</h1>
		<form action="/cassa/nuovo_conto" method="POST">
			<input type="text" value="" id="id_tessera" name="id_tessera" 
				<?php 
					if($no_tessera != "1") { 
						echo "placeholder='Inserire codice fiscale cliente'"; 
						echo "autofocus";
					} 
					else { 
						echo "style='background-color: #ffff99'";
						echo "readonly";
					} 
				?> autofocus />

			<input type="text" value="" placeholder="Cerca per nome cliente" id="famiglia" name="famiglia" />

			<?php if($no_tessera == 1) { ?>
				<div class="alert alert-danger" style="text-align: center; width:100%"><i class="fa fa-times"></i> Codice Fiscale non trovato!</div>
			<?php  } ?>

			<?php if($sospeso == 1) { ?>
				<div class="alert alert-danger" style="text-align: center; width:100%"><h4><i class="fa fa-ban"></i> NON ACCETTATO</h4>Cliente sospeso</div>
			<?php } ?>

			<?php if($accettato == "no") { ?>
                <div class="alert alert-danger" style="text-align: center; width:100%"><h4><i class="fa fa-times"></i> NON ACCETTATO</h4> Credito insufficiente</div>
            <?php } ?>

            <?php if($accettato == "scaduto") { ?>
                <div class="alert alert-danger" style="text-align: center; width:100%"><h4><i class="fa fa-times"></i> NON ACCETTATO</h4> Tessera scaduta il 
                    <strong><?php echo $scadenza; ?></strong></div>
            <?php } ?>

			<button class="btn btn-danger">Avanti</button>
		</form>
	</div>

	</div>
</div>
</div>

<!-- Modal -->
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Annulla operazione di cassa</h4>
      </div>
      <div class="modal-body">
        <p>Annullare l'operazione e tornare alla schermata iniziale?</p>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-danger" name="submit_delete" id="submit_delete" data-dismiss="modal">Conferma</button>
        <button class="btn btn-default" data-dismiss="modal">Esci</button>
      </div>
    </div>

  </div>
</div>
<!-- Modal end -->

<form id="fake-form" method="POST" action="">
</form>

<form id="fake-form-cart" method="POST" action="">
    <input type="hidden" id="fake-form-barcode" name="barcode">
</form>

<script type="text/javascript">
$('body').on('hidden.bs.modal', '.modal', function () {
    $(this).removeData('bs.modal');
});
</script>

<script type="text/javascript">
$('.stampascontrino').removeAttr('href');
$('.stampascontrino').css('color', '#999999');
$('.ristampascontrino').removeAttr('href');
$('.ristampascontrino').css('color', '#999999');
$('.resetcarrello').removeAttr('href');
$('.resetcarrello').css('color', '#999999');
$('.annullaedesci').removeAttr('href');
$('.annullaedesci').css('color', '#999999');
</script>

<script type="text/javascript">
</script>

<script type="text/javascript">
 $(document).ready(function(){
    $("#famiglia").autocomplete({

        source: function (request, response) {
            var re = $.ui.autocomplete.escapeRegex(request.term);
            var matcher = new RegExp("^" + re, "i");
            $.getJSON("/cassa/ajax_cercaFamiglia", function (data) {
                response($.grep(($.map(data, function (v, i) {
                    return {
                        label: v.nome,
                        value: v.nome,
                        cf: v.codice_fiscale
                    };
                })), function (item) {
                    return matcher.test(item.value);
                }))
            });
        },
        select: function (event, ui) {
            $('#id_tessera').val(ui.item.cf);
        },
        change: function (event, ui) {
            $('#id_tessera').val(ui.item.cf);
        }

    });
 });
</script>

<script type="text/javascript">
$(document).ready(function(){
    $(".nuovaoperazione").bind('click', function() {
        var action = $(this).attr('form-action');
        var titleModal = "Nuova operazione di cassa";
        var textMessage = "<p>Uscire dall'operazione corrente e tornare alla schermata iniziale?</p>"

        $('#fake-form').attr('action', action);
        $('.modal-title').html(titleModal);
        $('.modal-body').html(textMessage);
    });

    // Esecuzione della conferma nel modal
    $("#submit_delete").bind('click', function() {
        $("#fake-form").submit();
    });

    return false;
});
</script>

<script >
     $.validator.addMethod('regex', function(value, element, param) {
        return this.optional(element) ||
            value.match(typeof param == 'string' ? new RegExp(param) : param);
    }, '');

$(document).ready(function(){
    $('form').validate({
        debug: true,
        rules: {
                    id_tessera: { required: true, regex: /^[a-zA-Z]{6}[0-9]{2}[a-zA-Z][0-9]{2}[a-zA-Z][0-9]{3}[a-zA-Z]$/ },
                    
        },
        highlight: function (element) { $(element).addClass('input-error'); },
        unhighlight: function(element) { $(element).removeClass('input-error'); },
        success: function (element) { $(element).removeClass('input-error'); },
        errorPlacement: function (error, element) { },
        submitHandler: function(form) { form.submit(); }
    });
});

</script>
