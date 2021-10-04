<?php
?>
<div class="col-md-12">
<h2>Configurazione emporio - 3</h2>
<div>&nbsp;</div>

<div class="row">

<div class="col-sm-9 col-md-9 col-lg-10">
<div class="well well-sm">
	<form action="/" method="POST">
		<div class="row" style="padding:6px;">
			<h4>Configurazione completata!</h4>
			<p>Per continuare e creare le utenze, inserire le seguenti credenziali:</p><br>
			Login: <strong>admin</strong><br>
			Password: <strong>emporio</strong><br><br>
			<strong>ATTENZIONE: le credenziali saranno valide solo per il primo accesso!</strong>
		</div>
</div>

</div>

<div class="col-sm-3 col-md-3 col-lg-2 col-right" style="height:80%">&nbsp;
	<div>&nbsp;</div>
    <button type="submit" class="btn btn-success btn-block">Avanti <i class="fa fa-arrow-right" aria-hidden="true"></i></button>
    </form>
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
                    dbname: { required: true, regex: /^[a-z0-9\.-_]+$/ },
                    dbuser: { required: true, regex: /^[a-zA-Z0-9\.-_]+$/ },
                    dbpass: { required: true, regex: /^[a-zA-Z0-9\.-_\*\!\$\%]+$/ },
                    dbhost: { required: true, regex: /^[a-zA-Z0-9\.-]+$/ },
                    dbport: { required: true, regex: /^[0-9]+$/ },
                                        
                },
        highlight: function (element) { $(element).addClass('input-error'); },
        unhighlight: function(element) { $(element).removeClass('input-error'); },
        success: function (element) { $(element).removeClass('input-error'); },
        errorPlacement: function (error, element) { },
        submitHandler: function(form) { form.submit(); }
        });
});

</script>
