<?php use yii\widgets\LinkPager;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
$this->title = 'Sort';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        .label-default {
            border: 1px solid #ddd;
            background: none;
            color: #333;
            min-width: 30px;
            display: inline-block;
        }
    </style>
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body>
<nav class="navbar navbar-fixed-top navbar-default">
    <div class="container-fluid">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed"
                    data-toggle="collapse" data-target="#bs-navbar-collapse">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
        </div>
        <div class="collapse navbar-collapse" id="bs-navbar-collapse">
            <ul class="nav navbar-nav">
                <li class="active"><a
                            href="<?= Url::to(['main/index']); ?>"><?= Yii::t('common',
                          'Orders') ?></a></li>
            </ul>
        </div>
    </div>
</nav>
<div class="container-fluid">
    <ul class="nav nav-tabs p-b">
        <li <?php if (!isset($status)): ?>class="active" <?php endif; ?>><a
                    href="<?= Url::to([
                      'main/index',
                      'search'      => $search,
                      'search-type' => $search_type,
                    ]); ?>">
                <?= Yii::t('common', 'All Orders') ?></a>
        </li>
        <?php foreach ($status_type as $key => $value): ?>
            <li <?php if (isset($status)
            && ($status == $key)): ?>class="active" <?php endif; ?>>
                <a href="<?= Url::to([
                  'main/index',
                  'status'      => $key,
                  'search'      => $search,
                  'search-type' => $search_type,
                ]); ?>"><?= Yii::t('common', $value) ?>
                </a>
            </li>
        <?php endforeach; ?>

        <li class="pull-right custom-search">
            <?php $form = ActiveForm::begin([
              'layout' => 'inline',
              'method' => 'get',
              'action' => '/web/index.php?r=order/main/index' . $status_url,
            ]) ?>
            <div class="input-group">
                <input type="text" name="search" class="form-control" value=""
                       placeholder="Search orders">
                <span class="input-group-btn search-select-wrap">

            <select class="form-control search-select" name="search-type">
              <option value="1" <?php if (isset($search_type)
              && ($search_type == 1)): ?>selected <?php endif; ?>>
                  <?= Yii::t('common', 'Order ID') ?>
              </option>
              <option value="2" <?php if (isset($search_type)
              && ($search_type == 2)): ?>selected <?php endif; ?>>
                  <?= Yii::t('common', 'Link') ?>
              </option>
              <option value="3" <?php if (isset($search_type)
              && ($search_type == 3)): ?>selected <?php endif; ?>>
                  <?= Yii::t('common', 'Username') ?>
              </option>
            </select>
            <button type="submit" class="btn btn-default"><span
                        class="glyphicon glyphicon-search"
                        aria-hidden="true"></span></button>
            </span>
            </div>
            <?php ActiveForm::end() ?>
        </li>
    </ul>
    <table class="table order-table">
        <thead>
        <tr>
            <th><?= Yii::t('common', 'ID') ?></th>
            <th><?= Yii::t('common', 'User') ?></th>
            <th><?= Yii::t('common', 'Link') ?></th>
            <th><?= Yii::t('common', 'Quantity') ?></th>
            <th class="dropdown-th">
                <div class="dropdown">
                    <button class="btn btn-th btn-default dropdown-toggle"
                            type="button" id="dropdownMenu1"
                            data-toggle="dropdown" aria-haspopup="true"
                            aria-expanded="true">
                        <?= Yii::t('common', 'Service') ?>
                        <span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
                        <li
                          <?php if (!isset($service_id)): ?>class="active"<?php endif; ?>>
                            <a href="<?= Url::to([
                              'main/index',
                              'mode' => $mode,
                            ]); ?>">
                                All (<?= $orders_sum[0]['quantity'] ?>)
                            </a>
                        </li>
                        <? foreach ($orders_quantity as $order_quantity): ?>
                            <li <?php if (isset($service_id)
                            && $order_quantity['service_id']
                            == $service_id): ?>class="active"<?php endif; ?>>
                                <a href="<?= Url::to([
                                  'main/index',
                                  'service_id'  => $order_quantity['service_id'],
                                  'mode'        => $mode,
                                  'status'      => $status,
                                  'search'      => $search,
                                  'search-type' => $search_type,
                                ]); ?>">
                                    <span class="label-id"><?= $order_quantity['quantity'] ?></span> <?= Yii::t('common',
                                      $order_quantity['name']) ?>
                                </a>
                            </li>
                        <? endforeach; ?>
                    </ul>
                </div>
            </th>
            <th><?= Yii::t('common', 'Status') ?></th>
            <th class="dropdown-th">
                <div class="dropdown">
                    <button class="btn btn-th btn-default dropdown-toggle"
                            type="button" id="dropdownMenu1"
                            data-toggle="dropdown" aria-haspopup="true"
                            aria-expanded="true">
                        <?= Yii::t('common', 'Mode') ?>
                        <span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
                        <li
                          <?php if (!isset($mode)): ?>class="active"<?php endif; ?>>
                            <a href="<?= Url::to([
                              'main/index',
                              'service_id'  => $service_id,
                              'status'      => $status,
                              'search'      => $search,
                              'search-type' => $search_type,
                            ]); ?>">
                                <?= Yii::t('common', 'All') ?>
                            </a>
                        </li>
                        <?php foreach ($mode_type as $key => $value): ?>
                            <li <?php if (isset($mode)
                            && ($key
                              == $mode)): ?>class="active"<?php endif; ?>>
                                <a href="<?= Url::to([
                                  'main/index',
                                  'mode'        => $key,
                                  'service_id'  => $service_id,
                                  'status'      => $status,
                                  'search'      => $search,
                                  'search-type' => $search_type,
                                ]); ?>">
                                    <?= Yii::t('common', $value) ?>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </th>
            <th><?= Yii::t('common', 'Created') ?></th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($orders as $order): ?>
            <tr>
                <td><?= $order['id'] ?></td>
                <td><?= $order['user'] ?></td>
                <td class="link"><?= $order['link'] ?></td>
                <td><?= $order['quantity'] ?></td>
                <td class="service">
                    <span class="label-id"><?= $order['service_id'] ?></span> <?= $order['service']['name'] ?>
                </td>
                <td><? foreach ($status_type as $key => $value):
                        if ($order['status'] == $key): ?>
                            <?= $value ?>
                            <? break; endif; endforeach; ?>
                </td>
                <td><? foreach ($mode_type as $key => $value):
                        if ($order['mode'] == $key): ?>
                            <?= $value ?>
                            <? break; endif; endforeach; ?>
                </td>
                <td>
                <span class="nowrap"><?= DateTime::createFromFormat('U',
                      $order['created_at'])->format('d-m-Y'); ?>
                   </span>
                    <span class="nowrap"><?= DateTime::createFromFormat('U',
                          $order['created_at'])->format('H:i:s'); ?>
                </span>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    <div class="row">
        <div class="col-sm-8">

            <nav>
                <ul class="pagination">
                    <?= LinkPager::widget([
                      'pagination' => $pages,
                    ]); ?>
                </ul>
            </nav>

        </div>
        <div class="col-sm-4 pagination-counters">
            <?php if (!$order): ?>
                <?= Yii::t('common', 'Nothing Found') ?>
            <?php elseif ($count <= $pages->limit):
                echo 'Found: ' . $count;
            else: ?>
                <?= $pages->offset + 1 ?> to <?= $range ?> of <?= $count ?>
            <?php endif; ?>
        </div>
        <a href="<?= Url::to([
          'export/index',
          'service_id'  => $service_id,
          'status'      => $status,
          'search'      => $search,
          'search-type' => $search_type,
        ]); ?>">Скачать</a>
    </div>
</div>
</body>
</html>
