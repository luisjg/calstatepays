<?php

namespace App\Services;

use App\Contracts\IndustryContract;
use App\Models\NaicsTitle;
use App\Models\University;
use App\Models\UniversityMajor;

class IndustryService implements IndustryContract
{
    private $helper;

    public function __construct()
    {
        $this->helper = new HelperService();
    }

    public function getAllIndustryNaicsTitles()
    {
        $allNaicsTitles = NaicsTitle::all()->map(function ($item, $key) {
            return [
                'naics_code' => $item['naics_code'],
                'title' => $item['naics_title'],
                'image' => asset($item['image'])
            ];
        });
        return $allNaicsTitles;
    }

    public function getIndustryPopulationByRank($hegis_code, $university_id)
    {
        /**
         *  Would here be the best choice to check if CSU Opts In or Not?
         */
        $this->helper->checkOptIn($university_id);
        
        $university_major = UniversityMajor::with(['industryPathTypes' => function ($query) {
                $query->where('entry_status', 'FTF + FTT');
                $query->where('student_path', 1);
                }, 'industryPathTypes.population', 'industryPathTypes.naicsTitle', 'industryPathTypes.industryWage'])
                    ->where('hegis_code', $hegis_code)
                    ->where('university_id', $university_id)
                    ->firstOrFail();
        // dd($university_major);

        $industry_populations= $this->helper->sortIndustryPopulation($university_major);
        
        $population_total = $this->helper->getIndustryPopulationTotals($industry_populations);
        
        $industry_populations = $this->helper->calculatePopulationPercentagesAndReturnImages($industry_populations, $population_total);

        return $industry_populations;
    }
}