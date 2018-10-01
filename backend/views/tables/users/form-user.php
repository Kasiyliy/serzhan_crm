<?php
use backend\models\Roles;
?>
<!-- ENGINE -->
<script type="text/javascript" src="/profile/files/js/plugins/notifications/sweet_alert.min.js"></script>
<script type="text/javascript" src="/profile/files/js/pages/form_layouts.js"></script>

<!---LOCAL --->
<script type="text/javascript" src="/profile/files/js/mytables/users/form.js"></script>
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

                        <?=$this->render('/layouts/modal-components/_input', array('info' => array("Имя", "first_name", "text", $model->first_name, "true")))?>
                        <?=$this->render('/layouts/modal-components/_input', array('info' => array("Фамилия", "last_name", "text", $model->last_name, "true")))?>
                        <?=$this->render('/layouts/modal-components/_input', array('info' => array("login", "login", "email", $model->login, "true")))?>

                        <?
                        if($model == null){ ?>
                            <?=$this->render('/layouts/modal-components/_input', array('info' => array("Пароль", "password", "text", $model->password, "true")))?>
                        <?}
                        ?>

                        <?=$this->render('/layouts/modal-components/_input', array('info' => array("Телефон", "phone_number", "text", $model->phone_number, "true")))?>
                    <?
                    $list = Roles::find()->all();
                    if($model->id != null){
                        $users_role = \backend\models\UsersRoles::find()->where(['user_id' => $model->id])->all();
                        $role_ids = [];
                        foreach ($users_role as $k => $v){
                            array_push($role_ids, $v->role_id);
                        }
                    }

                    ?>
                        <input hidden name="roles" id="facil">

                        <div class="col-md-6" style="padding-top: 2em; padding-bottom: 2em;">
                            <label class = "text-semibold">Роль:</label>
                            <select  id="roles" class="select" multiple required ="required">
                                <? foreach ($list as $key => $value) { ?>
                                    <option <? if( in_array($value->id, $role_ids)){?>selected<?} ?> value="<?=$value->id?>"><?=$value->name?></option>
                                <? } ?>
                            </select>
                        </div>

                    </div>
                    <?
                    if(Yii::$app->session->get('profile_role') == 1){
                    ?>
                    <div class = "col-md-12">
                        <div class="text-right">
                            <a href = "<?=Yii::$app->request->cookies['back']?>" class="cs-link btn btn-default">Отмена <i class="icon-x position-right"></i></a>
                            <? if ($model->id != null) { ?>
                                <a href = "#delete" data-id = "<?=$model->id?>" data-table = "users" data-redirect = "users" class="delete btn btn-danger">Удалить <i class="icon-trash-alt position-right"></i></a>
                            <? } ?>
                            <button type="submit" class="btn btn-primary">Сохранить <i class="icon-check position-right"></i></button>
                        </div>
                    </div>
                    <?php } ?>
                </div>
            </div>
    </form>
</div>


<script>
    $(document).ready(function() {

        $(document.body).on("change","#roles",function(){

            var countries = [];
            $.each($("#roles option:selected"), function(){
                countries.push($(this).val());
            });
            $('#facil').val(countries);
            //console.log('value ' + val);

        });



    });

    function models() {
        var id = document.getElementById('mark').value;
        console.log(id);
        $.ajax({url: "moderators/get-models/",
            type: 'POST',
            data: {id:id},
            success: function(result) {
                var array = result.models;
                var selectList = document.getElementById('model');
                selectList.innerHTML = '';

                for (var i = 0; i < array.length; i++) {
                    var option = document.createElement("option");
                    option.value = array[i].id;
                    option.text = array[i].model;
                    selectList.appendChild(option);
                }

                $('#model').select2();
            }


        });
    }


    //        $("button").click(function(){
    //
    //        });
</script>