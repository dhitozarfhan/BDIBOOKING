<?php

namespace Database\Seeders;

use App\Models\Blog;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class BlogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if(Blog::count()){
            Blog::truncate();
        }

        $datas = DB::connection('second_db')->table('blog')->orderBy('blog_id')->get();

        foreach ($datas as $data) {
            $blog = [
                'id' => $data->blog_id,
                'category_id' => $data->category_id,
                'image' => null,
                'title' => [
                    'en' => $data->en_title,
                    'id' => $data->id_title
                ],
                'summary' => [
                    'en' => $data->en_summary,
                    'id' => $data->id_summary
                ],
                'content' => [
                    'en' => $data->en_content,
                    'id' => $data->id_content
                ],
                'hit' => $data->hit,
                'is_active' => $data->is_active,
                'created_at' => $data->time_stamp,
            ];
            $url = env('URL_BEFORE').'filebox/blog/'.$data->image;
            $contents = @file_get_contents($url);
            if($contents !== false && $data->image){
                Storage::put($blog['image'] = config('services.disk.blog.image').'/'.$data->image, $contents);
            }
            Blog::create($blog);
        }

    }
}
