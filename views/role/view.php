<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Role */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Roles', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;



$menu = [];
$menu_child = [];
foreach ($menu_temp as $key => $value) {
    if($value['menu_root'] > 0) {
        $menu_child[$value['menu_root']]['id'][] = $value['menu_id']; 
        $menu_child[$value['menu_root']]['nama'][] = $value['menu_nama']; 
        $menu_child[$value['menu_root']]['icon'][] = $value['menu_icon']; 
        $menu_child[$value['menu_root']]['route'][] = $value['menu_route'];
    } else {
        $menu[$value['menu_id']]['nama'] = $value['menu_nama']; 
        $menu[$value['menu_id']]['icon'] = $value['menu_icon']; 
        $menu[$value['menu_id']]['route'] = $value['menu_route']; 
    }
}

?>
<div class="role-view">

    <?php $form = ActiveForm::begin(); ?>

    <ul style="list-style-type: none">
    <?php foreach($menu as $menu_id => $menu_value): ?>
    <li> <input type="checkbox" <?= in_array($menu_id, $menu_access) ? 'checked="checked"' : "" ?> class="form-control" value="1" name="menu[<?= $menu_id ?>]">
        <?= isset($menu_child[$menu_id]) ? '<i class="fa fa-'.$menu_value['icon'].'"></i><span class="title">'.$menu_value['nama'].'</span><span class="arrow open"></span>' : '<i class="fa fa-'.$menu_value['icon'].'"></i><span class="title">'.$menu_value['nama'].'</span>' ?>

        <?php 
        if(isset($menu_child[$menu_id])){
            echo '<ul style="list-style-type: none">';
            foreach ($menu_child[$menu_id]['nama'] as $key => $nama_menu) {
                $id_menu = $menu_child[$menu_id]['id'][$key];
                $checked = in_array($id_menu, $menu_access) ? 'checked="checked"' : "";
                echo "<li>". '<input type="checkbox" '.$checked.' class="form-control" value="1" name="menu['.$id_menu.']">' .'<i class="fa fa-'.$menu_child[$menu_id]['icon'][$key].'"></i><span class="title">'.$nama_menu.'</span>'."</li>";
            }    
            echo '</ul>';                            
        }
        ?>
    </li>
    <?php endforeach; ?>
    </ul>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>
