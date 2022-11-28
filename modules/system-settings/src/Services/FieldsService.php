<?php

namespace SkylarkSoft\GoRMG\SystemSettings\Services;

class FieldsService
{
    public static function getKeys(): array
    {
        return array_keys(self::FIELDS);
    }

    public static function getAllFields(): array
    {
        return self::FIELDS;
    }

    public static function getValue($key): string
    {
        return self::FIELDS[$key];
    }

    private const FIELDS = [
        'body_part' => 'Body Part',
        'item_color' => 'Item Color',
        'combo_color' => 'COMBO COLOR',
        'pantone_code' => 'PANTONE / CODE',
        'item_size' => 'Item Size',
        'care_label_type' => 'CARE LABEL TYPE',
        'size_range' => 'AGE GROUP',
        'item_description' => 'Description',
        'brand' => 'Brand / Sup',
        'ref' => 'Computer Ref',
        'style_ref' => 'STYLE REF',
        'factory_ref_no' => 'FACTORY REF NO',
        'production_batch' => 'PRODUCTION BATCH',
        'fabric_ref' => 'FABRIC REF',
        'po_ref' => 'PO REF',
        'item_code' => 'ITEM CODE',
        'length_inch' => 'LENGTH (inch)',
        'width_inch' => 'WIDTH (inch)',
        'length_cm' => 'LENGTH (CM)',
        'width_cm' => 'Width (CM)',
        'measurement' => 'MEASUREMENT',
        'qty_per_carton' => 'QTY PER CARTON',
        'thread_count' => 'THREAD COUNT',
        'cons_per_mtr' => 'CONS PER MTR',
        'team_id' => 'TEAM ID',
        'league' => 'LEAGUE',
        'division' => 'DIVISION',
        'age_or_size' => 'AGE OR SIZE',
        'fold_over' => 'FOLD OVER',
        'poly_thickness' => 'POLY THICKNESS',
        'sizer' => 'SIZER',
        'binding_color' => 'BINDING COLOR',
        'contrast_cord_color' => 'CONTRAST CORD COLOR',
        'zip_puller_ref' => 'ZIP PULLER REF.',
        'zipper_puller_teeth_color' => 'ZIPPER PULLER /TEETH COLOR',
        'zipper_tape_color' => 'ZIPPER TAPE COLOR',
        'zipper_size' => 'ZIPPER SIZE',
        'fusing_status' => 'FUSING STATUS',
        'plaster_fastener_adjustable_straps_quality' => 'PLASTER FASTENER & ADJUSTABLE STRAPS QUALITY',
        'quality' => 'QUALITY',
        'fiber_composition' => 'FABRIC COMPOSITION',
        'care_symbol' => 'CARE SYMBOL',
        'care_instruction' => 'CARE INSTRUCTION',
        'swatch' => 'SWATCH',
        'poly_bag_art_work' => 'POLY BAG ART WORK',
        'sample' => 'SAMPLE',
        'image_hints' => 'IMAGE HINTS',
        'item_sizes' => 'Item Sizes',
    ];
}
