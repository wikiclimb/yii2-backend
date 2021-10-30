<?php

namespace common\models;

use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "i18n_string".
 *
 * @property int $id
 * @property string $default
 * @property string|null $de
 * @property string|null $en
 * @property string|null $es
 * @property string|null $hi
 * @property string|null $id-ID
 * @property string|null $jp
 * @property string|null $ko
 * @property string|null $pt
 * @property string|null $ru
 * @property string|null $zh
 * @property int|null $created_at
 * @property int|null $updated_at
 * @property int|null $created_by
 * @property int|null $updated_by
 *
 * @property User $createdBy
 * @property User $updatedBy
 */
class I18nString extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'i18n_string';
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors(): array
    {
        return [
            TimestampBehavior::class,
            BlameableBehavior::class,
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['default'], 'required'],
            [['created_at', 'updated_at', 'created_by', 'updated_by'], 'integer'],
            [['default', 'de', 'en', 'es', 'hi', 'id-ID', 'jp', 'ko', 'pt', 'ru', 'zh'], 'string', 'max' => 255],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['created_by' => 'id']],
            [['updated_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['updated_by' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'default' => Yii::t('app', 'Default'),
            'de' => Yii::t('app', 'De'),
            'en' => Yii::t('app', 'En'),
            'es' => Yii::t('app', 'Es'),
            'hi' => Yii::t('app', 'Hi'),
            'id-ID' => Yii::t('app', 'Id  ID'),
            'jp' => Yii::t('app', 'Jp'),
            'ko' => Yii::t('app', 'Ko'),
            'pt' => Yii::t('app', 'Pt'),
            'ru' => Yii::t('app', 'Ru'),
            'zh' => Yii::t('app', 'Zh'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_by' => Yii::t('app', 'Updated By'),
        ];
    }

    /**
     * Gets query for [[CreatedBy]].
     *
     * @return ActiveQuery
     */
    public function getCreatedBy(): ActiveQuery
    {
        return $this->hasOne(User::class, ['id' => 'created_by']);
    }

    /**
     * Gets query for [[UpdatedBy]].
     *
     * @return ActiveQuery
     */
    public function getUpdatedBy(): ActiveQuery
    {
        return $this->hasOne(User::class, ['id' => 'updated_by']);
    }
}
