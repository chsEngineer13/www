<!DOCTYPE html>
<html lang="ru">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        {{ get_title() }}
        <link rel="shortcut icon" href="{{ config.assets.favicon }}">
        {{ stylesheet_link(config.fontAwesome.fontAwesomeCss) }}
        {{ stylesheet_link(config.bootstrap.bootstrapCss) }}
        {{ stylesheet_link(config.assets.engsurveyCss) }}
    </head>
    <body>
        <div class="page">
            <div class="page-header">
                <header class="header">
                    <nav class="navbar navbar-gpp navbar-fixed-top">
                        <div class="container-fluid">
                            <div class="navbar-header">
                                <a class="navbar-brand" href="#">{{ config.app.appShortName }}</a>
                            </div>
                        </div>
                    </nav>
                </header>
            </div>
            <div class="page-main">
                <main class="main">
                    {{ content() }}
                </main>
            </div>
            <div class="page-footer">
                <footer class="footer">
                    {{ config.app.copyright }}
                </footer>
            </div>
        </div>
        {{ javascript_include(config.jquery.jqueryJs) }}
        {{ javascript_include(config.bootstrap.bootstrapJs) }}
        {{ javascript_include(config.assets.engsurveyJs) }}
    </body>
</html>
