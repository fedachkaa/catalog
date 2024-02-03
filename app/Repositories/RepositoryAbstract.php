<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model as Model;

abstract class RepositoryAbstract
{
    /**
     * @return string
     */
    abstract public function getModelName() : string;

    /**
     * @param array $data
     * @return Model
     */
    public function getNew(array $data = []) : Model
    {
        /** @var Model $newModel */
        $modelClass = $this->getModelName();
        $newModel = new $modelClass;
        return $newModel::create($data);
    }
}
