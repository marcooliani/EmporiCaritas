<?php
?>
<div class="col-md-12">

<div>
<h2>Configurazione emporio - Database</h2>
<div>&nbsp;</div>
</div>

<div class="row">
<div class="col-sm-9 col-md-9 col-lg-10 col-main">
<div class="well well-sm">
	<form action="/setup/" method="POST">
		<input type="hidden" name="createdb" id="createdb" value="ok">
		<div class="row">
			<div class="col-sm-2 col-md-2 col-lg-2">
            	<div class="form-group">
                	<label for="exampleInputEmail1">Database</label>
             		<input type="text" class="form-control" id="dbname" name="dbname" tabindex="1" placeholder="Inserire nome database">
           		</div>
           	</div>
			<div class="col-sm-2 col-md-2 col-lg-2">
            	<div class="form-group">
                	<label for="exampleInputEmail1">Utente</label>
             		<input type="text" class="form-control" id="dbuser" name="dbuser" tabindex="1" placeholder="Inserire utente db">
           		</div>
           	</div>
			<div class="col-sm-2 col-md-2 col-lg-2">
            	<div class="form-group">
                	<label for="exampleInputEmail1">Password</label>
             		<input type="text" class="form-control" id="dbpass" name="dbpass" tabindex="1" placeholder="Inserire password">
           		</div>
           	</div>
			<div class="col-sm-2 col-md-2 col-lg-2">
            	<div class="form-group">
                	<label for="exampleInputEmail1">Host</label>
             		<input type="text" class="form-control" id="dbhost" name="dbhost" tabindex="1" value="localhost">
           		</div>
           	</div>
			<div class="col-sm-2 col-md-2 col-lg-2">
            	<div class="form-group">
                	<label for="exampleInputEmail1">Porta</label>
             		<input type="text" class="form-control" id="dbport" name="dbport" tabindex="1" value="5432">
           		</div>
           	</div>
		</div>

		<div class="row">
            <div class="col-sm-4 col-md-4 col-lg-4">
                <div class="form-group">
                    <label>Tipologia emporio: &nbsp;</label>
                    <label class="radio-inline">
                        <input type="radio" name="tipo_emporio" value="rete" checked>In rete
                    </label>
                    <label class="radio-inline">
                        <input type="radio" name="tipo_emporio" value="standalone">Standalone
                    </label>
                </div>
            </div>
        </div>
</div>

<p>
<strong>Database:</strong> nome del database <i>(deve essere gi&agrave; creato)</i><br>
<strong>Utente:</strong> nome dell'utente con permessi di accesso al database <i>(deve essere gi&agrave; creato e avere permessi di superuser)</i><br>
<strong>Password:</strong> password dell'utente database<br>
<strong>Host:</strong> indirizzo del server del database <i>(si consiglia di lasciare il valore di default)</i><br>
<strong>Porta:</strong> porta di connessione al database <i>(si consiglia di lasciare il valore di default)</i><br>
</p>
<p>
<strong>Tipologia emporio:</strong><br> 
&nbsp;&nbsp;&nbsp;<i>In rete</i> - Utilizza un database condiviso tra diversi empori per alcune sezioni<br>
&nbsp;&nbsp;&nbsp;<i>Standalone</i> - Utilizza un database interamente locale<br>
</p>

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
