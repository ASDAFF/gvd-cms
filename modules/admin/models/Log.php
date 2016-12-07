<?php

namespace app\modules\admin\models;

use app\modules\pages\models\Page;
use app\modules\photo\models\Photo;
use app\modules\photo\models\PhotoCategory;
use app\modules\sliders\models\SliderItem;
use app\modules\video\models\VideoCategory;
use app\modules\video\models\Video;
use Yii;
use app\modules\gvd_user\models\User;
use app\modules\news\models\News;

/**
 * This is the model class for table "log".
 *
 * @property integer $log_id
 * @property string $item_class
 * @property integer $item_id
 * @property string $item_name
 * @property integer $user_id
 * @property string $action
 * @property string $time
 * @property string $user_ip
 * @property string $user_agent
 */
class Log extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'log';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'action', 'user_ip', 'user_agent'], 'required'],
            [['item_class', 'item_name', 'action', 'user_agent'], 'string'],
            [['item_id', 'user_id'], 'integer'],
            [['user_ip'], 'string', 'max' => 255],

            [['item_id'], 'default', 'value' => null]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'log_id' => 'Log ID',
            'item_class' => 'Item Class',
            'item_id' => 'Item ID',
            'item_name' => 'Item Name',
            'user_id' => 'User ID',
            'action' => 'Action',
            'time' => 'Time',
            'user_ip' => 'User Ip',
            'user_agent' => 'User Agent',
        ];
    }

    public function getUser() {
        return User::findOne(['id' => $this->user_id]);
    }

    public function getOS() {
        $os_platform = "Неизвестная ОС";

        $os_array = array(
            '/windows nt 10/i'     =>  'Windows 10',
            '/windows nt 6.3/i'     =>  'Windows 8.1',
            '/windows nt 6.2/i'     =>  'Windows 8',
            '/windows nt 6.1/i'     =>  'Windows 7',
            '/windows nt 6.0/i'     =>  'Windows Vista',
            '/windows nt 5.2/i'     =>  'Windows Server 2003/XP x64',
            '/windows nt 5.1/i'     =>  'Windows XP',
            '/windows xp/i'         =>  'Windows XP',
            '/windows nt 5.0/i'     =>  'Windows 2000',
            '/windows me/i'         =>  'Windows ME',
            '/win98/i'              =>  'Windows 98',
            '/win95/i'              =>  'Windows 95',
            '/win16/i'              =>  'Windows 3.11',
            '/macintosh|mac os x/i' =>  'Mac OS X',
            '/mac_powerpc/i'        =>  'Mac OS 9',
            '/linux/i'              =>  'Linux',
            '/ubuntu/i'             =>  'Ubuntu',
            '/iphone/i'             =>  'iPhone',
            '/ipod/i'               =>  'iPod',
            '/ipad/i'               =>  'iPad',
            '/android/i'            =>  'Android',
            '/blackberry/i'         =>  'BlackBerry',
            '/webos/i'              =>  'Mobile'
        );

        foreach ($os_array as $regex => $value) {

            if (preg_match($regex, $this->user_agent)) {
                $os_platform    =   $value;
            }

        }

        return $os_platform;
    }

    public function getBrowser() {
        $browser = "Неизвестный браузер";

        $browser_array = array(
            '/msie/i'       =>  'Internet Explorer',
            '/firefox/i'    =>  'Mozilla Firefox',
            '/safari/i'     =>  'Safari',
            '/chrome/i'     =>  'Google Chrome',
            '/edge/i'       =>  'Microsoft Edge',
            '/opera/i'      =>  'Opera',
            '/opr/i'        =>  'Opera',
            '/yabrowser/i'  =>  'Yandex Browser',
            '/netscape/i'   =>  'Netscape',
            '/maxthon/i'    =>  'Maxthon',
            '/konqueror/i'  =>  'Konqueror',
            '/mobile/i'     =>  'Handheld Browser'
        );

        foreach ($browser_array as $regex => $value) {

            if (preg_match($regex, $this->user_agent)) {
                $browser    =   $value;
            }

        }

        return $browser;
    }

    public function getNews() {
        $news_item = News::findOne(['news_id' => $this->item_id]);
        if ($news_item) return $news_item;
        return null;
    }

    public function getVideoCategory() {
        $item = VideoCategory::findOne(['video_category_id' => $this->item_id]);
        if ($item) return $item;
        return null;
    }

    public function getVideo() {
        $item = Video::findOne(['video_id' => $this->item_id]);
        if ($item) return $item;
        return null;
    }

    public function getPhotoCategory() {
        $item = PhotoCategory::findOne(['photo_category_id' => $this->item_id]);
        if ($item) return $item;
        return null;
    }

    public function getPhoto() {
        $item = Photo::findOne(['photo_id' => $this->item_id]);
        if ($item) return $item;
        return null;
    }

    public function getPage() {
        $page_item = Page::findOne(['page_id' => $this->item_id]);
        if ($page_item) return $page_item;
        return null;
    }

    public function getSlide() {
        $slide_item = SliderItem::findOne(['slider_item_id' => $this->item_id]);
        if ($slide_item) return $slide_item;
        return null;
    }
}
