<?php

namespace App;

use App\Enums\LeadStatus;
use App\Enums\UserTypes;
use App\Notifications\CustomPasswordResetNotification;
use App\Traits\Activable;
use App\Traits\KamScopable;
use App\Traits\Localizable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Activable, KamScopable, Localizable, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'cel_to_edit',
        'cel_whatsapp_to_edit',
    ];

    /**
     * Belongs to dealership los vendedores, managers y encargados
     */
    public function dealership()
    {
        return $this->belongsTo(Dealership::class);
    }

    /**
     * Los kams tienen muchos dealerships
     */
    public function dealerships()
    {
        return $this->hasMany(Dealership::class, 'kam_id');
    }

    /**
     * Has many activities
     */
    public function activities()
    {
        return $this->hasMany(Activity::class);
    }

    /**
     * Has many leads
     */
    public function leads()
    {
        return $this->hasMany(Lead::class);
    }

    /**
     * Leads que el vendedor esta trabajando y no se han cerrado
     */
    public function pending_leads()
    {
        return $this->hasMany(Lead::class)
            ->whereNotIn('status', [
                LeadStatus::Vendido,
                LeadStatus::Perdido,
                LeadStatus::Descartado,
                LeadStatus::Clausurado,
            ]);
    }

    /**
     * Leads que el vendedor cerro o anulo
     */
    public function closed_leads()
    {
        return $this->hasMany(Lead::class)
            ->whereIn('status', [
                LeadStatus::Vendido,
                LeadStatus::Perdido,
                LeadStatus::Clausurado,
                LeadStatus::Descartado,
            ]);
    }

    /**
     * Leads que el vendedor ganados
     */
    public function won_leads()
    {
        return $this->hasMany(Lead::class)
            ->where('status', LeadStatus::Vendido);
    }

    /**
     * Los leads del consecionario del vendedore
     * que no han sido trabajados por otro vendedor
     */
    public function open_leads()
    {
        return $this->dealership->leads()
            ->whereNull('user_id')
            ->whereIn('status', [
                LeadStatus::Creado,
                LeadStatus::Derivado,
            ]);
    }

    public function isAdmin(): bool
    {
        return $this->isType('Admin');
    }

    public function isKam(): bool
    {
        return $this->isType('Kam');
    }

    /**
     * Kam y admin comparten rutas y algunos permisos
     */
    public function isAdminLevel(): bool
    {
        return $this->isAdmin() || $this->isKam();
    }

    public function isManager(): bool
    {
        return $this->isType('Manager');
    }

    /**
     * Dueños y encargados comparten los permisos
     */
    public function isExecutiveLevel(): bool
    {
        return $this->isManager() || $this->isOwner();
    }

    /**
     * Dueños, encargados o vendedores. Personal del distribuidor
     */
    public function isDealerPersonal(): bool
    {
        return $this->isManager() || $this->isOwner() || $this->isSeller();
    }

    /**
     * Says if if of type by string type description
     */
    public function isType(string $type): bool
    {
        return $this->type == UserTypes::getValue($type);
    }

    public function isOwner(): bool
    {
        return $this->isType('Owner');
    }

    public function isSeller(): bool
    {
        return $this->isType('Seller');
    }

    /**
     * Solo un role por usuario
     *
     * @return void
     */
    public function getTypeDescriptionAttribute()
    {
        return UserTypes::getDescription($this->type);
    }

    /**
     * Set the user's first name.
     *
     * @param  string  $value
     * @return void
     */
    public function setNameAttribute($value)
    {
        $this->attributes['name'] = ucwords(strtolower($value));
    }

    /**
     * Set the user's first name.
     *
     * @param  string  $value
     * @return void
     */
    public function getNameAttribute($value)
    {
        return ucwords(strtolower($value));
    }

    /**
     * Get the cel whitout code for edit forms
     *
     * @param  string  $value
     * @return void
     */
    public function getCelToEditAttribute($value)
    {
        return $this->cel ? substr($this->cel, 3) : null;
    }

    /**
     * Get the cel whitout code for edit forms
     *
     * @param  string  $value
     * @return void
     */
    public function getCelWhatsappToEditAttribute($value)
    {
        return $this->cel_whatsapp ? substr($this->cel_whatsapp, 3) : null;
    }

    /**
     * Set the user's first name.
     *
     * @param  string  $value
     * @return void
     */
    public function setEmailAttribute($value)
    {
        $this->attributes['email'] = strtolower($value);
    }

    /**
     * Esta funcion le quita los parentesis y espacios
     *
     * @return void
     */
    public function setCelAttribute($value)
    {
        $this->attributes['cel'] = $value ? preg_replace("/\(|\)|\s/", '', $value) : null;
    }

    /**
     * Esta funcion le quita los parentesis y espacios
     *
     * @return void
     */
    public function setCelWhatsappAttribute($value)
    {
        $this->attributes['cel_whatsapp'] = $value ? preg_replace("/\(|\)|\s/", '', $value) : null;
    }

    /**
     * Scope for admins
     *
     * @param [type] $query
     * @return void
     */
    public function scopeAdmins($query)
    {
        return $query->where('type', UserTypes::Admin);
    }

    /**
     * Scope for admins
     *
     * @param [type] $query
     * @return void
     */
    public function scopeSellers($query)
    {
        return $query->where('type', UserTypes::Seller);
    }

    /**
     * Scope for kams
     *
     * @param [type] $query
     * @return void
     */
    public function scopeKams($query)
    {
        return $query->where('type', UserTypes::Kam);
    }

    /**
     * Scope for with Whatsapp
     *
     * @param [type] $query
     * @return void
     */
    public function scopeWithWhatsapp($query)
    {
        return $query->whereNotNull('cel_whatsapp');
    }

    /**
     * Send the password reset notification.
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new CustomPasswordResetNotification($token));
    }

    /**
     * El telefono para whatsapp
     *
     * @return void
     */
    public function routeNotificationForWhatsApp()
    {
        return $this->cel_whatsapp;
    }
}
