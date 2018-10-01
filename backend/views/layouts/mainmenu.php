<? use backend\components\Helpers; ?>

<div class="sidebar-category sidebar-category-visible">
    <div class="category-content no-padding">
        <ul class="navigation navigation-main navigation-accordion">
            <li class="navigation-header"><span>Администрирование</span> <i class="icon-menu" title="Администрирование"></i></li>

            <?
                if(Yii::$app->session->get('profile_role') == 1){
                    ?>
                    <li class="nav-item"><a id = "users" href="users" class = "cs-link"><i class="icon-chess-king"></i><span>Пользователи</span></a></li>
                    <li class="nav-item"><a id = "customers" href="customers" class = "cs-link"><i class="icon-piggy-bank"></i><span>Клиенты</span></a></li>
                    <li class="nav-item"><a id = "categories" href="categories" class = "cs-link"><i class="icon-folder6"></i><span>Категории</span></a></li>
                    <li class="nav-item"><a id = "goods" href="goods" class = "cs-link"><i class="icon-gift"></i><span>Товары</span></a></li>
                    <li class="nav-item"><a id = "orders" href="orders" class = "cs-link"><i class="icon-cart2"></i><span>Заказы</span></a></li>
                    <li class="nav-item"><a id = "debts" href="debts" class = "cs-link"><i class="icon-coins"></i><span>Задолженности</span></a></li>
                    <li class="nav-item"><a id = "returnings" href="returnings" class = "cs-link"><i class="icon-wallet"></i><span>Возвраты</span></a></li>

                    <?

                }else if(Yii::$app->session->get('profile_role') == 2){
                   ?>

                    <li class="nav-item"><a id = "categories" href="categories" class = "cs-link"><i class="icon-folder6"></i><span>Категории</span></a></li>
                    <li class="nav-item"><a id = "goods" href="goods" class = "cs-link"><i class="icon-gift"></i><span>Товары</span></a></li>
                    <li class="nav-item"><a id = "orders" href="orders" class = "cs-link"><i class="icon-cart2"></i><span>Заказы</span></a></li>
                    <li class="nav-item"><a id = "debts" href="debts" class = "cs-link"><i class="icon-coins"></i><span>Задолженности</span></a></li>
                    <li class="nav-item"><a id = "returnings" href="returnings" class = "cs-link"><i class="icon-wallet"></i><span>Возвраты</span></a></li>

                    <?
                }else if(Yii::$app->session->get('profile_role') == 3){
                    ?>
                    <li class="nav-item"><a id = "customers" href="customers" class = "cs-link"><i class="icon-piggy-bank"></i><span>Клиенты</span></a></li>
                    <li class="nav-item"><a id = "categories" href="categories" class = "cs-link"><i class="icon-folder6"></i><span>Категории</span></a></li>
                    <li class="nav-item"><a id = "goods" href="goods" class = "cs-link"><i class="icon-gift"></i><span>Товары</span></a></li>
                    <li class="nav-item"><a id = "orders" href="orders" class = "cs-link"><i class="icon-cart2"></i><span>Заказы</span></a></li>
                    <li class="nav-item"><a id = "debts" href="debts" class = "cs-link"><i class="icon-coins"></i><span>Задолженности</span></a></li>
                    <li class="nav-item"><a id = "returnings" href="returnings" class = "cs-link"><i class="icon-wallet"></i><span>Возвраты</span></a></li>
                    <?
                }
            ?>


        </ul>
    </div>
</div>