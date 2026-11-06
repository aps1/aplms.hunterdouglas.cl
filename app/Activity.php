<?php

namespace App;

use App\Enums\ActivityTypes;
use App\Enums\DiscardReasons;
use App\Traits\Localizable;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media; // Importa Media

class Activity extends Model implements HasMedia
{
    use  Localizable;
    use InteractsWithMedia;

    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('thumb')
              ->width(368)
              ->height(232)
              ->sharpen(10);

        $this->addMediaConversion('normal')
              ->width(800)
              ->height(600)
              ->sharpen(10);
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('images')
             ->singleFile();
    }

    /**
     * Mass asigment
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'created_at',
        'updated_at',
        'previous_at',
    ];

    /**
     * @return void
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return void
     */
    public function lead()
    {
        return $this->belongsTo(Lead::class);
    }

    /**
     * @return void
     */
    public function dealership()
    {
        return $this->belongsTo(Dealership::class);
    }

    /**
     * El tipo de actividad
     *
     * @return void
     */
    public function getTypeDescriptionAttribute()
    {
        return ActivityTypes::getDescription($this->type);
    }

    /**
     * El tipo de actividad
     *
     * @return void
     */
    public function getReasonDescriptionAttribute()
    {
        return DiscardReasons::getDescription($this->reason);
    }

    /**
     * El tipo de actividad
     *
     * @return void
     */
    public function getTypeColorAttribute()
    {
        switch ($this->type) {
            case ActivityTypes::Creado:
                $color = 'yellow';
                break;
            case ActivityTypes::Derivar:
                $color = 'navy';
                break;
            case ActivityTypes::Asignar:
                $color = 'purple';
                break;
            case ActivityTypes::Tomar:
                $color = 'purple';
                break;
            case ActivityTypes::Contacto:
                $color = 'indigo';
                break;
            case ActivityTypes::Cotizar:
                $color = 'green';
                break;
            case ActivityTypes::Desiste:
                $color = 'red';
                break;
            case ActivityTypes::Venta:
                $color = 'blue';
                break;
            case ActivityTypes::Foliar:
                $color = 'blue';
                break;
            case ActivityTypes::Comentario:
                $color = 'yellow';
                break;
            case ActivityTypes::Visitar:
                $color = 'indigo';
                break;
            case ActivityTypes::Descartar:
                $color = 'gray';
                break;
            default:
                $color = 'gray';
                break;
        }

        return $color;
    }

    /**
     * El tipo de actividad
     *
     * @return void
     */
    public function getTypeIconAttribute()
    {
        switch ($this->type) {
            case ActivityTypes::Creado:
                $icon = 'fa-envelope-open';
                break;
            case ActivityTypes::Derivar:
                $icon = 'fa-paper-plane';
                break;
            case ActivityTypes::Asignar:
                $icon = 'fa-sitemap';
                break;
            case ActivityTypes::Tomar:
                $icon = 'fa-sitemap';
                break;
            case ActivityTypes::Comentario:
                $icon = 'fa-comment-o';
                break;
            case ActivityTypes::Contacto:
                $icon = 'fa-phone';
                break;
            case ActivityTypes::Cotizar:
                $icon = 'fa-coffee';
                break;
            case ActivityTypes::Desiste:
                $icon = 'fa-thumbs-o-down';
                break;
            case ActivityTypes::Venta:
                $icon = 'fa-thumbs-o-up';
                break;
            case ActivityTypes::Clausurar:
                $icon = 'fa-lock';
                break;
            case ActivityTypes::Descartar:
                $icon = 'fa-trash';
                break;
            case ActivityTypes::Ignorar:
                $icon = 'fa-archive';
                break;
            case ActivityTypes::Foliar:
                $icon = 'fa-barcode';
                break;
            case ActivityTypes::Visitar:
                $icon = 'fa-home';
                break;
            default:
                $icon = '';
                break;
        }

        return $icon;
    }
}
