<?php

namespace app\modules\order\controllers;

use app\modules\order\models\Order;
use app\modules\order\models\SearchForm;
use app\modules\order\models\Status;
use app\modules\order\models\Mode;
use yii\web\Controller;
use yii\data\Pagination;
use yii;

class MainController extends Controller
{

    function actionIndex()
    {
        $status = Yii::$app->request->get('status');
        $mode = Yii::$app->request->get('mode');
        $service_id = Yii::$app->request->get('service_id');
        $search_type = Yii::$app->request->get('search-type');
        $search = Yii::$app->request->get('search');
        $arr = [
          'status'     => $status,
          'mode'       => $mode,
          'service_id' => $service_id,
        ];

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

            $query = Order::find();
            $count = $query->where($array)->andFilterWhere($like)->count();
            $pages = new Pagination([
              'totalCount' => $count,
              'pageSize'   => 100,
            ]);
            $orders = $query->where($array)->andFilterWhere($like)
              ->orderBy(['id' => SORT_DESC])
              ->offset($pages->offset)
              ->limit($pages->limit)->joinWith('service')->asArray()
              ->all();
            $pages->offset + $pages->limit < $count ?
              $range = $pages->offset + $pages->limit : $range = $count;
        } else {
            $query = Order::find();
            $count = $query->count();
            $pages = new Pagination([
              'totalCount' => $count,
              'pageSize'   => 100,
            ]);
            $orders = $query->orderBy(['id' => SORT_DESC])
              ->offset($pages->offset)
              ->limit($pages->limit)->joinWith('service')->asArray()
              ->all();
            $pages->offset + $pages->limit < $count ?
              $range = $pages->offset + $pages->limit : $range = $count;
        }

        $orders_quantity = Order::find()
          ->select([
            'service_id',
            'name',
            'SUM(quantity) AS quantity',
          ])
          ->groupBy(['service_id'])
          ->orderBy(['quantity' => SORT_DESC])
          ->joinWith('service')
          ->asArray()
          ->createCommand()
          ->queryAll();

        $orders_sum = Order::find()
          ->select(['SUM(quantity) AS quantity'])
          ->asArray()
          ->createCommand()
          ->queryAll();

        $status_type = Status::getStatus();

        if (isset($status)) {
            $status_url = '&status=' . $status;
        }

        $mode_type = Mode::getMode();

        $form_model = new SearchForm();

        return $this->render('index', [
          'orders'          => $orders,
          'pages'           => $pages,
          'count'           => $count,
          'range'           => $range,
          'status'          => $status,
          'mode'            => $mode,
          'service_id'      => $service_id,
          'array'           => $array,
          'orders_quantity' => $orders_quantity,
          'orders_sum'      => $orders_sum,
          'form_model'      => $form_model,
          'search'          => $search,
          'search_type'     => $search_type,
          'status_type'     => $status_type,
          'mode_type'       => $mode_type,
          'status_url'      => $status_url
        ]);
    }

}