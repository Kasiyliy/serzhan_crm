<?php
use backend\models\Roles;
?>
<!-- ENGINE -->
<script type="text/javascript" src="/profile/files/js/plugins/notifications/sweet_alert.min.js"></script>
<script type="text/javascript" src="/profile/files/js/pages/form_layouts.js"></script>

<!---LOCAL --->
<script type="text/javascript" src="/profile/files/js/mytables/orders/form.js"></script>
<!------->

<?=$this->render("/layouts/header/_header", array("model" => $model))?>

<div class="content">
    <form id = "form">
        <div class="panel panel-flat">
            <div class="panel-body">
                <div class="col-md-12">
                    <div class="col-md-12">
                        <input name="id" type="hidden" class="form-control" value = "<?=$model->id?>">
                        <input name="_csrf-backend" type="hidden" class="form-control" value = "<?=Yii::$app->getRequest()->getCsrfToken()?>">
                        <?
                        $client = \backend\models\Customers::find()->all();
                        $orders_goods = \backend\models\OrdersGoods::find()->where(['order_id' => $model->id])->all();
                        $goods = \backend\models\Goods::find()->all();
                        ?>
                        <label class="text-semibold">Выберите клиента:</label>
                        <select name = "client" class="select" required ="required">
                            <? foreach ($client as $k => $v) { ?>
                                <option <? if ($v->id == $model->customer_id) { ?>selected<? } ?> value="<?=$v->id?>"><?=$v->name?></option>
                            <? } ?>
                        </select>
                        <?
                        foreach ($orders_goods as $key => $value){
                            $rand = rand();
                            ?>
                            <div class="col-md-12" style="margin-top: 2em; margin-bottom: 2em">
                                <label class="text-semibold">Выберите товар:</label>
                                <select name = "goods[<?=$rand?>]" class="select" required ="required">
                                    <? foreach ($goods as $k => $v) { ?>
                                        <option <? if ($v->id == $value->good_id) { ?>selected<? } ?> value="<?=$v->id?>"><?=$v->name?></option>
                                    <? } ?>
                                </select>
                                <input name="amount[<?=$rand?>]" placeholder="3" class="form-control" value="<?=$value->quantity?>">
                            </div>
                            <?
                        }
                        ?>


                        <div id="good" class="col-md-12">

                        </div>
                        <button class="btn btn-primary" style="margin-top: 2em" type="button" onclick="addGood()">Добавить товар</button>
                    </div>

                    <div class = "col-md-12">
                        <div class="text-right">
                            <a href = "<?=Yii::$app->request->cookies['back']?>" class="cs-link btn btn-default">Отмена <i class="icon-x position-right"></i></a>
                            <? if ($model->id != null) { ?>
                                <a href = "#delete" data-id = "<?=$model->id?>" data-table = "orders" data-redirect = "orders" class="delete btn btn-danger">Удалить <i class="icon-trash-alt position-right"></i></a>
                            <? } ?>
                            <button type="submit" class="btn btn-primary">Сохранить <i class="icon-check position-right"></i></button>
                        </div>
                    </div>
                </div>
            </div>
    </form>
</div>

<script>
    function addGood() {
        var parent = document.getElementById('good');
        var div = document.createElement('div');

        var select = document.createElement('select');
        select.classList.toggle("select");
        select.id = "select2";
        var label = document.createElement('label');
        label.classList.toggle('text-semibold');
        label.innerText = 'Выберите товар:';
        var rand = Math.random();
        select.name = "goods[" + rand + "]";
        div.classList.toggle("col-md-12");

        var label1 = document.createElement('label');
        label1.classList.toggle('text-semibold');
        label1.innerText = 'Введите количество товара:';

        var input = document.createElement("input");
        input.name = "amount[" + rand + "]";
        input.classList.toggle("form-control");
        input.required = true;
        input.type = 'number';
        input.placeholder = "3";
        div.setAttribute("style", "padding-top: 2em; padding-bottom: 2em;");




        $.ajax({
            url: "/profile/orders/get-goods/",
            dataType: "json",
            type: "GET",
            success: function (response) {
                for(var i = 0; i < response.goods.length; i++) {
                    var option = document.createElement('option');
                    option.value = response.goods[i].id;
                    option.innerHTML = response.goods[i].name;
                    select.appendChild(option)
                }
                div.appendChild(label);
                div.appendChild(select);
                div.appendChild(input);
                parent.appendChild(div);
                $('[name="goods[' + rand + ']"]').select2();
                }
            });


    }


</script>