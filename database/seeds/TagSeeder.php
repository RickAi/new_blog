<?php

use Illuminate\Database\Seeder;
use Illuminate\Filesystem\Filesystem;

class TagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $tag_array = array();
        $first_add = true;

        $dir = "/root/blog";
        $file_system = new Filesystem();

        $files = $file_system->allFiles($dir);

        foreach($files as $file) {
            $file_extension = $file_system->extension($file);
            if ($file_extension != 'md') {
                continue;
            }

            $last_dir = dirname($file);
            $tag_name = preg_replace('/^.+[\\\\\\/]/', '', $last_dir);

            $create_time_stamp = $file_system->lastModified($file);
            $create_time = gmdate("Y-m-d", $create_time_stamp);

            if ($first_add) {
                $tag_info = array();
                $tag_info[0] = $tag_name;
                $tag_info[1] = $create_time;

                $tag_array[0] = $tag_info;
                $first_add = false;
            }

            $is_new = true;
            foreach ($tag_array as $tag) {
                if(strcmp($tag[0], $tag_name) == 0){
                    $is_new = false;
                }
            }

            if($is_new){
                $tag_count = count($tag_array);

                $tag_info = array();
                $tag_info[0] = $tag_name;
                $tag_info[1] = $create_time;

                $tag_array[$tag_count] = $tag_info;
            }
        }

        foreach ($tag_array as $tag_io) {
            \App\Model\Tag::create([
                'name' => $tag_io[0],
            ]);

            \App\Model\Category::create([
                'cate_name' => $tag_io[0],
                'as_name' => $tag_io[0],
                'parent_id' => 0,
                'seo_key' => $tag_io[0],
                'seo_desc' => $tag_io[0],
                'created_at' => $tag_io[1],
                'updated_at' => $tag_io[1],
            ]);
        }
    }
}
