<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Detail informasi destinasi {{ $marker->location_name ?? $marker->title }} - Eco Ranger Tourism">
    <title>{{ $marker->location_name ?? $marker->title ?? 'Detail Lokasi' }} — Eco Ranger Tourism</title>
    
    <!-- Preconnect for Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Space+Grotesk:wght@700&display=swap" rel="stylesheet">
    
    <!-- Vite React bundle -->
    @vite(['resources/js/eco-ranger/main.tsx'])

    <!-- Inject Laravel Data for React -->
    <script>
        window.markerDetailData = {
            marker: @json($marker),
            weather: @json($weather),
            weatherDescription: @json($weatherDescription),
            lat: @json($lat),
            lng: @json($lng),
            reviews: @json($reviews),
            totalReviews: @json($totalReviews),
            averageRating: @json($averageRating),
            starDistribution: @json($starDistribution),
            userHasReviewed: @json($userHasReviewed),
            user: @json(auth()->user()),
            csrfToken: '{{ csrf_token() }}',
            loginUrl: '{{ route("login") }}',
            backUrl: '{{ request("from") === "academy" ? route("academy.index") : url("/") }}',
            reviewStoreUrl: '{{ route("markers.reviews.store", $marker->id) }}',
            sessionSuccess: @json(session('review_success')),
            sessionError: @json(session('review_error'))
        };
    </script>
</head>
<body style="margin:0;padding:0;background:#F8FAF9;">
    <!-- Marker Detail React Container -->
    <div id="marker-detail-root"></div>
</body>
</html>
