<div class="container">
    <div class="row">
        <div class="auth-block">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Смена пароля</h3>
                </div>
                <div class="panel-body">
                    <form accept-charset="UTF-8" autocomplete="off"
                        action="verification-new-password" method="post">
                        <fieldset>
                            {{ flashSession.output() }}
                            {{ form.renderDecorated("login") }}
                            {{ form.renderDecorated("oldPassword") }}
                            {{ form.renderDecorated("newPassword") }}
                            {{ form.renderDecorated("confirmationPassword") }}
                            {{ submit_button("Изменить пароль", "class": "btn btn-primary btn-block") }}
                        </fieldset>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
