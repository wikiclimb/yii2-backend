<?php

namespace common\models;

use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "image".
 *
 * @property int $id
 * @property int|null $name_id
 * @property int|null $description_id
 * @property string $file_name
 * @property int|null $validated
 * @property int|null $created_at
 * @property int|null $updated_at
 * @property int|null $created_by
 * @property int|null $updated_by
 *
 * @property User $createdBy
 * @property I18nText $description
 * @property I18nString $name
 * @property NodeImage[] $nodeImages
 * @property User $updatedBy
 */
class Image extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'image';
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
            [['name_id', 'description_id', 'validated', 'created_at', 'updated_at', 'created_by', 'updated_by'], 'integer'],
            [['file_name'], 'required'],
            [['file_name'], 'string', 'max' => 255],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['created_by' => 'id']],
            [['description_id'], 'exist', 'skipOnError' => true, 'targetClass' => I18nText::class, 'targetAttribute' => ['description_id' => 'id']],
            [['name_id'], 'exist', 'skipOnError' => true, 'targetClass' => I18nString::class, 'targetAttribute' => ['name_id' => 'id']],
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
            'name_id' => Yii::t('app', 'Name ID'),
            'description_id' => Yii::t('app', 'Description ID'),
            'file_name' => Yii::t('app', 'File Name'),
            'validated' => Yii::t('app', 'Validated'),
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
     * Gets query for [[Description]].
     *
     * @return ActiveQuery
     */
    public function getDescription(): ActiveQuery
    {
        return $this->hasOne(I18nText::class, ['id' => 'description_id']);
    }

    /**
     * Gets query for [[Name]].
     *
     * @return ActiveQuery
     */
    public function getName(): ActiveQuery
    {
        return $this->hasOne(I18nString::class, ['id' => 'name_id']);
    }

    /**
     * Gets query for [[NodeImages]].
     *
     * @return ActiveQuery
     */
    public function getNodeImages(): ActiveQuery
    {
        return $this->hasMany(NodeImage::class, ['image_id' => 'id']);
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
