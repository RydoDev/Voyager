<?php

use Illuminate\Database\Seeder;

class BigBangSeeder extends Seeder
{
    private $clusternames = array(
        "Vega",
        "Sirius",
        "Telmor",
        "Desto",
        "Catar",
        "Distor",
        "Membu"
    );

    private $startypes = array(
        "Red Giant",
        "Blue Giant",
        "White Dwarf",
        "Red Supergiant"
    );

    public function run()
    {
        //Create clusters
        for($i = 0; $i < count($this->clusternames); $i++)
        {
            $cluster = array('name' => $this->clusternames[$i],
                'x' => rand(1000, 10000),
                'y' => rand(1000, 10000),
                'density' => rand(1, 10)
            );

            DB::table('clusters')->insert($cluster);
            $cluster_id = DB::getPdo()->lastInsertId();

            //Create star systems
            for($j = 0; $j < $cluster['density'] * 1000; $j++)
            {
                $coords = $this->GenerateStarSystemCoords($cluster['x'], $cluster['y']);

                $starsystem = array
                (
                    'cluster_id' => $cluster_id,
                    'x' => $coords['x'],
                    'y' => $coords['y']
                );

                DB::table('starsystems')->insert($starsystem);
                $starsystem_id = DB::getPdo()->lastInsertId();

                //Create star
                $star = array(
                    'starsystem_id' => $starsystem_id,
                    'name' => $cluster['name'].'-'.str_random(5),
                    'type' => $this->startypes[rand(0, count($this->startypes)-1)]
                );

                DB::table('stars')->insert($star);

                //Create planets
            }
        }
    }

    private function GenerateStarSystemCoords($x, $y)
    {
        $newXY = array();

        //Generate small x deviation either positive or negative
        $tmpX = rand(10, 100);
        if ($rand = rand(0,1))
        {
            $newXY['x'] = $x + $tmpX;
        }
        else
        {
            $newXY['x'] = $x - $tmpX;
        }

        //Generate small y deviation either positive or negative
        $tmpY = rand(10, 100);
        if ($rand = rand(0,1))
        {
            $newXY['y'] = $y + $tmpY;
        }
        else
        {
            $newXY['y'] = $y - $tmpY;
        }

        //return array with xy
        return $newXY;
    }
}
