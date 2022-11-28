<?php

namespace SkylarkSoft\GoRMG\Merchandising\Services\TechPackFile;

use Maatwebsite\Excel\Facades\Excel;
use SkylarkSoft\GoRMG\Merchandising\Exception\TeamNameNotWellFormedException;
use SkylarkSoft\GoRMG\Merchandising\Imports\TechPackFilesImport;

class TechPackExcelConverter
{
    protected $file_path;

    protected $creepers = 0;
    protected $style = '';
    protected $body_parts = '';

    public function __construct($file_path)
    {
        $this->file_path = $file_path;
    }

    public function convert($style, $creepers, $body_parts)
    {
        $this->style = $style;
        $this->creepers = $creepers;
        $this->body_parts = $body_parts;

        return $this->makeJson();
    }

    /**
     * @throws TeamNameNotWellFormedException
     */
    private function makeJson()
    {
        //public_path('/storage/tech_pack_files/tech_pack.xlsx')
        $data = Excel::toArray(new TechPackFilesImport(), $this->file_path);
        $rowData = $data[0];
        unset($data);

        $teamIndex = $this->getIndexOf('TEAM', $rowData);
        if (!$teamIndex) {
            throw new TeamNameNotWellFormedException("Team Name Not Well Formed");
        }
        $style = $this->style;
        $teamColors = array_slice($rowData, $teamIndex['row'] + 1);
        $creeperOptionsArr = $this->creeperOptionArr($this->body_parts);

        $colorCount = count($creeperOptionsArr);
        $contrastColors = collect($teamColors)->flatMap(function ($teamColor) use ($colorCount, $style, $creeperOptionsArr) {
            return collect($teamColor)->filter(function ($value) {
                return $value !== null;
            })->splice(1)->chunk($colorCount)
                ->flatMap(function ($contrastColor, $creeperIndex) use ($teamColor, $style, $creeperOptionsArr, $colorCount) {
                    return collect($contrastColor)
                        ->map(function ($color, $colorIndex) use ($teamColor, $style, $creeperOptionsArr, $colorCount, $creeperIndex) {
                            $bodyPart = $colorIndex % $colorCount;
                            return [
                                'color' => collect($teamColor)->first(),
                                'style' => $style,
                                'creeper' => 'Creeper_' . ($creeperIndex + 1) . '_' . $creeperOptionsArr[$bodyPart],
                                'contrast_color' => $color
                            ];
                        });
                });

        })->toArray();

        return json_encode($contrastColors);
    }

    public function getContent()
    {
        return $this->makeJson();
    }

    private function generateCreeperValue($creeper, $creeperOption): string
    {
        return 'Creepers_' . $creeper . '_' . $creeperOption;
    }

    private function getIndexOf($needle, $data): array
    {
        $indices = [];
        foreach ($data as $idx => $arr) {
            $idx2 = array_search(strtolower($needle), array_map('strtolower', $arr));
            if ($idx2 !== false) {
                $indices['row'] = $idx;
                $indices['col'] = $idx2;
            }
        }

        return $indices;
    }

    private function creeperOptionArr($creeperOptions)
    {
        return explode(',', $creeperOptions);
    }
}
