<?php
    $page = Yii::$app->session->get('navigation_page');
    $arr_page = explode("/", $page);
    if ($model == null) {
        $links = array(

            "users" => "Пользователи",
            "form-user" => "Добавить пользователя",
            "customers" => "Клиенты",
            "form-customer" => "Добавить клиента",
            "categories" => "Категории товаров",
            "goods" => "Товары",
            "form-good" => "Добавить товар",
            "orders" => "Заказы",
            "form-order" =>"Добавить заказ",
        );
    } else {
        $links = array(
            "users" => "Пользователи",
            "form-user" => "Редактировать пользователя",
            "customers" => "Клиенты",
            "form-customer" => "Редактировать клиента",
            "categories" => "Категории товаров",
            "goods" => "Товары",
            "form-good" => "Редактировать товар",
            "orders" => "Заказы",
            "form-order" => "Редактировать заказ",
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