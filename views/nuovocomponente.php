<?php include("_navbar.php"); ?>

<div class="col-md-12">
    <div class="row">
        <div class="col-sm-9 col-md-9 col-lg-10 col-main">
			<div style="float:left; height:100%; width:100%;">

				<div class="panel panel-default"  style="height:100%; background:#FFFFFF">
					<div class="panel-heading"><i class="fa fa-user" aria-hidden="true"></i> <strong>NUOVO FAMIGLIARE (Famiglia <?php echo $tessera; ?>)</strong></div>
						<div class="panel-body" style="height:93%; overflow-y:auto; ">
                            <?php if(isset($insert_memberok) && $insert_memnerok == 1) { ?>
                                <div class="alert alert-success">
                                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                    <strong>Inserimento riuscito</strong>
                                </div>
                            <?php }
                                  else if(isset($insert_memberok) && $insert_memberok == 0) {
                            ?>
                                <div class="alert alert-danger">
                                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                    <strong>Inserimento fallito</strong>
                                </div>
                            <?php
                                  }
                            ?>

							<div style="font-size:12px; padding-bottom:10px;"><i>(*) = Campi obbligatori</i></div>
							<div class="well well-sm">
								<div class="row">
									<div class="col-sm-3 col-md-3 col-lg-3">
										<div class="form-group">
								
									<form action="/famiglie/inserisci_componente" method="POST">
											<input type="hidden" name="famiglia" id="famiglia" value="<?php echo $tessera; ?>">
											<label>Codice Fiscale *</label>
											<input class="form-control" type="text" id="ccf" name="ccf" tabindex="1" placeholder="Inserire codice fiscale" autofocus>
										</div>
									</div>
									<div class="col-sm-3 col-md-3 col-lg-3">
										<div class="form-group">
											<label>Cognome *</label>
											<input class="form-control" type="text" id="ccognome" name="ccognome" placeholder="Inserire cognome" tabindex="2">
										</div>
									</div>
									<div class="col-sm-3 col-md-3 col-lg-3">
										<div class="form-group">
											<label>Nome *</label>
											<input class="form-control" type="text" id="cnome" name="cnome" placeholder="Inserire nome" tabindex="3">
										</div>
									</div>
									<div class="col-sm-1 col-md-1 col-lg-1">
										<div class="form-group">
											<label>Sesso *</label>
											<select class="form-control" name="csesso" id="csesso" tabindex="4">
												<option value=""> --- </option>
												<option value="M"> M </option>
												<option value="F"> F </option>
											</select>
										</div>
									</div>
									<div class="col-sm-2 col-md-2 col-lg-2">
										<div class="form-group">
											<label>Ruolo *</label>
											<select class="form-control" name="cruolo" id="cruolo" tabindex="5">
												<option value=""> --- </option>
                                				<option value="Coniuge"> Coniuge </option>
                                				<option value="Figlio/a"> Figlio/a </option>
                                				<option value="Genitore"> Genitore </option>
                                				<option value="Altro"> Altro </option>
                             				</select>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-sm-3 col-md-3 col-lg-3">
										<div class="form-group">
											<label>Data di nascita *</label>
											<div class="input-group date" data-provide="datepicker">
                                                <input type="text" class="form-control" id="cdata_nascita" name="cdata_nascita" placeholder="Es: 01/01/1970" tabindex="6">
                                                <div class="input-group-addon">
                                                    <span class="fa fa-calendar"></span>
                                                </div>
                                            </div>
										</div>
									</div>
									<div class="col-sm-3 col-md-3 col-lg-3">
										<div class="form-group">
											<label>Luogo di nascita *</label>
											<input class="form-control" type="text" id="cluogo_nascita" name="cluogo_nascita" placeholder="Inserire luogo di nascita" tabindex="7">
										</div>
									</div>
									<div class="col-sm-3 col-md-3 col-lg-3">
										<div class="form-group">
											<label>Nazione di nascita *</label>
											<input class="form-control" type="text" id="cnazione_vis" name="cnazione_vis" placeholder="Inserire nazione di nascita" tabindex="8">
											<input class="form-control" type="hidden" id="cnazione" name="cnazione">
										</div>
									</div>
									<div class="col-sm-3 col-md-3 col-lg-3">
										<div class="form-group">
											<label>Nazionalit&agrave; *</label>
											<input class="form-control" type="text" id="cnazionalita_vis" name="cnazionalita_vis" placeholder="Inserire nazionalit&agrave;" tabindex="9">
											<input class="form-control" type="hidden" id="cnazionalita" name="cnazionalita">
										</div>
									</div>
								</div>
							</div>

							<table class="table table-responsive table-striped">
                                <thead>
                                    <th>Cod Fiscale</th>
                                    <th>Cognome</th>
                                    <th>Nome</th>
                                    <th>Sesso</th>
                                    <th>Ruolo</th>
                                    <th>Nato/a il</th>
                                    <th>Luogo nascita</th>
                                    <th>Nazione</th>
                                    <th>Nazionalit&agrave;</th>
                                </thead>
                                <tbody>
								<?php 
										foreach($res as $key=>$val) {
											print("<tr >");
											print("<td>".strtoupper($val['c_codice_fiscale'])."</td>"); 
											print("<td>".ucwords($val['cognome'])."</td>"); 
											print("<td>".ucwords($val['nome'])."</td>"); 
											print("<td>".$val['sesso']."</td>"); 
											print("<td>".$val['ruolo']."</td>"); 
											print("<td>".date("d/m/Y", strtotime($val['data_nascita']))."</td>"); 
											print("<td>".ucwords($val['luogo_nascita'])."</td>"); 
											print("<td>".ucwords($val['nazione'])."</td>"); 
											print("<td>".ucwords($val['nazionalita'])."</td>"); 
											print("</tr>");
										}
								?>
								</tbody>
							</table>

						</div>
				</div>
			</div>

        </div>
        <div class="col-sm-3 col-md-3 col-lg-2 col-right">&nbsp;

			<div>&nbsp;</div>
			<button type="submit" class="btn btn-success btn-sm btn-block" id="invia" name="invia" tabindex="10"><i class="fa fa-plus" aria-hidden="true"></i> Inserisci</button>
			<button type="reset" class="btn btn-danger btn-sm btn-block" id="reset" name="reset" tabindex="11"><i class="fa fa-times" aria-hidden="true"></i> Reset</button>
			</form>

            <div>&nbsp;</div>
            <div>&nbsp;</div>
            <a class="btn btn-info btn-sm btn-block" href="/famiglie/index"><i class="fa fa-list" aria-hidden="true"></i> Torna all'elenco</a>

        </div>
    </div>
