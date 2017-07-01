<?php

use yii\db\Migration;

/**
 * Handles the creation of table `flow`.
 */
class m170623_081544_create_flow_table extends Migration
{
    /**
     * @inheritdoc
     */
//id 	primaryKey
//goods_id 	int 	商品id
//amount 	int 	商品数量
//member_id 	int 	用户id
    public function up()
    {
        $this->createTable('flow', [
            'id' => $this->primaryKey(),
            'goods_id'=>$this->integer()->comment('商品id'),
            'amount'=>$this->integer()->comment('商品数量'),
            'member_id'=>$this->integer()->comment('用户id'),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('flow');
    }
}
