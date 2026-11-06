<?php

namespace App;

use App\Enums\ContactSources;
use App\Enums\ContactTypes;
use App\Traits\KamScopable;
use App\Traits\Localizable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class Contact extends Model
{
    use KamScopable, Localizable;

    protected $guarded = [];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'is_subscribed' => 'boolean',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['type_description', 'source_description'];

    /**
     * @return void
     */
    public function dealership()
    {
        return $this->belongsTo(Dealership::class);
    }

    /**
     * @return void
     */
    public function leads()
    {
        return $this->hasMany(Lead::class);
    }

    /**
     * POr ahora las actividades de los leads del contacto.
     */
    public function getActivities(): Collection
    {
        $leads_ids = $this->leads()->get('id')->pluck('id')->toArray();

        return DB::table('activities')
            ->whereIn('lead_id', $leads_ids)
            ->get();
    }

    /**
     * El tipo de contacto
     *
     * @return void
     */
    public function getTypeDescriptionAttribute()
    {
        return ContactTypes::getDescription($this->type);
    }

    /**
     * La fuente de contacto
     *
     * @return void
     */
    public function getSourceDescriptionAttribute()
    {
        return ContactSources::getDescription($this->source);
    }

    /**
     * La fuente de contacto
     *
     * @return void
     */
    public function getPhoneToEditAttribute()
    {
        return Str::startsWith($this->phone, __('phoneCode')) ?
            Str::replaceFirst(__('phoneCode'), '', $this->phone) :
            $this->phone;
    }

    /**
     * La fuente de contacto
     *
     * @return void
     */
    public function setPhoneAttribute($value)
    {
        $this->attributes['phone'] = Str::startsWith($value, __('phoneCode')) ?
            $value :
           __('phoneCode').$value;
    }

    /**
     * La fuente de contacto
     *
     * @return void
     */
    public function setPhone2Attribute($value)
    {
        $this->attributes['phone2'] = Str::startsWith($value, __('phoneCode')) ?
            $value :
           __('phoneCode').$value;
    }

    /**
     * La fuente de contacto
     *
     * @return void
     */
    public function getPhone2ToEditAttribute()
    {
        return Str::startsWith($this->phone2, __('phoneCode')) ?
            Str::replaceFirst(__('phoneCode'), '', $this->phone2) :
            $this->phone2;
    }
}
