<?php

namespace App\Exporters;

use Illuminate\Database\Eloquent\Model;

abstract class ExporterAbstract
{
    /**
     * @param Model $model
     * @return array
     */
    abstract protected function exportModel(Model $model): array;

    /**
     * @return array
     */
    public function getAllowedExpands(): array
    {
        return [];
    }

    /**
     * @param Model $model
     * @param array $expands
     * @return array
     */
    public function export(Model $model, array $expands = [])
    {
        $data = $this->exportModel($model);
        $allowedExpands = $this->getAllowedExpands();
        foreach ($expands as $expand) {
            $exploded = explode(':', $expand, 2);
            $key = $exploded[0];
            $params = (!empty($exploded[1])) ? explode(':', $exploded[1]) : [];
            $method = 'expand' . $allowedExpands[$key];
            $data[$key] = $this->$method($model, $params);
        }

        return $data;
    }
}
