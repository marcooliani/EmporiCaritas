<?php include("_navbar.php"); ?>

<div class="col-md-12">
    <div class="row">
        <div class="col-sm-9 col-md-9 col-lg-10 col-main">
			<div style="float:left; height:100%; width:100%;">

				<div class="panel panel-info"  style="height:100%; background:#FFFFFF">
					<div class="panel-heading"><i class="fa fa-ban" aria-hidden="true"></i> <strong>MODIFICA LIMITE </strong></div>
						<div class="panel-body" style="height:93%; overflow-y:auto; ">

							<div style="font-size:12px; padding-bottom:10px;"><i>(*) = Campi obbligatori</i></div>
							<form id="modifica_limite" method="POST" action="/limiti/update_limite/<?php echo $categoria; ?>">
							<?php $val = $ris->fetch(); ?>
							<div class="well well-sm">
							  <div class="row">
								<div class="col-sm-4 col-md-3 col-lg-3">
									<div class="form-group">
										<label for="exampleInputEmail1">Categoria *</label>
										<input type="text" class="form-control" tabindex="2" id="id_categoria" name="id_categoria"
											value="<?php echo ucwords($val['descrizione_categoria']); ?>" disabled>
										</select>
									</div>
								</div>
								<div class="col-sm-8 col-md-8 col-lg-8">&nbsp;
								</div>
							  </div>
                            </div>

							<div class="row">&nbsp;</div>

							<div class="row">

								<div class="col-sm-12 col-md-12 col-lg-12">
								  <div class="form-group">
									<table class="table table-responsive" style="border: 1px solid #ddd">
										<thead>
										</thead>
										<tbody>
											<tr class='info'>
                                                <th >Num. famigliari</th>
                                                <th id='1c' scope='col' style='width:7%; text-align:center'> 1 </th>
                                                <th id='2c' scope='col' style='width:7%; text-align:center'> 2 </th>
                                                <th id='3c' scope='col' style='width:7%; text-align:center'> 3 </th>
                                                <th id='4c' scope='col' style='width:7%; text-align:center'> 4 </th>
                                                <th id='5c' scope='col' style='width:7%; text-align:center'> 5 </th>
                                                <th id='6c' scope='col' style='width:7%; text-align:center'> 6 </th>
                                                <th id='7c' scope='col' style='width:7%; text-align:center'> 7 </th>
                                                <th id='8c' scope='col' style='width:7%; text-align:center'> 8 </th>
                                                <th id='9c' scope='col' style='width:7%; text-align:center'> 9 </th>
                                                <th id='10c' scope='col' style='width:7%; text-align:center'> 10 </th>
												<th>&nbsp;</th>
											</tr>
											<tr>
												<th id='lim_spesa' class='info'>Limite spesa *</th>
                                                <td> <input type="text" class="form-control" id="lim_1c_spesa" name="lim_1c_spesa" value="<?php echo $val['lim_1c_spesa'] ?>"> </td>
                                                <td> <input type="text" class="form-control" id="lim_2c_spesa" name="lim_2c_spesa" value="<?php echo $val['lim_2c_spesa'] ?>"> </td>
                                                <td> <input type="text" class="form-control" id="lim_3c_spesa" name="lim_3c_spesa" value="<?php echo $val['lim_3c_spesa'] ?>"> </td>
                                                <td> <input type="text" class="form-control" id="lim_4c_spesa" name="lim_4c_spesa" value="<?php echo $val['lim_4c_spesa'] ?>"> </td>
                                                <td> <input type="text" class="form-control" id="lim_5c_spesa" name="lim_5c_spesa" value="<?php echo $val['lim_5c_spesa'] ?>"> </td>
                                                <td> <input type="text" class="form-control" id="lim_6c_spesa" name="lim_6c_spesa" value="<?php echo $val['lim_6c_spesa'] ?>"> </td>
                                                <td> <input type="text" class="form-control" id="lim_7c_spesa" name="lim_7c_spesa" value="<?php echo $val['lim_7c_spesa'] ?>"> </td>
                                                <td> <input type="text" class="form-control" id="lim_8c_spesa" name="lim_8c_spesa" value="<?php echo $val['lim_8c_spesa'] ?>"> </td>
                                                <td> <input type="text" class="form-control" id="lim_9c_spesa" name="lim_9c_spesa" value="<?php echo $val['lim_9c_spesa'] ?>"> </td>
                                                <td> <input type="text" class="form-control" id="lim_10c_spesa" name="lim_10c_spesa" value="<?php echo $val['lim_10c_spesa'] ?>"> </td>
												<td style='width:10%'> &nbsp; </td>
                                            </tr>
                                            <tr>
                                                <th id='lim_mese' class='info'>Limite mensile *</th>
												<td> <input type="text" class="form-control" id="lim_1c_mese" name="lim_1c_mese" value="<?php echo $val['lim_1c_mese'] ?>"> </td>
                                                <td> <input type="text" class="form-control" id="lim_2c_mese" name="lim_2c_mese" value="<?php echo $val['lim_2c_mese'] ?>"> </td>
                                                <td> <input type="text" class="form-control" id="lim_3c_mese" name="lim_3c_mese" value="<?php echo $val['lim_3c_mese'] ?>"> </td>
                                                <td> <input type="text" class="form-control" id="lim_4c_mese" name="lim_4c_mese" value="<?php echo $val['lim_4c_mese'] ?>"> </td>
                                                <td> <input type="text" class="form-control" id="lim_5c_mese" name="lim_5c_mese" value="<?php echo $val['lim_5c_mese'] ?>"> </td>
                                                <td> <input type="text" class="form-control" id="lim_6c_mese" name="lim_6c_mese" value="<?php echo $val['lim_6c_mese'] ?>"> </td>
                                                <td> <input type="text" class="form-control" id="lim_7c_mese" name="lim_7c_mese" value="<?php echo $val['lim_7c_mese'] ?>"> </td>
                                                <td> <input type="text" class="form-control" id="lim_8c_mese" name="lim_8c_mese" value="<?php echo $val['lim_8c_mese'] ?>"> </td>
                                                <td> <input type="text" class="form-control" id="lim_9c_mese" name="lim_9c_mese" value="<?php echo $val['lim_9c_mese'] ?>"> </td>
                                                <td> <input type="text" class="form-control" id="lim_10c_mese" name="lim_10c_mese" value="<?php echo $val['lim_10c_mese'] ?>"> </td>
                                                <td style='width:10%'> &nbsp; </td>
											</tr>
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
			<button type="submit" class="btn btn-success btn-sm btn-block"><i class="fa fa-save" aria-hidden="true"></i> Salva Modifiche</button>
			<button type="reset" class="btn btn-danger btn-sm btn-block"><i class="fa fa-times" aria-hidden="true"></i> Reset</button>
			</form>

            <div>&nbsp;</div>
            <div>&nbsp;</div>
            <a class="btn btn-info btn-sm btn-block" href="/limiti/index"><i class="fa fa-list" aria-hidden="true"></i> Torna all'elenco</a>

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
                    id_categoria: { required: true },
                    lim_1c_spesa: { required: true, regex: /^[0-9]+$/ },
                    lim_2c_spesa: { required: true, regex: /^[0-9]+$/ },
                    lim_3c_spesa: { required: true, regex: /^[0-9]+$/ },
                    lim_4c_spesa: { required: true, regex: /^[0-9]+$/ },
                    lim_5c_spesa: { required: true, regex: /^[0-9]+$/ },
                    lim_6c_spesa: { required: true, regex: /^[0-9]+$/ },
                    lim_7c_spesa: { required: true, regex: /^[0-9]+$/ },
                    lim_8c_spesa: { required: true, regex: /^[0-9]+$/ },
                    lim_9c_spesa: { required: true, regex: /^[0-9]+$/ },
                    lim_10c_spesa: { required: true, regex: /^[0-9]+$/ },
                    lim_1c_mese: { required: true, regex: /^[0-9]+$/ },
                    lim_2c_mese: { required: true, regex: /^[0-9]+$/ },
                    lim_3c_mese: { required: true, regex: /^[0-9]+$/ },
                    lim_4c_mese: { required: true, regex: /^[0-9]+$/ },
                    lim_5c_mese: { required: true, regex: /^[0-9]+$/ },
                    lim_6c_mese: { required: true, regex: /^[0-9]+$/ },
                    lim_7c_mese: { required: true, regex: /^[0-9]+$/ },
                    lim_8c_mese: { required: true, regex: /^[0-9]+$/ },
                    lim_9c_mese: { required: true, regex: /^[0-9]+$/ },
                    lim_10c_mese: { required: true, regex: /^[0-9]+$/ },
                                        
                },
        highlight: function (element) { $(element).addClass('input-error'); },
        unhighlight: function(element) { $(element).removeClass('input-error'); },
        success: function (element) { $(element).removeClass('input-error'); },
        errorPlacement: function (error, element) { },
        submitHandler: function(form) { form.submit(); }
        });
});

</script>

