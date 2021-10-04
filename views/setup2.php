<?php
?>
<div class="col-sm-12 col-md-12 col-lg-12">

<h2>Configurazione emporio - Dati emporio</h2>
<div>&nbsp;</div>

<div class="row">

<div class="col-sm-9 col-md-9 col-lg-10">
<div style="font-size:12px; padding-bottom:10px;"><i>(*) = Campi obbligatori</i></div>
<div class="well well-sm">
	<form name="setup2" id="setup2" action="/setup/config/2" method="POST">
		<div class="row">
			<div class="col-sm-6 col-md-6 col-lg-6">
            	<div class="form-group">
                	<label for="exampleInputEmail1">Denominazione emporio *</label>
             		<input type="text" class="form-control" name="nome_emporio" id="nome_emporio" tabindex="1" placeholder="Inserire denominazione emporio">
           		</div>
           	</div>
			<div class="col-sm-2 col-md-2 col-lg-2">
            	<div class="form-group">
                	<label for="exampleInputEmail1">Numero struttura *</label>
             		<input type="text" class="form-control" name="num_struttura" id="num_struttura" tabindex="2" placeholder="Inserire num. struttura">
           		</div>
           	</div>
		</div>
</div>
<div class="well well-sm">
		<div class="row">
			<div class="col-sm-6 col-md-6 col-lg-6">
            	<div class="form-group">
                	<label for="exampleInputEmail1">Associazione *</label>
             		<input type="text" class="form-control" name="nome_associazione" id="nome_associazione" tabindex="3" placeholder="Inserire nome associazione" >
           		</div>
           	</div>
		</div>
		<div class="row">
			<div class="col-sm-3 col-md-3 col-lg-3">
                <div class="form-group">
                    <label for="exampleInputEmail1">Indirizzo *</label>
                    <input type="text" class="form-control" name="indirizzo_associazione" id="indirizzo_associazione" tabindex="4" placeholder="Inserire indirizzo associazione">
                </div>
            </div>
			<div class="col-sm-3 col-md-3 col-lg-3">
                <div class="form-group">
                    <label for="exampleInputEmail1">Localit&agrave;</label>
                    <input type="text" class="form-control" name="localita" id="localita" tabindex="5" placeholder="Inserire localit&agrave;">
                </div>
            </div>
			<div class="col-sm-2 col-md-2 col-lg-2">
                <div class="form-group">
                    <label for="exampleInputEmail1">Comune *</label>
                    <input type="text" class="form-control" name="comune_assoc" id="comune_assoc" tabindex="6" placeholder="Inserire comune">
					<input type="hidden" class="form-control" id="comune_associazione" name="comune_associazione" value="000000">
                </div>
            </div>
			<div class="col-sm-2 col-md-2 col-lg-2">
                <div class="form-group">
                    <label for="exampleInputEmail1">Telefono</label>
                    <input type="text" class="form-control" name="telefono" id="telefono" tabindex="7" placeholder="Inserire telefono">
                </div>
            </div>
			<div class="col-sm-2 col-md-2 col-lg-2">
                <div class="form-group">
                    <label for="exampleInputEmail1">Email</label>
                    <input type="text" class="form-control" name="email" id="email" tabindex="8" placeholder="Inserire email">
                </div>
            </div>
		</div>
        <div class="row">&nbsp;</div>
		<div class="row">
			<div class="col-sm-3 col-md-3 col-lg-3">
            	<div class="form-group">
                	<label for="exampleInputEmail1">Legale rappresentante *</label>
             		<input type="text" class="form-control" name="cognome_responsabile" id="cognome_responsabile" tabindex="9" placeholder="Cognome legale rappresentante" *>
           		</div>
           	</div>
			<div class="col-sm-3 col-md-3 col-lg-3">
            	<div class="form-group">
                	<label for="exampleInputEmail1">&nbsp;</label>
             		<input type="text" class="form-control" name="nome_responsabile" id="nome_responsabile" tabindex="10" placeholder="Nome legale rappresentante">
           		</div>
           	</div>
			<div class="col-sm-2 col-md-2 col-lg-2">
            	<div class="form-group">
                	<label for="exampleInputEmail1">Data di nascita *</label>
					<div class="input-group date" data-provide="datepicker">
             			<input type="text" class="form-control" name="data_nascita" id="data_nascita" tabindex="11" placeholder="Es. 01/01/1970">
						<div class="input-group-addon">
                       		<span class="fa fa-calendar"></span>
                  		</div>
          			</div>
           		</div>
           	</div>
			<div class="col-sm-2 col-md-2 col-lg-2">
            	<div class="form-group">
                	<label for="exampleInputEmail1">Comune di nascita *</label>
             		<input type="text" class="form-control" name="comune_nasc" id="comune_nasc" tabindex="12" placeholder="Inserire comune" required>
					<input type="hidden" class="form-control" id="comune_nascita" name="comune_nascita" value="000000">
           		</div>
           	</div>
		</div>
