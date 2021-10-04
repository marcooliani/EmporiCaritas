<?php include("_navbaradmin.php"); ?>

<div class="col-md-12">
    <div class="row">
        <div class="col-sm-9 col-md-9 col-lg-10 col-main">
			<div style="float:left; height:100%; width:100%;">

				<div class="panel panel-default"  style="height:100%; background:#FFFFFF">
					<div class="panel-heading"><i class="fa fa-info" aria-hidden="true"></i> <strong>DATI EMPORIO</strong></div>
						<div class="panel-body" style="height:93%; overflow-y:auto; ">

							<?php $val = $ris->fetch(); ?>
							<div class="well well-sm">
						      <div class="row">

								<div class="col-sm-4 col-md-4 col-lg-4">
									<div class="form-group">
										<label for="exampleInputEmail1">ID emporio</label><br>
										<?php echo ucwords($val['id_emporio']); ?>
									</div>
								</div>

								<div class="col-sm-4 col-md-4 col-lg-4">
									<div class="form-group">
										<label for="exampleInputEmail1">Nome emporio</label><br>
										<?php echo ucwords($val['nome_emporio']); ?> 
									</div>
								</div>

								<div class="col-sm-4 col-md-4 col-lg-4">
									<div class="form-group">
										<label for="exampleInputPassword1">ID struttura</label><br>
										<?php echo ucwords($val['num_struttura']); ?>
									</div>
								</div>

							  </div>
						</div>

						<div class="well well-sm">			
                            <div class="row">
                                <div class="col-sm-4 col-md-4 col-lg-4">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Responsabile amministrativo</label><br>
                                        <?php echo ucwords($val['cognome_responsabile']). " " . ucwords($val['nome_responsabile']); ?>
                                    </div>
                                </div>
                                <div class="col-sm-4 col-md-4 col-lg-4">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Data di nascita</label><br>
                                        <?php echo date("d/m/Y", strtotime($val['data_nascita'])); ?>
                                    </div>
                                </div>
                                <div class="col-sm-4 col-md-4 col-lg-4">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Comune di nascita</label><br>
                                        <?php echo ucwords($val['comune_n']). " (" .strtoupper($val['provincia_n']). ")"; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
						
                        <div class="well well-sm">
                            <div class="row">
                                <div class="col-sm-3 col-md-3 col-lg-3">
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">Nome associazione</label><br>
                                            <?php echo ucwords($val['nome_associazione']); ?>
                                        </div>
                                </div>
                            </div>

							<div class="row">
                                <div class="col-sm-4 col-md-4 col-lg-4">
										<div class="form-group">
											<label for="exampleInputEmail1">Indirizzo</label><br>
											<?php echo ucwords($val['indirizzo_associazione']); ?>
										</div>
								</div>
								<div class="col-sm-4 col-md-4 col-lg-4">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Localit&agrave;</label><br>
                                        <?php echo ucwords($val['localita']); ?>
                                    </div>
								</div>
								<div class="col-sm-4 col-md-4 col-lg-4">
									<div class="form-group">
										<label for="exampleInputEmail1">Comune</label><br>
										<?php echo ucwords($val['comune_a']). " (".strtoupper($val['provincia_a']).")"; ?>
									</div>
								</div>
							</div>
                            <div class="row">
                                <div class="col-sm-4 col-md-4 col-lg-4">
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">Telefono</label><br>
                                            <?php echo $val['telefono']; ?>
                                        </div>
                                </div>
                                <div class="col-sm-4 col-md-4 col-lg-4">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Email</label><br>
                                        <a href="mailto:<?php echo $val['email']; ?>"><?php echo $val['email']; ?></a>
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
			<a href="/utilities/modificaemporio" class="btn btn-success btn-sm btn-block" tabindex="20"><i class="fa fa-edit" aria-hidden="true"></i> Modifica dati</a>

        </div>
    </div>
</div>


<script type="text/javascript">
$(document).ready(function(){

     return false;
});

</script>

<script type="text/javascript">
$(document).ready(function(){
    $('#iscrittoda').datepicker();
</script>

<script type="text/javascript">
 $(document).ready(function(){
	$("#comune_nome").autocomplete({

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
                    ragione_sociale: { required: true, regex: /^[0-9 A-Za-z\'àèìòù\.&]+$/ },
                    cognome: { required: false ,regex: /^[0-9 A-Za-z\'àèìòù\.&]+$/ },
                    nome: { required: false, regex: /^[0-9 A-Za-z\'àèìòù]+$/ },
                    indirizzo: { required: false, regex: /^[0-9 A-Za-z\'\.\,àèìòù]+$/ },
                    localita: { required: false, regex: /^[0-9 A-Za-z\'\.àèìòù]+$/ },
                    cap: { required: false, regex: /^[0-9]{5}$/ },
                    comune: { required: false },
                    telefono: { required:false, regex: /^[0-9]+$/ },
                    cellulare: { required: false, regex: /^[0-9]{10}$/ },
                    email: { required: false, regex: /^[\w\-\.]*[\w\.]\@[\w\.]*[\w\-\.]+[\w\-]+[\w]\.+[\w]+[\w $]/ },
                    donatore: { required: true },
                    iscrittoda: { required: true, regex: /^(((0[1-9]|[12]\d|3[01])\/(0[13578]|1[02])\/((19|[2-9]\d)\d{2}))|((0[1-9]|[12]\d|30)\/(0[13456789]|1[012])\/((19|[2-9]\d)\d{2}))|((0[1-9]|1\d|2[0-8])\/02\/((19|[2-9]\d)\d{2}))|(29\/02\/((1[6-9]|[2-9]\d)(0[48]|[2468][048]|[13579][26])|((16|[2468][048]|[3579][26])00))))$/ },
                    note: { required: false },
                                        
                },
        highlight: function (element) { $(element).addClass('input-error'); },
        unhighlight: function(element) { $(element).removeClass('input-error'); },
        success: function (element) { $(element).removeClass('input-error'); },
        errorPlacement: function (error, element) { },
        submitHandler: function(form) { form.submit(); }
        });
});

</script>
