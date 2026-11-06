@component('mail::message')
# Prospecto derivado

Hola. Te enviamos este correo para avisarte que te hemos derivado un prospecto desde el LMS.

**ID** : {{$lead_web_identifier}}


**Nombre** :  {{$lead_name}}


**Teléfono** : {{$lead_phone}}


**Email** : {{$lead_email}}


**Mensaje** : {{$lead_message}} 

@component('mail::button', ['url' => $url])
Gestionar Prospecto
@endcomponent

Gracias,<br>
{{ config('app.name') }}
@endcomponent
