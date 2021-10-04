<?php
    $config = Config::getInstance();
    $nome_emporio = $config->config_values['emporio']['nome_emporio'];
?>

<div class="col-md-12">
<div class="row">
    <div class="col-sm-12 col-main col-lg-12">

		<div class="login-block">
            <h1 style="color:#fff; background:#c0392b; padding:5px;"><?php echo $nome_emporio; ?></h1>
            <div>&nbsp;</div>
			<h1><i class="fa fa-user"></i> Autenticazione utenti</h1>
			<form action="/auth/login" method="POST">
				<input type="text" value="" placeholder="Username" id="username" name="username" autofocus />
				<input type="password" value="" placeholder="Password" id="password" name="password" />
			
				<?php if(!empty($login_error)) { ?>
					<div class="alert alert-danger" style="text-align: center; width:100%"><?php echo $login_error; ?></div>
				<?php } ?>

				<?php if($expired == 1) { ?>
					<div class="alert alert-danger" style="text-align: center; width:100%">Sessione scaduta!</div>
				<?php } ?>

				<button class="btn btn-danger">Login</button>
			</form>
<!--            <div>&nbsp;</div>
            <div>&nbsp;</div>
            <div>Credits: <a href="http://www.infosysnet.net" style="text-decoration:none">InfoSysNet di Cherubini Enrico</a></div> -->
		</div>
	
		<div>
		</div>

    </div>
</div>
</div>

<script >
     $.validator.addMethod('regex', function(value, element, param) {
        return this.optional(element) ||
            value.match(typeof param == 'string' ? new RegExp(param) : param);
    }, '');

$(document).ready(function(){
    $('form').validate({
        debug: true,
        rules: {
                    username: { required: true, regex: /^[0-9A-Za-z\.\-_@]+$/ },
                    password: { required: true },
                                        
                },
        highlight: function (element) { $(element).addClass('input-error'); },
        unhighlight: function(element) { $(element).removeClass('input-error'); },
        success: function (element) { $(element).removeClass('input-error'); },
        errorPlacement: function (error, element) { },
        submitHandler: function(form) { form.submit(); }
        });
});

</script>
