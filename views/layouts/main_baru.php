<?php

/* @var $this \yii\web\View */
/* @var $content string */

// use app\widgets\Alert;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Breadcrumbs;
use app\assets\MetronicAssetBaru;
use app\models\Menu;
use app\models\Dokter;

MetronicAssetBaru::register($this);
$role = Yii::$app->user->identity->role;

# GET ICON HERE: https://keenthemes.com/metronic/preview/?page=components/icons/flaticon&demo=demo5


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
// echo '<pre>';
// print_r($menu_child);exit;

?>

<?php $this->beginPage() ?>

<!DOCTYPE html>

<html lang="en">

	<!-- begin::Head -->
	<head>
		<meta charset="utf-8" />
		<title><?= Yii::$app->name ?></title>
		<meta name="description" content="Latest updates and statistic charts">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

		<!--begin::Fonts -->
		<script src="https://ajax.googleapis.com/ajax/libs/webfont/1.6.16/webfont.js"></script>
		<script>
			WebFont.load({
				google: {
					"families": ["Poppins:300,400,500,600,700", "Roboto:300,400,500,600,700"]
				},
				active: function() {
					sessionStorage.fonts = true;
				}
			});
		</script>
		<meta charset="<?= Yii::$app->charset ?>">
	    <meta http-equiv="X-UA-Compatible" content="IE=edge">
	    <meta name="viewport" content="width=device-width, initial-scale=1">
	    <?php $this->registerCsrfMetaTags() ?>
	    <title><?= Html::encode($this->title) ?></title>
	    <?php $this->head() ?>
		<link rel="shortcut icon" href="images/favicon.ico" />

	</head>
	<!-- end::Head -->

	<!-- begin::Body -->
	<body class="kt-header--fixed kt-header-mobile--fixed kt-subheader--enabled kt-subheader--transparent kt-aside--enabled kt-aside--fixed kt-aside--minimize kt-page--loading">
	<?php $this->beginBody() ?>
		<!-- begin:: Page -->

		<!-- begin:: Header Mobile -->
		<div id="kt_header_mobile" class="kt-header-mobile  kt-header-mobile--fixed ">
			<div class="kt-header-mobile__logo">
				<a href="<?= Url::to(['/site/index']) ?>">
					<img alt="Logo" src="images/rsz_logo-only.png" />
				</a>
			</div>
			<div class="kt-header-mobile__toolbar">
				<button class="kt-header-mobile__toolbar-toggler kt-header-mobile__toolbar-toggler--left" id="kt_aside_mobile_toggler"><span></span></button>
				<button class="kt-header-mobile__toolbar-toggler" id="kt_header_mobile_toggler"><span></span></button>
				<button class="kt-header-mobile__toolbar-topbar-toggler" id="kt_header_mobile_topbar_toggler"><i class="flaticon-more"></i></button>
			</div>
		</div>

		<!-- end:: Header Mobile -->
		<div class="kt-grid kt-grid--hor kt-grid--root">
			<div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--ver kt-page">

				<!-- begin:: Aside -->
				<button class="kt-aside-close " id="kt_aside_close_btn"><i class="la la-close"></i></button>
				<div class="kt-aside  kt-aside--fixed  kt-grid__item kt-grid kt-grid--desktop kt-grid--hor-desktop" id="kt_aside">

					<!-- begin:: Brand -->
					<div class="kt-aside__brand kt-grid__item " id="kt_aside_brand">
						<div class="kt-aside__brand-logo">
							<a href="<?= Url::to(['/site/index']) ?>">
								<img alt="Logo" src="images/rsz_logo-only.png" />
							</a>
						</div>
					</div>

					<!-- end:: Brand -->

					<!-- begin:: Aside Menu -->
					<div class="kt-aside-menu-wrapper kt-grid__item kt-grid__item--fluid" id="kt_aside_menu_wrapper">
						<div id="kt_aside_menu" class="kt-aside-menu  kt-aside-menu--dropdown " data-ktmenu-vertical="1" data-ktmenu-dropdown="1" data-ktmenu-scroll="0">
							<ul class="kt-menu__nav ">
								<?php foreach($menu as $k=>$v): ?>
								<li class="kt-menu__item  kt-menu__item--submenu kt-menu__item--submenu-fullheight kt-menu__item--open kt-menu__item--here" aria-haspopup="true" data-ktmenu-submenu-toggle="click" data-ktmenu-dropdown-toggle-class="kt-aside-menu-overlay--on">
									<?php if(isset($v['route'])): ?>
									<a href="<?= Url::to($v['route']) ?>" class="kt-menu__link kt-menu__toggle"><i class="kt-menu__link-icon <?= $v['icon'] ?>"></i><span class="kt-menu__link-text"><?= $v['nama'] ?></span><i class="kt-menu__ver-arrow la la-angle-right"></i></a>
									<?php else: ?>
									<a href="javascript:;" class="kt-menu__link kt-menu__toggle"><i class="kt-menu__link-icon <?= $v['icon'] ?>"></i><span class="kt-menu__link-text"><?= $v['nama'] ?></span><i class="kt-menu__ver-arrow la la-angle-right"></i></a>
									<div class="kt-menu__submenu "><span class="kt-menu__arrow"></span>
										<div class="kt-menu__wrapper">
											<ul class="kt-menu__subnav">
												<li class="kt-menu__item  kt-menu__item--parent kt-menu__item--submenu-fullheight" aria-haspopup="true"><span class="kt-menu__link"><span class="kt-menu__link-text"><?= $v['nama'] ?></span></span></li>
												<?php foreach($menu_child[$k]['nama'] as $k2=>$v2): ?>
												<li class="kt-menu__item " aria-haspopup="true"><a href="<?= Url::to($menu_child[$k]['route'][$k2]) ?>" class="kt-menu__link "><span class="kt-menu__link-text"><?= $v2 ?></span></a></li>
												<?php endforeach; ?>

											</ul>
										</div>
									</div>
									<?php endif; ?>
								</li>
								<?php endforeach; ?>
							</ul>
						</div>
					</div>

					<!-- end:: Aside Menu -->
				</div>
				<div class="kt-aside-menu-overlay"></div>
				<!-- end:: Aside -->
				<div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor kt-wrapper" id="kt_wrapper">

					<!-- begin:: Header -->
					<div id="kt_header" class="kt-header kt-grid kt-grid--ver  kt-header--fixed ">

						<!-- begin: Header Menu -->
						<button class="kt-header-menu-wrapper-close" id="kt_header_menu_mobile_close_btn"><i class="la la-close"></i></button>
						<div class="kt-header-menu-wrapper kt-grid__item kt-grid__item--fluid" id="kt_header_menu_wrapper">
							<div id="kt_header_menu" class="kt-header-menu kt-header-menu-mobile  kt-header-menu--layout- ">
								<ul class="kt-menu__nav ">
									<li class="kt-menu__item  kt-menu__item--active " aria-haspopup="true"><a href="<?= Url::to(['/site/index']) ?>" class="kt-menu__link "><span class="kt-menu__link-text">Dashboard</span></a></li>
									<li class="kt-menu__item  kt-menu__item--active " aria-haspopup="true"><a href="<?= Url::to(['/program/index']) ?>" class="kt-menu__link "><span class="kt-menu__link-text">Program</span></a></li>
									<li class="kt-menu__item  kt-menu__item--active " aria-haspopup="true"><a href="<?= Url::to(['/kegiatan/index']) ?>" class="kt-menu__link "><span class="kt-menu__link-text">Kegiatan</span></a></li>
								</ul>
							</div>
						</div>
						<!-- end: Header Menu -->
						<!-- begin:: Header Topbar -->
						<div class="kt-header__topbar">
							<div class="kt-header__topbar-item kt-header__topbar-item--user">
							<?php 
								echo Html::beginForm(['/site/logout'], 'post'); 
								echo Html::submitButton(
			                        'Logout (' . Yii::$app->user->identity->username . ')',
			                        ['class' => 'btn btn-label-brand btn-sm btn-bold']
			                    );
			                    echo Html::endForm();
							?>
							</div>
							<!--begin: User bar -->
							<div class="kt-header__topbar-item kt-header__topbar-item--user">
								<div class="kt-header__topbar-wrapper" data-toggle="dropdown" data-offset="10px,0px">
									<span class="kt-hidden kt-header__topbar-welcome">Hi,</span>
									<span class="kt-hidden kt-header__topbar-username"><?= Yii::$app->user->identity->username ?></span>
									<img class="kt-hidden" alt="Pic" src="metronic7/assets/media/users/300_21.jpg" />
									<span class="kt-header__topbar-icon"><i class="flaticon2-user-outline-symbol"></i></span>
								</div>
								<div class="dropdown-menu dropdown-menu-fit dropdown-menu-right dropdown-menu-anim dropdown-menu-xl">

									<!--begin: Head -->
									<div class="kt-user-card kt-user-card--skin-light kt-notification-item-padding-x">
										<div class="kt-user-card__avatar">
											<img class="kt-hidden-" alt="Pic" src="metronic7/assets/media/users/300_25.jpg" />

											<!--use below badge element instead the user avatar to display username's first letter(remove kt-hidden class to display it) -->
											<span class="kt-badge kt-badge--username kt-badge--unified-success kt-badge--lg kt-badge--rounded kt-badge--bold kt-hidden">S</span>
										</div>
										<div class="kt-user-card__name">
											<?= Yii::$app->user->identity->username ?>
										</div>
										<!-- <div class="kt-user-card__badge">
											<span class="btn btn-label-primary btn-sm btn-bold btn-font-md">23 messages</span>
										</div> -->
									</div>

									<!--end: Head -->

									<!--begin: Navigation -->
									<div class="kt-notification">
										<a href="#" class="kt-notification__item">
											<div class="kt-notification__item-icon">
												<i class="flaticon2-calendar-3 kt-font-success"></i>
											</div>
											<div class="kt-notification__item-details">
												<div class="kt-notification__item-title kt-font-bold">
													My Profile
												</div>
												<div class="kt-notification__item-time">
													Account settings and more
												</div>
											</div>
										</a>
										
										<div class="kt-notification__custom">
											<?php 
											echo Html::beginForm(['/site/logout'], 'post'); 
											echo Html::submitButton(
						                        'Logout (' . Yii::$app->user->identity->username . ')',
						                        ['class' => 'btn btn-label-brand btn-sm btn-bold']
						                    );
						                    echo Html::endForm();

											?>
											<!-- <a href="custom_user_login-v2.html" target="_blank" class="btn btn-label-brand btn-sm btn-bold">Sign Out</a> -->
										</div>
									</div>

									<!--end: Navigation -->
								</div>
							</div>

							<!--end: User bar -->

							<!-- end:: Header Topbar -->
						</div>
					</div>

					<!-- end:: Header -->

					<div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor">

						<!-- begin:: Subheader -->
						<div class="kt-subheader   kt-grid__item" id="kt_subheader">
							<div class="kt-subheader__main">
								<h3 class="kt-subheader__title">
									<?= $this->title ?> </h3>
							</div>
							<div class="kt-subheader__toolbar">
								<div class="kt-subheader__wrapper">
								</div>
							</div>
						</div>
						<!-- end:: Subheader -->

						<!-- begin:: Content -->
						<div class="kt-content  kt-grid__item kt-grid__item--fluid" id="kt_content">

							<!--Begin::Dashboard 7-->

							<!--Begin::Section-->
							<div class="row">
								<div class="col-xl-12">
									<div class="kt-portlet kt-portlet--height-fluid kt-portlet--mobile ">
										<div class="kt-portlet__head kt-portlet__head--lg kt-portlet__head--noborder kt-portlet__head--break-sm">
											<!-- <div class="kt-portlet__head-label">
												<h3 class="kt-portlet__head-title">
												</h3>
											</div> -->
											<div class="kt-portlet__head-toolbar">
												<?= Breadcrumbs::widget([
										            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
										        ]) ?>
											</div>
										</div>
										<div class="kt-portlet__body">
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

		<script>
			var KTAppOptions = {
				"colors": {
					"state": {
						"brand": "#22b9ff",
						"light": "#ffffff",
						"dark": "#282a3c",
						"primary": "#5867dd",
						"success": "#34bfa3",
						"info": "#36a3f7",
						"warning": "#ffb822",
						"danger": "#fd3995"
					},
					"base": {
						"label": ["#c5cbe3", "#a1a8c3", "#3d4465", "#3e4466"],
						"shape": ["#f0f3ff", "#d9dffa", "#afb4d4", "#646c9a"]
					}
				}
			};
		</script>
	
	<?php $this->endBody() ?>

	</body>
</html>
<?php $this->endPage() ?>
