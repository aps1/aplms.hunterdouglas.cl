@component('mail::message')

Hola. Hemos derivado tu solicitud de cotización a nuestro distribuidor autorizado ***{{$dealer_name}}*** quien dará seguimiento a tu solicitud, sus datos de contacto son:

Tel: {{$dealer_cel}} <br>
Mail: {{$dealer_email}} <br>
Dirección: {{$dealer_address}}


Saludos,<br>
@endcomponent
