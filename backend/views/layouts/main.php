<?php
    use backend\assets\AppAsset;
    use backend\components\Helpers;
    use yii\helpers\Html;
    AppAsset::register($this);
?>
<?php $this->beginPage() ?>
    <!DOCTYPE html>
    <html lang="<?= Yii::$app->language ?>">
        <head>
            <meta charset="<?= Yii::$app->charset ?>">
            <meta http-equiv="X-UA-Compatible" content="IE=edge">
            <meta name="viewport" content="width=device-width, initial-scale=1">
            <?= Html::csrfMetaTags() ?>
            <title><?= Html::encode($this->title) ?></title>
            <?php $this->head() ?>
        </head>
        <body>
        <?php $this->beginBody() ?>
            <? if (Yii::$app->controller->action->id != "authentication") { ?>
                <? Helpers::CheckAuth("no-redirect", null); ?>
                <div class="navbar navbar-default header-highlight">
                    <div class="navbar-header">

                        <a class="navbar-brand" style = "color:#000;" href="javascript:void(0);">Serzhan CRM</a>


                        <ul class="nav navbar-nav visible-xs-block">
                            <li><a data-toggle="collapse" data-target="#navbar-mobile" class="legitRipple"><i class="icon-tree5"></i></a></li>
                            <li><a class="sidebar-mobile-main-toggle legitRipple"><i class="icon-paragraph-justify3"></i></a></li>
                        </ul>
                    </div>

                    <div class="navbar-collapse collapse" id="navbar-mobile">
                        <ul class="nav navbar-nav">
                            <li><a class="sidebar-control sidebar-main-toggle hidden-xs legitRipple"><i class="icon-paragraph-justify3"></i></a></li>

                        </ul>


                    </div>
                </div>
                <div class="page-container">
                    <div class="page-content">
                        <div class="sidebar sidebar-main">
                            <div class="sidebar-content">
                                <div class="sidebar-user-material">
                                    <div class="category-content">
                                        <div class="sidebar-user-material-content">
                                            <a href="account" class = "cs-link"><img src="/profile/uploads/associate/<?=Yii::$app->session->get('profile_avatar')?>" class="account_avatar img-responsive" alt=""></a>
                                            <h6><?=Yii::$app->session->get('profile_fio')?></h6>
                                            <span class="text-size-small text-muted"><?=$roles[Yii::$app->session->get('profile_role')]?></span>
                                        </div>

                                        <div class="sidebar-user-material-menu">
                                            <a href="#user-nav" data-toggle="collapse"><span>Мой профиль</span> <i class="caret"></i></a>
                                        </div>
                                    </div>

                                    <div class="navigation-wrapper collapse" id="user-nav">
                                        <ul class="navigation">
                                            <li><a href="account" class = "cs-link"><i class="icon-cog5"></i> <span>Настройки аккаунта</span></a></li>
                                            <li><a href="/profile/logout/"><i class="icon-switch2"></i> <span>Выход</span></a></li>
                                        </ul>
                                    </div>
                                </div>
                                <?=$this->render('/layouts/mainmenu')?>
                            </div>
                        </div>
                        <div id = "dynamic_content" class="content-wrapper"></div>
                    </div>
                </div>
            <? } else { ?>
                <?=$this->render('/layouts/authentication'); ?>
            <? } ?>
        <?php $this->endBody() ?>
        </body>
    </html>
<?php $this->endPage() ?>
