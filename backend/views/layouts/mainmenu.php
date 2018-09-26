<? use backend\components\Helpers; ?>

<div class="sidebar-category sidebar-category-visible">
    <div class="category-content no-padding">
        <ul class="navigation navigation-main navigation-accordion">
            <li class="navigation-header"><span>Администрирование</span> <i class="icon-menu" title="Администрирование"></i></li>
            <?php
                if(Yii::$app->session->get("profile_role") == 3){
                    ?>
                    <li class="nav-item"><a id = "moderators" href="moderators" class = "cs-link"><i class="icon-office"></i>Модераторы</a></li>
                    <li class="nav-item"><a id = "admins" href="admins" class = "cs-link"><i class="icon-office"></i>Администраторы</a></li>
                    <li class="nav-item"><a id = "tadmins" href="tadmins" class = "cs-link"><i class="icon-office"></i>Администраторы таксопарков</a></li>


                    <?
                }
            ?>
            <?
            if(Yii::$app->session->get("profile_role") == 6){
                ?>
                <li class="nav-item"><a id = "cashier" href="cashier" class = "cs-link"><i class="icon-office"></i>Пополнение баланса таксопарков</a></li>
                <?
            }
            ?>
            <li class="nav-item"><a id = "taxi-parks" href="taxi-parks" class = "cs-link"><i class="icon-office"></i>Таксопарки</a></li>
            <li class="nav-item"><a id = "drivers" href="drivers" class = "cs-link"><i class="icon-office"></i>Водители</a></li>

            <li class="nav-item"><a id = "users" href="users" class = "cs-link"><i class="icon-office"></i>Клиенты</a></li>
            <?
            if(Yii::$app->session->get("profile_role") == 5) {
                ?>
                <li>
                    <a href="#"><i class="icon-lock2"></i> <span>Покупка</span></a>
                    <ul>
                        <li><a id="" href="" class="cs-link">Пополнть счет</a></li>
                        <li><a id="access" href="access" class="cs-link">Доступ к общему чату </a></li>
                    </ul>
                </li>
                <?
            }
            ?>
            <?
            if(Yii::$app->session->get("profile_role") == 3){
                ?>
<!--                <li class="nav-item"><a id = "traffic" href="traffic" class = "cs-link"><i class="icon-office"></i>Оборот монет</a></li>-->
                <li class="nav-item"><a id = "cashiers" href="cashiers" class = "cs-link"><i class="icon-office"></i>Кассиры</a></li>
                <li class="nav-item"><a id = "settings" href="settings" class = "cs-link"><i class="icon-office"></i>Настройки</a></li>
                <li class="nav-item"><a id = "price" href="price" class = "cs-link"><i class="icon-office"></i>Просмотр цен</a></li>
                <?
            }
            ?>

        </ul>
    </div>
</div>