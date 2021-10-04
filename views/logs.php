<?php include("_navbaradmin.php"); ?>

<div class="col-md-12">
    <div class="row">
        <div class="col-sm-9 col-md-9 col-lg-10 col-main">
			<div style="float:left; height:100%; width:100%">

			<!--	<div class="panel panel-info"  style="height:49%; background:#FFFFFF"> -->
				<div class="panel panel-default"  style="height:100%; background:#FFFFFF">
					<div class="panel-heading"><i class="fa fa-list" aria-hidden="true"></i> <strong>LOGS</strong></div>
						<!-- <div class="panel-body" style="height:86%; overflow-y: auto;"> -->
						<div class="panel-body" style="height:93%; overflow-y: auto;">
									<table id="tab_logs" class="table table-responsive table-hover table-striped">
										<thead>
                                          <tr>
											<th style="display:none;">&nbsp;</th>
											<th>Data</th>
											<th>Tipo</th>
											<th>Descrizione</th>
											<th>Utente</th>
                                          </tr>
										</thead>
										<tbody>
											<?php
												foreach($ris as $key=>$val) {
                                                    if($val['login'] == "admin") {
                                                        $val['login'] = "SYSTEM";
                                                    }
													print("<tr class='clickable-row' href='' data-url='' id_tipologia='".$val['log_id']."'>");
													print("<td style='display:none'>".$val['log_data']."</td>");
													print("<td>".date("d/m/Y H:i:s", strtotime($val['log_data']))."</td>");
													print("<td>".$val['flag']."</td>");
													print("<td>".$val['log_descrizione']."</td>");
													print("<td>".$val['login']."</td>");
													print("</tr>");
												}
											?>
										</tbody>
									</table>
						</div>
				</div>

				<!--
				<div class="panel panel-info" style="height:49%; background:#FFFFFF">
                    <div class="panel-heading"><i class="fa fa-barcode" aria-hidden="true"></i> <strong>DETTAGLIO PRODOTTI</strong></div>
                        <div class="panel-body" id="dati_barcode" style="height:86%; overflow-y: auto;">
                        </div>
                </div>
				-->
			</div>

        </div>
        <div class="col-sm-3 col-md-3 col-lg-2 col-right">&nbsp;

			<form id="cerca" method="POST" action="/logs/cerca">
            <div class="form-group">
                <label>Cerca per intervallo di date</label>
                <div class="input-group date" data-provide="datepicker">
                    <input type="text" class="form-control" id="date-start" name="date-start" placeholder="Data inizio intervallo">
                    <div class="input-group-addon">
                        <span class="fa fa-calendar"></span>
                    </div>
                </div>
                <div class="input-group date" data-provide="datepicker">
                    <input type="text" class="form-control" name="date-end" id="date-end" placeholder="Data fine intervallo">
                    <div class="input-group-addon">
                        <span class="fa fa-calendar"></span>
                    </div>
                </div>

                <div>&nbsp;</div>

                <div class="form-group">
                    <label>Cerca per tipo</label><br>
                    <select class="form-control multi" id="tipo" name="tipo[]" multiple>
                        <?php
                            foreach($ris_tipo as $key=>$val_tipo) {
                                print("<option value='".$val_tipo['flag']."'>".$val_tipo['flag']."</option>'");
                            }
                        ?>
                    </select>
                </div>

                <div class="form-group">
                    <label>Cerca per utente</label><br>
                    <select class="form-control" id="utente" name="utente">
                        <option value="" selected disabled> -- Seleziona -- </option>
                        <?php
                            foreach($ris_login as $key=>$val_login) {
                                if($val_login['login'] == "admin") { $user = "SYSTEM"; }
                                else { $user = $val_login['login']; }
                                print("<option value='".$val_login['login']."'>".$user."</option>'");
                            }
                        ?>
                    </select>
                </div>

                <div class="form-group">
                    <label>Cerca nella descrizione</label>
                    <input type="text" class="form-control" id="descrizione" name="descrizione" placeholder="Inserire testo">
                </div>
            </div>

				<button class="btn btn-info btn-sm btn-block"><i class="fa fa-search" aria-hidden="true"></i> Cerca</button>

            <?php
                if($cerca == "1") {
            ?>
                <div>&nbsp;</div>
                <a class="btn btn-danger btn-sm btn-block" href="/logs/index"><i class="fa fa-times" aria-hidden="true"></i> Azzera risultati ricerca</a>
            <?php
                }
            ?>
			</form>

			<div>&nbsp;</div>

        </div>
    </div>
</div>

<script type="text/javascript">
$(document).ready(function(){
    $('#date-start').datepicker();
    $('#date-end').datepicker();
});

</script>

<script type="text/javascript">
$(document).ready(function() {
    $('#tab_logs').dataTable( {
        "order": [[ 0, "desc" ]],
        "scrollY": "710px",
        "scrollCollapse": false,
        "paging": false,
        "filter": false,
        "info": false,
        "language": {
            "infoEmpty": "Nessuna categoria trovata",
        }
    });
});
</script>

<script type="text/javascript">
$(".multi").select2({ placeholder: "-- Seleziona --" });
</script>
