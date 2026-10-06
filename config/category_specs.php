<?php

/**
 * Per-category extra fields. Each category can define an array of fields.
 * The `specs` form will render these dynamically.
 */
return [
    // ---------------- SCENTS ----------------
    'Oil Bared Perfume' => [
        ['name' => 'volume_ml', 'label' => 'Volume', 'type' => 'number', 'unit' => 'ml', 'required' => true],
        ['name' => 'fragrance_family', 'label' => 'Fragrance Family', 'type' => 'select',
         'options' => ['Floral','Woody','Oriental','Fresh','Citrus','Musk','Other']],
    ],
    'Essential Oils' => [
        ['name' => 'volume_ml', 'label' => 'Volume', 'type' => 'number', 'unit' => 'ml', 'required' => true],
        ['name' => 'purity', 'label' => 'Purity', 'type' => 'select',
         'options' => ['100% Pure','Blended']],
    ],
    'Humidifier' => [
        ['name' => 'capacity_l', 'label' => 'Tank Capacity', 'type' => 'number', 'unit' => 'L', 'required' => true],
        ['name' => 'power_w', 'label' => 'Power', 'type' => 'number', 'unit' => 'W'],
    ],
    'Body Mist' => [
        ['name' => 'volume_ml', 'label' => 'Volume', 'type' => 'number', 'unit' => 'ml', 'required' => true],
        ['name' => 'scent', 'label' => 'Scent', 'type' => 'text'],
    ],
    'Body Splash / Spray' => [
        ['name' => 'volume_ml', 'label' => 'Volume', 'type' => 'number', 'unit' => 'ml', 'required' => true],
    ],
    'Air Fresheners' => [
        ['name' => 'volume_ml', 'label' => 'Volume', 'type' => 'number', 'unit' => 'ml'],
        ['name' => 'form', 'label' => 'Form', 'type' => 'select',
         'options' => ['Spray','Gel','Solid','Plug-in','Sachet']],
    ],
    'Air Pocket' => [
        ['name' => 'scent', 'label' => 'Scent', 'type' => 'text'],
    ],
    'Car Pocket' => [
        ['name' => 'scent', 'label' => 'Scent', 'type' => 'text'],
    ],
    'Bowry' => [],
    'Stasoft' => [],

    // ---------------- DECOR ----------------
    'White Duvet' => [
        ['name' => 'size', 'label' => 'Size', 'type' => 'select', 'required' => true,
         'options' => ['Single','Double','Queen','King','Super King']],
        ['name' => 'material', 'label' => 'Material', 'type' => 'text'],
    ],
    'Coloured Duvet' => [
        ['name' => 'size', 'label' => 'Size', 'type' => 'select', 'required' => true,
         'options' => ['Single','Double','Queen','King','Super King']],
        ['name' => 'colour', 'label' => 'Colour', 'type' => 'text'],
    ],
    'White Bedsheets' => [
        ['name' => 'size', 'label' => 'Size', 'type' => 'select', 'required' => true,
         'options' => ['Single','Double','Queen','King']],
    ],
    'Polycotton Bedsheets' => [
        ['name' => 'size', 'label' => 'Size', 'type' => 'select', 'required' => true,
         'options' => ['Single','Double','Queen','King']],
    ],
    'Cotton Bedsheets' => [
        ['name' => 'size', 'label' => 'Size', 'type' => 'select', 'required' => true,
         'options' => ['Single','Double','Queen','King']],
    ],
    'Throw Pillow' => [
        ['name' => 'dimensions', 'label' => 'Dimensions', 'type' => 'text', 'placeholder' => 'e.g. 45×45 cm'],
    ],
    '45×45 Pillow Cases' => [
        ['name' => 'pack_size', 'label' => 'Pack Size', 'type' => 'number', 'unit' => 'pcs'],
    ],
    'Sleeping Pillow — VIP' => [
        ['name' => 'firmness', 'label' => 'Firmness', 'type' => 'select',
         'options' => ['Soft','Medium','Firm']],
    ],
    'Sleeping Pillow — Regular' => [
        ['name' => 'firmness', 'label' => 'Firmness', 'type' => 'select',
         'options' => ['Soft','Medium','Firm']],
    ],
    'Sleeping Pillow Cases' => [
        ['name' => 'dimensions', 'label' => 'Dimensions', 'type' => 'text'],
    ],
    'Door Sialers' => [
        ['name' => 'dimensions', 'label' => 'Dimensions', 'type' => 'text'],
    ],
    'Throw Blankets' => [
        ['name' => 'size', 'label' => 'Size', 'type' => 'text'],
    ],
    'Table Runners' => [
        ['name' => 'length_cm', 'label' => 'Length', 'type' => 'number', 'unit' => 'cm'],
    ],
    'fireplace' => [
        ['name' => 'power_w', 'label' => 'Power', 'type' => 'number', 'unit' => 'W'],
    ],
    'Bedside Lamps' => [
        ['name' => 'power_w', 'label' => 'Power', 'type' => 'number', 'unit' => 'W'],
        ['name' => 'colour', 'label' => 'Colour', 'type' => 'text'],
    ],
    'Standing Lamps' => [
        ['name' => 'height_cm', 'label' => 'Height', 'type' => 'number', 'unit' => 'cm'],
    ],
    '2mtrs Turkish Curtains' => [
        ['name' => 'width_cm', 'label' => 'Width', 'type' => 'number', 'unit' => 'cm'],
    ],
    '1.5mtrs Chance Curtains' => [
        ['name' => 'width_cm', 'label' => 'Width', 'type' => 'number', 'unit' => 'cm'],
    ],

    // ---------------- CARPET ----------------
    'Floor Carpet' => [
        ['name' => 'length_m', 'label' => 'Length', 'type' => 'number', 'unit' => 'm', 'required' => true],
        ['name' => 'width_m', 'label' => 'Width', 'type' => 'number', 'unit' => 'm', 'required' => true],
        ['name' => 'pile', 'label' => 'Pile', 'type' => 'select',
         'options' => ['Low','Medium','High']],
    ],
    'Wall-to-Wall Carpet' => [
        ['name' => 'length_m', 'label' => 'Length', 'type' => 'number', 'unit' => 'm'],
        ['name' => 'width_m', 'label' => 'Width', 'type' => 'number', 'unit' => 'm'],
    ],
    'Carpet Tiles' => [
        ['name' => 'tile_size', 'label' => 'Tile Size', 'type' => 'text', 'placeholder' => 'e.g. 50×50 cm'],
        ['name' => 'tiles_per_box', 'label' => 'Tiles per box', 'type' => 'number'],
    ],

    // ---------------- FLOWERS ----------------
    'Artificial Flowers' => [
        ['name' => 'height_cm', 'label' => 'Height', 'type' => 'number', 'unit' => 'cm'],
    ],
    'Natural Flowers' => [
        ['name' => 'variety', 'label' => 'Variety', 'type' => 'text'],
    ],
    'Flower Vases' => [
        ['name' => 'height_cm', 'label' => 'Height', 'type' => 'number', 'unit' => 'cm'],
        ['name' => 'material', 'label' => 'Material', 'type' => 'text'],
    ],
    'Flower Arrangements' => [
        ['name' => 'occasion', 'label' => 'Occasion', 'type' => 'select',
         'options' => ['Wedding','Birthday','Sympathy','Anniversary','Other']],
    ],

    // ---------------- RUGS ----------------
    'Small Rugs' => [
        ['name' => 'length_cm', 'label' => 'Length', 'type' => 'number', 'unit' => 'cm'],
        ['name' => 'width_cm', 'label' => 'Width', 'type' => 'number', 'unit' => 'cm'],
    ],
    'Medium Rugs' => [
        ['name' => 'length_cm', 'label' => 'Length', 'type' => 'number', 'unit' => 'cm'],
        ['name' => 'width_cm', 'label' => 'Width', 'type' => 'number', 'unit' => 'cm'],
    ],
    'Large Rugs' => [
        ['name' => 'length_cm', 'label' => 'Length', 'type' => 'number', 'unit' => 'cm'],
        ['name' => 'width_cm', 'label' => 'Width', 'type' => 'number', 'unit' => 'cm'],
    ],
    'Round Rugs' => [
        ['name' => 'diameter_cm', 'label' => 'Diameter', 'type' => 'number', 'unit' => 'cm'],
    ],

    // ---------------- ARTIFICIAL FOUNTAIN DECOR ----------------
    'Indoor Fountains' => [
        ['name' => 'height_cm', 'label' => 'Height', 'type' => 'number', 'unit' => 'cm'],
        ['name' => 'power_w', 'label' => 'Power', 'type' => 'number', 'unit' => 'W'],
    ],
    'Outdoor Fountains' => [
        ['name' => 'height_cm', 'label' => 'Height', 'type' => 'number', 'unit' => 'cm'],
    ],
    'Tabletop Fountains' => [
        ['name' => 'height_cm', 'label' => 'Height', 'type' => 'number', 'unit' => 'cm'],
    ],
    'Wall Fountains' => [
        ['name' => 'width_cm', 'label' => 'Width', 'type' => 'number', 'unit' => 'cm'],
    ],

    // ---------------- DOOR MATS ----------------
    'Indoor Door Mats' => [
        ['name' => 'length_cm', 'label' => 'Length', 'type' => 'number', 'unit' => 'cm'],
        ['name' => 'width_cm', 'label' => 'Width', 'type' => 'number', 'unit' => 'cm'],
    ],
    'Outdoor Door Mats' => [
        ['name' => 'length_cm', 'label' => 'Length', 'type' => 'number', 'unit' => 'cm'],
        ['name' => 'width_cm', 'label' => 'Width', 'type' => 'number', 'unit' => 'cm'],
    ],
    'Rubber Door Mats' => [
        ['name' => 'length_cm', 'label' => 'Length', 'type' => 'number', 'unit' => 'cm'],
        ['name' => 'width_cm', 'label' => 'Width', 'type' => 'number', 'unit' => 'cm'],
    ],
    'Coir Door Mats' => [
        ['name' => 'length_cm', 'label' => 'Length', 'type' => 'number', 'unit' => 'cm'],
        ['name' => 'width_cm', 'label' => 'Width', 'type' => 'number', 'unit' => 'cm'],
    ],
];