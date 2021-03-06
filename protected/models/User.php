<?php

/**
 * This is the model class for table "user".
 *
 * The followings are the available columns in table 'user':
 * @property integer $user_id
 * @property string $user_name
 * @property string $user_email
 * @property string $user_company
 * @property string $user_lastlogin
 * @property string $user_gender
 * @property string $user_url
 * @property string $user_address
 * @property string $user_avatar
 * @property string $user_rate
 *
 * The followings are the available model relations:
 * @property File[] $files
 * @property UserCompany[] $userCompanies
 * @property UserProject[] $userProjects
 * @property UserRole[] $userRoles
 */
class User extends CActiveRecord {

    public $user_role, $image;

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return user the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'user';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('user_name, user_email', 'required'),
            array('user_name, user_company', 'length', 'max' => 127),
            array('user_email, user_url, user_address, user_avatar', 'length', 'max' => 255),
            array('user_gender', 'length', 'max' => 1),
            array('user_rate', 'length', 'max' => 45),
            array('user_lastlogin', 'safe'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('user_id, user_name, user_email, user_company, user_lastlogin, user_gender, user_url, user_address, user_avatar, user_rate', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'files' => array(self::HAS_MANY, 'File', 'user_id'),
            'userCompanies' => array(self::HAS_MANY, 'UserCompany', 'user_company_user_id'),
            'userProjects' => array(self::HAS_MANY, 'UserProject', 'user_id'),
            'userRoles' => array(self::HAS_MANY, 'UserRole', 'user_role_user_id'),
                // 'AuthAssignment' => array(self::HAS_MANY. 'AuthAssignment', 'user_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'user_id' => 'User',
            'user_fullname' => 'nama panjang',
            'user_name' => 'username',
            'user_email' => 'Email',
            'user_company' => 'Perusahaan',
            'user_lastlogin' => 'terakhir login',
            'user_gender' => 'Jenis Kelamin',
            'user_url' => 'Url',
            'user_address' => 'Alamat',
            'user_avatar' => 'picture',
            'user_rate' => 'User Rate',
            'user_mobilephone' => 'Nomer Telepon',
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search() {
        // Warning: Please modify the following code to remove attributes that
        // should not be searched.

        $criteria = new CDbCriteria;

        $criteria->compare('user_id', $this->user_id);
        $criteria->compare('user_name', $this->user_name, true);
        $criteria->compare('user_email', $this->user_email, true);
        $criteria->compare('user_company', $this->user_company, true);
        $criteria->compare('user_lastlogin', $this->user_lastlogin, true);
        $criteria->compare('user_gender', $this->user_gender, true);
        $criteria->compare('user_url', $this->user_url, true);
        $criteria->compare('user_address', $this->user_address, true);
        $criteria->compare('user_avatar', $this->user_avatar, true);
        $criteria->compare('user_rate', $this->user_rate, true);

        return new CActiveDataProvider($this, array(
                    'criteria' => $criteria,
                ));
    }

    public function saveImage(User $model) {
        Yii::import('application.extensions.EUploadedImage');
        $image = new stdClass();
        $image = EUploadedImage::getInstance($model, 'user_avatar');
        $image->maxWidth = 800;
        $image->maxHeight = 600;

        $image->thumb = array(
            'maxWidth' => 150,
            'maxHeight' => 150,
            'dir' => 'thumb',
            'prefix' => 'thumb_',
        );
        $model->user_avatar = $image->getName();
        if (!$image->saveAs(Yii::getPathOfAlias('webroot') . '/uploads/image/' . $image->getName())) {
            throw new CHttpException('Error pada saat upload gambar', 501);
        }
    }

}