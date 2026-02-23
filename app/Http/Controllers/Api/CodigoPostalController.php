<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class CodigoPostalController extends Controller
{
    /**
     * Consulta estado, ciudad, municipio y colonias por código postal (México).
     * Responde JSON para autocompletar el formulario de inmuebles.
     */
    public function show(Request $request, string $cp): JsonResponse
    {
        $cp = preg_replace('/\D/', '', $cp);
        if (strlen($cp) !== 5) {
            return response()->json([
                'ok' => false,
                'message' => 'El código postal debe tener 5 dígitos.',
            ], 422);
        }

        $cacheKey = 'cp_mx_' . $cp;
        $data = Cache::remember($cacheKey, now()->addDays(7), function () use ($cp) {
            return $this->fetchFromApi($cp);
        });

        if ($data === null) {
            return response()->json([
                'ok' => false,
                'message' => 'No se encontró información para este código postal.',
            ], 404);
        }

        return response()->json([
            'ok' => true,
            'state' => $data['state'] ?? '',
            'city' => $data['city'] ?? '',
            'municipality' => $data['municipality'] ?? '',
            'localities' => $data['localities'] ?? [],
        ]);
    }

    private function fetchFromApi(string $cp): ?array
    {
        $baseUrl = rtrim(config('services.codigo_postal_api_url', ''), '/');

        try {
            $response = Http::timeout(8)->get($baseUrl . '/' . $cp);
            if (!$response->successful()) {
                return $this->tryAlternativeApi($cp);
            }
            $body = $response->json();
            return $this->normalizeResponse($body, $cp);
        } catch (\Throwable $e) {
            return $this->tryAlternativeApi($cp);
        }
    }

    /**
     * APIs alternativas sin clave (SEPOMEX, Zippopotam, etc.).
     */
    private function tryAlternativeApi(string $cp): ?array
    {
        $urls = [
            'https://api-sepomex.hckdrk.mx/query/info_cp/' . $cp,
            'https://zip-api.herokuapp.com/api/v1/zip_codes?zip_code=' . $cp,
        ];

        foreach ($urls as $url) {
            try {
                $response = Http::timeout(5)->get($url);
                if ($response->successful()) {
                    $body = $response->json();
                    $normalized = $this->normalizeResponse($body, $cp);
                    if ($normalized !== null) {
                        return $normalized;
                    }
                }
            } catch (\Throwable $e) {
                continue;
            }
        }

        return $this->fetchZippopotam($cp);
    }

    /**
     * Zippopotam (api.zippopotam.us) — sin clave, formato distinto.
     */
    private function fetchZippopotam(string $cp): ?array
    {
        try {
            $response = Http::timeout(5)->get('https://api.zippopotam.us/mx/' . $cp);
            if (!$response->successful()) {
                return null;
            }
            $body = $response->json();
            $places = $body['places'] ?? [];
            if (empty($places)) {
                return null;
            }
            $first = $places[0];
            $state = $first['state'] ?? '';
            $localities = array_values(array_unique(array_map(fn ($p) => $p['place name'] ?? '', $places)));
            $localities = array_filter($localities);
            $city = $localities[0] ?? '';
            return [
                'state' => $state,
                'city' => $city,
                'municipality' => $city,
                'localities' => array_values($localities),
            ];
        } catch (\Throwable $e) {
            return null;
        }
    }

    /**
     * Normaliza distintas respuestas de APIs (array, objeto, clave "response", etc.).
     */
    private function normalizeResponse(array $body, string $cp): ?array
    {
        $data = $body['response'] ?? $body;
        if (isset($body['response']) && is_array($body['response'])) {
            $data = $body['response'];
            if (isset($data[0]) && is_array($data[0])) {
                $first = $data[0];
                $state = $first['estado']['nombre'] ?? $first['state'] ?? $first['estado'] ?? '';
                $city = $first['ciudad'] ?? $first['city'] ?? $first['municipio']['nombre'] ?? '';
                $municipality = $first['municipio']['nombre'] ?? $first['municipio'] ?? $first['municipality'] ?? $city;
                $localities = [];
                foreach ($data as $row) {
                    $col = $row['colonia'] ?? $row['asentamiento'] ?? $row['locality'] ?? null;
                    if ($col && !in_array($col, $localities, true)) {
                        $localities[] = is_string($col) ? $col : ($col['nombre'] ?? (string) $col);
                    }
                }
                return [
                    'state' => $state,
                    'city' => $city,
                    'municipality' => $municipality,
                    'localities' => array_values(array_unique($localities)),
                ];
            }
        }

        if (isset($data['estado'])) {
            $state = is_array($data['estado']) ? ($data['estado']['nombre'] ?? $data['estado']['name'] ?? '') : (string) $data['estado'];
            $city = is_array($data['ciudad']) ? ($data['ciudad']['nombre'] ?? '') : (string) ($data['ciudad'] ?? $data['city'] ?? '');
            $municipality = is_array($data['municipio']) ? ($data['municipio']['nombre'] ?? '') : (string) ($data['municipio'] ?? $data['municipality'] ?? $city);
            $localities = $data['colonias'] ?? $data['asentamientos'] ?? $data['localities'] ?? [];
            if (is_array($localities) && isset($localities[0]) && is_array($localities[0])) {
                $localities = array_map(fn ($c) => $c['nombre'] ?? $c['name'] ?? $c['colonia'] ?? (string) $c, $localities);
            }
            return [
                'state' => $state,
                'city' => $city,
                'municipality' => $municipality,
                'localities' => array_values(array_unique($localities)),
            ];
        }

        $state = $data['state'] ?? $data['estado'] ?? '';
        $city = $data['city'] ?? $data['ciudad'] ?? '';
        $municipality = $data['municipality'] ?? $data['municipio'] ?? $city;
        $localities = $data['localities'] ?? $data['colonias'] ?? [];
        if (!empty($state) || !empty($city) || !empty($municipality)) {
            return [
                'state' => (string) $state,
                'city' => (string) $city,
                'municipality' => (string) $municipality,
                'localities' => is_array($localities) ? array_values(array_unique($localities)) : [],
            ];
        }

        return null;
    }
}
