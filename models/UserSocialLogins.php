<?php

/**
 * This is the model class for table "user_social_logins".
 *
 * The followings are the available columns in table 'user_social_logins':
 * @property integer $user_id
 * @property string $service_name
 * @property string $service_id
 * @property string $access_token
 *
 * The followings are the available model relations:
 * @property User $user
 */
class UserSocialLogins extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return UserSocialLogins the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'user_social_logins';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('user_id, service_name', 'required'),
			array('user_id', 'numerical', 'integerOnly'=>true),
			array('service_name, service_id, access_token', 'length', 'max'=>255),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('user_id, service_name, service_id, access_token', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'user' => array(self::BELONGS_TO, 'User', 'user_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'user_id' => 'User',
			'service_name' => 'Service Name',
			'service_id' => 'Service',
			'access_token' => 'Access Token',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('user_id',$this->user_id);
		$criteria->compare('service_name',$this->service_name,true);
		$criteria->compare('service_id',$this->service_id,true);
		$criteria->compare('access_token',$this->access_token,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}