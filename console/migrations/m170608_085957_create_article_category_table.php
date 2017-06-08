<?php

use yii\db\Migration;

/**
 * Handles the creation of table `article_category`.
 */
class m170608_085957_create_article_category_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('article_category', [
            'id' => $this->primaryKey(),
            'name'=>$this->string(50)->notNull()->comment('文章名'),
            'intro'=>$this->text()->comment('内容'),
            'sort'=>$this->integer()->comment('排序'),
            'status'=>$this->smallInteger(2)->comment('状态'),
            'is_help'=>$this->smallInteger(2)->comment('类型')
        ]);
    }




//{
//$this->createTable('brand', [
//'id' => $this->primaryKey(),
//'name'=>$this->string(50)->notNull()->comment('Ʒ��'),
//'intro'=>$this->text()->comment('���'),
//'iogo'=>$this->string(255)->comment('LOGO'),
//'sort'=>$this->integer()->comment('����'),
//'status'=>$this->smallInteger(2)->comment('״ֵ̬')
//]);
//}

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('article_category');
    }
}
