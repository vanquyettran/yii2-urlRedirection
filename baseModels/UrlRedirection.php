<?php

namespace common\modules\urlRedirection\baseModels;

use Yii;
use common\models\User;

/**
 * This is the model class for table "url_redirection".
 *
 * @property integer $id
 * @property string $from_url
 * @property string $to_url
 * @property integer $active
 * @property integer $status
 * @property integer $type
 * @property integer $sort_order
 * @property integer $response_code
 * @property integer $create_time
 * @property integer $update_time
 * @property integer $creator_id
 * @property integer $updater_id
 *
 * @property User $creator
 * @property User $updater
 */
class UrlRedirection extends \common\db\MyActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'url_redirection';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['from_url', 'to_url', 'response_code', 'create_time', 'creator_id'], 'required'],
            [['active', 'status', 'type', 'sort_order', 'response_code', 'create_time', 'update_time', 'creator_id', 'updater_id'], 'integer'],
            [['from_url', 'to_url'], 'string', 'max' => 255],
            [['creator_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['creator_id' => 'id'], 'except' => 'test'],
            [['updater_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['updater_id' => 'id'], 'except' => 'test'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'from_url' => 'From Url',
            'to_url' => 'To Url',
            'active' => 'Active',
            'status' => 'Status',
            'type' => 'Type',
            'sort_order' => 'Sort Order',
            'response_code' => 'Response Code',
            'create_time' => 'Create Time',
            'update_time' => 'Update Time',
            'creator_id' => 'Creator ID',
            'updater_id' => 'Updater ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCreator()
    {
        return $this->hasOne(User::className(), ['id' => 'creator_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUpdater()
    {
        return $this->hasOne(User::className(), ['id' => 'updater_id']);
    }
}
