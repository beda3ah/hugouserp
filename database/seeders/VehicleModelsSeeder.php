<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\VehicleModel;
use Illuminate\Database\Seeder;

class VehicleModelsSeeder extends Seeder
{
    public function run(): void
    {
        $vehicleModels = [
            ['brand' => 'Toyota', 'model' => 'Camry', 'year_from' => 2018, 'year_to' => 2024, 'category' => 'sedan', 'engine_type' => 'gasoline'],
            ['brand' => 'Toyota', 'model' => 'Corolla', 'year_from' => 2019, 'year_to' => 2024, 'category' => 'sedan', 'engine_type' => 'gasoline'],
            ['brand' => 'Toyota', 'model' => 'Hilux', 'year_from' => 2016, 'year_to' => 2024, 'category' => 'truck', 'engine_type' => 'diesel'],
            ['brand' => 'Toyota', 'model' => 'Land Cruiser', 'year_from' => 2015, 'year_to' => 2024, 'category' => 'suv', 'engine_type' => 'diesel'],
            ['brand' => 'Hyundai', 'model' => 'Elantra', 'year_from' => 2017, 'year_to' => 2024, 'category' => 'sedan', 'engine_type' => 'gasoline'],
            ['brand' => 'Hyundai', 'model' => 'Tucson', 'year_from' => 2018, 'year_to' => 2024, 'category' => 'suv', 'engine_type' => 'gasoline'],
            ['brand' => 'Hyundai', 'model' => 'Accent', 'year_from' => 2016, 'year_to' => 2024, 'category' => 'sedan', 'engine_type' => 'gasoline'],
            ['brand' => 'Nissan', 'model' => 'Sunny', 'year_from' => 2015, 'year_to' => 2024, 'category' => 'sedan', 'engine_type' => 'gasoline'],
            ['brand' => 'Nissan', 'model' => 'X-Trail', 'year_from' => 2017, 'year_to' => 2024, 'category' => 'suv', 'engine_type' => 'gasoline'],
            ['brand' => 'Kia', 'model' => 'Cerato', 'year_from' => 2018, 'year_to' => 2024, 'category' => 'sedan', 'engine_type' => 'gasoline'],
            ['brand' => 'Kia', 'model' => 'Sportage', 'year_from' => 2016, 'year_to' => 2024, 'category' => 'suv', 'engine_type' => 'gasoline'],
            ['brand' => 'Honda', 'model' => 'Civic', 'year_from' => 2016, 'year_to' => 2024, 'category' => 'sedan', 'engine_type' => 'gasoline'],
            ['brand' => 'Honda', 'model' => 'CR-V', 'year_from' => 2017, 'year_to' => 2024, 'category' => 'suv', 'engine_type' => 'gasoline'],
            ['brand' => 'Mercedes-Benz', 'model' => 'C-Class', 'year_from' => 2015, 'year_to' => 2024, 'category' => 'sedan', 'engine_type' => 'gasoline'],
            ['brand' => 'BMW', 'model' => '3 Series', 'year_from' => 2015, 'year_to' => 2024, 'category' => 'sedan', 'engine_type' => 'gasoline'],
            ['brand' => 'BMW', 'model' => 'X5', 'year_from' => 2016, 'year_to' => 2024, 'category' => 'suv', 'engine_type' => 'diesel'],
            ['brand' => 'Chevrolet', 'model' => 'Optra', 'year_from' => 2014, 'year_to' => 2020, 'category' => 'sedan', 'engine_type' => 'gasoline'],
            ['brand' => 'Suzuki', 'model' => 'Swift', 'year_from' => 2017, 'year_to' => 2024, 'category' => 'sedan', 'engine_type' => 'gasoline'],
            ['brand' => 'Mitsubishi', 'model' => 'Lancer', 'year_from' => 2010, 'year_to' => 2018, 'category' => 'sedan', 'engine_type' => 'gasoline'],
            ['brand' => 'Peugeot', 'model' => '301', 'year_from' => 2015, 'year_to' => 2024, 'category' => 'sedan', 'engine_type' => 'gasoline'],
        ];

        foreach ($vehicleModels as $data) {
            VehicleModel::firstOrCreate(
                ['brand' => $data['brand'], 'model' => $data['model'], 'year_from' => $data['year_from']],
                array_merge($data, ['is_active' => true])
            );
        }
    }
}