</div>

<script type="text/javascript">
 $(document).ready(function(){
    $("#cnazione_vis").autocomplete({

        source: function (request, response) {
            var re = $.ui.autocomplete.escapeRegex(request.term);
            var matcher = new RegExp("^" + re, "i");
            $.getJSON("/famiglie/ajax_nazioni", function (data) {
                response($.grep(($.map(data, function (v, i) {
                    return {
                        label: v.nome,
                        value: v.nome,
                        istat: v.cod_internazionale,
                    };
                })), function (item) {
                    return matcher.test(item.value);
                }))
            });
        },
        select: function (event, ui) {
        //  alert(ui.item ? ("You picked '" + ui.item.label + "' with an ID of " + ui.item.istat) : "Nothing selected, input was " + this.value);
            $('#cnazione').val(ui.item.istat);
        //  console.log($('#comune').val + ' has faded!');
        },
        change: function (event, ui) {
        //  alert(ui.item ? ("You picked '" + ui.item.label + "' with an ID of " + ui.item.istat) : "Nothing selected, input was " + this.value);
            $('#cnazione').val(ui.item.istat);
        //  console.log($('#comune').val+'has faded!')
        }

    });
 });
</script>

<script type="text/javascript">
 $(document).ready(function(){
    $("#cnazionalita_vis").autocomplete({

        source: function (request, response) {
            var re = $.ui.autocomplete.escapeRegex(request.term);
            var matcher = new RegExp("^" + re, "i");
            $.getJSON("/famiglie/ajax_nazioni", function (data) {
                response($.grep(($.map(data, function (v, i) {
                    return {
                        label: v.nome,
                        value: v.nome,
                        istat: v.cod_internazionale,
                    };
                })), function (item) {
                    return matcher.test(item.value);
                }))
            });
        },
        select: function (event, ui) {
        //  alert(ui.item ? ("You picked '" + ui.item.label + "' with an ID of " + ui.item.istat) : "Nothing selected, input was " + this.value);
            $('#cnazionalita').val(ui.item.istat);
        //  console.log($('#comune').val + ' has faded!');
        },
        change: function (event, ui) {
        //  alert(ui.item ? ("You picked '" + ui.item.label + "' with an ID of " + ui.item.istat) : "Nothing selected, input was " + this.value);
            $('#cnazionalita').val(ui.item.istat);
        //  console.log($('#comune').val+'has faded!')
        }

    });
 });
</script>

<script type="text/javascript">
$(document).ready(function(){
	$('#cdata_nascita').datepicker();
});

</script>

<script type="text/javascript">
$(document).ready(function(){
    $('#ccf').on('keypress', function(e) {
        return e.which !== 13;
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
        debug: false,
        rules: {
                    ccf: { required: true, regex: /^[a-zA-Z]{6}[0-9]{2}[a-zA-Z][0-9]{2}[a-zA-Z][0-9]{3}[a-zA-Z]$/ },
                    ccognome: { required: true, regex: /^[0-9 A-Za-z\'àèìòù\.]+$/ },
                    cnome: { required: true, regex: /^[0-9 A-Za-z\'àèìòù]+$/ },
                    cdata_nascita: { required: true, regex: /^(((0[1-9]|[12]\d|3[01])\/(0[13578]|1[02])\/((19|[2-9]\d)\d{2}))|((0[1-9]|[12]\d|30)\/(0[13456789]|1[012])\/((19|[2-9]\d)\d{2}))|((0[1-9]|1\d|2[0-8])\/02\/((19|[2-9]\d)\d{2}))|(29\/02\/((1[6-9]|[2-9]\d)(0[48]|[2468][048]|[13579][26])|((16|[2468][048]|[3579][26])00))))$/ },
                    cluogo_nascita: { required: true, regex: /^[0-9 A-Za-z\'àèìòù]+$/ },
                    cnazione: { required: true, regex: /^[0-9 A-Za-z\'àèìòù]+$/ },
                    cnazionalita: { required: true, regex: /^[0-9 A-Za-z\'àèìòù]+$/ },
                    csesso: { required: true },
                    cruolo: { required: true }, 
                    
        },
        highlight: function (element) { $(element).addClass('input-error'); },
        unhighlight: function(element) { $(element).removeClass('input-error'); },
        success: function (element) { $(element).removeClass('input-error'); },
        errorPlacement: function (error, element) { },
        submitHandler: function(form) { form.submit(); }
    });
});

</script>
