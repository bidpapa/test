<?php

namespace app\modules\order\models;

use yii\db\ActiveRecord;

class Mode extends ActiveRecord {
    public static function getMode()
    {
        return [0 => 'Manual', 1 => 'Auto'];
    }
}