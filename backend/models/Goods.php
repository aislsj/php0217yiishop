<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "goods".
 *
 * @property integer $id
 * @property string $name
 * @property string $sn
 * @property string $logo
 * @property integer $goods_category_id
 * @property integer $brand_id
 * @property string $market_price
 * @property string $shop_price
 * @property integer $stock
 * @property integer $is_on_sale
 * @property integer $status
 * @property integer $sort
 * @property integer $create_time
 */
class Goods extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public $imgFile;
    public static function tableName()
    {
        return 'goods';
    }

    //建立关系
    public function getBrand(){
        return $this->hasOne(Brand::className(),['id'=>'brand_id']);
    }
    public function getGoods_category(){
        return $this->hasOne(GoodsCategory::className(),['id'=>'goods_category_id']);
    }
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['goods_category_id', 'brand_id', 'stock', 'is_on_sale', 'status', 'sort', 'create_time'], 'integer'],
            [['market_price', 'shop_price'], 'number'],
            [['name', 'sn', 'logo'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => '商品名称',
            'sn' => '货号',
            'logo' => 'LOGO',
            'goods_category_id' => '商品分类ID',
            'brand_id' => '品牌分类',
            'market_price' => '市场价格',
            'shop_price' => '商品价格',
            'stock' => '库存',
            'is_on_sale' => '是否在售',
            'status' => '状态',
            'sort' => '排序',
            'create_time' => '添加时间',
        ];
    }
    public function getCustomer()
    {
        return $this->hasOne(Customer::className(), ['id' => 'customer_id']);
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
        return $this->hasMany(Photo::className(),['good_id'=>'id']);
    }

    /*
     * 获取商品详情
     */
    public function getGoodsIntro()
    {
        return $this->hasOne(GoodsIntro::className(),['goods_id'=>'id']);
    }
}
