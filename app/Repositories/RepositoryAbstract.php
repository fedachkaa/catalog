<?php

namespace App\Repositories;

use App\Exporters\ExporterAbstract;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model as Model;

abstract class RepositoryAbstract
{
    /**
     * @var ExporterAbstract
     */
    protected $exporter;

    /**
     * @return string
     */
    abstract public function getModelName(): string;

    /**
     * @return string
     */
    abstract public function getExporterName(): string;

    /**
     * @param array $data
     * @return Model
     */
    public function getNew(array $data = []): Model
    {
        /** @var Model $newModel */
        $modelClass = $this->getModelName();
        $newModel = new $modelClass;
        return $newModel::create($data);
    }

    /**
     * @return ExporterAbstract
     */
    public function getExporter(): ExporterAbstract
    {
        if (!$this->exporter) {
            $exporterClass = $this->getExporterName();
            $this->exporter = new $exporterClass();
        }
        return $this->exporter;
    }

    /**
     * @param Model|null $model
     * @param array $expands
     * @return array
     */
    public function export(?Model $model, array $expands = [])
    {
        return !empty($model) ? $this->getExporter()->export($model, $expands) : [];
    }

    /**
     * @param Collection|null $collection
     * @param array $expands
     * @return array
     */
    public function exportAll(?Collection $collection, array $expands = []): array
    {
        $exported = [];

        if (!empty($collection)) {
            foreach ($collection as $model) {
                $exported[] = $this->export($model, $expands);
            }
        }

        return $exported;
    }
}
