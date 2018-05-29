<?php

use Illuminate\Database\Seeder;
use App\Models\StudentBackground;
use App\Models\Investment;
use App\Models\MajorPath;
use App\Models\UniversityMajor;
use App\Models\MajorPathWage;
use App\Models\Population;
use App\Models\IndustryPathType;
use App\Models\IndustryWage;
class University_Majors_TableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $json = File::get("database/data/university_majors.json");
        $data = json_decode($json);

        //Data for Student_Background table
        $education_levels = ['FTT', 'FTF'];
        $age_ranges = [
            [ 'age_range_id' => 1],
            [ 'age_range_id' => 2],
            [ 'age_range_id' => 3],
            [ 'age_range_id' => 4]
        ];
        //Data for Investments table
        $annual_earnings = [
            [ 'annual_earnings_id' => 1],
            [ 'annual_earnings_id' => 2],
            [ 'annual_earnings_id' => 3],
            [ 'annual_earnings_id' => 4],
            [ 'annual_earnings_id' => 5],
            [ 'annual_earnings_id' => 6],
        ];
        $annual_financial_aid = [
            [ 'annual_financial_aid_id' => 1],
            [ 'annual_financial_aid_id' => 2],
            [ 'annual_financial_aid_id' => 3],
            [ 'annual_financial_aid_id' => 4],

        ];

        //Data for major_paths table
        $student_paths = [
            [ 'student_path' => 1],
            [ 'student_path' => 2],
            [ 'student_path' => 3]
        ];
        $years = [
            ['years' => 2],
            ['years' => 5],
            ['years' => 10],
        ];

        //Data for_industry_path_types
        $naics = [
            ['code' => 21],
            ['code' => 11],
            ['code' => 22],
            ['code' => 55],
            ['code' => 48],
            ['code' => 23],
            ['code' => 71],
            ['code' => 53],
            ['code' => 72],
            ['code' => 42],
            ['code' => 56],
            ['code' => 31],
            ['code' => 21],
            ['code' => 44],
            ['code' => 92],
            ['code' => 52],
            ['code' => 54],
            ['code' => 62],
            ['code' => 61],

        ];

        //Each University_Major row will have 8 related StudentBackground rows, each StudentBackground row will have 24
        //related Investment rows,so then each University_Major row has 576 possible combinations for Investment data.
        foreach($data as $row){
            $university_major = new UniversityMajor();
            $university_major->hegis_code = $row->hegis_code;
            $university_major->college_id = 1;
            $university_major->university_id = $row->university_id;
            $university_major->save();
            foreach($age_ranges as $age_range){
                foreach($education_levels as $education_level){
                    $student_background = factory(StudentBackground::class)->create([
                        'university_major_id' => $university_major->id,
                        'age_range_id' => $age_range['age_range_id'],
                        'education_level' => $education_level
                    ]);
                    foreach($annual_earnings as $annual_earning){
                        foreach($annual_financial_aid as $annual_aid){
                            factory(Investment::class)->create([
                                'student_background_id'     => $student_background->id,
                                'annual_earnings_id'        => $annual_earning['annual_earnings_id'],
                                'annual_financial_aid_id'   => $annual_aid['annual_financial_aid_id'],
                            ]);
                        }
                    }
                }
            }
            //create 3 Major Paths
            foreach($student_paths as $student_path){
                foreach($years as $year) {
                    $majorPath = new MajorPath();
                    $majorPath->student_path = $student_path['student_path'];
                    $majorPath->university_majors_id = $university_major->id;
                    $majorPath->entry_status = 'All';
                    $majorPath->years  = $year['years'];
                    $majorPath->save();

                    factory(MajorPathWage::class)->create(['major_path_id'=> $majorPath->id, 'population_sample_id' => $majorPath->id]);
                    factory(Population::class)->create(['id' => $majorPath->id])->id;
                }
            }

            foreach($naics as $naic){
                $population = factory(Population::class)->create()->id;
                factory(IndustryPathType::class)->create([
                    'naics_code'           => $naic['code'],
                    'university_majors_id' => $university_major->id,
                    'population_sample_id' => $population
                ]);
            }
        };
    }
}
