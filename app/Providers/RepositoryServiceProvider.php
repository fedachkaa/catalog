<?php

namespace App\Providers;

use App\Repositories\Interfaces\CatalogGroupRepositoryInterface;
use App\Repositories\Interfaces\CatalogRepositoryInterface;
use App\Repositories\Interfaces\CatalogSupervisorRepositoryInterface;
use App\Repositories\Interfaces\CatalogTopicRepositoryInterface;
use App\Repositories\Interfaces\SubjectRepositoryInterface;
use App\Repositories\Interfaces\TeacherRepositoryInterface;
use App\Repositories\Interfaces\TeacherSubjectRepositoryInterface;
use App\Repositories\Interfaces\UserRoleRepositoryInterface;
use App\Repositories\Student as StudentRepository;
use App\Repositories\Course as CourseRepository;
use App\Repositories\Faculty as FacultyRepository;
use App\Repositories\Group as GroupRepository;
use App\Repositories\Interfaces\CourseRepositoryInterface;
use App\Repositories\Interfaces\FacultyRepositoryInterface;
use App\Repositories\Interfaces\GroupRepositoryInterface;
use App\Repositories\Interfaces\StudentRepositoryInterface;
use App\Repositories\Interfaces\UniversityRepositoryInterface;
use App\Repositories\Interfaces\UserRepositoryInterface;
use App\Repositories\University as UniversityRepository;
use App\Repositories\User as UserRepository;
use App\Repositories\UserRole as UserRoleRepository;
use App\Repositories\Teacher as TeacherRepository;
use App\Repositories\Subject as SubjectRepository;
use App\Repositories\TeacherSubject as TeacherSubjectRepository;
use App\Repositories\Catalog as CatalogRepository;
use App\Repositories\CatalogTopic as CatalogTopicRepository;
use App\Repositories\CatalogSupervisor as CatalogSupervisorRepository;
use App\Repositories\CatalogGroup as CatalogGroupRepository;

use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(UniversityRepositoryInterface::class, UniversityRepository::class);
        $this->app->bind(FacultyRepositoryInterface::class, FacultyRepository::class);
        $this->app->bind(CourseRepositoryInterface::class, CourseRepository::class);
        $this->app->bind(GroupRepositoryInterface::class, GroupRepository::class);
        $this->app->bind(StudentRepositoryInterface::class, StudentRepository::class);
        $this->app->bind(UserRoleRepositoryInterface::class, UserRoleRepository::class);
        $this->app->bind(TeacherRepositoryInterface::class, TeacherRepository::class);
        $this->app->bind(SubjectRepositoryInterface::class, SubjectRepository::class);
        $this->app->bind(TeacherSubjectRepositoryInterface::class, TeacherSubjectRepository::class);
        $this->app->bind(CatalogRepositoryInterface::class, CatalogRepository::class);
        $this->app->bind(CatalogTopicRepositoryInterface::class, CatalogTopicRepository::class);
        $this->app->bind(CatalogSupervisorRepositoryInterface::class, CatalogSupervisorRepository::class);
        $this->app->bind(CatalogGroupRepositoryInterface::class, CatalogGroupRepository::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
