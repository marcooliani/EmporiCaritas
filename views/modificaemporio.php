<?php include("_navbaradmin.php"); ?>

<div class="col-md-12">
    <div class="row">
        <div class="col-sm-9 col-md-9 col-lg-10 col-main">
			<div style="float:left; height:100%; width:100%;">

				<div class="panel panel-default"  style="height:100%; background:#FFFFFF">
					<div class="panel-heading"><i class="fa fa-info" aria-hidden="true"></i> <strong>MODIFICA DATI EMPORIO</strong></div>
						<div class="panel-body" style="height:93%; overflow-y:auto; ">
                   
                            <div style="font-size:12px; padding-bottom:10px;"><i>(*) = Campi obbligatori</i></div>

 
                            <form name="modificaemporio" id="modificaemporio" action="/utilities/update_emporio" method="POST">
							<?php $val = $ris->fetch(); ?>
							<div class="well well-sm">
						      <div class="row">

								<div class="col-sm-4 col-md-4 col-lg-4">
									<div class="form-group">
										<label for="exampleInputEmail1">ID emporio</label><br>
										<input type="text" id="id_emp" name="id_emp" class="form-control" value="<?php echo ucwords($val['id_emporio']); ?>" readonly>
                                        <input type="hidden" name="id_emporio" id="id_emporio" value="<?php echo $val['id_emporio']; ?>">
									</div>
								</div>

								<div class="col-sm-5 col-md-5 col-lg-5">
									<div class="form-group">
										<label for="exampleInputEmail1">Nome emporio *</label>
										<input type="text" id="nome_emporio" name="nome_emporio" class="form-control" value="<?php echo ucwords($val['nome_emporio']); ?>" >
									</div>
								</div>

								<div class="col-sm-2 col-md-2 col-lg-2">
									<div class="form-group">
										<label for="exampleInputPassword1">Num. struttura *</label>
										<input type="text" class="form-control" id="num_struttura" name="num_struttura" value="<?php echo ucwords($val['num_struttura']); ?>" >
									</div>
								</div>

							  </div>
						</div>

						<div class="well well-sm">			
                            <div class="row">
                                <div class="col-sm-3 col-md-3 col-lg-3">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Responsabile amministrativo *</label>
                                        <input type="text" class="form-control" id="cognome_responsabile" name="cognome_responsabile" value="<?php echo ucwords($val['cognome_responsabile']); ?>">
                                    </div>
                                </div>
                                <div class="col-sm-3 col-md-3 col-lg-3">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">&nbsp;</label>
                                        <input type="text" class="form-control" id="nome_responsabile" name="nome_responsabile" value="<?php echo ucwords($val['nome_responsabile']); ?>">
                                    </div>
                                </div>
                                <div class="col-sm-3 col-md-3 col-lg-3">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Data di nascita *</label>
                                        <div class="input-group date" data-provide="datepicker">
                                            <input type="text" class="form-control" id="data_nascita" name="data_nascita" 
                                                value="<?php echo date("d/m/Y", strtotime($val['data_nascita'])); ?>">
                                            <div class="input-group-addon">
                                                <span class="fa fa-calendar"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-3 col-md-3 col-lg-3">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Comune di nascita *</label>
                                        <input type="text" class="form-control" id="comune_n" name="comune_n" value="<?php echo ucwords($val['comune_n']). " (" .strtoupper($val['provincia_n']). ")"; ?>">
                                        <input type="hidden" id="comune_nascita" name="comune_nascita" value="<?php echo ucwords($val['comune_nascita']); ?>">
                                    </div>
                                </div>
                            </div>
                        </div>
						
                        <div class="well well-sm">
                            <div class="row">
                                <div class="col-sm-3 col-md-3 col-lg-3">
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">Nome associazione *</label>
                                            <input type="text" class="form-control" id="nome_associazione" name="nome_associazione" value="<?php echo ucwords($val['nome_associazione']); ?>">
                                        </div>
                                </div>
                            </div>

							<div class="row">
                                <div class="col-sm-4 col-md-4 col-lg-4">
										<div class="form-group">
											<label for="exampleInputEmail1">Indirizzo *</label>
											<input type="text" class="form-control" id="indirizzo_associazione" name="indirizzo_associazione" value="<?php echo ucwords($val['indirizzo_associazione']); ?>">
										</div>
								</div>
								<div class="col-sm-4 col-md-4 col-lg-4">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Localit&agrave;</label>
                                        <input type="text" class="form-control" id="localita" name="localita" value="<?php echo ucwords($val['localita']); ?>">
                                    </div>
								</div>
								<div class="col-sm-4 col-md-4 col-lg-4">
									<div class="form-group">
										<label for="exampleInputEmail1">Comune *</label><br>
										<input type="text" class="form-control" id="comune_a" name="comune_a" value="<?php echo ucwords($val['comune_a']). " (".strtoupper($val['provincia_a']).")"; ?>">
                                        <input type="hidden" id="comune" name="comune" value="<?php echo $val['comune']; ?>">
                                        <input type="hidden" id="cap" name="cap" value="<?php echo $val['cap']; ?>">
									</div>
								</div>
							</div>

                            <div class="row">
								<div class="col-sm-4 col-md-4 col-lg-4">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Telefono</label>
                                        <input type="text" class="form-control" id="telefono" name="telefono" value="<?php echo ucwords($val['telefono']); ?>">
                                    </div>
								</div>
								<div class="col-sm-4 col-md-4 col-lg-4">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Email</label>
                                        <input type="text" class="form-control" id="email" name="email" value="<?php echo ucwords($val['email']); ?>">
                                    </div>
								</div>
                            </div>
			
						</div>

					</div>

				</div>
			</div>

        </div>
        <div class="col-sm-3 col-md-3 col-lg-2 col-right">&nbsp;

			<div>&nbsp;</div>
			<button type="submit" class="btn btn-success btn-sm btn-block" tabindex="20"><i class="fa fa-save" aria-hidden="true"></i> Salva modifiche</button>
			<button type="reset" class="btn btn-danger btn-sm btn-block" tabindex="21"><i class="fa fa-times" aria-hidden="true"></i> Reset</button>

        </div>
        </form>
    </div>
