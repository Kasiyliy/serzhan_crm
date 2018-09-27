<?php
use backend\models\Roles;
?>
<!-- ENGINE -->
<script type="text/javascript" src="/profile/files/js/plugins/notifications/sweet_alert.min.js"></script>
<script type="text/javascript" src="/profile/files/js/pages/form_layouts.js"></script>

<!---LOCAL --->
<script type="text/javascript" src="/profile/files/js/mytables/goods/form.js"></script>
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

                        <?=$this->render('/layouts/modal-components/_input', array('info' => array("Название", "name", "text", $model->name, "true")))?>
                        <?=$this->render('/layouts/modal-components/_input', array('info' => array("Цена", "price", "number", $model->price, "true")))?>
                        <?=$this->render('/layouts/modal-components/_input', array('info' => array("Количество", "quantity", "number", $model->quantity, "true")))?>

                        <?
                            $list = \backend\models\Statuses::find()->all();
                            $categories = \backend\models\Categories::find()->all();
                        ?>
                        <div class="col-md-6" style="padding-top: 2em; padding-bottom: 2em;">
                            <label class = "text-semibold">Статус:</label>
                            <select  name="status" class="select"  required ="required">
                                <? foreach ($list as $key => $value) { ?>
                                    <option <? if($value->id == $model->status_id){?>selected<?} ?> value="<?=$value->id?>"><?=$value->name?></option>
                                <? } ?>
                            </select>
                        </div>


                        <div class="col-md-6" style="padding-top: 2em; padding-bottom: 2em;">
                            <label class = "text-semibold">Категория товара:</label>
                            <select  name="category" class="select"  required ="required">
                                <? foreach ($categories as $key => $value) { ?>
                                    <option <? if($value->id == $model->category_id){?>selected<?} ?> value="<?=$value->id?>"><?=$value->name?></option>
                                <? } ?>
                            </select>
                        </div>

                    </div>

                    <div class = "col-md-12">
                        <div class="text-right">
                            <a href = "<?=Yii::$app->request->cookies['back']?>" class="cs-link btn btn-default">Отмена <i class="icon-x position-right"></i></a>
                            <? if ($model->id != null) { ?>
                                <a href = "#delete" data-id = "<?=$model->id?>" data-table = "goods" data-redirect = "goods" class="delete btn btn-danger">Удалить <i class="icon-trash-alt position-right"></i></a>
                            <? } ?>
                            <button type="submit" class="btn btn-primary">Сохранить <i class="icon-check position-right"></i></button>
                        </div>
                    </div>
                </div>
            </div>
    </form>
</div>
