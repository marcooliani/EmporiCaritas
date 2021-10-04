<?php include("_navbaradmin.php"); ?>

<div class="col-md-12">
    <div class="row">
        <div class="col-sm-9 col-md-9 col-lg-10 col-main">
			<div style="float:left; height:100%; width:100%;">

				<div class="panel panel-default"  style="height:100%; background:#FFFFFF">
					<div class="panel-heading"><i class="fa fa-tasks" aria-hidden="true"></i> <strong>SCALA CARBONARO </strong></div>
						<div class="panel-body" style="height:93%; overflow-y:auto; ">

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
                                                foreach($ris as $key=>$val) {
                                                    print('<tr>');
                                                    print('<td><strong>'.$val['famigliari'].'</strong></td>');
                                                    print('<td>'.$val['punti_famiglia'].'</td>');
                                                    print('<td>'.$val['coefficiente'].'</td>');
                                                    print('<td>'.$val['punti_corretti'].'</td>');
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
			<a href="/utilities/modificacarbonaro" class="btn btn-success btn-sm btn-block"><i class="fa fa-edit" aria-hidden="true"></i> Modifica</a>

        </div>
    </div>
</div>


<script type="text/javascript">
$(document).ready(function(){

     return false;
});

</script>

