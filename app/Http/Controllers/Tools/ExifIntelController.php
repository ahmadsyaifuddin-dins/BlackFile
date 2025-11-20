<?php

namespace App\Http\Controllers\Tools;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ExifIntelController extends Controller
{
    public function index()
    {
        return view('tools.exif.index');
    }

    public function analyze(Request $request)
    {
        $request->validate([
            'image' => 'required|file|mimes:jpg,jpeg,tiff|max:10240', // Max 10MB, JPG only (PNG jarang punya EXIF lengkap)
        ]);

        $file = $request->file('image');
        
        // 1. Simpan file sementara agar bisa dibaca function PHP
        // Kita pakai nama acak agar tidak menimpa file lain
        $path = $file->storeAs('temp_exif', Str::random(10) . '.' . $file->getClientOriginalExtension());
        $fullPath = storage_path('app/' . $path);

        try {
            // 2. Cek apakah server support EXIF
            if (!function_exists('exif_read_data')) {
                return response()->json(['status' => 'ERROR', 'message' => 'Server PHP extension (exif) not enabled.'], 500);
            }

            // 3. Baca Data EXIF
            // '@' digunakan untuk menekan warning jika format gambar korup
            $exif = @exif_read_data($fullPath, 0, true);

            // Hapus file sementara segera setelah dibaca (Privacy First & Hemat Storage)
            Storage::delete($path);

            if (!$exif) {
                return response()->json(['status' => 'ERROR', 'message' => 'No EXIF data found. Image might be scrubbed (e.g., from WhatsApp/Facebook).'], 404);
            }

            // 4. Parsing Data Penting
            $data = [
                'status' => 'SUCCESS',
                'device' => [
                    'make' => $exif['IFD0']['Make'] ?? 'Unknown',
                    'model' => $exif['IFD0']['Model'] ?? 'Unknown Device',
                    'software' => $exif['IFD0']['Software'] ?? null,
                ],
                'file' => [
                    'name' => $file->getClientOriginalName(),
                    'size' => round($file->getSize() / 1024, 2) . ' KB',
                    'type' => $exif['FILE']['MimeType'] ?? 'image/jpeg',
                    'datetime' => $exif['IFD0']['DateTime'] ?? ($exif['EXIF']['DateTimeOriginal'] ?? 'Unknown Date'),
                    'resolution' => ($exif['COMPUTED']['Width'] ?? '?') . ' x ' . ($exif['COMPUTED']['Height'] ?? '?'),
                ],
                'gps' => null
            ];

            // 5. Kalkulasi GPS (Bagian Tersulit)
            if (isset($exif['GPS']['GPSLatitude']) && isset($exif['GPS']['GPSLongitude'])) {
                $lat = $this->gpsToDecimal($exif['GPS']['GPSLatitude'], $exif['GPS']['GPSLatitudeRef'] ?? 'N');
                $lon = $this->gpsToDecimal($exif['GPS']['GPSLongitude'], $exif['GPS']['GPSLongitudeRef'] ?? 'E');

                $data['gps'] = [
                    'latitude' => $lat,
                    'longitude' => $lon,
                    'map_url' => "https://www.google.com/maps?q={$lat},{$lon}",
                    'altitude' => isset($exif['GPS']['GPSAltitude']) ? $this->rationalToFloat($exif['GPS']['GPSAltitude']) . ' m' : 'N/A'
                ];
            }

            return response()->json($data);

        } catch (\Exception $e) {
            // Pastikan file terhapus meski error
            if(Storage::exists($path)) Storage::delete($path);
            
            return response()->json(['status' => 'ERROR', 'message' => 'Analysis failed: ' . $e->getMessage()], 500);
        }
    }

    // --- HELPER FUNCTIONS UNTUK MATEMATIKA GPS ---

    private function gpsToDecimal($coordinate, $hemisphere)
    {
        // Format EXIF GPS biasanya Array: [Degrees, Minutes, Seconds]
        // Setiap nilai berbentuk pecahan string "num/den"
        
        $degrees = count($coordinate) > 0 ? $this->rationalToFloat($coordinate[0]) : 0;
        $minutes = count($coordinate) > 1 ? $this->rationalToFloat($coordinate[1]) : 0;
        $seconds = count($coordinate) > 2 ? $this->rationalToFloat($coordinate[2]) : 0;

        $decimal = $degrees + ($minutes / 60) + ($seconds / 3600);

        // Jika South (S) atau West (W), nilainya negatif
        if ($hemisphere == 'S' || $hemisphere == 'W') {
            $decimal *= -1;
        }

        return $decimal;
    }

    private function rationalToFloat($rational)
    {
        // Mengubah "500/10" menjadi 50
        $parts = explode('/', $rational);
        if (count($parts) <= 0) return 0;
        if (count($parts) == 1) return (float)$parts[0];
        
        // Hindari division by zero
        if ($parts[1] == 0) return 0;

        return (float)$parts[0] / (float)$parts[1];
    }
}