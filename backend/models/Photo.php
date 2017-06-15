<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "photo".
 *
 * @property integer $id
 * @property string $url
 * @property integer $good_id
 */
class Photo extends \yii\db\ActiveRecord
{
    public $imgFile;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'photo';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['good_id'], 'integer'],
            [['url'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'url' => '图片地址',
            'good_id' => '商品名字',
        ];
    }


    /*
* 品牌选项
*/
    public static function getBrandOptions(){
        return ArrayHelper::map(Brand::find()->asArray()->all(),'id','name');
    }
    /*
     * 商品和相册关系 1对多
     */
    public function getGalleries()
    {
        return $this->hasMany(GoodsGallery::className(),['goods_id'=>'id']);
    }

    /*
     * 获取商品详情
     */
    public function getGoodsIntro()
    {
        return $this->hasOne(GoodsIntro::className(),['goods_id'=>'id']);
    }
}
