<?php

namespace SkylarkSoft\GoRMG\Merchandising\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\Writer\Exception;
use SkylarkSoft\GoRMG\Merchandising\Exports\TechPackSampleFileExport;
use SkylarkSoft\GoRMG\Merchandising\Models\PoColorSizeBreakdown;
use SkylarkSoft\GoRMG\SystemSettings\Models\Color;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class TechPackSampleFileController extends Controller
{
    /**
     * @param Request $request
     * @return BinaryFileResponse
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws Exception
     */
    public function __invoke(Request $request): BinaryFileResponse
    {
        $style = $request->get('style');

        $bodyPart = array_map(function ($item) {
            return trim($item);
        }, explode(',', $request->get('body_part_count')));

        $colors = PoColorSizeBreakdown::query()
            ->whereHas('order', function ($query) use ($style) {
                $query->where('style_name', $style);
            })
            ->select('colors')
            ->pluck('colors')
            ->flatten()->unique()
            ->values();

        $colorValues = Color::query()
            ->select('name')
            ->whereIn('id', $colors)
            ->get();

        $pageData = [
            'style' => $style,
            'color_values' => $colorValues,
            'creeper_count' => $request->get('creeper_count'),
            'body_part_count' => $bodyPart,
        ];

        return Excel::download(new TechPackSampleFileExport($pageData), $style . '_sample_tech_pack.xlsx');
    }
}
