<?php

namespace SkylarkSoft\GoRMG\Subcontract\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;

class MultipleRecipeDownloadExport implements WithTitle, ShouldAutoSize, FromView
{
    use Exportable;

    private $dyeingRecipe;

    public function __construct($dyeingRecipe)
    {
        $this->dyeingRecipe = $dyeingRecipe;
    }

    public function title(): string
    {
        return 'Multiple Recipe Download';
    }

    public function view(): View
    {
        return view('subcontract::textile_module.dyeing_process.multiple_recipe_download.excel', [
            'dyeingRecipe' => $this->dyeingRecipe,
        ]);
    }
}
