<?php

namespace common\modules\urlRedirection\models;

use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\web\NotFoundHttpException;

/**
 * This is the model class for table "url_redirection".
 *
 * @property integer $id
 * @property integer $creator_id
 * @property integer $updater_id
 * @property string $from_url
 * @property string $to_url
 * @property integer $active
 * @property integer $type
 * @property integer $status
 * @property integer $sort_order
 * @property integer $create_time
 * @property integer $update_time
 * @property integer $response_code
 *
 * @property User $creator
 * @property User $updater
 */
class UrlRedirection extends \common\modules\urlRedirection\baseModels\UrlRedirection
{
    const TYPE_EQUALS = 1;
    const TYPE_CONTAINS = 2;
    const TYPE_STARTS_WITH = 3;
    const TYPE_ENDS_WITH = 4;
    const TYPE_REGEXP = 5;

    /**
     * @return array
     */
    public static function getTypes()
    {
        return [
            self::TYPE_EQUALS => Yii::t('app', 'URL equals'),
            self::TYPE_CONTAINS => Yii::t('app', 'URL contains'),
            self::TYPE_STARTS_WITH => Yii::t('app', 'URL starts with'),
            self::TYPE_ENDS_WITH => Yii::t('app', 'URL ends with'),
            self::TYPE_REGEXP => Yii::t('app', 'Regular Expression'),
        ];
    }

    /**
     * @return array
     */
    public static function getResponseCodes()
    {
        return [
            301 => '301 Moved Permanently',
            302 => '302 Found',
            300 => '300 Multiple Choice',
            303 => '303 See Other',
            304 => '304 Not Modified',
            307 => '307 Temporary Redirect',
            308 => '308 Permanent Redirect',
        ];
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            [
                'class' => BlameableBehavior::className(),
                'createdByAttribute' => 'creator_id',
                'updatedByAttribute' => 'updater_id',
            ],
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'create_time',
                'updatedAtAttribute' => 'update_time',
                'value' => time(),
            ],
        ];
    }

//    /**
//     * @inheritdoc
//     */
//    public static function tableName()
//    {
//        return 'url_redirection';
//    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [[/*'creator_id', 'updater_id',*/ 'active', 'type', 'status', 'sort_order',
                /*'create_time', 'update_time',*/ 'response_code'], 'integer'],
            [['from_url', 'to_url'], 'required'],
            [['from_url', 'to_url'], 'string', 'max' => 255],
//            [['creator_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['creator_id' => 'id']],
//            [['updater_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['updater_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'creator_id' => 'Creator ID',
            'updater_id' => 'Updater ID',
            'from_url' => 'From Url',
            'to_url' => 'To Url',
            'active' => 'Active',
            'type' => 'Type',
            'status' => 'Status',
            'sort_order' => 'Sort Order',
            'create_time' => 'Create Time',
            'update_time' => 'Update Time',
            'response_code' => 'Response Code',
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

    /**
     * @param null $from_url
     * @throws NotFoundHttpException
     */
    public static function findOneAndRedirect($from_url = null)
    {
        if (!$from_url) {
            $from_url = Yii::$app->request->url;
        }

        $to_url = null;
        $response_code = 301;

        /** @var self $model */
        $model = self::find()
            ->where(['active' => 1])
            ->andWhere([
                'OR',
                [
                    'AND',
                    ['=', 'type', self::TYPE_EQUALS],
                    ":from_url LIKE `from_url`",
                ],
                [
                    'AND',
                    ['=', 'type', self::TYPE_CONTAINS],
                    ":from_url LIKE CONCAT('%', `from_url`, '%')",
                ],
                [
                    'AND',
                    ['=', 'type', self::TYPE_STARTS_WITH],
                    ":from_url LIKE CONCAT(`from_url`, '%')",
                ],
                [
                    'AND',
                    ['=', 'type', self::TYPE_ENDS_WITH],
                    ":from_url LIKE CONCAT('%', `from_url`)",
                ],
            ])
            ->addParams([':from_url' => $from_url])
            ->orderBy('sort_order ASC, id ASC')
            ->one();

        if ($model) {
            $to_url = $model->to_url;
            if (in_array($model->response_code, array_keys(self::getResponseCodes()))) {
                $response_code = $model->response_code;
            }
        } else {
            /** @var self[] $models */
            $models = self::find()
                ->where(['active' => 1])
                ->andWhere(['type' => self::TYPE_REGEXP])
                ->orderBy('sort_order ASC, id ASC')
                ->all();

            foreach ($models as $model) {
                try {
                    preg_match($model->from_url, $from_url, $matches);
                    if (isset($matches[0])) {
                        $pattern = $model->from_url;
                        $replacement = $model->to_url;
                        $transform = null;
                        switch (true) {
                            case '~\lowercase' === substr($replacement, -11):
                                $replacement = substr($replacement, 0, -11);
                                $transform = 'lowercase';
                                break;
                            case '~\uppercase' === substr($replacement, -11):
                                $replacement = substr($replacement, 0, -11);
                                $transform = 'uppercase';
                                break;
                            default:
                        }

                        $to_url = preg_replace($pattern, $replacement, $from_url);

                        switch ($transform) {
                            case 'lowercase':
                                $to_url = strtolower($to_url);
                                break;
                            case 'uppercase':
                                $to_url = strtoupper($to_url);
                                break;
                            default:

                        }

                        if (in_array($model->response_code, array_keys(self::getResponseCodes()))) {
                            $response_code = $model->response_code;
                        }

                        break;
                    }
                } catch (\Exception $e) {
                    continue;
                }
                if ($to_url) {
                    break;
                }
            }
        }

        var_dump($to_url);
        die;

        if ($to_url) {
            header("Location: $to_url", true, $response_code);
            exit();
        }

        throw new NotFoundHttpException();
    }
}
