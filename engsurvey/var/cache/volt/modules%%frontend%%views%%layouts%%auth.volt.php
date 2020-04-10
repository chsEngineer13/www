<!DOCTYPE html>
<html lang="ru">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <?= $this->tag->getTitle() ?>
        <link rel="shortcut icon" href="<?= $this->config->assets->favicon ?>">
        <?= $this->tag->stylesheetLink($this->config->fontAwesome->fontAwesomeCss) ?>
        <?= $this->tag->stylesheetLink($this->config->bootstrap->bootstrapCss) ?>
        <?= $this->tag->stylesheetLink($this->config->assets->engsurveyCss) ?>
    </head>
    <body>
        <div class="page">
            <div class="page-header">
                <header class="header">
                    <nav class="navbar navbar-gpp navbar-fixed-top">
                        <div class="container-fluid">
                            <div class="navbar-header">
                                <a class="navbar-brand" href="#"><?= $this->config->app->appShortName ?></a>
                            </div>
                        </div>
                    </nav>
                </header>
            </div>
            <div class="page-main">
                <main class="main">
                    <?= $this->getContent() ?>
                </main>
            </div>
            <div class="page-footer">
                <footer class="footer">
                    <?= $this->config->app->copyright ?>
                </footer>
            </div>
        </div>
        <?= $this->tag->javascriptInclude($this->config->jquery->jqueryJs) ?>
        <?= $this->tag->javascriptInclude($this->config->bootstrap->bootstrapJs) ?>
        <?= $this->tag->javascriptInclude($this->config->assets->engsurveyJs) ?>
    </body>
</html>
