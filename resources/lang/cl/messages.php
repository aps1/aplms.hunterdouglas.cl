<?php

return [
    'leads' => [
        'clausurar' => 'Se ha clausurado este Prospecto porque no requiere actividades posteriores de parte de los distribuidores',
        'descartar' => 'Se ha descartado este Prospecto',
        'no_para_distribuidor' => 'No hay ningún prospecto para este distribuidor',
        'no_registrado' => 'No hay ningún prospecto registrado',
        'registro' => 'Se registó este prospecto con origen :origen , y el siguiente mensaje:',
        'confirmar_eliminar' => '¿Estás seguro que quieres eliminar este prospecto? Esta acción no puede deshacerse. Luego de eliminado no quedará registro de que existió el prospecto y se eliminarán las actividades asociadas.',
        'success_eliminar' => 'Se eliminó el prospecto con todas sus actividades',
        'success_clausurar' => 'Se clausuró el prospecto',
        'success_strategic' => 'Se califico al prospecto',
        'confirmar_tomar' => 'Luego de que tomes este prospecto sólo tú podrás gestionarlo',
        'derivado' => 'Se derivó el prospecto al distribuidor',
        'derivado_manual' => 'Prospecto derivado con un registro manual',
        'derivado_web' => 'Prospecto derivado con un registro automático',
        'registrado_manual' => 'Prospecto registrado manualmente',
        'liberado' => 'Se liberá el prospecto para ser tomado por otro vendedor',
        'asignado' => 'Se asignó a :vendedor para manejar este prospecto',
        'tomado' => ':vendedor tomó este prospecto para gestionarlo',
        'confirmar_estrategico' => '¿Estas seguro que quieres calificiar a este prospecto como estratégico?',
        'confirmar_no_estrategico' => '¿Estas seguro que quieres quitar el calificativo de estratégico a este prospecto?',
    ],
    'users' => [
        'confirmar_inactivar' => 'Una vez inactivado, el usuario no podrá iniciar sesión en la aplicación y no se le podrá asignar recursos.',
    ],
    'dealerships' => [
        'confirmar_inactivar' => 'Una vez inactivado el Distribuidor, sus usuarios no podrán iniciar sesión en la aplicación y no se les podrán asignar recursos.',
    ],
    'contacts' => [
        'success_store' => 'Se creó el contacto',
        'confirmar_eliminar' => '¿Estás seguro que quieres eliminar este contacto? Esta acción no puede deshacerse. Luego de eliminado no quedará registro de que existió el contacto.',
    ],
    'kams' => [
        'success_store' => 'Se agregó al KAM',
        'sucess_update_dealerships' => 'Se actualizaron los distribuidores del KAM',
    ],
];
