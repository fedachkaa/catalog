<?php

namespace App\Console\Commands;

use App\Repositories\CatalogTopic;
use App\Repositories\Topic;
use Illuminate\Console\Command;

class MigrateCatalogTopicsData extends Command
{
    /** @var string */
    protected $signature = 'migrate:catalog_topics';
    protected $description = 'Migrate data from old catalog_topics to new structure';

    /** @var CatalogTopic */
    protected $catalogTopicsRepository;

    /** @var Topic */
    protected $topicsRepository;

    /**
     * @param CatalogTopic $catalogTopicsRepository
     * @param Topic $topicsRepository
     */
    public function __construct(CatalogTopic $catalogTopicsRepository, Topic $topicsRepository)
    {
        parent::__construct();

        $this->catalogTopicsRepository = $catalogTopicsRepository;
        $this->topicsRepository = $topicsRepository;
    }
    /**
     * Execute the console command.
     **/
    public function handle()
    {
//        $catalogTopics = $this->catalogTopicsRepository->getAll();
//
//        foreach ($catalogTopics as $catalogTopic) {
//            $topic = $this->topicsRepository->getOne([
//                'topic' => $catalogTopic->getTopic(),
//            ]);
//
//            if (empty($topic)) {
//                $topic = $this->topicsRepository->getNew([
//                    'teacher_id' => $catalogTopic->getTeacherId(),
//                    'topic' => $catalogTopic->getTopic(),
//                    'is_ai_generated' => 0,
//                ]);
//
//                $topic->saveOrFail();
//            }
//
//            $catalogTopic->updateOrFail([
//                'topic_id' => $topic->getId(),
//            ]);
//        }
//
//        $this->info('Catalog topics migrated successfully!');
    }
}
