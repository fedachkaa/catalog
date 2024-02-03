<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class University extends Model
{
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

    /** @var int */
    public $id;

    /** @var int */
    public $admin_id;

    /** @var string */
    public $name;

    /** @var string */
    public $city;

    /** @var string */
    public $address;

    /** @var string */
    public $phone_number;

    /** @var string */
    public $email;

    /** @var string */
    public $accreditation_level;

    /** @var string */
    public $founded_at;

    /** @var string */
    public $website;

    /** @var string */
    public $activated_at;

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
    ];

    /**
     * @return HasOne
     */
    public function admin(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'admin_id');
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return (int) $this->id;
    }

    /**
     * @return int
     */
    public function getAdminId() : int
    {
        return (int) $this->admin_id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return (string) $this->name;
    }

    /**
     * @return string
     */
    public function getCity() : string
    {
        return (string) $this->city;
    }

    /**
     * @return string
     */
    public function getAddress(): string
    {
        return (string) $this->address;
    }

    /**
     * @return string
     */
    public function getPhoneNumber(): string
    {
        return (string) $this->phone_number;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return (string) $this->email;
    }

    /**
     * @return string
     */
    public function getAccreditationLevel(): string
    {
        return (string) $this->accreditation_level;
    }

    /**
     * @return string
     */
    public function getFoundedAt(): string
    {
        return (string) $this->founded_at;
    }

    /**
     * @return string
     */
    public function getWebsite(): string
    {
        return (string) $this->website;
    }

    /**
     * @return string
     */
    public function getActivatedAt(): string
    {
        return (string) $this->activated_at;
    }
}
