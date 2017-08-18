<?php

namespace common\modules\urlRedirection\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * UrlRedirectionSearch represents the model behind the search form about `UrlRedirection`.
 */
class UrlRedirectionSearch extends UrlRedirection
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'creator_id', 'updater_id', 'active', 'type', 'status', 'sort_order', 'create_time', 'update_time'], 'integer'],
            [['from_url', 'to_url'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = UrlRedirection::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'creator_id' => $this->creator_id,
            'updater_id' => $this->updater_id,
            'active' => $this->active,
            'type' => $this->type,
            'status' => $this->status,
            'sort_order' => $this->sort_order,
            'create_time' => $this->create_time,
            'update_time' => $this->update_time,
        ]);

        $query->andFilterWhere(['like', 'from_url', $this->from_url])
            ->andFilterWhere(['like', 'to_url', $this->to_url]);

        return $dataProvider;
    }
}