</div>


<script type="text/javascript">
$(document).ready(function(){

     return false;
});

</script>

<script type="text/javascript">
$(document).ready(function(){
    $('#data_nascita').datepicker();
});
</script>

<script type="text/javascript">
 $(document).ready(function(){
	$("#comune_a").autocomplete({

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
		//  alert(ui.item ? ("You picked '" + ui.item.label + "' with an ID of " + ui.item.istat) : "Nothing selected, input was " + this.value);
			$('#comune').val(ui.item.istat);
			$('#cap').val(ui.item.cap);
		//  console.log($('#comune').val + ' has faded!');
		},
		change: function (event, ui) {
		//  alert(ui.item ? ("You picked '" + ui.item.label + "' with an ID of " + ui.item.istat) : "Nothing selected, input was " + this.value);
			$('#comune').val(ui.item.istat);
			$('#cap').val(ui.item.cap);
		//  console.log($('#comune').val+'has faded!')
		}

	});

    $("#comune_n").autocomplete({
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
        //  alert(ui.item ? ("You picked '" + ui.item.label + "' with an ID of " + ui.item.istat) : "Nothing selected, input was " + this.value);
            $('#comune_nascita').val(ui.item.istat);
            $('#cap').val(ui.item.cap);
        //  console.log($('#comune').val + ' has faded!');
        },
        change: function (event, ui) {
        //  alert(ui.item ? ("You picked '" + ui.item.label + "' with an ID of " + ui.item.istat) : "Nothing selected, input was " + this.value);
            $('#comune_nascita').val(ui.item.istat);
            $('#cap').val(ui.item.cap);
        //  console.log($('#comune').val+'has faded!')
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
                    num_struttura: { required: true, regex: /^[0-9]+$/ },
                    nome_emporio: { required: true, regex: /^[0-9 A-Za-z\'àèìòù\.&]+$/ },
                    cognome_responsabile: { required: true ,regex: /^[0-9 A-Za-z\'àèìòù]+$/ },
                    nome_resposabile: { required: true, regex: /^[0-9 A-Za-z\'àèìòù]+$/ },
                    data_nascita: { required: true, regex: /^(((0[1-9]|[12]\d|3[01])\/(0[13578]|1[02])\/((19|[2-9]\d)\d{2}))|((0[1-9]|[12]\d|30)\/(0[13456789]|1[012])\/((19|[2-9]\d)\d{2}))|((0[1-9]|1\d|2[0-8])\/02\/((19|[2-9]\d)\d{2}))|(29\/02\/((1[6-9]|[2-9]\d)(0[48]|[2468][048]|[13579][26])|((16|[2468][048]|[3579][26])00))))$/ },
                    comune_n: { required: true },
                    nome_associazione: { required: true, regex: /^[0-9 A-Za-z\'àèìòù\-_@&]+$/ },
                    indirizzo_associazione: { required: true, regex: /^[0-9 A-Za-z\'\.\,àèìòù]+$/ },
                    localita: { required: false, regex: /^[0-9 A-Za-z\'\.àèìòù]+$/ },
                    comune_a: { required: true },
                    telefono: { required: false, regex: /^[0-9]+$/ },
                    email: { required: false, regex: /^[\w\-\.]*[\w\.]\@[\w\.]*[\w\-\.]+[\w\-]+[\w]\.+[\w]+[\w $]/ }
                                        
                },
        highlight: function (element) { $(element).addClass('input-error'); },
        unhighlight: function(element) { $(element).removeClass('input-error'); },
        success: function (element) { $(element).removeClass('input-error'); },
        errorPlacement: function (error, element) { },
        submitHandler: function(form) { form.submit(); }
        });
});

</script>
