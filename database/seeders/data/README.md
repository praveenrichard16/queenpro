# Saudi Arabia Location Data

This directory contains the location data files for Saudi Arabia (regions, cities, districts).

## Automatic Download

Run this command to automatically download the data:

```bash
php artisan saudi-arabia:download-locations
```

## Manual Download

If automatic download fails, download manually from:
https://github.com/homaily/Saudi-Arabia-Regions-Cities-and-Districts

Place the JSON files in this directory:
- `regions.json`
- `cities.json`
- `districts.json`

## Import to Database

After downloading, run:

```bash
php artisan db:seed --class=SaudiArabiaLocationSeeder
```

Or use the combined command:

```bash
php artisan saudi-arabia:setup
```

