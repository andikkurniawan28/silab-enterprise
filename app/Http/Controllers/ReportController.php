<?php

namespace App\Http\Controllers;

use App\Models\Setup;
use App\Models\Analysis;
use App\Models\Material;
use App\Models\ReportType;
use Illuminate\Http\Request;
use App\Models\MaterialParameter;
use App\Models\ReportTypeContent;
use App\Http\Controllers\AuthController;

class ReportController extends Controller
{
    /**
     * Menampilkan laporan berdasarkan jenis laporan yang dipilih.
     *
     * @param int $report_type_id ID jenis laporan.
     * @return \Illuminate\View\View
     */
    public function index($report_type_id)
    {
        // Mendapatkan nilai tanggal dan waktu mulai dari sesi, atau nilai default jika tidak ada di sesi.
        $from_datetime = session('from_datetime', now()->startOfDay()->format('Y-m-d 06:00:00'));
        // Mendapatkan nilai tanggal dan waktu akhir dari sesi, atau nilai default jika tidak ada di sesi.
        $to_datetime = session('to_datetime', now()->endOfDay()->addDay()->format('Y-m-d 06:00:00'));

        // Menginisialisasi setup
        $setup = Setup::init();

        // Menemukan jenis laporan berdasarkan ID, atau gagal jika tidak ditemukan.
        $report_type = ReportType::findOrFail($report_type_id);

        // Mendapatkan ID material yang terkait dengan jenis laporan ini, tanpa duplikasi.
        $material_ids = ReportTypeContent::where('report_type_id', $report_type_id)->distinct()->pluck('material_id');

        // Mengambil parameter material beserta opsinya yang terkait dengan material yang dipilih, tanpa duplikasi parameter.
        $parameters = MaterialParameter::with('parameter.parameter_option.option')
            ->whereIn('material_id', $material_ids)
            ->get()
            ->unique('parameter_id');

        // Mengambil material yang terkait dengan ID material yang dipilih, tanpa duplikasi.
        $materials = Material::whereIn('id', $material_ids)->select(['id', 'name'])->distinct()->get();

        // Melakukan perhitungan agregat untuk setiap parameter pada setiap material.
        foreach($materials as $material) {
            foreach($parameters as $pd) {
                // Mengganti spasi dengan underscore dan mengubah huruf pertama setiap kata menjadi huruf besar.
                $parameter_name = ucwords(str_replace(' ', '_', $pd->parameter->name));

                // Menentukan metode pelaporan dan melakukan perhitungan yang sesuai.
                switch($pd->parameter->reporting_method) {
                    case "Average":
                        // Menghitung rata-rata nilai parameter.
                        $aggregated_value = Analysis::where('material_id', $material->id)
                            ->whereBetween('created_at', [$from_datetime, $to_datetime])
                            ->avg($parameter_name);
                        break;

                    case "Sum":
                        // Menghitung jumlah total nilai parameter.
                        $aggregated_value = Analysis::where('material_id', $material->id)
                            ->whereBetween('created_at', [$from_datetime, $to_datetime])
                            ->sum($parameter_name);
                        break;

                    case "Count":
                        // Menghitung jumlah kemunculan nilai parameter.
                        $aggregated_value = Analysis::where('material_id', $material->id)
                            ->whereBetween('created_at', [$from_datetime, $to_datetime])
                            ->count($parameter_name);
                        break;

                    case "Qualitative":
                        // Menghitung jumlah kemunculan setiap opsi parameter.
                        $aggregated_value = [];
                        $options = $pd->parameter->parameter_option->pluck('option')->flatten();

                        // Menghitung total jumlah entri untuk parameter tersebut.
                        $total_count = Analysis::where('material_id', $material->id)
                            ->whereBetween('created_at', [$from_datetime, $to_datetime])
                            ->count();

                        foreach ($options as $option) {
                            $count = Analysis::where('material_id', $material->id)
                                ->whereBetween('created_at', [$from_datetime, $to_datetime])
                                ->where($parameter_name, $option->name)
                                ->count();
                            // Menghitung persentase kemunculan setiap opsi.
                            $percentage = $total_count > 0 ? ($count / $total_count) * 100 : 0;
                            $aggregated_value[$option->name] = $percentage;
                            }
                        break;

                    default:
                        // Jika metode pelaporan tidak dikenal, tidak ada nilai agregat yang dihitung.
                        $aggregated_value = null;
                        break;
                }
                // Menyimpan nilai agregat pada material.
                $material->$parameter_name = $aggregated_value;
            }
        }

        // Mengembalikan tampilan laporan dengan data yang telah diproses.
        return view('report.index', compact('setup', 'report_type', 'materials', 'parameters'));
    }

    /**
     * Mengubah filter tanggal dan waktu, kemudian mengarahkan kembali ke halaman laporan.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function filter(Request $request)
    {
        // Mengubah nilai sesi untuk tanggal dan waktu berdasarkan input dari pengguna.
        AuthController::changeDatetime($request);

        // Mengarahkan kembali ke halaman laporan dengan jenis laporan yang dipilih.
        return redirect()->route("report.index", $request->report_type);
    }
}
