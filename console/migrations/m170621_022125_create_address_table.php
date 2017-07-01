<?php

use yii\db\Migration;

/**
 * Handles the creation of table `address`.
 */
class m170621_022125_create_address_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('address', [
            'id' => $this->primaryKey(),
            'username'=>$this->string()->notNull()->comment('收货姓名'),
            'locations'=>$this->string()->comment('收货地址'),
            'location_detailed'=>$this->string()->comment('详细地址'),
            'tel'=>$this->integer()->comment('电话号码'),
            'is_default'=>$this->smallInteger(2)->comment('是否设为默认地址'),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('address');
    }
}
