<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "address".
 *
 * @property integer $id
 * @property string $username
 * @property string $locations
 * @property string $location_detailed
 * @property integer $tel
 * @property integer $is_default
 */
class Address extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
//    public $city;//城市
//    public $province;//省份
//    public $area;//县区
    public static function tableName()
    {
        return 'address';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['username'], 'required','message'=>'收件人不能为空!'],
//            ['locations','required','message'=>'地址不能为空!'],
            ['tel','required','message'=>'手机号不能为空'],
            ['is_default','safe'],
//            ['city','required'],
            [['city','province','area'],'required','message'=>'地址不能为空'],
            ['tel', 'match','pattern'=>"/^1[34578]\d{9}$/", 'message'=>'手机格式不对,请重新输入!'],
            [['username', 'location_detailed'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => '收货人:',
            'province' => '选择地区:',
            'location_detailed' => '详细地址:',
            'tel' => '手机号码:',
            'is_default' => '是否设为默认地址',
        ];
    }

    //添加新地址的方法
    public function Save_address($model){
//        var_dump($model);exit;
        if($this->is_default){//判断是否设置成了默认地址
            $model_all = Address::findAll(['member_id'=>$model->member_id]);//所有地址
        foreach($model_all as $default){
            $default->is_default = 0;
            $model_all->save();
        }
          $model->save();
           return true;
       }else{
          $model->is_default = 0;
           $model->save();
           return true;
       }
    }
}
