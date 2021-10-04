<?php 
	include("_navbar.php"); 
	include("script/functions.php");
?>

<div class="col-md-12">
    <div class="row">
        <div class="col-sm-9 col-md-9 col-lg-10 col-main">
			<div style="float:left; height:100%; width:100%;">

				<div class="panel panel-default"  style="height:100%; background:#FFFFFF">
					<div class="panel-heading"><i class="fa fa-file-text-o" aria-hidden="true"></i> <strong>AGEA - DICHIARAZIONE DI CONSEGNA</strong></div>
						<div class="panel-body" style="height:93%; overflow-y:auto; ">
			
							<?php if(isset($insertok) && $insertok == 1) { ?>
								<div class="alert alert-success">
									<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
									<strong>Inserimento riuscito</strong> 
								</div>
							<?php } 
								  else if(isset($insertok) && $insertok == 0) {
							?>
								<div class="alert alert-danger">
									<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                    <strong>Inserimento fallito</strong> 
                                </div>
							<?php
								  }
							?>
							
							<div class="well well-sm">
									<strong>Data report: </strong><span id="data_report" data_report="<?php echo $data_report; ?>" 
										class="label label-info" style="font-size:14px"><?php echo date("d/m/Y", strtotime($data_report)); ?></span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
									<strong>Indigenti forniti: </strong>
										<span class="label label-success" style="font-size:14px"><?php echo $indigenti; ?></span> &nbsp;&nbsp;(<strong><?php echo $clienti; ?></strong> famiglie totali)
							</div>

							<table id="tab_agea" class="table table-striped table-responsive">
								<thead>
									<th>Prodotto</th>
									<th>Unit&agrave; di misura</th>
									<th>Scarico</th>
									<th>Giacenza</th>
									<th>&nbsp;</th>
								</thead>
								<tbody>
								<?php 
									foreach($ris as $key=>$val) {
										print("<tr>");
										print("<td>".ucfirst($val['descrizione'])."</td>");
										print("<td>".$val['um_1']."</td>");
										print("<td>".$val['qta']."</td>");
										print("<td>".$val['stock']."</td>");
										print("<td>&nbsp;</td>");
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

			<form id="agea_dichiarazione" method="POST" action="">
			<div class="form-group">
            	<label for="exampleInputEmail1">Data report</label>
          		<div class="input-group date" data-provide="datepicker">
                    <input type="text" class="form-control" id="data" name="data" tabindex="6" value="<?php echo date("d/m/Y"); ?>">
                    <div class="input-group-addon">
                        <span class="fa fa-calendar"></span>
                    </div>
               	</div>
            </div>
			</form>

			<button type="submit" id="genera" class="btn btn-success btn-sm btn-block" tabindex="20"><i class="fa fa-search" aria-hidden="true"></i> Genera report</button>
			<div class="from-group">&nbsp;</div>
			<a href="" id="genera_word" class="btn btn-info btn-block" style="white-space: normal;" tabindex="20">
				<i class="fa fa-print" aria-hidden="true"></i> Stampa dichiarazione</a>

        </div>
    </div>
</div>

<script type="text/javascript">
$(document).ready(function(){
    $('#data').datepicker();
});
</script>

<script type="text/javascript">
$(document).ready(function(){
	$('#genera').bind('click', function() {
		var dd = $('#data').val();

		var d = new Date(dd.split("/").reverse().join("-"));
        month = '' + (d.getMonth() + 1);
        day = '' + d.getDate();
        year = d.getFullYear();

    	if (month.length < 2) month = '0' + month;
    	if (day.length < 2) day = '0' + day;

		data = year + '-' + month + '-' + day;

		$('#agea_dichiarazione').attr('action', '/agea/dichiarazione/' + data );
		$('#agea_dichiarazione').submit();
	});

	$('#genera_word').printPage({
		url: '/agea/dichiarazione_doc/' + $('#data_report').attr('data_report'),
		message: "Stampa dichiarazione di consegna in corso"
	});

/*	$('#genera_word').bind('click', function() {
		var dd = $('#data_report').attr('data_report');

        $('#genera_word').attr('href', '/agea/dichiarazione_doc/' + dd );
	}); */

     return false;
});
</script>

<script type="text/javascript">
$(document).ready(function() {
    $('#tab_agea').dataTable( {
        "scrollY": "550px",
        "scrollCollapse": false,
        "paging": false,
        "filter": false,
        "info": false,
        "language": {
            "infoEmpty": "Nessun prodotto trovato",
        }
    });
});
</script>
