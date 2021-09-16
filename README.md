END POINT CONFIGURADO EN XAMPP: http://localhost/ExamenGeniat/controller/API.php

-TODOS LOS REQUEST SE TIENEN QUE METER EN BODY "RAW"
-CUANTO SE TRABAJE CON EL TOKEN SE DEBERA PONER EN Authorization -> Bearer Token
-SE HABILITO EL USUARIO master@gmail.com CON  PASS master para hacer las pruebas.

INSTRUMENTACION Y EJEMPLOS

REQUEST GET: USUARIO DEFAULT CARGADO EN LA BD PARA PRUEBAS

{
    "sCorreo":"master@gmail.com",
    "sPassword":"master",
    "accion":"Login"
}

RESPONSE:
{
    "nCodigo":0,
    "sMensaje":"Bienvenido Jose Francisco",
    "token":"eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJleHAiOjE2MzE4MDIxNTUsImF1ZCI6IjA0OWQ4NTUwYzBlY2U4MmUxZjZhZTc3MTk0YmNjZTQ0ZTZhZTU1MzUiLCJkYXRhIjp7ImlkIjozLCJuYW1lIjoiSm9zZSBGcmFuY2lzY28iLCJyb2wiOjV9fQ.o5e8TeWgm-xMgrn0-M5ekcA2_Am3L_-XDjgKdhF7SP01"
}

REQUEST POST:
{
    "sNombre":"Jose Francisco",
    "sApellidoPaterno":"Requena",
    "sApellidoMaterno":"Aguayo",
    "sCorreo":"jrequena@redefectiva.com",
    "sPassword":"requena1",
    "sRol":"5",
    "accion":"registrarUsuario"
}

RESPONSE:
{
    "nCodigo": 0,
    "sMensaje": "Usuario registrado exitosamente"
}


REQUEST POST:
{
    "sTitulo":"El señor de los anillos",
    "sDescripcion":"Llevar en anillo a la montaña del destino",
    "accion":"registrarPublicacion"
}

RESPONSE:

{
    "nCodigo": 0,
    "sMensaje": "Publiación registrada exitosamente",
    "nIdPublicacion": 3
}

REQUEST PUT:
{
    "nIdPublicacion": 3,
    "sTitulo": "El señor de los anillos Prte 2",
    "sDescripcion": "Las dos torres",
    "accion": "actualizarPublicacion"
}

RESPONSE:
{
    "nCodigo": 0,
    "sMensaje": "Publiación actualizada exitosamente",
    "nIdPublicacion": 3
}

REQUEST DELETE:
{
    "nIdPublicacion": 1,
    "accion": "eliminarPublicacion"
}

RESPONSE:

{
    "nCodigo": 0,
    "sMensaje": "Publiación eliminada exitosamente",
    "nIdPublicacion": 1
}

REQUEST GET:
{
    "accion": "consultarPublicaciones"
}

RESPONSE:

[
    {
        "sTitulo": "Cosas chidas 2",
        "sDescripcion": "Son cosas que pasan 2",
        "dFecRegistro": "2021-09-16 10:43:40",
        "sUsuarioNombreCompleto": "Jose Francisco Requena Aguayo",
        "sNombreRol": "Rol alto"
    },
    {
        "sTitulo": "El señor de los anillos Prte 2",
        "sDescripcion": "Las dos torres",
        "dFecRegistro": "0000-00-00 00:00:00",
        "sUsuarioNombreCompleto": "Jose Francisco2 Requena2 Aguayo2",
        "sNombreRol": "Rol alto"
    }
]
