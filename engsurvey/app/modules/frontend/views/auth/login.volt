<div class="container">
    <div class="row">
    	<div class="auth-block">
    		<div class="panel panel-default">
			  	<div class="panel-heading">
			    	<h3 class="panel-title">Вход в систему</h3>
			 	</div>
			  	<div class="panel-body">
					<form accept-charset="UTF-8" autocomplete="off" action="verification" method="post">
						<fieldset>
							{{ flashSession.output() }}
							{{ form.renderDecorated("login") }}
							{{ form.renderDecorated("password") }}
							{{ submit_button("Войти", "class": "btn btn-primary btn-block") }}
						</fieldset>
			      	</form>
			    </div>
			</div>
		</div>
	</div>
</div>
