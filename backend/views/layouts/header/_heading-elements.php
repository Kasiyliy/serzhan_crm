<div class="heading-elements">
    <div class="heading-btn-group">

        <? if ($page == "users") { ?>
            <a data-id = "0" href="users/form-user" class="action-link btn bg-success btn-labeled heading-btn legitRipple"><b><i class="icon-plus2"></i></b> Добавить пользователя<span class="legitRipple-ripple"></span></a>
        <? } else if($page == "customers"){?>
            <a data-id = "0" href="customers/form-customer" class="action-link btn bg-success btn-labeled heading-btn legitRipple"><b><i class="icon-plus2"></i></b> Добавить клиента<span class="legitRipple-ripple"></span></a>
        <? } else if($page == "goods"){?>
            <a data-id = "0" href="goods/form-good" class="action-link btn bg-success btn-labeled heading-btn legitRipple"><b><i class="icon-plus2"></i></b> Добавить товар<span class="legitRipple-ripple"></span></a>

        <? } else if($page == "orders"){?>
            <a data-id = "0" href="orders/form-order" class="action-link btn bg-success btn-labeled heading-btn legitRipple"><b><i class="icon-plus2"></i></b> Добавить заказ<span class="legitRipple-ripple"></span></a>

        <? } else if($page == "categories"){?>
            <a onclick="addCategory()" class="btn btn-primary" type="button" ><b><i class="icon-plus2"></i></b> Добавить категорию<span class="legitRipple-ripple"></span></a>
        <? } ?>
    </div>
</div>
