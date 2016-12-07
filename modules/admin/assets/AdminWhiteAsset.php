<?php

namespace app\modules\admin\assets;

use yii\web\AssetBundle;

class AdminWhiteAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';

    public $css = [
        '//fonts.googleapis.com/css?family=Roboto:300,400,400i,500,700',
        'css/bootstrap.min.css',
        'css/morris.css',
        'css/jquery-jvectormap-2.0.3.css',
        'css/adminnine.css',
        'css/font-awesome.min.css'
    ];
    public $js = [
        //'js/jquery.min.js',
        'js/bootstrap.min.js',

        'js/jquery.dataTables.min.js',
        'js/dataTables.bootstrap.min.js',
        'js/dataTables.responsive.js',

        'js/raphael.js',
        'js/morris.min.js',
        //'js/morris-data.js',

        'js/jquery-jvectormap.js',
        'js/jquery-jvectormap-world-mill-en.js',

        'js/masonry.pkgd.min.js',

        'js/dropzone.js',

        'js/adminnine.js',
        'js/user.js',
        'js/news.js',
        'js/log.js',
        'js/video.js',
        'js/photo.js',
        'js/pages.js',
        'js/sliders.js'
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}