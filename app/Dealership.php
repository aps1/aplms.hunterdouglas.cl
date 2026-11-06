<?php

namespace App;

use App\Enums\UserTypes;
use App\Traits\Activable;
use App\Traits\KamScopable;
use App\Traits\Localizable;
use Illuminate\Database\Eloquent\Model;

class Dealership extends Model
{
    use Activable, KamScopable, Localizable;

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'is_active' => 'boolean',
        'wcp1' => 'boolean',
        'wcp2' => 'boolean',
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'name' => 'required',
    ];

    /**
     * mass asigment yolo
     *
     * @var array
     */
    public $guarded = [];

    /**
     * Has many users
     */
    public function users()
    {
        return $this->hasMany(User::class);
    }

    /**
     * Has many users
     */
    public function sellers()
    {
        return $this->hasMany(User::class)->where('type', UserTypes::Seller);
    }

    /**
     * Has many users
     */
    public function owners()
    {
        return $this->hasMany(User::class)->where('type', UserTypes::Owner);
    }

    /**
     * managers y owners
     */
    public function executives()
    {
        return $this->hasMany(User::class)->whereIn('type', [UserTypes::Owner, UserTypes::Manager]);
    }

    /**
     * pertenece a un Kam
     */
    public function kam()
    {
        return $this->belongsTo(User::class, 'kam_id');
    }

    /**
     * Has many leads
     *
     * @return Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function leads()
    {
        return $this->hasMany(Lead::class);
    }

    /**
     * activa el recurso
     */
    public function activate()
    {
        return $this->update(['is_active' => 1]);
    }

    /**
     * inactiva el recurso
     */
    public function inactivate()
    {
        User::where('dealership_id', $this->id)->update(['is_active' => 0]);

        return $this->update(['is_active' => 0]);
    }

    /**
     * Las marcas del dealer
     */
    public function brands(): array
    {
        $brands = [];
        if ($this->wcp1) {
            array_push($brands, brand_name('wcp1', $this->localization));
        }
        if ($this->wcp2) {
            array_push($brands, brand_name('wcp2', $this->localization));
        }

        return $brands;
    }

    /**
     * Set the user's first name.
     *
     * @param  string  $value
     */
    public function setNameAttribute($value)
    {
        $this->attributes['name'] = trim($value);
    }

    /**
     * Consigue el usuario que sirve de contacto para el distribuidor
     * Es o el primer manager activo o el primer owner activo
     */
    public function getContactResponsable()
    {
        $manager = $this->users()->active()->where('type', UserTypes::Manager)->first();
        if ($manager) {
            return $manager;
        }
        $owner = $this->users()->active()->where('type', UserTypes::Owner)->first();
        if ($owner) {
            return $owner;
        }

        return null;
    }
}
