<?php include("_navbaradmin.php"); ?>

<div class="col-md-12">
    <div class="row">
        <div class="col-sm-9 col-md-9 col-lg-10 col-main">
			<div style="float:left; height:100%; width:100%;">

            <form action="/settings/salva" method="POST">
				<div class="panel panel-default"  style="height:100%; background:#FFFFFF">
					<div class="panel-heading"><i class="fa fa-wrench" aria-hidden="true"></i> <strong>IMPOSTAZIONI</strong></div>
					<div class="panel-body" style="height:93%; overflow-y:auto; ">

                        <h4><i class="fa fa-print" aria-hidden="true"></i> Stampante</h4>
                        <div class="well well-sm">
                            <div class="row">
                                <div class="col-sm-4 col-md-4 col-lg-4">
                                    <div class="form-group">
                                        <label>Tipo carta: &nbsp;</label>
                                            <label class="radio-inline">
                                                <input type="radio" name="tipo_carta" value="continua" <?php if($paper == "continua") echo "checked"; ?>>Continua
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="tipo_carta" value="a4" <?php if($paper == "a4") echo "checked"; ?>>A4
                                            </label>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-4 col-md-4 col-lg-4">
                                    <div class="form-group">
                                        <label>Dimensione carattere: &nbsp;</label>
                                            <label class="radio-inline" style="font-size:6pt; height: 12pt; vertical-align:middle">
                                                <input type="radio" name="fontsize" value="small" <?php if($font == "6pt") echo "checked"; ?>>Piccolo
                                            </label>
                                            <label class="radio-inline" style="font-size:8pt; height: 12pt; vertical-align:middle">
                                                <input type="radio" name="fontsize" value="medium" <?php if($font == "8pt") echo "checked"; ?>>Medio
                                            </label>
                                            <label class="radio-inline" style="font-size:12pt; height: 12pt; vertical-align:middle">
                                                <input type="radio" name="fontsize" value="big" <?php if($font == "12pt") echo "checked"; ?>>Grande
                                            </label>
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
			<button type="submit" class="btn btn-success btn-sm btn-block" tabindex="20"><i class="fa fa-save" aria-hidden="true"></i> Salva impostazioni</button>
			<button type="reset" class="btn btn-danger btn-sm btn-block" tabindex="20"><i class="fa fa-times" aria-hidden="true"></i> Reset</button>

        </div>
        </form>
    </div>
</div>


