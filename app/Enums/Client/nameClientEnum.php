<?php

namespace App\Enums\Client;

enum nameClientEnum: string
{
    case ALBENI = 'ALBENI';
    case ALFONSO = 'ALFONSO';
    case ALID = 'ALID';
    case ALIRIO_MIRANDA = 'ALIRIO MIRANDA';
    case ANA = 'ANA';
    case ANGELA = 'ANGELA';
    case AMPARO = 'AMPARO';
    case ANDRES_SANTIAGO = 'ANDRES SANTIAGO';
    case BELEN = 'BELEN';
    case BLANCA_JAIMES = 'BLANCA JAIMES';
    case BLANCA_LIZCANO = 'BLANCA LIZCANO';
    case CAMILA = 'CAMILA';
    case CARLOS_FONSECA = 'CARLOS FONSECA';
    case CARLOS_MATIZ = 'CARLOS MATIZ';
    case CAROLINA_CATATUMBO = 'CAROLINA CATATUMBO';
    case CAROLINA_MORA = 'CAROLINA_MORA';
    case CELINA_VILLAMIZAR = 'CELINA VILLAMIZAR';
    case CHICHO = 'CHICHO';
    case DAVID = 'DAVID';
    case ELIDA_ESPERANZA_DUQUE = 'ELIDA ESPERANZA_DUQUE';
    case ELIZABETH = 'ELIZABETH';
    case ENEIL_JEFFERSON = 'ENEIL JEFFERSON';
    case FANNY = 'FANNY';
    case FREDDY_SANCHEZ = 'FREDDY_SANCHEZ';
    case GEOVANNY = 'GEOVANNY';
    case GEOVANNY_ANTONIA = 'GEOVANNY_ANTONIA';
    case GINNA = 'GINNA';
    case JACKELYN = 'JACKELYN';
    case JAVIER_ARDILA = 'JAVIER ARDILA';
    case JHON_EDUAR = 'JHON EDUAR';
    case JOSE_GOMEZ = 'JOSE_GOMEZ';
    case JOSE_RODRIGUEZ = 'JOSE RODRIGUEZ';
    case JOSEFA = 'JOSEFA';
    case KAREN = 'KAREN';
    case KELLY = 'KELLY';
    case LEONIDAS_TORRES = 'LEONIDAS TORRES';
    case LILIANA = 'LILIANA';
    case LORENA_SUAREZ = 'LORENA SUAREZ';
    case LUIS_TORRES = 'LUIS TORRES';
    case MARCOS_TARAZONA = 'MARCOS_TARAZONA';
    case MARIA_TIZOI = 'MARIA_TIZOI';
    case MARIANO_LOPEZ = 'MARIO_LOPEZ';
    case MERCEDES = 'MERCEDES';
    case MIRIAM = 'MIRIAM';
    case MAURICIO = 'MAURICIO';
    case PAOLA_RIVAS = 'PAOLA_RIVAS';
    case PALACIO = 'PALACIO';
    case RAUL = 'RAUL';
    case REYNA = 'REYNA';
    case ROSA_PARADA = 'ROSA PARADA';
    case RUBEN = 'RUBEN';
    case RUTH_GLORIA = 'RUTH GLORIA';
    case SANDRA_MORALES_JANNER = 'SANDRA_MORALES_JANNER';
    case SAIRE = 'SAIRE';
    case SINDY_KATERINE_PIO = 'SINDY_KATERINE_PIO';
    case TAVO = 'TAVO';
    case WILLIAM = 'WILLIAM';
    case YAMILE = 'YAMILE';
    case YURI_CAROLINA_SARMIENTO = 'YURI CAROLINA_SARMIENTO';
    case YURI_OJEDA = 'YURI OJEDA';
    case YEPE = 'YEPE';
    
    public static function getValues(): array
    {
        return array_column(self::cases(), 'value');
    }
}