<?php

namespace App\Services;

use App\Events\CatalogActivation;
use App\Exceptions\ServiceUserException;
use App\Models\Catalog;
use App\Models\CatalogTopic;
use App\Models\Student;
use App\Models\TopicRequest;
use App\Repositories\Interfaces\CatalogGroupRepositoryInterface;
use App\Repositories\Interfaces\CatalogRepositoryInterface;
use App\Repositories\Interfaces\CatalogSupervisorRepositoryInterface;
use App\Repositories\Interfaces\TopicRequestRepositoryInterface;

class CatalogService
{
    /** @var CatalogRepositoryInterface */
    private $catalogRepository;

    /** @var CatalogGroupRepositoryInterface */
    private $catalogGroupRepository;

    /** @var CatalogSupervisorRepositoryInterface */
    private $catalogSupervisorRepository;

    /** @var TopicRequestRepositoryInterface */
    private $topicRequestRepository;

    /**
     * @param CatalogRepositoryInterface $catalogRepository
     * @param CatalogGroupRepositoryInterface $catalogGroupRepository
     * @param CatalogSupervisorRepositoryInterface $catalogSupervisorRepository
     * @param TopicRequestRepositoryInterface $topicRequestRepository
     */
    public function __construct(
        CatalogRepositoryInterface $catalogRepository,
        CatalogGroupRepositoryInterface $catalogGroupRepository,
        CatalogSupervisorRepositoryInterface $catalogSupervisorRepository,
        TopicRequestRepositoryInterface $topicRequestRepository
    ){
        $this->catalogRepository = $catalogRepository;
        $this->catalogGroupRepository = $catalogGroupRepository;
        $this->catalogSupervisorRepository = $catalogSupervisorRepository;
        $this->topicRequestRepository = $topicRequestRepository;
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
                if ($catalog->getIsActive() !== Catalog::IS_ACTIVE_TRUE) {
                    $catalog->update([
                        'is_active' => Catalog::IS_ACTIVE_TRUE,
                        'activated_at' => date('Y-m-d'),
                    ]);

                    event(new CatalogActivation($catalog));
                }
            } else {
                $catalog->update([
                    'is_active' => Catalog::IS_ACTIVE_FALSE,
                    'activated_at' => null,
                ]);
            }
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
        $catalog->getGroups()->delete();

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
        $catalog->getSupervisors()->delete();

        foreach ($teachersIds as $teacherId) {
            $catalogTeacher = $this->catalogSupervisorRepository->getNew([
                'catalog_id' => $catalog->getId(),
                'teacher_id' => (int) $teacherId,
            ]);

            $catalogTeacher->saveOrFail();
        }

        return $catalog;
    }

    /**
     * @param CatalogTopic $catalogTopic
     * @param Student $student
     * @return bool
     * @throws \Throwable
     */
    public function sendRequestTopic(CatalogTopic $catalogTopic, Student $student): bool
    {
        $studentRequests = $student->getTopicRequests();

        if (count($studentRequests) >= TopicRequest::MAX_TOPIC_REQUESTS_PER_STUDENT) {
            throw new ServiceUserException('Can`t request topic. User reached max allowed requests.');
        }

        $topicRequest = $this->topicRequestRepository->getOne([
            'topic_id' => $catalogTopic->getId(),
            'student_id' => $student->getUserId(),
        ]);

        if (empty($topicRequest)) {
            $topicRequest = $this->topicRequestRepository->getNew([
                'topic_id' => $catalogTopic->getId(),
                'student_id' => $student->getUserId(),
            ]);

            $topicRequest->saveOrFail();
        }

        return true;
    }

    /**
     * @param TopicRequest $topicRequest
     * @return bool
     * @throws ServiceUserException
     * @throws \Throwable
     */
    public function approveRequest(TopicRequest $topicRequest): bool
    {
        /** @var Student $student */
        $student = $topicRequest->getStudent();
        $otherStudentRequests = $student->getTopicRequests();
        foreach ($otherStudentRequests as $otherStudentRequest) {
            if ($otherStudentRequest->getStatus() === TopicRequest::STATUS_APPROVED) {
                throw new ServiceUserException('Can`t approve student topic request, because student already has approved topic.');
            }
        }

        $topicRequest->updateOrFail(['status' => TopicRequest::STATUS_APPROVED]);
        $topic = $topicRequest->getCatalogTopic();
        $topic->updateOrFail(['student_id' => $student->getUserId()]);

        $otherTopicRequests = $this->topicRequestRepository->getAll([
            'topic_id' => $topicRequest->getTopicId(),
            'idNotIn' => [$topicRequest->getId()],
        ]);

        foreach ($otherTopicRequests as $otherTopicRequest) {
            $this->rejectRequest($otherTopicRequest);
        }

        $otherStudentRequests = $this->topicRequestRepository->getAll([
            'student_id' => $student->getUserId(),
            'idNotIn' => [$topicRequest->getId()],
        ]);

        foreach ($otherStudentRequests as $otherStudentRequest) {
            $this->rejectRequest($otherStudentRequest);
        }

        return true;
    }

    /**
     * @param TopicRequest $topicRequest
     * @return bool
     * @throws ServiceUserException
     * @throws \Throwable
     */
    public function rejectRequest(TopicRequest $topicRequest): bool
    {
        if ($topicRequest->getStatus() === TopicRequest::STATUS_APPROVED) {
            throw new ServiceUserException('Can`t reject already approved topic request.');
        }

        $topicRequest->updateOrFail(['status' => TopicRequest::STATUS_REJECTED]);

        return true;
    }
}
