<?php

use Illuminate\Database\Seeder;
use Illuminate\Filesystem\Filesystem;

class ArticleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 从数据库中获取的ArticleTag集合
        $tags = \App\Model\Tag::all();

        // 初始化博客的路径
        $dir = "/root/blog";
        $file_system = new Filesystem();

        $files = $file_system->allFiles($dir);

        foreach($files as $file){
            $file_extension = $file_system->extension($file);
            if($file_extension != 'md'){
                continue;
            }

            $create_time_stamp = $file_system->lastModified($file);
            $create_time = gmdate("Y-m-d", $create_time_stamp);

            $file_content = $file_system->get($file);
            $file_name = preg_replace('/^.+[\\\\\\/]/', '', $file);
            $file_name = explode(".md", $file_name);
            $blog_name = $file_name[0];

            $last_dir = dirname($file);
            $current_tag_name = preg_replace('/^.+[\\\\\\/]/', '', $last_dir);

            $article_type_id = 0;

            foreach ($tags as $tag) {
                $tag_name = $tag->name;
                if(strcmp($current_tag_name, $tag_name) == 0){
                    $article_type_id = $tag->id;
                    break;
                }
            }

            $article_id = \App\Model\Article::create([
                'cate_id' => $article_type_id,
                'user_id' => 1,
                'title' => $blog_name,
                'content' => $file_content,
                'tags' => $article_type_id,
                'created_at' => $create_time,
                'updated_at' => $create_time,
            ])->id;

            \App\Model\ArticleStatus::create([
                'art_id' => $article_id,
                'view_number' => 0,
            ]);
        }
    }
}
