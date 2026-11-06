<?php

namespace App;

use App\Actions\StoreActivity;
use App\Enums\DiscardReasons;
use App\Enums\LeadOrigins;
use App\Enums\LeadPriority;
use App\Enums\LeadSources;
use App\Enums\LeadStatus;
use App\Enums\ProductCategory;
use App\Traits\KamScopable;
use App\Traits\Localizable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Lead extends Model
{
    use KamScopable, Localizable;

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'date',
        'created_at',
        'updated_at',
    ];

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'status_description',
        'status_color',
        'origin_description',
        'is_taken',
        'is_strategic',
    ];

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'id';
    }

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
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * aliad
     *
     * @return void
     */
    public function seller()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return void
     */
    public function contact()
    {
        return $this->belongsTo(Contact::class);
    }

    /**
     * Activities
     */
    public function activities()
    {
        return $this->hasMany(Activity::class);
    }

    /**
     * Folios
     *
     * @return void
     */
    public function folios()
    {
        return $this->hasMany(Folio::class);
    }

    /**
     * La logica paso al action StoreActivity.
     *
     * @return void
     */
    public function addActivity(array $data)
    {
        $action = new StoreActivity;

        return $action->handle($this, $data);
    }

    /**
     * Origin manual
     *
     * @return bool
     */
    public function isManual()
    {
        return $this->origin == LeadOrigins::Manual;
    }

    /**
     * Dice si es estatus es creado.
     * se usa por ejemplo para saber si se puede clausurar
     */
    public function isCreado(): bool
    {
        return $this->status == LeadStatus::Creado;
    }

    /**
     * Dice si es estatus es clausurado.
     */
    public function isClausurado(): bool
    {
        //   return $this->status == LeadStatus::Clausurado;
        return $this->status;
    }

    /**
     * Says if a quotations has been lost
     */
    public function isLost(): bool
    {
        return $this->status == LeadStatus::Perdido;
    }

    public function isDerivable()
    {
        return ! $this->isDiscarded();
    }

    /**
     * Says if a lead has been discarded
     */
    public function isDiscarded(): bool
    {
        return $this->status == LeadStatus::Descartado;
    }

    /**
     * Says if a lead has been discarded for the correct reason and is rederivable
     */
    public function isRederivable(): bool
    {
        return $this->status == LeadStatus::Rederivar;
    }

    /**
     * Says if a lead has been discarded
     */
    public function notRequiresAcction(): bool
    {
        return $this->status == LeadStatus::NoRequiereAccion;
    }

    /**
     * Says if a quotations has been won
     */
    public function isWon(): bool
    {
        return $this->status == LeadStatus::Vendido;
    }

    /**
     * Says if a quotations has been won or lost
     */
    public function isClosed(): bool
    {
        return $this->isWon() || $this->isLost();
    }

    /**
     * Not closed
     */
    public function isOpen(): bool
    {
        return ! $this->isClosed();
    }

    /**
     * Says if a quotations has been won or lost
     */
    public function isTaken(): bool
    {
        return $this->user_id != null;
    }

    /**
     * Says if a quotations has been won or lost
     */
    public function isFree(): bool
    {
        return $this->user_id == null;
    }

    /**
     * @return bool
     */
    public function isStrategic()
    {
        return $this->priority == LeadPriority::Strategic;
    }

    /**
     * @return void
     */
    public function getIsStrategicAttribute()
    {
        return $this->isStrategic();
    }

    /**
     * El status
     *
     * @return void
     */
    public function getStatusDescriptionAttribute()
    {
        return LeadStatus::getDescription($this->status);
    }

    /**
     * El Ap no tiene web identifier
     *
     * @return void
     */
    public function getWebIdentifierAttribute()
    {
        return $this->id;
    }

    /**
     * El status
     *
     * @return void
     */
    public function getStatusReasonDescriptionAttribute()
    {
        return DiscardReasons::getDescription($this->status_reason);
    }

    /**
     * La prioridad
     *
     * @return void
     */
    public function getPriorityDescriptionAttribute()
    {
        return LeadPriority::getDescription($this->priority);
    }

    /**
     * El tipo de origen
     *
     * @return void
     */
    public function getStatusColorAttribute()
    {
        $color = '';
        switch ($this->status) {
            case LeadStatus::Creado:
                $color = 'warning';
                break;
            case LeadStatus::Vendido:
                $color = 'success';
                break;
            case LeadStatus::Perdido:
                $color = 'danger';
                break;
                /*case LeadStatus::Asignado:
                    $color = 'purple';
                    break;¨*/
            case LeadStatus::Derivado:
                $color = 'teal';
                break;
            case LeadStatus::Cotizado:
                $color = 'green';
                break;
            case LeadStatus::Contactado:
                $color = 'indigo';
                break;
                /* case LeadStatus::Foliado:
                     $color = 'cyan';
                     break; */
                /* case LeadStatus::Rederivar:
                     $color = 'warning';
                     break;*/
        }

        return $color;
    }

    /**
     * El origen
     *
     * @return void
     */
    public function getOriginDescriptionAttribute()
    {
        return LeadOrigins::getTexto($this->origin);
    }

    /**
     * La fuente
     *
     * @return void
     */
    public function getSourceDescriptionAttribute()
    {
        return LeadSources::getDescription($this->source);
    }

    /**
     * La categoria del producto, WCP, AP, Otros
     *
     * @return void
     */
    public function getProductCategoryDescriptionAttribute()
    {
        return ProductCategory::getDescription($this->product_category);
    }

    /**
     * El origen
     *
     * @return void
     */
    public function getFullAddressAttribute()
    {
        return $this->geo_loc1.' '.$this->geo_loc2.' '.$this->geo_loc3.' '.$this->address;
    }

    /**
     * Nombre ucfirst
     *
     * @return void
     */
    public function getNameAttribute($value)
    {
        return ucwords(strtolower($value));
    }

    /**
     * pagina recortada
     *
     * @return void
     */
    public function getPageTitleAttribute($value)
    {
        return Str::before($value, '?');
    }

    /**
     * Los de formularios llegan con este formato (+56) 954552325
     * Esta funcion le quita los parentesis y espacios
     *
     * @return void
     */
    public function getPhoneAttribute($value)
    {
        return preg_replace("/\(|\)|\s/", '', $value);
    }

    /**
     * Los de formularios llegan con este formato (+56) 954552325
     * Esta funcion le quita los parentesis y espacios
     *
     * @return void
     */
    public function setPhoneAttribute($value)
    {
        $this->attributes['phone'] = preg_replace("/\(|\)|\s/", '', $value);
    }

    /**
     * Is taken attribute
     *
     * @return void
     */
    public function getIsTakenAttribute()
    {
        return $this->isTaken();
    }

    /**
     * Formate el numero.
     * Sin decimales, puede que tenga
     * que hacer una funcion para cambiar esto por local
     *
     * @param [type] $net_value
     * @return void
     */
    public function getNetValueAttribute($net_value)
    {
        return $net_value ? number_format($net_value, 0, ',', '.') : $net_value;
    }

    public function getDynamicDataStringAttribute()
    {
        // Puede ser null o string con con corchetes vacios
        if ($this->dynamic_data == '[]' || $this->dynamic_data == null || $this->dynamic_data == '') {
            return '';
        }
        $dynamic_data_array = json_decode($this->dynamic_data, true, 256, JSON_UNESCAPED_UNICODE);
        $dynamic_data_string = '';
        foreach ($dynamic_data_array as $data) {
            $value = $data['value'] ? $data['value'] : '---';
            $campo = ucfirst(str_replace('_', ' ', $data['campo']));
            // con estos hay que hacer modificaciones al formulario inteligente para saber el nombre input
            if ($campo == 'Checkboxes') {
                continue;
            }
            $dynamic_data_string .= $campo.': '.$value.'; ';
        }

        return $dynamic_data_string;
    }
}
