<?php

use Illuminate\Database\Seeder;

class LinksTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker\Factory::create();
        $data = array();
        for($i=1; $i<=5; $i++){
            $data[$i] = [
                'link_title' => $faker->title,
                'link_description' => $faker->sentence,
                'link_url' => $faker->url,
                'link_order' => $i,
            ];
        }
        DB::table('blog_links')->insert($data);
    }
}
