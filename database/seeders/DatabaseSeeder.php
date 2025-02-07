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
        $this->call(FamiliaSeeder::class);
        $this->call(SubFamiliaSeeder::class);
        $this->call(UnidadMedidaSeeder::class);
        $this->call(UbicacionSeeder::class);
        $this->call(EstadoOperativoSeeder::class);
        $this->call(AccesoSeeder::class);
        $this->call(RolSeeder::class);
        $this->call(TipoDocumentoSeeder::class);
        $this->call(AreaSeeder::class);
        $this->call(AdminSeeder::class);
        $this->call(DestinoCombustibleSeeder::class);
        // $this->call(ProveedorSeeder::class);
        // $this->call(InventarioValorizadoSeeder::class);
    }
}
