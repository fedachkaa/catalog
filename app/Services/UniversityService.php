<?php

namespace App\Services;

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
}