</div>

</div>

<div class="col-sm-3 col-md-3 col-lg-2 col-right" style="height:80%">&nbsp;
	<div>&nbsp;</div>
    <button type="submit" class="btn btn-success btn-block">Avanti <i class="fa fa-arrow-right" aria-hidden="true"></i></button>
    </form>
</div>

</div>

<script type="text/javascript">
     $.validator.addMethod('regex', function(value, element, param) {
        return this.optional(element) ||
            value.match(typeof param == 'string' ? new RegExp(param) : param);
    }, '');

$(document).ready(function(){
    $('form').validate({
        debug: true,
        rules: {
                    nome_emporio: { required: true, regex: /^[A-Za-z 0-9\.\'\-\_&]+$/ },
                    num_struttura: { required: true, regex: /^[0-9]+$/ },
                    nome_associazione: { required: true, regex: /^[A-Za-z 0-9\.\'\-\_&]+$/ },
					cognome_responsabile: { required: true, regex: /^[a-zA-Z \'\-]+$/ },
					nome_responsabile: { required: true, regex: /^[a-zA-Z ]+$/ },
					data_nascita: { required: true, regex: /^(((0[1-9]|[12]\d|3[01])\/(0[13578]|1[02])\/((19|[2-9]\d)\d{2}))|((0[1-9]|[12]\d|30)\/(0[13456789]|1[012])\/((19|[2-9]\d)\d{2}))|((0[1-9]|1\d|2[0-8])\/02\/((19|[2-9]\d)\d{2}))|(29\/02\/((1[6-9]|[2-9]\d)(0[48]|[2468][048]|[13579][26])|((16|[2468][048]|[3579][26])00))))$/ },
					comune_nasc: { required: true }
                                        
                },
        highlight: function (element) { $(element).addClass('input-error'); },
        unhighlight: function(element) { $(element).removeClass('input-error'); },
        success: function (element) { $(element).removeClass('input-error'); },
        errorPlacement: function (error, element) { },
        submitHandler: function(form) { form.submit(); }
        });
});

</script>

<script type="text/javascript">
$(document).ready(function(){
    $('#data_nascita').datepicker();
});

</script>

<script type="text/javascript">
 $(document).ready(function(){
    $("#comune_nasc").autocomplete({
        source: function (request, response) {
            var re = $.ui.autocomplete.escapeRegex(request.term);
            var matcher = new RegExp("^" + re, "i");
            $.getJSON("/famiglie/ajax_comuni", function (data) {
                response($.grep(($.map(data, function (v, i) {
                    return {
                        label: v.nome,
                        value: v.nome,
                        istat: v.cod_istat,
                        cap: v.cap
                    };
                })), function (item) {
                    return matcher.test(item.value);
                }))
            });
        },
        select: function (event, ui) {
            $('#comune_nascita').val(ui.item.istat);
            $('#cap').val(ui.item.cap);
        },
        change: function (event, ui) {
            $('#comune_nascita').val(ui.item.istat);
            $('#cap').val(ui.item.cap);
        }

    });

    $("#comune_assoc").autocomplete({
        source: function (request, response) {
            var re = $.ui.autocomplete.escapeRegex(request.term);
            var matcher = new RegExp("^" + re, "i");
            $.getJSON("/famiglie/ajax_comuni", function (data) {
                response($.grep(($.map(data, function (v, i) {
                    return {
                        label: v.nome,
                        value: v.nome,
                        istat: v.cod_istat,
                        cap: v.cap
                    };
                })), function (item) {
                    return matcher.test(item.value);
                }))
            });
        },
        select: function (event, ui) {
            $('#comune_associazione').val(ui.item.istat);
            $('#cap').val(ui.item.cap);
        },
        change: function (event, ui) {
            $('#comune_associazione').val(ui.item.istat);
            $('#cap').val(ui.item.cap);
        }

    });
 });
</script>
