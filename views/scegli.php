<style>
<?php
    if($_SESSION['ruolo'] == "superlocale" || $_SESSION['ruolo'] == "super") {
        $col = 3;
        echo ".login-block-opt {
                text-align: center;
                left: 30%;
                margin-left: -215px;
                margin-top: -170px;
                position: absolute;
                top: 45%;
                width: 1100px;
                background: #fff;
                border-radius: 5px;";
    }
    else {
        $col = 4;
        echo ".login-block-opt {
                text-align: center;
                left: 35%;
                margin-left: -215px;
                margin-top: -170px;
                position: absolute;
                top: 45%;
                width: 900px;
                background: #fff;
                border-radius: 5px;";
    }
?>

.login-block-opt h1 {
    text-align: center;
    color: #000;
    font-size: 18px;
    text-transform: uppercase;
    margin-top: 0;
    margin-bottom: 20px;
}

.col-sm-3, 
.col-md-3, 
.col-lg-3, 
.col-sm-4, 
.col-md-4, 
.col-lg-4 {
    border: solid 2px;
    text-align: center:
}
</style>

<div class="col-sm-12 col-md-12 col-lg-12">
	<div class="row">

		<div class="login-block-opt">
			<h1><i class="fa fa-user"></i> Scegli visualizzazione</h1>
			<div>&nbsp;</div>
			<div>&nbsp;</div>

            <div class="col-sm-<?php echo $col; ?> col-md-<?php echo $col; ?> col-lg-<?php echo $col; ?>" >
                <a href="/accettazione/index" class="btn-circle btn-default btn-xl" style="text-decoration:none;"><i class="fa fa-user"></i> ACCETTAZIONE</a>
            </div>

			<div class="col-sm-<?php echo $col; ?> col-md-<?php echo $col; ?> col-lg-<?php echo $col; ?>">
				<a href="/cassa/index" class="btn-circle btn-success btn-xl" style="text-decoration:none;"><i class="fa fa-shopping-cart"></i> CASSA</a>
			</div>
	
			<div class="col-sm-<?php echo $col; ?> col-md-<?php echo $col; ?> col-lg-<?php echo $col; ?>">
				<a href="/barcodes/index" class="btn-circle btn-info btn-xl" style="text-decoration:none;"><i class="fa fa-database"></i> BACKOFFICE</a>
			</div>

			<?php
				if($_SESSION['ruolo'] == "superlocale" || $_SESSION['ruolo'] == "super") {
                    print('<div class="col-sm-'.$col.' col-md-'.$col.' col-lg-'.$col.'">');
                    print('<a href="/utenti/index" class="btn-circle btn-danger btn-xl" style="text-decoration:none;"><i class="fa fa-dashboard"></i> ADMIN</a>');
                    print('</div>');
				}
			?>
		</div>
	
		<div>
		</div>

    </div>
</div>
