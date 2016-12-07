<?php

namespace app\components\behaviors;

use yii\db\ActiveRecord;
use yii\base\Behavior;
use app\modules\admin\models\Log;
use Yii;

class LogBehavior extends Behavior
{
    private $del_name;
    private $class_name;

    public function events()
    {
        return [
            ActiveRecord::EVENT_BEFORE_DELETE => 'beforeDelete',
            ActiveRecord::EVENT_AFTER_DELETE => 'afterDelete',
            ActiveRecord::EVENT_AFTER_INSERT => 'afterInsert',
        ];
    }

    public function beforeDelete($event)
    {
        switch ($this->owner->className()) {
            case 'app\modules\video\models\Video': {
                $this->del_name = $this->owner->title ? $this->owner->title : $this->owner->url;

                $l = Log::findOne(['item_class' => $this->owner->className(), 'item_id' => $this->owner->primaryKey]);
                if ($l) {
                    $l->item_name = $this->owner->title ? $this->owner->title : $this->owner->url;
                    $l->save();
                }
                break;
            }
            case 'app\modules\photo\models\Photo':
            case 'app\modules\sliders\models\SliderItem': {
                if ($this->owner->photo) {
                    $this->del_name = $this->insert_base64_encoded_image_src(Yii::getAlias('@webroot') . $this->owner->photo);

                    $l = Log::findOne(['item_class' => $this->owner->className(), 'item_id' => $this->owner->primaryKey, 'action' => 'create']);
                    if ($l) {
                        $l->item_name = $this->del_name;
                        $l->save();
                    }
                    $ls = Log::findAll(['item_class' => $this->owner->className(), 'item_id' => $this->owner->primaryKey, 'action' => 'update']);
                    foreach ($ls as $l) {
                        $l->item_name = $this->del_name;
                        $l->save();
                    }
                }
                break;
            }
            default: {
                $this->del_name = $this->owner->title;
                $l = Log::findOne(['item_class' => $this->owner->className(), 'item_id' => $this->owner->primaryKey, 'action' => 'create']);
                if ($l) {
                    $l->item_name = $this->owner->title;
                    $l->save();
                }
                $ls = Log::findAll(['item_class' => $this->owner->className(), 'item_id' => $this->owner->primaryKey, 'action' => 'update']);
                foreach ($ls as $l) {
                    $l->item_name = $this->owner->title;
                    $l->save();
                }
                break;
            }
        }
        $this->class_name = $this->owner->className();
    }

    public function afterDelete($event)
    {
        if (!($this->class_name == 'app\modules\news\models\News' && $this->del_name == '')) {
            $log = new Log();
            $log->action = 'delete';
            $log->user_ip = Yii::$app->request->userIP;
            $log->user_agent = Yii::$app->request->userAgent;
            $log->user_id = Yii::$app->user->id;
            $log->item_name = $this->del_name;
            $log->item_class = $this->class_name;
            $log->save();
        }
    }

    public function afterInsert($event)
    {
        $l = Log::findOne(['item_class' => $this->owner->className(), 'item_id' => $this->owner->primaryKey, 'action' => 'create']);
        if (!$l) {
            if (!($this->owner->className() == 'app\modules\news\models\News' && $this->owner->title == '')) {
                $log = new Log();
                $log->action = 'create';
                $log->user_ip = Yii::$app->request->userIP;
                $log->user_agent = Yii::$app->request->userAgent;
                $log->user_id = Yii::$app->user->id;
                $log->item_id = $this->owner->primaryKey;
                $log->item_class = $this->owner->className();
                $log->save();
            }
        }
    }

    private function insert_base64_encoded_image_src($img){
        $imageSize = getimagesize($img);
        $imageData = base64_encode(file_get_contents($img));
        $imageSrc = "data:{$imageSize['mime']};base64,{$imageData}";
        return $imageSrc;
    }
}

?>