<?php include("_navbaraccettazione.php"); ?>

<div class="col-md-12">
<div class="row">
    <div class="col-sm-12 col-main col-lg-12">

    <div class="login-block">
        <h1><i class="fa fa-id-card-o"></i> Accettazione clienti</h1>
        <form action="/accettazione/verifica" method="POST">
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

			<?php if($accettato == "riserva") { ?>
				<div class="alert alert-warning" style="text-align: center; width:100%"><h4><i class="fa fa-exclamation"></i> CON RISERVA</h4> Credito uguale o inferiore a 20 punti</div>
			<?php 
                    if($bimbi == "1") {
            ?>
                        <div class="alert alert-default" style="text-align: center; width:100%"><h4><i class="fa fa-group"></i> <?php echo $num_bimbi; ?> Minori presenti!</h4>
            <?php   
                        foreach($bimbi_array as $k=>$val) {
                            echo ucwords($val['nome']).", <strong>".$val['eta']." anni</strong> - ".ucwords($val['sesso']).", ".$val['ruolo']."<br>" ;
                        }
                        echo "</div>";
                    }
                  } 
            ?>

			<?php if($accettato == "ok") { ?>
				<div class="alert alert-success" style="text-align: center; width:100%"><h4><i class="fa fa-check"></i> ACCETTATO</h4></div>
			<?php 
                    if($bimbi == "1") {
            ?>
                        <div class="alert alert-default" style="text-align: center; width:100%"><h4><i class="fa fa-group"></i> <?php echo $num_bimbi; ?> Minori presenti!</h4>
            <?php   
                        foreach($bimbi_array as $k=>$val) {
                            echo ucwords($val['nome']).", <strong>".$val['eta']." anni</strong> - ".ucwords($val['sesso']).", ".$val['ruolo']."<br>" ;
                        }
                        echo "</div>";
                    }
                  }
            ?>


			<button class="btn btn-danger">Avanti</button>
		</form>
	</div>

	</div>
</div>
</div>

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
</script>

<script type="text/javascript">
 $(document).ready(function(){
    $("#famiglia").autocomplete({

        source: function (request, response) {
            var re = $.ui.autocomplete.escapeRegex(request.term);
            var matcher = new RegExp("^" + re, "i");
            $.getJSON("/accettazione/ajax_cercaFamiglia", function (data) {
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
