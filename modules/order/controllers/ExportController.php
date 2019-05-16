<?php
/**
 * Created by PhpStorm.
 * User: Дмитрий
 * Date: 15.05.2019
 * Time: 19:05
 */

namespace app\modules\order\controllers;

use app\modules\order\models\Order;
use app\modules\order\models\Status;
use app\modules\order\models\Mode;
use yii\web\Controller;
use yii;

class ExportController extends Controller
{

    public function actionIndex()
    {

        $status = Yii::$app->request->get('status');
        $mode = Yii::$app->request->get('mode');
        $service_id = Yii::$app->request->get('service_id');
        $search_type = Yii::$app->request->get('search-type');
        $search = Yii::$app->request->get('search');
        $arr = [
          'status' => $status,
          'mode' => $mode,
          'service_id' => $service_id,
        ];

        $data = "Id;User;Link;Quantity;Service;Status;Mode;Created\r\n";
        if (isset($mode) || isset($service_id) || isset($status)
          || isset($search)
        ) {
            $array = [];
            foreach ($arr as $key => $value) {
                if (isset($value)) {
                    $array[$key] = $value;
                }
            }
            $like = [];
            switch ($search_type) {
                case 1:
                    $like = ['like', 'orders.id', $search];
                    break;
                case 2:
                    $like = ['like', 'link', $search];
                    break;
                case 3:
                    $like = ['like', 'user', $search];
                    break;
            }

            $model = Order::find()->where($array)->andFilterWhere($like)
              ->orderBy(['id' => SORT_DESC])
              ->joinWith('service')->asArray()
              ->all();
        } else {
            $model = Order::find()->orderBy(['id' => SORT_DESC])
              ->joinWith('service')->asArray()
              ->all();
        }
        $status_type = Status::getStatus();
        $mode_type = Mode::getMode();
        foreach ($model as $value) {
            $data .= $value['id'] . ';';
            $data .= $value['user'] . ';';
            $data .= $value['link'] . ';';
            $data .= $value['quantity'] . ';';
            $data .= $value['service']['name'] . ';';
            foreach ($status_type as $key => $val):
                if ($key == $value['status']):
                    $data .= $val . ';';
                    break;
                endif;
            endforeach;
            foreach ($mode_type as $key => $val):
                if ($key == $value['mode']):
                    $data .= $val . ';';
                    break;
                endif;
            endforeach;

            $data .= \DateTime::createFromFormat('U', $value['created_at'])
              ->format('d-m-Y H:i:s');

            $data .= "\r\n";
        }

        header('Content-type: text/csv');
        header('Content-Disposition: attachment; filename="export_'
          . date('d.m.Y') . '.csv"');
        header("Pragma: no-cache");
        header("Expires: 0");

        return $data;

        Yii::$app->end();
    }

}