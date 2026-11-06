@component('mail::message')
# Recuperar contraseña

Te enviamos este correo porque recibimos una solicitud de recuperación de contraseña para tu cuenta de HunterDouglas LMS.

@component('mail::button', ['url' => $url])
Cambiar contraseña
@endcomponent

Si no hiciste la solicitud, puedes ignorar este correo.<br>
{{ config('app.name') }}
@endcomponent