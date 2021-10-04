<?php include("_navbar.php"); ?>

<div class="col-md-12">
    <div class="row">
        <div class="col-sm-9 col-md-9 col-lg-10 col-main">
            <div style="float:left; height:100%; width:100%">

                <div class="panel panel-default"  style="height:49%; background:#FFFFFF">
                    <div class="panel-heading"><i class="fa fa-list" aria-hidden="true"></i> <strong>DONAZIONI - 
                            <?php 
                                if(!empty($mese)) { 
                                    setlocale(LC_ALL, 'it_IT.UTF-8');
                                    echo strtoupper( strftime( '%B', mktime( 0, 0, 0, $mese + 1, 0, 0, 0 ) ))." ";
                                }
                                echo $anno;
                            ?></strong></div>
                        <div class="panel-body" style="height:86%; overflow-y: auto;">

                            <table id="tab_donazioni" class="table table-responsive table-hover table-striped">
                                <thead>
                                  <tr>
                                    <th style="display:none">&nbsp;</th>
                                    <th width="10%">Data</th>
                                    <th width="15%">Ragione Sociale</th>
                                    <th width="10%">Prodotto</th>
                                    <th width="20%">Descrizione</th>
                                    <th width="15%">Tipologia</th>
                                    <th width="15%">Categoria</th>
                                    <th width="5%">Qt&agrave;</th>
                                    <th>&nbsp;</th>
                                  </tr>
                                </thead>
                                <tbody>
								<?php
									foreach($ris_donazioni as $key=>$val) {
										print("<tr class='clickable-row' href='#dati_barcode' 
                                                data-url='/barcodes/ajax_barcodes' id_tipologia='".$val['id_tipologia']."'>");
										print("<td style='display:none'>".$val['data_donazione']."</td>");
										print("<td>".date("d/m/Y", strtotime($val['data_donazione']))."</td>");
										print("<td>".$val['ragione_sociale']."</td>");
										print("<td>".$val['barcode']."</td>");
										print("<td>".$val['descrizione']."</td>");
										print("<td>".ucwords($val['descrizione_tipologia'])."</td>");
										print("<td>".ucwords($val['descrizione_categoria'])."</td>");
										print("<td>".$val['quantita']."</td>");
										print("<td style='text-align:right'>&nbsp;</td>");
                                        print("</tr>");
									}
								?>
								</tbody>
							</table>
						</div>
				</div>
				<div class="panel panel-default" style="height:49%; background:#FFFFFF">
                    <div class="panel-heading"><i class="fa fa-list" aria-hidden="true"></i> <strong>ACQUISTI - 
                        <?php 
                                if(!empty($mese)) {
                                    setlocale(LC_ALL, 'it_IT.UTF-8');
                                    echo strtoupper( strftime( '%B', mktime( 0, 0, 0, $mese + 1, 0, 0, 0 ) ))." "; 
                                }
                                echo $anno;
                            ?></strong></div>
                        <div class="panel-body" id="dati_barcode" style="height:86%; overflow-y: auto;">
                            <table id="tab_acquisti" class="table table-responsive table-hover table-striped">
                                <thead>
                                  <tr>
                                    <th style="display:none">&nbsp;</th>
                                    <th width="10%">Data</th>
                                    <th width="15%">Ragione Sociale</th>
                                    <th width="10%">Prodotto</th>
                                    <th width="20%">Descrizione</th>
                                    <th width="15%">Tipologia</th>
                                    <th width="15%">Categoria</th>
                                    <th width="5%">Qt&agrave;</th>
                                    <th>&nbsp;</th>
                                  </tr>
                                </thead>
                                <tbody>
								<?php
									foreach($ris_acquisti as $key=>$val2) {
										print("<tr class='clickable-row' href='#dati_barcode' 
                                                data-url='/barcodes/ajax_barcodes' id_tipologia='".$val['id_tipologia']."'>");
										print("<td style='display:none'>".$val2['data_donazione']."</td>");
                                        print("<td>".date("d/m/Y", strtotime($val2['data_donazione']))."</td>");
                                        print("<td>".$val2['ragione_sociale']."</td>");
                                        print("<td>".$val2['barcode']."</td>");
                                        print("<td>".$val2['descrizione']."</td>");
                                        print("<td>".ucwords($val2['descrizione_tipologia'])."</td>");
                                        print("<td>".ucwords($val2['descrizione_categoria'])."</td>");
                                        print("<td>".$val2['quantita']."</td>");
										print("<td style='text-align:right'>&nbsp;</td>");
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

            <form id="cerca" method="POST" action="/report/donazioni/<?php echo $tipo; ?>">
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
                        <option value=""> --- </option>
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
                <a class="btn btn-danger btn-sm btn-block" href="/report/donazioni/<?php echo $tipo; ?>"><i class="fa fa-times" aria-hidden="true"></i> Azzera risultati ricerca</a>
            <?php
                }

                if($ris_donazioni->rowCount() <= 0) { $disabled_donazioni = "disabled"; }
                else { $disabled_donazioni = ""; }
                if($ris_acquisti->rowCount() <= 0) { $disabled_acquisti = "disabled"; }
                else { $disabled_acquisti = ""; }
            ?>

            <div>&nbsp;</div>
            <div>&nbsp;</div>
            <a href="/report/esportadonazioni/donazioni/<?php echo $anno; ?>/<?php echo $mese; ?>" class="btn btn-default btn-sm btn-block" <?php echo $disabled_donazioni; ?>>
                <i class="fa fa-file-excel-o fa-lg" aria-hidden="true"></i> Esporta dati donazioni</a>
            <a href="/report/esportadonazioni/acquisti/<?php echo $anno; ?>/<?php echo $mese; ?>" class="btn btn-default btn-sm btn-block" <?php echo $disabled_acquisti; ?>>
                <i class="fa fa-file-excel-o fa-lg" aria-hidden="true"></i> Esporta dati acquisti</a>

        </div>
    </div>
</div>

<script type="text/javascript">
$(document).ready(function() {
    $('#tab_donazioni').dataTable( {
        "order": [[ 0, "desc" ]],
        "scrollY": "280px",
        "scrollCollapse": false,
        "paging": false,
        "filter": false,
        "info": false,
        "language": {
            "infoEmpty": "Nessun prodotto trovato",
        }
    });

    $('#tab_acquisti').dataTable( {
        "order": [[ 0, "desc" ]],
        "scrollY": "280px",
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
