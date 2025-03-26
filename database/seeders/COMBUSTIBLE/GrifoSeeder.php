<?php

namespace Database\Seeders\COMBUSTIBLE;

use App\Models\COMBUSTIBLE\Grifo;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class GrifoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $grifos=[
            'ADMINISTRACION DE GRIFOS LEP S.A.C.',
            'CARRASCO TORRES HORTENCIO',
            'CATERER PERU GLOBAL S.A.C.',
            'COESTI S.A.',
            'CONSORCIO ORO NEGRO S.A.C.',
            'CORPORACION JUDY S.A.C.',
            'CORPORACION OIL GEMS S.A.C.',
            'ESTACION ATOCONGO S.A.C.',
            'CORPORACION RIO BRANCO S.A',
            'ESTACION DE SERVICIOS ANDERS E.I.R.L.',
            'ESTACION DE SERVICIOS ANCON S.A.',
            'ESTACION DE SERVICIOS DAVID NR S.A.C.',
            'ESTACION DE SERVICIOS EL SOL S.R.L.',
            'ESTACION DE SERVICIOS EL TREN S.R.L.',
            'ESTACION DE SERVICIOS EL TRANSPORTISTA II E.I.R.L.',
            'ESTACION DE SERVICIOS GRIFO DENVER S.R.L.',
            'ESTACION DE SERVICIOS FRAY MARTIN S.R.L.',
            'ESTACION DE SERVICIOS OLMOS E.I.R.L.',
            'ESTACION DE SERVICIOS MIRWAL S.A.C.',
            'ESTACION DE SERVICIOS PIAMONTE SAC',
            'ESTACION DE SERVICIOS PASO DE LOS ANDES SAC',
            'ESTACION DE SERVICIOS SAN ANTONIO S.A.C.',
            'ESTACION DE SERVICIOS VENTURA SAC',
            'GASOCENTRO CAMPANA S.C.R.L.',
            'GASOLINERA LA PARCELA 45 S.A.C.',
            'GASOLINERAS PIURA S.R.L.',
            'GRAN PRIX CORPORACION E.I.R.L.',
            'GRIFO EL PORVENIR S.R.L.',
            'GRIFO ATICO SRL.',
            'GRIFO LOS PORTALES S.A.C.',
            'GRIFO LA LAGUNA S.A.C.',
            'GRIFO RISO COMPANY S.A.C.',
            'GRIFO SANTO DOMINGO DE GUZMAN SRLTDA',
            'GRIFOS ESPINOZA S.A.',
            'GRIFO SERVITOR S.A',
            'HALCON GROUP S.A.C.',
            'GRIFOS MIRAMAR S.A.C.',
            'HIRIDARAN E.I.R.L.',
            'INVERSIONES ARIAS S.A.C.',
            'INVERSIONES E INDUSTRIAS MIRFER S.A.C.',
            'INVERSIONES GARAY S.R.L.',
            'INVERSIONES Y SERVICIOS SAN SEBASTIAN S.A.C.',
            'INVERSIONES SALCANI S.A.C.',
            'LK COMBUSTIBLES S.A.C.',
            'J & C TRADING CORPORATION S.A.C.',
            'MARBELLA PERU S.A.C.',
            'MULTISERVICIOS E INVERSIONES ANDERSON E.I.R.L',
            'MULTISERVICIOS LUCHIN`S E.I.R.L',
            'MULTISERVICIOS FADA S.R.L.',
            'NEGOCIACION KIO S.A.C.',
            'NEGOCIACIONES & COMBUSTIBLES OSBERAL S.A.C',
            'PASCUAL FRETEL DAVID EDGAR',
            'REPRESENTACIONES E IMPORTACIONES MIJ S.R.L',
            'RIOS VARGAS MIGUEL OSCAR',
            'REPSOL COMERCIAL S.A.C.',
            'SERVICENTRO CAMPANA S.C.R.L.',
            'SELVA COMBUSTIBLES E.I.R.L.',
            'SERVICENTRO DOCE S.A.',
            'SERVICENTRO EL CAMIONERO SAC',
            'SERVICENTRO HUARMEY S.A.C',
            'SERVICENTRO KEVN E.I.R.L.',
            'SERVICIOS GENERALES FULL GRIFOS DEL PERU S.A.C.',
            'YAVA S.A.C.',
            'VIPETROS S.A.C.',
            'SERVICIOS MULTIPLES SANTA CECILIA S.A.C. SERMUSCE S.A.C.'
        ];
        foreach($grifos as $grifo){
            Grifo::create([
                'nombre'=>$grifo,
            ]);
        }
    }
}
