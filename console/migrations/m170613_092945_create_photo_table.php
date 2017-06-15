<?php

use yii\db\Migration;

/**
 * Handles the creation of table `photo`.
 */
class m170613_092945_create_photo_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('photo', [
            'id' => $this->primaryKey(),
            'url'=>$this->string()->comment('图片地址'),
            'good_id'=>$this->integer()->comment('商品名字')
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('photo');
    }
}
