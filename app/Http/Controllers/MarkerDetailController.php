<?php

namespace App\Http\Controllers;

use App\Models\Marker;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
class MarkerDetailController extends Controller
{
    public function show(Marker $marker)
    {
        // Get coordinates for weather API
        $coordinates = $marker->coordinates;
        if (is_string($coordinates)) {
            $coordinates = json_decode($coordinates, true) ?: [];
        }

        $lat = null;
        $lng = null;

        // Determine lat/lng based on shape type
        if ($marker->shape_type === 'Marker' || $marker->shape_type === 'Circle') {
            $lat = $coordinates[0] ?? $marker->latitude;
            $lng = $coordinates[1] ?? $marker->longitude;
        } elseif (is_array($coordinates) && count($coordinates) > 0) {
            // For polygon/rectangle/line, use the first coordinate or centroid
            if (is_array($coordinates[0])) {
                $latSum = 0;
                $lngSum = 0;
                $count = count($coordinates);
                foreach ($coordinates as $coord) {
                    $latSum += $coord[0];
                    $lngSum += $coord[1];
                }
                $lat = $latSum / $count;
                $lng = $lngSum / $count;
            } else {
                $lat = $coordinates[0];
                $lng = $coordinates[1];
            }
        }

        // Fetch weather data from Open-Meteo API
        $weather = null;
        if ($lat && $lng) {
            try {
                $weather = Cache::remember("weather_{$lat}_{$lng}", 3600, function () use ($lat, $lng) {
                    $response = Http::timeout(5)->get('https://api.open-meteo.com/v1/forecast', [
                        'latitude' => $lat,
                        'longitude' => $lng,
                        'current_weather' => true,
                    ]);

                    if ($response->successful()) {
                        $data = $response->json();
                        return $data['current_weather'] ?? null;
                    }
                    return null;
                });
            } catch (\Exception $e) {
                // Silently fail — weather is optional
                $weather = null;
            }
        }

        // Map weather code to description
        $weatherDescription = $this->getWeatherDescription($weather['weathercode'] ?? null);

        // We'll load the reviews asynchronously on the client-side via apiReviews()
        // Here we just calculate the aggregates
        
        $totalReviews = Review::where('marker_id', $marker->id)->count();
        $averageRating = Review::where('marker_id', $marker->id)->avg('rating');
        $averageRating = $totalReviews > 0 ? round($averageRating, 1) : 0;

        // Star distribution (1-5)
        $starDistribution = [];
        for ($i = 5; $i >= 1; $i--) {
            $starDistribution[$i] = Review::where('marker_id', $marker->id)->where('rating', $i)->count();
        }

        // Check if current logged-in user already reviewed
        $userHasReviewed = false;
        if (auth()->check()) {
            $userHasReviewed = Review::where('marker_id', $marker->id)
                                     ->where('user_id', auth()->id())
                                     ->exists();
        }

        return view('markers.detail', compact(
            'marker', 'weather', 'weatherDescription', 'lat', 'lng',
            'totalReviews', 'averageRating', 'starDistribution', 'userHasReviewed'
        ));
    }

    public function apiReviews(Request $request, Marker $marker)
    {
        $reviews = Review::with('user')
            ->where('marker_id', $marker->id)
            ->orderByDesc('created_at')
            ->paginate(5); // You can adjust pagination count here

        return response()->json($reviews);
    }

    /**
     * Map WMO weather code to Indonesian weather description
     */
    private function getWeatherDescription(?int $code): string
    {
        if ($code === null) return 'Tidak diketahui';

        return match (true) {
            $code === 0 => 'Cerah',
            $code === 1 => 'Cerah Berawans',
            $code === 2 => 'Berawan',
            $code === 3 => 'Mendung',
            in_array($code, [45, 48]) => 'Berkabut',
            in_array($code, [51, 53, 55]) => 'Gerimis',
            in_array($code, [61, 63, 65]) => 'Hujan',
            in_array($code, [66, 67]) => 'Hujan Beku',
            in_array($code, [71, 73, 75, 77]) => 'Salju',
            in_array($code, [80, 81, 82]) => 'Hujan Lebat',
            in_array($code, [85, 86]) => 'Salju Lebat',
            in_array($code, [95, 96, 99]) => 'Badai Petir',
            default => 'Berawan',
        };
    }
}
