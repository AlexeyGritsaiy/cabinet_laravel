<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{

    use Notifiable;

    public const STATUS_WAIT = 'wait';
    public const STATUS_ACTIVE = 'active';


    protected $fillable = [
        'name', 'email', 'password', 'status', 'verify_token'
    ];


    protected $hidden = [
        'password', 'remember_token',
    ];

    public function isActive(): bool
    {
        return $this->status === self::STATUS_ACTIVE;
    }

    public function verify(): void
    {
        if (!$this->isWait()) {
            throw new \DomainException('User is already verified.');
        }

        $this->update([
            'status' => self::STATUS_ACTIVE,
            'verify_token' => null,
        ]);
    }

    public function isWait(): bool
    {
        return $this->status === self::STATUS_WAIT;
    }

    public function getAvatarPath()
    {
        return 'public/avatar/' . $this->id;
    }

    public function getAvatarImage()
    {
        return $this->getAvatarPublicPath() . '/' . $this->avatar;
    }

    public function getAvatarPublicPath()
    {
        return '/storage/avatar/' . $this->id;
    }

    public function getAvatarMedium()
    {
        return $this->getAvatarPublicPath() . '/md_' . $this->avatar;
    }

    public function getAvatarSmall()
    {
        return $this->getAvatarPublicPath() . '/l_' . $this->avatar;
    }
}
