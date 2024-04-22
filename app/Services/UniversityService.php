<?php

namespace App\Services;

use App\Events\UniversityProcessed;
use App\Models\University;
use App\Repositories\Interfaces\UniversityRepositoryInterface;

class UniversityService
{
    /** @var UniversityRepositoryInterface  */
    private $universityRepository;

    /**
     * @param UniversityRepositoryInterface $universityRepository
     */
    public function __construct(UniversityRepositoryInterface $universityRepository)
    {
        $this->universityRepository = $universityRepository;
    }

    /**
     * @param array $data
     * @return University
     * @throws \Exception
     */
    public function createUniversity(array $data) : University
    {
        try {
            /** @var University $university */
            $university = $this->universityRepository->getNew($data);
        } catch(\Exception $e) {
            throw new \Exception('University not created. Errors: ' . $e->getMessage());
        }

        return $university;
    }

    /**
     * @param University $university
     * @param array $data
     * @return University
     * @throws \Throwable
     */
    public function updateUniversity(University $university, array $data): University
    {
        if (isset($data['is_active']) && is_bool($data['is_active'])) {
            if ($data['is_active']) {
                if (!$university->getActivatedAt()) {
                    $university->updateOrFail(['activated_at' => date('Y-m-d H:i:s')]);
                    event(new UniversityProcessed($university));
                }
            } else {
                $university->updateOrFail(['activated_at' => null]);
                event(new UniversityProcessed($university));
            }
        }

        return $university;
    }
}
