<?php

class WebUser extends CWebUser
{

	public $loginUrl = array("user/user/login");

	public function init() {

		$userModel = Yii::app()->getModule('user')->userModel;

		parent::init();

		if(!$this->hasState("__userInfo")) {
			$this->setState('__userInfo',
	       		$userModel::model()->getAttributes()
	        );
		}

	}

    public function __get($name)
    {
        if ($this->hasState('__userInfo')) {

            $user = $this->getState('__userInfo',array());
            if (array_key_exists($name, $user)) {
                return $user[$name];
            }
        }

        return parent::__get($name);
    }

    public function login($identity, $duration = 0) {

    	$userModel = Yii::app()->getModule('user')->userModel;

        $this->setState('__userInfo',
       		$userModel::model()->findByAttributes(array("id" => $identity->getId()))->attributes
        );
        parent::login($identity, $duration);
    }
}
?>