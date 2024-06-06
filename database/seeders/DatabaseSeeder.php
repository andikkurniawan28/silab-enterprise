<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Role;
use App\Models\User;
use App\Models\Setup;
use App\Models\Option;
use App\Models\Feature;
use App\Models\Station;
use App\Models\Material;
use App\Models\Parameter;
use App\Models\Permission;
use App\Models\MeasurementUnit;
use Illuminate\Database\Seeder;
use App\Models\MaterialCategory;
use App\Models\MaterialParameter;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $setup = [
            'app_name' => ucwords(str_replace('_', ' ', 'silab')),
        ];
        Setup::insert($setup);

        $roles = [
            ['name' => ucwords(str_replace('_', ' ', 'manager'))],
            ['name' => ucwords(str_replace('_', ' ', 'supervisor'))],
            ['name' => ucwords(str_replace('_', ' ', 'admin'))],
            ['name' => ucwords(str_replace('_', ' ', 'user'))],
        ];
        Role::insert($roles);

        $users = [
            ['name' => ucwords(str_replace('_', ' ', 'master')), 'username' => 'master', 'password' => bcrypt('master'), 'role_id' => 1, 'is_active' => 1],
        ];
        User::insert($users);

        $features = [
            ['name' => ucfirst(str_replace('_', ' ', 'setup')), 'route' => 'setup.index'],
            ['name' => ucfirst(str_replace('_', ' ', 'update_setup')), 'route' => 'setup.update'],
            ['name' => ucfirst(str_replace('_', ' ', 'list_of_role')), 'route' => 'role.index'],
            ['name' => ucfirst(str_replace('_', ' ', 'create_role')), 'route' => 'role.create'],
            ['name' => ucfirst(str_replace('_', ' ', 'save_role')), 'route' => 'role.store'],
            ['name' => ucfirst(str_replace('_', ' ', 'edit_role')), 'route' => 'role.edit'],
            ['name' => ucfirst(str_replace('_', ' ', 'update_role')), 'route' => 'role.update'],
            ['name' => ucfirst(str_replace('_', ' ', 'delete_role')), 'route' => 'role.destroy'],
            ['name' => ucfirst(str_replace('_', ' ', 'list_of_user')), 'route' => 'user.index'],
            ['name' => ucfirst(str_replace('_', ' ', 'create_user')), 'route' => 'user.create'],
            ['name' => ucfirst(str_replace('_', ' ', 'save_user')), 'route' => 'user.store'],
            ['name' => ucfirst(str_replace('_', ' ', 'edit_user')), 'route' => 'user.edit'],
            ['name' => ucfirst(str_replace('_', ' ', 'update_user')), 'route' => 'user.update'],
            ['name' => ucfirst(str_replace('_', ' ', 'delete_user')), 'route' => 'user.destroy'],
            ['name' => ucfirst(str_replace('_', ' ', 'activity_log')), 'route' => 'activity_log'],
            ['name' => ucfirst(str_replace('_', ' ', 'list_of_station')), 'route' => 'station.index'],
            ['name' => ucfirst(str_replace('_', ' ', 'create_station')), 'route' => 'station.create'],
            ['name' => ucfirst(str_replace('_', ' ', 'save_station')), 'route' => 'station.store'],
            ['name' => ucfirst(str_replace('_', ' ', 'edit_station')), 'route' => 'station.edit'],
            ['name' => ucfirst(str_replace('_', ' ', 'update_station')), 'route' => 'station.update'],
            ['name' => ucfirst(str_replace('_', ' ', 'delete_station')), 'route' => 'station.destroy'],
            ['name' => ucfirst(str_replace('_', ' ', 'list_of_material_category')), 'route' => 'material_category.index'],
            ['name' => ucfirst(str_replace('_', ' ', 'create_material_category')), 'route' => 'material_category.create'],
            ['name' => ucfirst(str_replace('_', ' ', 'save_material_category')), 'route' => 'material_category.store'],
            ['name' => ucfirst(str_replace('_', ' ', 'edit_material_category')), 'route' => 'material_category.edit'],
            ['name' => ucfirst(str_replace('_', ' ', 'update_material_category')), 'route' => 'material_category.update'],
            ['name' => ucfirst(str_replace('_', ' ', 'delete_material_category')), 'route' => 'material_category.destroy'],
            ['name' => ucfirst(str_replace('_', ' ', 'list_of_measurement_unit')), 'route' => 'measurement_unit.index'],
            ['name' => ucfirst(str_replace('_', ' ', 'create_measurement_unit')), 'route' => 'measurement_unit.create'],
            ['name' => ucfirst(str_replace('_', ' ', 'save_measurement_unit')), 'route' => 'measurement_unit.store'],
            ['name' => ucfirst(str_replace('_', ' ', 'edit_measurement_unit')), 'route' => 'measurement_unit.edit'],
            ['name' => ucfirst(str_replace('_', ' ', 'update_measurement_unit')), 'route' => 'measurement_unit.update'],
            ['name' => ucfirst(str_replace('_', ' ', 'delete_measurement_unit')), 'route' => 'measurement_unit.destroy'],
            ['name' => ucfirst(str_replace('_', ' ', 'list_of_option')), 'route' => 'option.index'],
            ['name' => ucfirst(str_replace('_', ' ', 'create_option')), 'route' => 'option.create'],
            ['name' => ucfirst(str_replace('_', ' ', 'save_option')), 'route' => 'option.store'],
            ['name' => ucfirst(str_replace('_', ' ', 'edit_option')), 'route' => 'option.edit'],
            ['name' => ucfirst(str_replace('_', ' ', 'update_option')), 'route' => 'option.update'],
            ['name' => ucfirst(str_replace('_', ' ', 'delete_option')), 'route' => 'option.destroy'],
            ['name' => ucfirst(str_replace('_', ' ', 'list_of_parameter')), 'route' => 'parameter.index'],
            ['name' => ucfirst(str_replace('_', ' ', 'create_parameter')), 'route' => 'parameter.create'],
            ['name' => ucfirst(str_replace('_', ' ', 'save_parameter')), 'route' => 'parameter.store'],
            ['name' => ucfirst(str_replace('_', ' ', 'edit_parameter')), 'route' => 'parameter.edit'],
            ['name' => ucfirst(str_replace('_', ' ', 'update_parameter')), 'route' => 'parameter.update'],
            ['name' => ucfirst(str_replace('_', ' ', 'delete_parameter')), 'route' => 'parameter.destroy'],
            ['name' => ucfirst(str_replace('_', ' ', 'list_of_material')), 'route' => 'material.index'],
            ['name' => ucfirst(str_replace('_', ' ', 'create_material')), 'route' => 'material.create'],
            ['name' => ucfirst(str_replace('_', ' ', 'save_material')), 'route' => 'material.store'],
            ['name' => ucfirst(str_replace('_', ' ', 'edit_material')), 'route' => 'material.edit'],
            ['name' => ucfirst(str_replace('_', ' ', 'update_material')), 'route' => 'material.update'],
            ['name' => ucfirst(str_replace('_', ' ', 'delete_material')), 'route' => 'material.destroy'],
            ['name' => ucfirst(str_replace('_', ' ', 'list_of_material_parameter')), 'route' => 'material_parameter.index'],
            ['name' => ucfirst(str_replace('_', ' ', 'adjust_material_parameter')), 'route' => 'material_parameter.adjust'],
            ['name' => ucfirst(str_replace('_', ' ', 'update_material_parameter')), 'route' => 'material_parameter.update'],
            ['name' => ucfirst(str_replace('_', ' ', 'list_of_analysis')), 'route' => 'analysis.index'],
            ['name' => ucfirst(str_replace('_', ' ', 'create_analysis')), 'route' => 'analysis.create'],
            ['name' => ucfirst(str_replace('_', ' ', 'save_analysis')), 'route' => 'analysis.store'],
            ['name' => ucfirst(str_replace('_', ' ', 'edit_analysis')), 'route' => 'analysis.edit'],
            ['name' => ucfirst(str_replace('_', ' ', 'update_analysis')), 'route' => 'analysis.update'],
            ['name' => ucfirst(str_replace('_', ' ', 'delete_analysis')), 'route' => 'analysis.destroy'],
            ['name' => ucfirst(str_replace('_', ' ', 'result_by_material')), 'route' => 'result_by_material.index'],
            ['name' => ucfirst(str_replace('_', ' ', 'result_by_station')), 'route' => 'result_by_station.index'],
            ['name' => ucfirst(str_replace('_', ' ', 'result_by_material_category')), 'route' => 'result_by_material_category.index'],
            // ['name' => ucfirst(str_replace('_', ' ', 'results_by_station_get_results')), 'route' => 'results_by_station.getResults'],
        ];
        Feature::insert($features);

        foreach (Feature::select('id')->orderBy('id')->get() as $feature) {
            Permission::insert([
                "feature_id" => $feature->id,
                "role_id" => 1,
            ]);
        }

        $stations = [
            ["name" => "Gilingan"],
            ["name" => "Permurnian"],
        ];
        Station::insert($stations);

        $material_categories = [
            ["name" => "Nira"],
            ["name" => "Masquite"],
            ["name" => "Gula"],
        ];
        MaterialCategory::insert($material_categories);

        $measurement_units = [
            ["name" => "%"],
            ["name" => "-"],
        ];
        MeasurementUnit::insert($measurement_units);

        $options = [
            ["name" => "High"],
            ["name" => "Low"],
        ];
        Option::insert($options);

        $parameters = [
            ["name" => "Brix", "measurement_unit_id" => 1, "min" => 0, "max" => 100, 'type' => 'Numeric'],
            ["name" => "Pol", "measurement_unit_id" => 1, "min" => 0, "max" => 100, 'type' => 'Numeric'],
            ["name" => "HK", "measurement_unit_id" => 1, "min" => 0, "max" => 100, 'type' => 'Numeric'],
            ["name" => "Rendemen", "measurement_unit_id" => 1, "min" => 0, "max" => 100, 'type' => 'Numeric'],
        ];
        Parameter::insert($parameters);

        $materials = [
            ["name" => "Nira Gilingan 1", "station_id" => 1, "material_category_id" => 1],
            ["name" => "Nira Gilingan 2", "station_id" => 1, "material_category_id" => 1],
            ["name" => "Nira Gilingan 3", "station_id" => 1, "material_category_id" => 1],
        ];
        Material::insert($materials);

        $material_parameters = [
            ["material_id" => 1, "parameter_id" => 1],
            ["material_id" => 1, "parameter_id" => 2],
            ["material_id" => 1, "parameter_id" => 3],
            ["material_id" => 1, "parameter_id" => 4],
        ];
        MaterialParameter::insert($material_parameters);

        $list_of_parameters = Parameter::all();
        foreach ($list_of_parameters as $list) {
            $column_name = str_replace(' ', '_', $list->name);
            $alter_query = "ALTER TABLE analyses ADD COLUMN `{$column_name}` FLOAT NULL";
            DB::statement($alter_query);
        }
    }
}
