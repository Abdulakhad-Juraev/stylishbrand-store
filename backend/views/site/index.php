<?php

use common\modules\order\models\Order;
use common\modules\product\models\Category;
use common\modules\product\models\Product;
use soft\widget\adminlte3\InfoBoxWidget;

$this->title = Yii::$app->name;

$user = Yii::$app->user;

?>

<?='' //$this->render('_info_box') ?>
<div class="container-fluid">
<div class="row">
    <div class="col">
<!--        --><?php //= $this->render('_daily_registrants') ?>



<?php


$this->title = Yii::t('app','brand_project');;
echo "<h2 class='ml-2'>".$this->title."</h2>";
$products = Product::find()->count();
$categories = Category::find()->count();
$order = Order::find()->count();
//?>
<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <!-- /.col -->
            <div class="col-md-3">
                <div class="card card-success  ">
                    <div class="card-header">
                        <h3 class="card-title">Mahsulotlar</h3>

                        <div class="card-tools">


                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                <i class="fas fa-plus"></i>
                            </button>
                            <button type="button" class="btn btn-tool" data-card-widget="remove">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                        <!-- /.card-tools -->
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        Mahsulotlar soni <?= $products; ?>
                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->
            </div>
            <!-- /.col -->

            <!-- /.col -->
            <div class="col-md-3">
                <div class="card card-danger  ">
                    <div class="card-header">
                        <h3 class="card-title">Kategoriyalar</h3>

                        <div class="card-tools">


                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                <i class="fas fa-plus"></i>
                            </button>
                            <button type="button" class="btn btn-tool" data-card-widget="remove">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                        <!-- /.card-tools -->
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        Kategoriyalar soni <?=$categories;?>
                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->
            </div>
            <!-- /.col -->

            <!-- /.col -->

<!--            <div class="col-md-3">-->
<!--                <div class="card card-secondary">-->
<!--                    <div class="card-header">-->
<!--                        <h3 class="card-title">Obunalar</h3>-->
<!---->
<!--                        <div class="card-tools">-->
<!--                            <button type="button" class="btn btn-tool" data-card-widget="collapse">-->
<!--                                <i class="fas fa-minus"></i>-->
<!--                            </button>-->
<!--                            <button type="button" class="btn btn-tool" data-card-widget="remove">-->
<!--                                <i class="fas fa-times"></i>-->
<!--                            </button>-->
<!--                        </div>-->
                        <!-- /.card-tools -->
<!--                    </div>-->
                    <!-- /.card-header -->
<!--                    <div class="card-body" >-->
<!--                        Xabarlar soni --><?php //=$signCount;?>
<!--                    </div>-->
                    <!-- /.card-body -->
<!--                </div>-->
                <!-- /.card -->
<!--            </div>-->
            <!-- /.col -->

            <!-- /.col -->
            <div class="col-md-3">
                <div class="card card-primary  ">
                    <div class="card-header">
                        <h3 class="card-title">Buyurtmalar</h3>

                        <div class="card-tools">


                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                <i class="fas fa-plus"></i>
                            </button>
                            <button type="button" class="btn btn-tool" data-card-widget="remove">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                        <!-- /.card-tools -->
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        Buyurtmalar soni <?=$order;?>
                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->
            </div>
            <!-- /.col -->
        </div>

    </div>
</section>
    </div>
</div>
</div>