<?php include("_navbar.php"); ?>

<div class="col-sm-12 col-md-12 col-lg-12">
    <div class="row">
        <div class="col-sm-9 col-md-9 col-lg-10 col-main">
			<div style="float:left; height:100%; width:100%">

				<div class="panel panel-default"  style="height:100%; background:#FFFFFF">
					<div class="panel-heading"><i class="fa fa-list" aria-hidden="true"></i> <strong>VENDUTO 
                        <?php
                            setlocale(LC_ALL, 'it_IT.UTF-8');
                            echo strtoupper( strftime( '%B', mktime( 0, 0, 0, $mese + 1, 0, 0, 0 ) ))." ",$anno; 
                        ?> - <?php echo strtoupper($tipo); ?></strong></div>
						<div class="panel-body" style="height:93%; overflow-y: auto;">
							<table id="tab_venduto" class="table table-responsive table-hover table-striped">
								<thead>
                                  <tr>
                                    <th style="display:none">&nbsp;</th>
									<th >Data</th>
                                    <?php if($tipo == "famiglie") { ?>
									<th >Famiglia</th>
									<th >Pti fam.</th>
                                    <?php } ?>
									<th >Barcode</th>
									<th >Descrizione</th>
									<th >Tipologia</th>
									<th >Categoria</th>
									<th >Pti</th>
									<th >Qt&agrave;</th>
                                    <?php if($tipo == "famiglie") { ?>
									<th >Tot</th>
                                    <?php } ?>
                                  </tr>
								</thead>
								<tbody>
											
									<?php 
										foreach($ris as $key=>$val) {
                                            if($val['scontato']) {
                                                $offerta = "<span class='label label-success' style='font-size:14px;'>
                                                        <a style='color:inherit; cursor:pointer; font-size:14px' data-toggle='tooltip' 
                                                        data-placement='bottom' title='Prodotto in offerta'><i class='fa fa-gift'></i></a></span>"; 
                                               /* $offerta = "<span class='fa-stack' style='cursor:pointer;' data-toggle='tooltip'>
                                                        <i class='fa fa-square fa-stack-2x' style='color:#5cb85c;'></i>
                                                        <i class='fa fa-gift fa-stack-1x fa-inverse'></i></span>"; */
                                            }
                                            else { $offerta = ""; }

											print("<tr class='clickable-row' href1='' data-url='' id_fornitore='".$val['id_fornitore']."'>");
											print("<td style='display:none'>".$val['date']."</td>");
											print("<td>".date("d/m/Y", strtotime($val['date']))."</td>");
                                            if($tipo == "famiglie") {
                                                print("<td>".ucwords($val['cognome'])." ".ucwords($val['nome'])."</td>");
                                                print("<td>".$val['punti_totali']."</td>");
                                            }
											print("<td>".$val['barcode']."</td>");
										    print("<td>".$val['descrizione']."</td>");
											print("<td>".ucwords($val['descrizione_tipologia'])."</td>");
											print("<td>".ucwords($val['descrizione_categoria'])."</td>");
											print("<td>".$val['punti']." ".$offerta."</td>");
											print("<td>".$val['qta']."</td>");
                                            if($tipo == "famiglie") {
                                                print("<td>".$val['qta'] * $val['punti']."</td>");
                                            }

											print("</tr>");
										}
									?>

								</tbody>
							</table>
						</div>
				</div>
<!--				<div class="panel panel-info"  style="height:48%; background:#FFFFFF">
					<div class="panel-heading"><i class="fa fa-users" aria-hidden="true"></i> <strong>COMPONENTI FAMIGLIA</strong></div>
						<div class="panel-body" id="dati_membri" style="height:86%; overflow-y: auto;">
						</div>
				</div> -->
			</div>
        </div>
        <div class="col-sm-3 col-md-3 col-lg-2 col-right">&nbsp;

			<form id="cerca" method="POST" action="/report/venduto/<?php echo $tipo; ?>">
                <input type="hidden" name="cerca" id="cerca" value="cerca">
				<div class="form-group">
					<label for="cerca_donatore">Visualizza anno</label>
					<select class="form-control" name="anno" id="anno">
                    <?php 
                        if($ris_anno->rowCount() <= 0) {
                            print("<option value='".date("Y")."'>".date("Y")."</option>");
                        }
                        else {
                            foreach($ris_anno as $key=>$val_anno) {
                                print("<option value='".$val_anno['anno']."'>".$val_anno['anno']."</option>");
                            }
                        }
                    ?>
                    </select>
				</div>
                <div class="form-group">
                    <label for="cerca_donatore">Visualizza mese</label>
                    <select class="form-control" name="mese" id="mese">
                    <?php
                        for( $i = 1; $i <= 12; $i++ ) {
                            setlocale(LC_ALL, 'it_IT.UTF-8');
                            $month_num = str_pad( $i, 2, 0, STR_PAD_LEFT );
                            $month_name = strftime( '%B', mktime( 0, 0, 0, $i + 1, 0, 0, 0 ) );
                            if(date("m") == $month_num) { $selected = "selected"; } else { $selected = ""; }
                            print("<option value='".$month_num."' ".$selected.">".ucwords($month_name)."</option>");
                        }
                    ?>
                    </select>
                </div>

				<button type="submit" class="btn btn-info btn-sm btn-block"><i class="fa fa-search" aria-hidden="true"></i> Cerca</button>
			</form>

            <?php
                if($cerca == "1") {
            ?>
                <div>&nbsp;</div>
                <a class="btn btn-danger btn-sm btn-block" href="/report/venduto/<?php echo $tipo; ?>"><i class="fa fa-times" aria-hidden="true"></i> Azzera risultati ricerca</a>
            <?php
                }

                if($ris->rowCount() <= 0) {
                    $disabled = "disabled";
                }
                else {
                    $disabled = "";
                }
            ?>

			<div>&nbsp;</div>
			<div>&nbsp;</div>
			<a href="/report/esportavenduto/<?php echo $tipo; ?>/<?php echo $anno; ?>/<?php echo $mese; ?>" class="btn btn-default btn-sm btn-block" <?php echo $disabled; ?>>
                <i class="fa fa-file-excel-o fa-lg" aria-hidden="true"></i> Esporta dati</a>

        </div>
    </div>
</div>

<script type="text/javascript">
$('body').on('hidden.bs.modal', '.modal', function () {
    $(this).removeData('bs.modal');
});
</script>

<script type="text/javascript">
$(document).ready(function() {
    $('#tab_venduto').dataTable( {
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
