<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Menu;
use app\models\Role;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;
/* @var $this yii\web\View */
/* @var $model app\models\MenuAkses */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="menu-akses-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'menu_id')->widget(Select2::classname(), [
        'data' => ArrayHelper::map(Menu::find()->all(), 'menu_id', 'menu_nama'),
        'options' => ['placeholder' => 'Pilih Menu...'],
        
    ]);
    ?>
    <?= $form->field($model, 'role')->dropDownList(
      ArrayHelper::map(Role::find()->all(), 'id', 'name')
    ) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
