@component('mail::message')
# Prospecto Asignado

Hola. Te enviamos este correo para avisarte que te hemos asignado un lead desde el LMS.


**ID** : {{$lead_web_identifier}}


**Nombre** :  {{$lead_name}}


**Email** : {{$lead_email}}


**Mensaje** : {{$lead_message}} 

@component('mail::button', ['url' => $url])
Gestionar Prospecto
@endcomponent

Gracias,<br>
{{ config('app.name') }}
@endcomponent
