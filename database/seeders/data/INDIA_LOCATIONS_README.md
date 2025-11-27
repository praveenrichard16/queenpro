# India Location Data Import Guide

This guide explains how to import India location data (states, districts, cities, and pincodes) into the system.

## Data Format

The system accepts two formats:

### 1. JSON Format

Create a file named `india-locations.json` in `database/seeders/data/` with the following structure:

```json
[
    {
        "state": "Maharashtra",
        "city": "Mumbai",
        "district": "Mumbai",
        "pincode": "400001",
        "latitude": 19.0760,
        "longitude": 72.8777
    },
    {
        "state": "Karnataka",
        "city": "Bangalore",
        "district": "Bangalore Urban",
        "pincode": "560001",
        "latitude": 12.9716,
        "longitude": 77.5946
    }
]
```

**Required fields:**
- `state` - State name (e.g., "Maharashtra", "Karnataka")
- `city` - City/Town name

**Optional fields:**
- `district` - District name
- `pincode` - 6-digit Indian postal code
- `latitude` - Latitude coordinate
- `longitude` - Longitude coordinate

### 2. CSV Format

Create a file named `india-locations.csv` in `database/seeders/data/` with the following columns:

```csv
state,city,district,pincode,latitude,longitude
Maharashtra,Mumbai,Mumbai,400001,19.0760,72.8777
Karnataka,Bangalore,Bangalore Urban,560001,12.9716,77.5946
```

**Required columns:**
- `state` - State name
- `city` - City/Town name

**Optional columns:**
- `district` - District name
- `pincode` - 6-digit Indian postal code
- `latitude` - Latitude coordinate
- `longitude` - Longitude coordinate

## Import Methods

### Method 1: Using Artisan Command

1. Place your data file in `database/seeders/data/` directory
2. Run the import command:

```bash
php artisan india:import-locations
```

To import a specific file:

```bash
php artisan india:import-locations --file=/path/to/your/india-locations.json
```

To clear existing data before import:

```bash
php artisan india:import-locations --clear
```

### Method 2: Using Database Seeder

1. Place your data file in `database/seeders/data/` directory
2. Run the seeder:

```bash
php artisan db:seed --class=IndiaLocationSeeder
```

### Method 3: Using Admin Panel

1. Go to Admin Panel → Settings → Location Manager
2. Click "Import" button
3. Upload your JSON or CSV file
4. Select import mode:
   - **Create**: Add new locations only
   - **Update**: Update existing locations by pincode
   - **Replace**: Clear all existing data and import new data
5. Click "Import"

## Data Sources

### Comprehensive Dataset Included

A comprehensive dataset with **100+ locations** covering all 28 states and 8 union territories is included:
- `india-locations.json` - Complete dataset in JSON format
- `india-locations.csv` - Complete dataset in CSV format

This dataset includes:
- All 36 states/union territories
- Major cities from each state
- Districts for each city
- Valid 6-digit pincodes
- Latitude/longitude coordinates

### Free India Location Data Sources (For Complete Dataset)

If you need a complete dataset with all ~150,000+ pincodes, you can obtain it from:

1. **India Post Pincode Directory**
   - Official source: https://www.indiapost.gov.in/
   - Contains all pincodes with locations
   - Download from: https://www.indiapost.gov.in/vas/Pages/FindPincode.aspx

2. **GitHub Repositories**
   - Search for "india pincode" or "india postal codes" on GitHub
   - Popular repositories:
     - `codelinten/india-pincode-data`
     - `subodhk01/India-Pincode-Database`
     - `sab99r/Indian-States-And-Districts`

3. **Open Data Portals**
   - Data.gov.in - Government open data portal: https://data.gov.in/
   - Contains various location datasets including pincodes

4. **Third-party APIs**
   - Postalpincode.in API
   - Pincode API services
   - India Pincode API

5. **NSK Multi Services**
   - District-wise pincode download: https://pincodes.nskmultiservices.in/tools/district-wise-pincode-download
   - Available in Excel and PDF formats

### Sample Data

A small sample file with 10 major cities is included: `india-locations-sample.json`

You can use this as a template or starting point for creating your own dataset.

## India Location Statistics

- **States**: 28 states
- **Union Territories**: 8 union territories
- **Total States/UTs**: 36
- **Districts**: ~700+ districts
- **Pincodes**: ~150,000+ pincodes
- **Cities/Towns**: Thousands of cities and towns

## Included Dataset Coverage

The included `india-locations.json` and `india-locations.csv` files contain:
- **100+ locations** covering all 36 states/union territories
- At least 1-2 major cities from each state/UT
- All major metropolitan areas
- Tier-1, Tier-2, and Tier-3 cities
- Valid pincodes and coordinates for each location

**Note**: For a complete dataset with all pincodes, you'll need to download from official sources mentioned above and import via the admin panel or artisan command.

## Notes

- The system uses the same database table (`saudi_arabia_locations`) for all location data
- State names are stored in `region_name_en` field
- City names are stored in `city_name_en` field
- District names are stored in `district_name_en` field
- Pincodes are stored in `postal_code` field
- Pincodes are automatically cleaned (spaces removed, limited to 6 digits)

## Troubleshooting

### File Not Found Error

Make sure your data file is in `database/seeders/data/` directory and named correctly:
- `india-locations.json` or
- `india-locations.csv`

### Invalid Format Error

- For JSON: Ensure valid JSON syntax (use a JSON validator)
- For CSV: Ensure proper comma separation and headers match expected format

### Import Takes Too Long

- Large datasets (100,000+ records) may take several minutes
- Data is imported in chunks of 500 records for performance
- Progress bars will show import status

### Duplicate Data

- Use `--clear` option to remove existing data before import
- Or use "Replace" mode in admin panel import

## Support

For issues or questions, check:
- Laravel logs: `storage/logs/laravel.log`
- Database connection settings
- File permissions on `database/seeders/data/` directory

