<?php

namespace SkylarkSoft\GoRMG\GeneralStore\Exports;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;

class ItemsReportExport implements WithTitle, ShouldAutoSize, FromView, ShouldQueue
{
    use Exportable;

    private $data;
    private $title;
    private $view;

    /**
     * @param $data
     * @param $title
     * @param $view
     */
    public function __construct($data, $title, $view)
    {
        $this->data = $data;
        $this->title = $title;
        $this->view = $view;
    }

    public function title(): string
    {
        return $this->title;
    }

    /**
     * @return View
     */
    public function view(): View
    {
        return view($this->view, $this->data);
    }
}
