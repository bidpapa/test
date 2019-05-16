<?php

namespace app\modules\order\models;

use yii\db\ActiveRecord;

class Status extends ActiveRecord {
    public static function getStatus()
    {
        return [0 => 'Pending', 1 => 'In Progress', 2 => 'Completed', 3 => 'Cancelled', 4 => 'Error'];
    }
}