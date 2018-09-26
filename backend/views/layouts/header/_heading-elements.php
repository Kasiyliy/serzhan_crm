<div class="heading-elements">
    <div class="heading-btn-group">
        <? if ($page == "moderators") { ?>
            <a data-id = "0" href="moderators/form-moderator" class="action-link btn bg-success btn-labeled heading-btn legitRipple"><b><i class="icon-plus2"></i></b> Добавить модератора<span class="legitRipple-ripple"></span></a>
        <? }
        else if ($page == "admins") { ?>
            <a data-id = "0" href="admins/form-admin" class="action-link btn bg-success btn-labeled heading-btn legitRipple"><b><i class="icon-plus2"></i></b> Добавить администратора<span class="legitRipple-ripple"></span></a>
        <? } else if ($page == "tadmins") {?>
            <a data-id = "0" href="tadmins/form-tadmin" class="action-link btn bg-success btn-labeled heading-btn legitRipple"><b><i class="icon-plus2"></i></b> Добавить администратора<span class="legitRipple-ripple"></span></a>
        <? } else if ($page == "taxi-parks") {?>
        <a data-id = "-1" href="taxi-parks/form-taxi-park" class="action-link btn bg-success btn-labeled heading-btn legitRipple"><b><i class="icon-plus2"></i></b> Добавить таксопарк<span class="legitRipple-ripple"></span></a>
        <? } else if ($page == "drivers") {?>
        <a data-id = "0" href="drivers/form-driver" class="action-link btn bg-success btn-labeled heading-btn legitRipple"><b><i class="icon-plus2"></i></b> Добавить водителя<span class="legitRipple-ripple"></span></a>
        <? } else if ($page == "cashiers") {?>
        <a data-id = "0" href="cashiers/form-cashier" class="action-link btn bg-success btn-labeled heading-btn legitRipple"><b><i class="icon-plus2"></i></b> Добавить кассира<span class="legitRipple-ripple"></span></a>
        <? } ?>
    </div>
</div>
