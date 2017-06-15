<?php

use yii\db\Migration;

/**
 * Handles the creation of table `goods`.
 */
class m170612_061244_create_goods_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('goods', [
            'id' => $this->primaryKey(),
            'name'=>$this->string()->notNull()->comment('商品名称'),
            'sn'=>$this->string()->comment('货号'),
            'logo'=>$this->string()->comment('LOGO'),
            'goods_category_id'=>$this->integer()->comment('商品分类ID'),
            'brand_id'=>$this->integer()->comment('品牌分类'),
            'market_price'=>$this->decimal()->comment('市场价格'),
            'shop_price'=>$this->decimal()->comment('商品价格'),
            'stock'=>$this->integer()->comment('库存'),
            'is_on_sale'=>$this->integer()->comment('是否在售(1在售,0下架)'),
            'status'=>$this->smallInteger()->comment('状态(1正常,0回收站)'),
            'sort'=>$this->integer()->comment('排序'),
            'create_time'=>$this->integer()->comment('添加时间'),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('goods');
    }
}
