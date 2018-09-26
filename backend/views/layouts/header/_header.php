<?php
    $page = Yii::$app->session->get('navigation_page');
    $arr_page = explode("/", $page);
    if ($model == null) {
        $links = array(

            "moderators" => "Модераторы",
            "form-moderator" => "Добавить модератора",
            "admins" => "Администраторы",
            "form-admin" => "Добавить администратора",
            "tadmins" => "Администраторы таксопарков",
            "form-tadmin" => "Добавить администратора таксопарка",
            "account" => "Настройки аккаунта",
            "users" => "Клиенты",
            "taxi-parks" => "Таксопарки",
            "form-taxi-park" => "Добавить таксопарк",
            "drivers" => "Водители",
            "form-driver" => "Добавить водителя",
            "settings" => "Настройки",
            "cashiers" => "Кассиры",
            "cashier" => "Пополнение баланса таксопарков",
            "form-cashier" => "Добавить кассира",
            "traffic" => "Оборот монет",
        );
    } else {
        $links = array(
            "account" => "Настройки аккаунта",
            "moderators" => "Модераторы",
            "form-moderator" => "Редактировать модератора",
            "admins" => "Администраторы",
            "form-tadmin" => "администратора таксопарка",
            "tadmins" => "Администраторы таксопарков",
            "form-admin" => "Редактировать администратора",
            "taxi-parks" => "Таксопарки",
            "form-taxi-park" => "Редактировать таксопарк",
            "users" => "Клиенты",
            "settings" => "Настройки",
            "drivers" => "Водители",
            "traffic" => "Оборот монет",
            "cashiers" => "Кассиры",
            "cashier" => "Пополнение баланса таксопарков",
            "form-cashier" => "Редактировать кассира",

            "form-driver" => "Редактировать водителя",
        );
    }
    $bread = '<li><a href="/profile/"><i class="icon-home2 position-left"></i> Главная</a></li>';
    $counter = 0;
    foreach ($arr_page as $value) {
        $counter++;
        if ($counter != count($arr_page)) {
            $bread .= '<li><a class = "cs-link" href="'.$value.'">' . $links[$value] . '</a></li>';
        } else {
            $bread .= '<li class = "active">' . $links[$value] . '</li>';
            $title = $links[$value];
        }
    }
?>

<div class="page-header">
    <div class="page-header">
        <div class="page-header-content">
            <div class="page-title">
                <h4>
                    <? if (count($arr_page) > 1) { ?>
                        <a class = "cs-link" href="<?=Yii::$app->request->cookies['back']?>"><i class="icon-arrow-left52 position-left"></i></a>
                    <? } ?>
                    <span class="text-semibold"><?=$title?></span>
                </h4>
            </div>
            <?=$this->render('/layouts/header/_heading-elements', array("page" => $page));?>
        </div>

        <div class="breadcrumb-line breadcrumb-line-component"><a class="breadcrumb-elements-toggle"><i class="icon-menu-open"></i></a>
            <ul class="breadcrumb">
                <?=$bread?>
            </ul>
        </div>
    </div>
</div>