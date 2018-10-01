
<?php
    if(Yii::$app->session->get('profile_role') == 1) {

        if ($page == "categories") { ?>
            <a style="margin-bottom: 2em; margin-top: 2em;" onclick="addCategory()" class="btn btn-primary"
               type="button"><b><i class="icon-plus2"></i></b> Добавить категорию<span
                        class="legitRipple-ripple"></span></a>
        <? } else if ($page == "users") { ?>
            <a data-id="0" href="users/form-user" style="margin-bottom: 2em; margin-top: 2em;"
               class="action-link btn bg-success btn-labeled heading-btn legitRipple"><b><i class="icon-plus2"></i></b>
                Добавить пользователя<span class="legitRipple-ripple"></span></a>
        <? } else if ($page == "customers") { ?>
            <a data-id="0" href="customers/form-customer" style="margin-bottom: 2em; margin-top: 2em;"
               class="action-link btn bg-success btn-labeled heading-btn legitRipple"><b><i class="icon-plus2"></i></b>
                Добавить клиента<span class="legitRipple-ripple"></span></a>
        <? } else if ($page == "goods") { ?>
            <a data-id="0" href="goods/form-good" style="margin-bottom: 2em; margin-top: 2em;"
               class="action-link btn bg-success btn-labeled heading-btn legitRipple"><b><i class="icon-plus2"></i></b>
                Добавить товар<span class="legitRipple-ripple"></span></a>

        <? }
    }
?>

<?  if ($page == "orders") { ?>
            <a data-id="0" href="orders/form-order" style="margin-bottom: 2em; margin-top: 2em;"
               class="action-link btn bg-success btn-labeled heading-btn legitRipple"><b><i class="icon-plus2"></i></b>
                Добавить заказ<span class="legitRipple-ripple"></span></a>
<?php
    }
?>
<div class="heading-elements">
    <div class="heading-btn-group">


    </div>
</div>
