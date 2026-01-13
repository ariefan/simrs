<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\MetronicAsset;
use app\models\Menu;
use app\models\Dokter;
$dokter = Dokter::findOne(Yii::$app->user->identity->id);

$menu_temp = Menu::find()->joinWith('menuAkses')->where(['role'=>Yii::$app->user->identity->role])->orderBy('menu_order')->asArray()->all();
$menu = [];
$menu_child = [];
foreach ($menu_temp as $key => $value) {
    if($value['menu_root'] > 0) {
        $menu_child[$value['menu_root']]['nama'][] = $value['menu_nama']; 
        $menu_child[$value['menu_root']]['icon'][] = $value['menu_icon']; 
        $menu_child[$value['menu_root']]['route'][] = $value['menu_route']; 
    } else {
        $menu[$value['menu_id']]['nama'] = $value['menu_nama']; 
        $menu[$value['menu_id']]['icon'] = $value['menu_icon']; 
        $menu[$value['menu_id']]['route'] = $value['menu_route']; 
    }
}

MetronicAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" href="<?= Yii::getAlias('@web/favicon.ico') ?>"/>
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    
    <?php $this->head() ?>
</head>
<body 
    style="background-image: url(/metronic6/media/bg-1.jpg)" 
    class="kt-page--loading-enabled kt-page--loading kt-page--fixed kt-quick-panel--right kt-demo-panel--right kt-offcanvas-panel--right kt-header--fixed kt-header--minimize-menu kt-header-mobile--fixed kt-subheader--enabled kt-subheader--transparent kt-aside--enabled kt-aside--left kt-aside--fixed kt-page--loading"
>

