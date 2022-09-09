<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "repository".
 *
 * @property int $user_id
 * @property string $name
 * @property string $link
 * @property int $updated_at
 *
 * @property GitUser $user
 */
class Repository extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'repository';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'name', 'link', 'updated_at'], 'required'],
            [['user_id', 'updated_at'], 'integer'],
            [['updated_at'], 'string'],
            [['name', 'link'], 'string', 'max' => 200],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => GitUser::class, 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'user_id' => 'User ID',
            'name' => 'Name',
            'link' => 'Link',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */

    public static function getLastRepositories()
    {
        return Repository::find()->orderBy(['updated_at' => SORT_DESC])->limit(10)->all();
    }

    

    public function getUser()
    {
        return $this->hasOne(GitUser::class, ['id' => 'user_id']);
    }
}
