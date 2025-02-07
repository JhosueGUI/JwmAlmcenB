<?php

namespace Database\Seeders;

use App\Models\Proveedor;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProveedorSeeder extends Seeder
{
    public function run(): void
    {
        $razon_sociales_primero = [
            'J.CH.COMERCIAL S.A.',
            'ECOTRANSPORTE PERU S.A.C.',
            'GLOBAL PERLA´S CAR S.A.C.',
            'PROCABLES SA',
            'MULTI TOLDERAS J & J E.I.R.L.',
            'RENOVA SAC',
        ];
        $RUCS_primero = [
            '20318171701',
            '20609279614',
            '20546153372',
            '20259659907',
            '20607994189',
            '20100359708',
        ];
        $direcciones_primero = [
            'AV. TOMAS MARSANO NRO. 900 LIMA - LIMA - SURQUILLO',
            'JR. PROLONGACION ENRIQUE PALLARDELLE NRO. 324 URB. EL RETABLO ET. UNO LIMA - LIMA - COMAS',
            'JR. UNION NRO. 166 COO. 27 DE ABRIL LIMA - LIMA - ATE',
            'AV. LOS MINERALES NRO. 771 URB. INDUSTRIAL WIESE LIMA - LIMA - LIMA',
            'PJ. VICTOR FAJARDO NRO. 29 OTR. AV. LA CULTURA 701 LIMA - LIMA - SANTA ANITA',
            'AV. INDUSTRIAL NRO. 3598 URB. INDUSTRIAL PANAMERICANA NORTE LIMA - LIMA - INDEPENDENCIA',
        ];
        $forma_pagos_primero = [
            'CRÉDITO 60 DÍAS',
            '',
            'CRÉDITO 30 DÍAS',
            '',
            '',
            'CRÉDITO 30 DÍAS',


        ];
        $contactos_primero = [
            ' EVER CASTILLO',
            '',
            'XIOMARA ESPINOZA',
            'NORKA DEXTRE',
            '',
            'WILBER SANCHEZ',


        ];
        $numero_celulares_primero = [
            '+ 51 947 844 555',
            '',
            '+51 940 426 959',
            '+ 51 960 186 459',
            '',
            '+ 51 987 962 490',


        ];
        foreach ($razon_sociales_primero as $index => $razon_social) {
            Proveedor::firstOrcreate([
                'razon_social' => $razon_social,
                'ruc' => $RUCS_primero[$index],
                'direccion' => $direcciones_primero[$index],
                'forma_pago' => $forma_pagos_primero[$index],
                'contacto' => $contactos_primero[$index],
                'numero_celular' => $numero_celulares_primero[$index]
            ]);
        }
        Proveedor::firstOrcreate([
            'id' => 8,
            'razon_social' => 'SOLTRAK S.A.',
            'ruc' => '20511914125',
            'direccion' => 'AV. ARGENTINA NRO. 5799 PROV. CONST. DEL CALLAO - PROV. CONST. DEL CALLAO - CARMEN DE LA LEGUA REYNOSO',
            'forma_pago' => 'CRÉDITO 30 DÍAS',
            'contacto' => 'MIGUEL ANGEL OROZCO',
            'numero_celular' => '+51 948 949 905',
        ]);
        $razon_sociales = [
            'INTERNATIONAL CAMIONES DEL PERU SAC',
            'BIOFLUID S.A.C.',
            'MULTILLANTAS SHADAAI SALEM E.I.R.L.',
            'CSI IMPORT SAC',
            'TRACTO CAMIONES USA',
            'BRAMA TRANSPORTES Y COMERCIALIZACIONES EIRL',
            'LUBRICENTRO NIAM,ROJAS VELÁSQUEZ INGRID THEDA',
            'AQUA DISPENSERS E.I.R.L.',
            'GRUPO DALER E.I.R.L',
            'REENCAUCHADORA BRADAR S.R.L.',
            'SUPERBLUE S.A.C.',
            'JANAMPA RIVERA ROSALYN SELMIRA',
            'FERRECOM C & R',
            'MULTITOOLS PERU S.A.C.',
            'AUTOMOTRIZ SAN BLAS S.A.',
            'AUTOMOTRIZ SAN CRISTOBAL S.A.',
            'AUTOESPAR S A',
            'PESCO PERU S.A.C.',
            'CASTRO SIXTO DARWIN MARCO',
            'TAI LOY S.A.',
            'CLIMBER WORLD PERU SAC',

        ];
        $RUCS = [

            '20600045521',
            '20609992299',
            '20601598095',
            '20608057278',
            '20293774308',
            '20429222428',
            '10767249239',
            '20604838895',
            '20610468331',
            '20512960287',
            '20610596046',
            '10405001549',
            '20601595932',
            '20609054027',
            '20120476816',
            '20538329542',
            '20100821371',
            '20601641250',
            '10438928303',
            '20100049181',
            '20515384988',

        ];

        $direcciones = [

            'AV. DOMINGO ORUE NRO. 973 LIMA - LIMA - SURQUILLO',
            'MZA. C LOTE. 8 DEMSA SANTA CLARA LIMA - LIMA - ATE',
            'AV. SANTA ANA MZA. C-7 LOTE. 17 URB. RESIDENCIAL SANTA ANITA LIMA - LIMA - SANTA ANITA',
            'OTR.CALLE 25 MZA. B7 LOTE. 16 URB. RESIDENCIAL SANTA ANITA LIMA - LIMA - SANTA ANITA',
            'AV. NICOLAS AYLLON NRO. 3094 - 3968 ATE - LIMA',
            'JR. LA UNION NRO. 189 COO. 27 DE ABRIL (PARADERO NAVARRETE) LIMA - LIMA - ATE',
            'AV. AGUSTIN DE LA ROSA TORO 130 URB. LA VIÑA SAN LUIS - LIMA - LIMA',
            'JOSE CRESPO URB. CERES PRIMERA ETAPA NRO. 246 DPTO. 301 , ATE , Lima - LIMA',
            'AV. COSTANERA N° 1502 , SAN MIGUEL , LIMA - LIMA',
            'AV. SANTA ANA MZA. D5 LOTE. 27 URB. PRIMAVERA LIMA - LIMA - SANTA ANITA',
            'CAL.VIRGEN DE LOURDES MZA. H LOTE. 4 P.J. VIRGEN DE LA PAZ LAMBAYEQUE - CHICLAYO - CHICLAYO',
            'AV. NICOLAS AYLLON NRO 11656 KM 14.5 CARRETERA CENTRAL ATE - LIMA ',
            'CAL.2 MZA. D1 LOTE. 20 INT. 03 URB. SAN ANTONIO DE CARAPONGO LIMA - LIMA - LURIGANCHO',
            'JR. SARGENTO ANTONIO LISHNER NRO. 1798 LIMA - LIMA - LIMA',
            'AV. NICOLAS AYLLON NRO. 1980 URB. VALDIVIEZO LIMA - LIMA - ATE',
            'AV. NICOLAS AYLLON NRO. 1980 (AV. NICOLAS AYLLON) LIMA - LIMA - ATE',
            'AV. ALFREDO MENDIOLA NRO. 1635 URB. LA MILLA LIMA - LIMA - SAN MARTIN DE PORRES',
            'AV. CASCANUECES NRO. 790 URB. FUNDO INQUISIDOR LIMA - LIMA - SANTA ANITA',
            '',
            'JR. MARIANO ODICIO NRO. 153 URB. MIRAFLORES (MZ L, LOTE 144, SUB LOTE A) LIMA - LIMA - SURQUILLO',
            'AV. ARGENTINA NRO. 3467 (COSTADO DEL MERCADO MINKA) PROV. CONST. DEL CALLAO - PROV. CONST. DEL CALLAO - CALLAO',

        ];

        $forma_pagos = [

            '',
            '',
            '',
            'CRÉDITO 30 DÍAS',
            '',
            '',
            '',
            'CONTADO',
            'CONTADO',
            'CONTADO',
            'CONTADO',
            'CONTADO ',
            'CONTADO ',
            'CONTADO',
            '',
            '',
            '',
            'TRANSFERENCIA INTERBANCARIA',
            '',
            '',
            ''

        ];

        $contactos = [

            'ROY PUENTE NIEVA',
            'NATALY ROJAS',
            '',
            'CECILIA DEL PILAR BERROCAL VEGA',
            'JAVIER SOLIS',
            '',
            '',
            'KELLY YAURI LEIVA',
            'BRAYAN ESPINOZA',
            'CARLOS FLORES',
            'NATHALY ROJAS',
            '',
            'DIEGO CARHUACHIN ROJAS',
            ' MAYRA GUILLEN VALENCIA',
            ' APOLO SUCASAIRE',
            '',
            'ROLANDO GARRIDO',
            'ANGELA ELLOIT',
            '',
            '',
            'CESAR VERGARAY SANCHEZ',

        ];

        $numero_celulares = [

            '+51 976 590 114',
            '+ 51 912 493 818',
            '',
            '+ 51 980 295 555',
            '+51 998 359 030',
            '',
            '',
            '+ 51 926 077 262',
            '+51 920 895 600',
            '+51 998 838 480',
            '+ 51 912 493 818',
            '',
            '+51 952 861 826',
            '+51 972 203 704',
            '+51 989 491 528',
            '+01 32 60599',
            '+51 936 875 051',
            '+51 991 525 025',
            '',
            '',
            '+51 991 830 063',

        ];
        foreach ($razon_sociales as $index => $razon_social) {
            Proveedor::firstOrcreate([
                'razon_social' => $razon_social,
                'ruc' => $RUCS[$index],
                'direccion' => $direcciones[$index],
                'forma_pago' => $forma_pagos[$index],
                'contacto' => $contactos[$index],
                'numero_celular' => $numero_celulares[$index]
            ]);
        }
        $razon_sociales_segundo = [
           
            'ORTIZ SABOYA ALBERTO',
            'FERRETERIA UNIVERSAL EN PERNOS & HERRAMIENTAS E.I.R.L.',
            'ITLIKE S.A.C',
            'INGENIERIA SERVICIOS CONSTRUCCION Y REPRESENTACIONES S.A.C. _ INSECORE S.A.C.',
            'JORGE LEON CENTURION',
            'JESUS MI FORTALEZA',
            'ARIAS CHAVEZ DEISY ROXANA',
            'HOMECENTERS PERUANOS S.A.',
            'CORPORACION AQUA ENVIRONMENTAL S.A.C.',
            'FERROSE IMPORT EXPORT S.A.C',
            'INVERSIONES LEO IMPRE E.I.R.L',
            'VISTONY COMPAÑIA INDUSTRIAL DEL PERU SOCIEDAD ANONIMA CERRADA',
            'MAXTIRE SOCIEDAD ANONIMA CERRADA',
            'IMPRENTA ANJOCREATIVO E.I.R.L.',
            'SUMINISTROS PREVEN PERU S.A.C.',
            'PRECON TELECOM S.A.C.',
            'GRUPO LLATA S.A.C.',
        ];
        $RUCS_segundo = [
            
            '10400341783',
            '20605744789',
            '20603744528',
            '20556129518',
            '8313986',
            '10106840674',
            '10446817022',
            '20536557858',
            '20600146352',
            '20513068485',
            '20602021191',
            '20102306598',
            '20610743464',
            '20604722889',
            '20603630395',
            '20602453589',
            '20600682921',
        ];
        $direcciones_segundo = [
            
            '',
            'AV. CARAPONGO MZA. E LOTE. 2 INT. 12 ASC. LOS TULIPANES LIMA - LIMA - LURIGANCHO',
            'CAL.1 DE MAYO NRO. 941 URB. LA ACHIRANA 2DA ETAPA (PUENTE AZUL) LIMA - LIMA - SANTA ANITA',
            'PJ. AREQUIPA NRO. 192 URB. 22 HECTAREAS PROV. CONST. DEL CALLAO - PROV. CONST. DEL CALLAO - CARMEN DE LA LEGUA REYNOSO',
            'SANTA ANITA',
            '',
            '',
            'AV. AVIACION NRO. 2405 (PISO 5) LIMA - LIMA - SAN BORJA',
            'URB. SAN ANTONIO DE CARAPONGO MZA. H LOTE. 9 LURIGANCHO - LIMA - LIMA',
            'AV. ALFONSO UGARTE NRO. 327 INT. REF (PISO 3) LIMA - LIMA - LIMA',
            'JR. ORBEGOSO NRO. 271 INT. 380A (GALERIA GUIZADO) LIMA - LIMA - BREÑA',
            'MZA. B1 LOTE 01 PQUE. IND. DE ANCÓN - ACOMPIA (ALT. KM.46.5 PAN.NORTE) ANCÓN-ANCÓN-LIMA',
            'AV. ALFREDO MENDIOLA NRO. 7002 LIMA - LIMA - SAN MARTIN DE PORRES',
            'CAR.CARRETERA CENTRAL KM. 13.5 LOTE. 39 C.P. GLORIA ALCANFORES LIMA - LIMA - ATE',
            'AV. TOMAS VALLE NRO. 3473 URB. JORGE CHAVEZ II ETAPA PROV. CONST. DEL CALLAO - PROV. CONST. DEL CALLAO - CALLAO',
            'MZA. F LOTE. 2 ASC. LOS TULIPANES LIMA - LIMA - LURIGANCHO',
            'AV. JOSE CARLOS MARIATEGUI MZA. G LOTE. 12 ASC. RESIDENCIAL VILLA HERMOSA LIMA - LIMA - ATE',
        ];
        $forma_pagos_segundo = [
            
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            'TRANSFERENCIA INTERBANCARIA',
            '',
            '',
            '',
            'CREDITO 90 DIAS',
            '',
            '',
            '',
            '',
        ];
        $contactos_segundo = [
            
            'ALBERTO ORTIZ SABOYA',
            'NILTON PAGÁN',
            'YESSENIA',
            'DANIELA RODRIGUEZ',
            'JORGE LEÓN CENTURIÓN',
            '',
            'ARIAS CHAVEZ DEISY ROXANA',
            '',
            'ESTEFANIA LLANOS',
            '',
            'LEO WALTER',
            'VANESSA MENDOZA ARIAS',
            'JANET CUYA VILCHEZ',
            '',
            '',
            'SHOWNNY ELIZABETH',
            'ERASMO LLATA',
        ];
        $numero_celulares_segundo = [
            
            '+51 983 401 925',
            '+51 900 621 832',
            '+51 946 053 088',
            '+51 940 727 081',
            '+51 910 533 496',
            '',
            '+51 922 961 155',
            '',
            '+51 962 096 094',
            '+51 977 829 471',
            '+51 999 540 154',
            '+51 949 883 313',
            '+51 950 348 887',
            '+51 948 766 818',
            '',
            '+51 956 062 127',
            '+51 992 295 187',
        ];
        Proveedor::firstOrcreate([
            'id'=>31,
            'razon_social' =>  'MECU SEGURIDAD INDUSTRIAL E.I.R.L.',
            'ruc' => '20607834360',
            'direccion' => 'AV. REPUBLICA DE ARGENTINA NRO. 639 INT. A073 URB. LIMA INDUSTRIAL (CC.CC UDAMPE) LIMA - LIMA - LIMA',
            'forma_pago' => '',
            'contacto' => 'TESLY TRUJILLO',
            'numero_celular' => '+51 934 093 840',
        ]);
        foreach ($razon_sociales_segundo as $index => $razon_social) {
            Proveedor::firstOrcreate([
                'razon_social' => $razon_social,
                'ruc' => $RUCS_segundo[$index],
                'direccion' => $direcciones_segundo[$index],
                'forma_pago' => $forma_pagos_segundo[$index],
                'contacto' => $contactos_segundo[$index],
                'numero_celular' => $numero_celulares_segundo[$index]
            ]);
        }
    }
}