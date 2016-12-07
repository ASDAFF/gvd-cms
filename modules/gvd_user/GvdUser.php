<?php

namespace app\modules\gvd_user;

use yii\base\Module;

/**
 * user module definition class
 */
class GvdUser extends Module
{
    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'yii\easyii\modules\gvd_user\controllers';

    public $domain = 'ih172726.myihor.ru';

    public static $this_domain = null;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        self::$this_domain = $this->domain;

        // custom initialization code goes here
    }

    public static function getDomain() {
        return self::$this_domain;
    }
}
