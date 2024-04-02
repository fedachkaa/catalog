<?php

namespace App\Services;

use App\Models\Catalog;
use App\Repositories\Interfaces\CatalogGroupRepositoryInterface;
use App\Repositories\Interfaces\CatalogRepositoryInterface;
use App\Repositories\Interfaces\CatalogSupervisorRepositoryInterface;

class CatalogService
{
    /** @var CatalogRepositoryInterface */
    private $catalogRepository;

    /** @var CatalogGroupRepositoryInterface */
    private $catalogGroupRepository;

    /** @var CatalogSupervisorRepositoryInterface */
    private $catalogSupervisorRepository;

    /**
     * @param CatalogRepositoryInterface $catalogRepository
     * @param CatalogGroupRepositoryInterface $catalogGroupRepository
     * @param CatalogSupervisorRepositoryInterface $catalogSupervisorRepository
     */
    public function __construct(
        CatalogRepositoryInterface $catalogRepository,
        CatalogGroupRepositoryInterface $catalogGroupRepository,
        CatalogSupervisorRepositoryInterface $catalogSupervisorRepository
    ){
        $this->catalogRepository = $catalogRepository;
        $this->catalogGroupRepository = $catalogGroupRepository;
        $this->catalogSupervisorRepository = $catalogSupervisorRepository;
    }

    /**
     * @param Catalog $catalog
     * @param array $data
     * @return Catalog
     * @throws \Throwable
     */
    public function updateCatalog(Catalog $catalog, array $data): Catalog
    {
        if (isset($data['is_active'])) {
            if ($data['is_active']) {
                $catalog->update([
                    'is_active' => Catalog::IS_ACTIVE_TRUE,
                    'activated_at' => date('Y-m-d'),
                ]);
            } else {
                $catalog->update([
                    'is_active' => Catalog::IS_ACTIVE_FALSE,
                    'activated_at' => null,
                ]);
            }
        }

        if (!empty($data['type'])) {
            $catalog->update([
                'type' => $data['type']
            ]);
        }

        if (isset($data['groupsIds'])) {
            $this->saveCatalogGroups($catalog, $data['groupsIds']);
        }

        if (isset($data['teachersIds'])) {
            $this->saveCatalogTeachers($catalog, $data['teachersIds']);
        }

        return $catalog;
    }

    /**
     * @param Catalog $catalog
     * @param array $groupIds
     * @return Catalog
     * @throws \Throwable
     */
    public function saveCatalogGroups(Catalog $catalog, array $groupIds): Catalog
    {
        $catalog->getGroups()->detach();

        foreach ($groupIds as $groupId) {
            $catalogGroup = $this->catalogGroupRepository->getNew([
                'catalog_id' => $catalog->getId(),
                'group_id' => (int) $groupId,
            ]);

            $catalogGroup->saveOrFail();
        }

        return $catalog;
    }

    /**
     * @param Catalog $catalog
     * @param array $teachersIds
     * @return Catalog
     * @throws \Throwable
     */
    public function saveCatalogTeachers(Catalog $catalog, array $teachersIds): Catalog
    {
        $catalog->getSupervisors()->detach();

        foreach ($teachersIds as $teacherId) {
            $catalogTeacher = $this->catalogSupervisorRepository->getNew([
                'catalog_id' => $catalog->getId(),
                'teacher_id' => (int) $teacherId,
            ]);

            $catalogTeacher->saveOrFail();
        }

        return $catalog;
    }
}
