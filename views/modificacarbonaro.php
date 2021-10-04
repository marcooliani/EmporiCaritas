<?php include("_navbaradmin.php"); ?>

<div class="col-md-12">
    <div class="row">
        <div class="col-sm-9 col-md-9 col-lg-10 col-main">
			<div style="float:left; height:100%; width:100%;">

				<div class="panel panel-default"  style="height:100%; background:#FFFFFF">
					<div class="panel-heading"><i class="fa fa-tasks" aria-hidden="true"></i> <strong>MODIFICA SCALA CARBONARO </strong></div>
						<div class="panel-body" style="height:93%; overflow-y:auto; ">

							<form id="nuova_prodotto" method="POST" action="/utilities/update_carbonaro">

							  <div class="row">

								<div class="col-sm-12 col-md-12 col-lg-12">
								  <div class="form-group">
									<table class="table table-responsive table-striped">
										<thead>
                                            <th width="20%">Num. Famigliari</th>
                                            <th width="20%">Punti</th>
                                            <th width="20%">Coefficiente</th>
                                            <th width="20%">Punti corretti</th>
                                            <th>&nbsp;</th>
										</thead>
										<tbody>
                                            <?php
                                                $i=1;
                                                foreach($ris as $key=>$val) {
                                                    print('<tr>');
                                                    print('<td><strong>'.$val['famigliari'].'</strong></td>');
                                                    print('<input type="hidden" id="famigliari[]" name="famigliari[]" value="'.$val['famigliari'].'">');
                                                    print('<td><input type="text" class="form-control" name="punti[]" id="punti[]" value="'.$val['punti_famiglia'].'"></td>');
                                                    print('<td><input type="text" class="form-control" name="coefficiente[]" id="coefficiente[]" value="'.$val['coefficiente'].'"></td>');
                                                    print('<td><input type="text" class="form-control" name="punti_corretti[]" id="punti_corretti[]" value="'.$val['punti_corretti'].'"></td>');
                                                    print('<td>&nbsp;</td>');
                                                    print('</tr>');

                                                    $punti_last = $val['punti_famiglia'];
                                                    $coeff_last = $val['coefficiente'];
                                                    $punti_corr_last = $val['punti_corretti'];

                                                    $i++;
                                                }

                                                for($k=$i; $k<=10; $k++) {
                                                    print('<tr>');
                                                    print('<td><strong>'.$k.'</strong></td>');
                                                    print('<input type="hidden" id="famigliari[]" name="famigliari[]" value="'.$k.'">');
                                                    print('<td><input type="text" class="form-control" name="punti[]" id="punti[]" value="'.$punti_last.'"></td>');
                                                    print('<td><input type="text" class="form-control" name="coefficiente[]" id="coefficiente[]" value="'.$coeff_last.'"></td>');
                                                    print('<td><input type="text" class="form-control" name="punti_corretti[]" id="punti_corretti[]" value="'.$punti_corr_last.'"></td>');
                                                    print('<td>&nbsp;</td>');
                                                    print('</tr>');
                                                }
                                            ?>
										</tbody>
									</table>
								  </div>
								</div>
	

							  </div>

						</div>
				</div>
			</div>


        </div>
        <div class="col-sm-3 col-md-3 col-lg-2 col-right">&nbsp;

			<div>&nbsp;</div>
			<button type="submit" class="btn btn-success btn-sm btn-block"><i class="fa fa-save" aria-hidden="true"></i> Salva modifiche</button>
			<button type="reset" class="btn btn-danger btn-sm btn-block"><i class="fa fa-times" aria-hidden="true"></i> Reset</button>
			</form>

            <div>&nbsp;</div>
            <div>&nbsp;</div>
            <a class="btn btn-info btn-sm btn-block" href="/utilities/scalacarbonaro"><i class="fa fa-th" aria-hidden="true"></i> Torna alla tabella</a>

        </div>
    </div>
</div>


<script type="text/javascript">
$(document).ready(function(){

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
                    'punti[]': { required: true, regex: /^[0-9]+$/ },
                    'coefficiente[]': { required: true, regex: /^[0-9]\d*(\.[0-9]+)?$/ },
                    'punti_corretti[]': { required: true, regex: /^[0-9]+$/ },
                    
        },
        highlight: function (element) { $(element).addClass('input-error'); },
        unhighlight: function(element) { $(element).removeClass('input-error'); },
        success: function (element) { $(element).removeClass('input-error'); },
        errorPlacement: function (error, element) { },
        submitHandler: function(form) { form.submit(); }
    });
});

</script>