<?php $this->beginBody() ?>

    <div class="kt-grid kt-grid--hor kt-grid--root">
        <div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--ver kt-page">
            <div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor kt-wrapper" id="kt_wrapper">

                <div id="kt_header" class="kt-header kt-grid__item  kt-header--fixed " data-ktheader-minimize="on">
                    <div class="kt-header__top">
                        <div class="kt-container">
                            <!-- begin:: Brand -->
                            <div class="kt-header__brand   kt-grid__item" id="kt_header_brand">
                                <div class="kt-header__brand-logo">
                                    <a href="">
                                        <h1 style="color: #fff;">RSUD Wates</h1>
                                    </a>
                                </div>
                            </div>
                            <!-- end:: Brand -->
                            <!-- begin:: Header Topbar -->
                            <div class="kt-header__topbar">
                                <!--begin: User bar -->
                                <div class="kt-header__topbar-item kt-header__topbar-item--user">
                                    <div class="kt-header__topbar-wrapper" data-toggle="dropdown" data-offset="0px,10px">
                                        <span class="kt-header__topbar-welcome kt-visible-desktop">Halo,</span>
                                        <span class="kt-header__topbar-username kt-visible-desktop"><?= Yii::$app->user->identity->username ?></span>
                                        <?= empty($dokter->foto) ? Html::img('@web/img/DR-avatar.png',['size'=>'300px']) : Html::img('@web/'.$dokter->foto,['size'=>'300px']) ?>
                                    </div>
                                </div>
                                <!--end: User bar -->

                                <?php /*** ?>
                                <div class="kt-header__topbar-item" data-toggle="kt-tooltip" title="Log out" data-placement="top">
                                    <div class="kt-header__topbar-wrapper">
                                        <span class="kt-header__topbar-icon" id="kt_quick_panel_toggler_btn">
                                            <?= 
                                            Yii::$app->user->isGuest ? Html::a('<i class="icon-key"></i> Login',['/site/login']) :
                                            Html::beginForm(['/site/logout'], 'post')
                                            . Html::submitButton(
                                                '<i class="fa fa-door-open"></i>',
                                                ['class' => 'btn']
                                            )
                                            . Html::endForm()
                                            ?>
                                    </div>
                                </div>
                                <?php /***/ ?>
                            </div>
                            <!-- end:: Header Topbar -->
                        </div>
                    </div>
                    <div class="kt-header__bottom">
                        <div class="kt-container">
                            <!-- begin: Header Menu -->
                            <button class="kt-header-menu-wrapper-close" id="kt_header_menu_mobile_close_btn"><i class="la la-close"></i></button>
                            <div class="kt-header-menu-wrapper" id="kt_header_menu_wrapper">
                                <div id="kt_header_menu" class="kt-header-menu kt-header-menu-mobile  kt-header-menu--layout-default ">

                                    <ul class="kt-menu__nav ">
                                    <?php foreach($menu as $menu_id => $menu_value): ?>
                                        <li class="kt-menu__item kt-menu__item--open kt-menu__item--here kt-menu__item--submenu kt-menu__item--rel kt-menu__item--active" 
                                            <?php if (!empty($menu_child[$menu_id])): ?>
                                            data-ktmenu-submenu-toggle="click" aria-haspopup="true"
                                            <?php endif ?>
                                            >
                                            <a href="<?= Url::to([$menu_value['route']]) ?>" class="kt-menu__link <?= empty($menu_child[$menu_id]) ? '' : 'kt-menu__toggle' ?>">
                                                <span class="kt-menu__link-text"><?= $menu_value['nama'] ?></span>
                                                <i class="kt-menu__ver-arrow la la-angle-right"></i>
                                            </a>

                                            <?php if (!empty($menu_child[$menu_id])): ?>
                                            <div class="kt-menu__submenu kt-menu__submenu--classic kt-menu__submenu--left">
                                                <ul class="kt-menu__subnav">
                                                    <?php foreach ($menu_child[$menu_id]['nama'] as $key => $nama_menu): ?>
                                                    <li class="kt-menu__item " aria-haspopup="true">
                                                        <a href="<?= Url::to([$menu_child[$menu_id]['route'][$key]]) ?>" class="kt-menu__link ">
                                                            <i class="fa fa-fw fa-<?= $menu_child[$menu_id]['icon'][$key] ?>"></i>
                                                            &nbsp;&nbsp;&nbsp; <span class="kt-menu__link-text"> <?= $nama_menu ?> </span>
                                                        </a>
                                                    </li>
                                                    <?php endforeach ?>
                                                </ul>
                                            </div>
                                            <?php endif ?>
                                        </li>
                                    <?php endforeach ?>

                                        <li class="kt-menu__item kt-menu__item--open kt-menu__item--here kt-menu__item--submenu kt-menu__item--rel kt-menu__item--active">
                                            <span class="kt-menu__link "> <span class="kt-menu__link-text">
                                            <?= 
                                            Html::beginForm(['/site/logout'], 'post')
                                            . Html::submitButton(
                                                'LOGOUT',
                                                ['class' => 'btn btn-logout', 
                                                'style' => "
                                                    color: #5d78ff; 
                                                    font-family: 'Asap Condensed';
                                                    font-weight: 500;
                                                    font-size: 1.2rem;
                                                    "]
                                            )
                                            . Html::endForm()
                                            ?>
                                        </span></span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <!-- end: Header Menu -->
                        </div>
                    </div>
                </div>

                <div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--ver kt-grid--stretch">
                    <div class="kt-container kt-body kt-grid kt-grid--ver" id="kt_body">
                        <div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor">
                            <div class="kt-subheader   kt-grid__item" id="kt_subheader">
                                <div class="kt-subheader__main">
                                    <h3 class="kt-subheader__title"><?= $this->title ?></h3>
                                </div>
                            </div>

                            <div class="kt-content kt-grid__item kt-grid__item--fluid" id="kt_content">
                                <div class="kt-portlet kt-portlet--height-fluid">
                                    <div class="kt-portlet__body">
                                        <div class="kt-widget12">
                                            <div class="kt-widget12__content">
                                                <div class="kt-widget12__item">
                                                    <div class="kt-widget12__info">
                                                        <?= $content ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="kt-footer kt-grid__item" id="kt_footer">
                    <div class="kt-container">
                        <div class="kt-footer__wrapper">
                            <div class="kt-footer__copyright">
                                <?= date('Y') ?> &copy; RSUD Wates
                            </div>
                            <div class="kt-footer__menu">
                                <a href="#" target="_blank" class="kt-link">Panduan</a>
                                <a href="#" target="_blank" class="kt-link">FAQ</a>
                                <a href="#" target="_blank" class="kt-link">Support</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


<?php /*** ?>
<pre>
<?php 
echo '<hr />';
print_r($menu);
echo '<hr />';
print_r($menu_child);
echo '<hr />';

?>
</pre>
<?php /***/ ?>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>