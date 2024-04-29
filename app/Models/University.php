<?php

namespace App\Models;

use App\Models\Interfaces\UniversityInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;

class University extends Model implements UniversityInterface
{
    /** @const string */
    const TABLE_NAME = 'universities';

    /** @const string  */
    const ACCREDITATION_LEVEL_FIRST = 'I';
    const ACCREDITATION_LEVEL_SECOND = 'II';
    const ACCREDITATION_LEVEL_THIRD = 'III';
    const ACCREDITATION_LEVEL_FOURTH = 'IV';

    /** @const array */
    const AVAILABLE_ACCREDITATION_LEVELS = [
        self::ACCREDITATION_LEVEL_FIRST => 'I рівень акредитації',
        self::ACCREDITATION_LEVEL_SECOND => 'II рівень акредитації',
        self::ACCREDITATION_LEVEL_THIRD => 'III рівень акредитації',
        self::ACCREDITATION_LEVEL_FOURTH => 'IV рівень акредитації',
    ];

    /** @var array */
    protected $fillable = [
        'admin_id',
        'name',
        'city',
        'address',
        'phone_number',
        'email',
        'accreditation_level',
        'founded_at',
        'website',
        'activated_at',
    ];

    // --- Model relationships

    /**
     * @return Model
     */
    public function getAdmin(): Model
    {
        return $this->hasOne(User::class, 'id', 'admin_id')->first();
    }

    /**
     * @return Collection
     */
    public function getFaculties(): Collection
    {
        return $this->hasMany(Faculty::class, 'university_id', 'id')->get();
    }

    /**
     * @return Collection
     */
    public function getCatalogs(): Collection
    {
        return $this->hasMany(Catalog::class, 'university_id', 'id')->get();
    }

    // --- Model getters

    /**
     * @return int
     */
    public function getId(): int
    {
        return (int) $this->getAttribute('id');
    }

    /**
     * @return int
     */
    public function getAdminId() : int
    {
        return (int) $this->getAttribute('admin_id');
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return (string) $this->getAttribute('name');
    }

    /**
     * @return string
     */
    public function getCity() : string
    {
        return (string) $this->getAttribute('city');
    }

    /**
     * @return string
     */
    public function getAddress(): string
    {
        return (string) $this->getAttribute('address');
    }

    /**
     * @return string
     */
    public function getPhoneNumber(): string
    {
        return (string) $this->getAttribute('phone_number');
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return (string) $this->getAttribute('email');
    }

    /**
     * @return string
     */
    public function getAccreditationLevel(): string
    {
        return (string) $this->getAttribute('accreditation_level');
    }

    /**
     * @return string
     */
    public function getFoundedAt(): string
    {
        return (string) $this->getAttribute('founded_at');
    }

    /**
     * @return string
     */
    public function getWebsite(): string
    {
        return (string) $this->getAttribute('website');
    }

    /**
     * @return string
     */
    public function getActivatedAt(): string
    {
        return (string) $this->getAttribute('activated_at');
    }

    /**
     * @return string
     */
    public function getCreatedAt(): string
    {
        return (string) $this->getAttribute('created_at');
    }

    /**
     * @return string
     */
    public function getUpdatedAt(): string
    {
        return (string) $this->getAttribute('updated_at');
    }
}
