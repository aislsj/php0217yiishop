<?php

use yii\db\Migration;

/**
 * Handles the creation of table `member`.
 */
class m170619_032750_create_member_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('member', [
            'id' => $this->primaryKey(),
          'username'=>$this->string(32)->notNull()->comment('用户名'),
            'password_hash'=>$this->string(150)->notNull()->comment('密码'),
            'email'=>$this->string(100)->comment('邮箱'),
            'tel'=>$this->char(11)->comment('电话'),
            'last_login_time'=>$this->integer()->comment('最后登录时间'),
            'status'=>$this->integer(1)->comment('状态(1正常，0删除)'),
            'created_at'=>$this->integer()->comment('创建时间'),
            'updated_at'=>$this->integer()->comment('修改时间'),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('member');
    }
}
