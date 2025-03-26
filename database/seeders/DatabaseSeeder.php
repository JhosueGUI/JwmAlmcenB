<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(\Database\Seeders\RRHH\HorarioSeeder::class);
        $this->call(AreaSeeder::class);
        $this->call(\Database\Seeders\RRHH\PlanillaSeeder::class);
        $this->call(\Database\Seeders\RRHH\CargoSeeder::class);
        // $this->call(\Database\Seeders\COMBUSTIBLE\GrifoSeeder::class);
        $this->call(FamiliaSeeder::class);
        $this->call(SubFamiliaSeeder::class);
        $this->call(UnidadMedidaSeeder::class);
        $this->call(UbicacionSeeder::class);
        $this->call(EstadoOperativoSeeder::class);
        $this->call(AccesoSeeder::class);
        $this->call(RolSeeder::class);
        $this->call(TipoDocumentoSeeder::class);
        $this->call(AdminSeeder::class);
        $this->call(DestinoCombustibleSeeder::class);
        // $this->call(ProveedorSeeder::class);
        // $this->call(InventarioValorizadoSeeder::class);
        //Finanza
        $this->call([\Database\Seeders\FINANZA\EmpresaSeeder::class]);
        $this->call([\Database\Seeders\FINANZA\EstadoComprobanteSeeder::class]);
        $this->call([\Database\Seeders\FINANZA\ClienteSeeder::class]);
        $this->call([\Database\Seeders\FINANZA\RendicionSeeder::class]);
        $this->call([\Database\Seeders\FINANZA\SustentoSeeder::class]);
        $this->call([\Database\Seeders\FINANZA\MonedaSeeder::class]);
        $this->call([\Database\Seeders\FINANZA\ModoSeeder::class]);
        $this->call([\Database\Seeders\FINANZA\CategoriaSeeder::class]);
        $this->call([\Database\Seeders\FINANZA\SubCategoriaSeeder::class]);
    }
}
