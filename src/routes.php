<?php

use Axiostudio\Comuni\Models\City;
use Axiostudio\Comuni\Models\Province;
use Axiostudio\Comuni\Models\Region;
use Axiostudio\Comuni\Models\Zip;
use Axiostudio\Comuni\Models\Zone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

Route::group(['middleware' => ['api'], 'prefix' => config('comuni.route')], function () {
    Route::middleware(config('comuni.middlewares'))->get('/zones', function () {
        return Cache::remember('zones', config('comuni.ttl'), function () {
            return Zone::orderBy('name', 'asc')->get()->toArray();
        });
    });

    Route::middleware(config('comuni.middlewares'))->get('/zones/{id}', function ($id) {
        return Cache::remember('zones-'.$id, config('comuni.ttl'), function () use ($id) {
            return Zone::where('id', $id)->with(['regions', 'regions.provinces', 'regions.provinces.cities', 'regions.provinces.cities.zips'])->firstOrFail()->toArray();
        });
    })->whereNumber('id');

    // --- REGIONS ---
    Route::middleware(config('comuni.middlewares'))->get('/regions', function (Request $request) {
        $query = Region::query();

        if ($request->filled('q')) {
            $query->where('name', 'like', '%'.$request->q.'%');
        }

        if ($request->filled('province') || $request->filled('prov')) {
            $prov = $request->input('province') ?? $request->input('prov');
            $query->whereHas('provinces', function ($q) use ($prov) {
                if (is_numeric($prov)) {
                    $q->where('id', $prov);
                } else {
                    $q->where('code', strtoupper($prov))->orWhere('name', 'like', '%'.$prov.'%');
                }
            });
        }

        return $query->orderBy('name', 'asc')->get()->toArray();
    });

    Route::middleware(config('comuni.middlewares'))->get('/regions/{id}', function ($id) {
        return Cache::remember('regions-'.$id, config('comuni.ttl'), function () use ($id) {
            return Region::where('id', $id)->with(['zone', 'provinces', 'provinces.cities', 'provinces.cities.zips'])->firstOrFail()->toArray();
        });
    })->whereNumber('id');

    // --- PROVINCES ---
    Route::middleware(config('comuni.middlewares'))->get('/provinces', function (Request $request) {
        $query = Province::query();

        if ($request->filled('q')) {
            $qVal = $request->q;
            $query->where(function ($q) use ($qVal) {
                $q->where('name', 'like', '%'.$qVal.'%')
                  ->orWhere('code', strtoupper($qVal));
            });
        }

        if ($request->filled('region_id')) {
            $query->where('region_id', $request->region_id);
        } elseif ($request->filled('region')) {
            $query->whereHas('region', function ($q) use ($request) {
                $q->where('name', 'like', '%'.$request->region.'%');
            });
        }

        return $query->orderBy('name', 'asc')->get()->toArray();
    });

    Route::middleware(config('comuni.middlewares'))->get('/provinces/{code}', function ($code) {
        return Cache::remember('provinces-'.$code, config('comuni.ttl'), function () use ($code) {
            return Province::where('code', $code)->with(['region', 'cities', 'cities.zips', 'region.zone'])->firstOrFail()->toArray();
        });
    })->whereAlpha('code');

    Route::middleware(config('comuni.middlewares'))->get('/provinces/{id}', function ($id) {
        return Cache::remember('provinces-'.$id, config('comuni.ttl'), function () use ($id) {
            return Province::where('id', $id)->with(['region', 'cities', 'cities.zips', 'region.zone'])->firstOrFail()->toArray();
        });
    })->whereNumber('id');

    // --- CITIES ---
    Route::middleware(config('comuni.middlewares'))->get('/cities', function (Request $request) {
        $query = City::query();

        if ($request->filled('q')) {
            $query->where('name', 'like', '%'.$request->q.'%');
        }

        if ($request->filled('prov') || $request->filled('province_code') || $request->filled('province')) {
            $prov = $request->input('prov') ?? $request->input('province_code') ?? $request->input('province');
            $query->whereHas('province', function ($q) use ($prov) {
                if (is_numeric($prov)) {
                    $q->where('id', $prov);
                } else {
                    $q->where('code', strtoupper($prov))->orWhere('name', 'like', '%'.$prov.'%');
                }
            });
        }

        if ($request->filled('region_id')) {
            $query->whereHas('province', function ($q) use ($request) {
                $q->where('region_id', $request->region_id);
            });
        } elseif ($request->filled('region')) {
            $query->whereHas('province.region', function ($q) use ($request) {
                $q->where('name', 'like', '%'.$request->region.'%');
            });
        }

        return $query->orderBy('name', 'asc')->get()->toArray();
    });

    Route::middleware(config('comuni.middlewares'))->get('/cities/{id}', function ($id) {
        return Cache::remember('cities-'.$id, config('comuni.ttl'), function () use ($id) {
            return City::where('id', $id)->with(['province', 'zips', 'province.region', 'province.region.zone'])->firstOrFail();
        });
    })->whereNumber('id');

    // --- ZIPS / CAP ---
    Route::middleware(config('comuni.middlewares'))->get('/zips', function (Request $request) {
        $query = Zip::query()->with('city', 'city.province', 'city.province.region');

        if ($request->filled('q')) {
            $query->where('code', 'like', $request->q.'%');
        }

        if ($request->filled('city_id')) {
            $query->where('city_id', $request->city_id);
        } elseif ($request->filled('city')) {
            $query->whereHas('city', function ($q) use ($request) {
                $q->where('name', 'like', '%'.$request->city.'%');
            });
        }

        if ($request->filled('prov') || $request->filled('province_code') || $request->filled('province')) {
            $prov = $request->input('prov') ?? $request->input('province_code') ?? $request->input('province');
            $query->whereHas('city.province', function ($q) use ($prov) {
                if (is_numeric($prov)) {
                    $q->where('id', $prov);
                } else {
                    $q->where('code', strtoupper($prov))->orWhere('name', 'like', '%'.$prov.'%');
                }
            });
        }

        if ($request->filled('region_id')) {
            $query->whereHas('city.province', function ($q) use ($request) {
                $q->where('region_id', $request->region_id);
            });
        } elseif ($request->filled('region')) {
            $query->whereHas('city.province.region', function ($q) use ($request) {
                $q->where('name', 'like', '%'.$request->region.'%');
            });
        }

        return $query->orderBy('code', 'asc')->get()->toArray();
    });

    Route::middleware(config('comuni.middlewares'))->get('/zips/{id}', function ($id) {
        return Cache::remember('zips-'.$id, config('comuni.ttl'), function () use ($id) {
            return Zip::where('id', $id)->with(['city', 'city.province', 'city.province.region', 'city.province.region.zone'])->firstOrFail()->toArray();
        });
    })->whereNumber('id');
});
