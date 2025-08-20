<?php

namespace Database\Seeders;

use App\Enums\ArticleType;
use App\Enums\LinkType;
use App\Enums\NavigationType;
use App\Models\Article;
use App\Models\Navigation;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class NavigationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        if(Navigation::count()){
            Navigation::truncate();
        }

        $types = ['header', 'footer'];

        foreach($types as $type){

            $datas = DB::connection('second_db')->select('select node.*, parent.navigation_id as parent_id, (COUNT(parent.navigation_id) - 1) AS depth from navigation node, navigation parent where (node.lft BETWEEN parent.lft AND parent.rgt) AND node.type=\''.$type.'\' AND parent.type=\''.$type.'\' AND node.is_active=\'Y\' AND parent.is_active=\'Y\' group by node.navigation_id order by node.lft');

            $navs = [];
            foreach($datas as $data){
                $navs[$data->navigation_id] = (array) $data;
            }

            foreach($navs as $k => $data){
                
                $nav = Navigation::create([
                    'navigation_type_id'=> NavigationType::{ucfirst($type)}->value,
                    'link_type_id'      => $linkTypeId = ($data['link_type'] == 'internal' ? ( strpos($data['path'], '/post/') !== false ? LinkType::Article->value : LinkType::Internal->value ) : ($data['link_type'] == 'page' ? LinkType::Article->value : LinkType::Empty->value)),
                    'article_id'        => strpos($data['path'], '/post/') !== false ? (
                        substr($data['path'], 0, strpos($data['path'], '/')) == 'page' ? 
                        Article::where('article_type_id', ArticleType::Page->value)->where('title->id', DB::connection('second_db')->table('page')->where('slug', explode('/', $data['path'])[2])->value('id_title'))->first()->id :
                        Article::where('article_type_id', ArticleType::{ucfirst(substr($data['path'], 0, strpos($data['path'], '/')))}->value)->where('title->id', DB::connection('second_db')->table(substr($data['path'], 0, strpos($data['path'], '/')))->where(substr($data['path'], 0, strpos($data['path'], '/')).'_id', explode('/', $data['path'])[substr($data['path'], 0, strpos($data['path'], '/')) == 'information' ? 2 : 5])->value('id_title'))->first()->id
                    ) : null,
                    'name'              => [
                        'en'    => $data['en_name'],
                        'id'    => $data['id_name'],
                    ],
                    'path'              => $linkTypeId == LinkType::Article->value || $linkTypeId == LinkType::Empty->value ? null : $data['path'],
                    'target_blank'      => $data['target'] == '_blank',
                ]);

                $navs[$k]['id'] = $nav->id;

                if($data['navigation_id'] != $data['parent_id']) {
                    //search parent
                    $parentNav = Navigation::find($navs[$data['parent_id']]['id']);
                    $parentNav->appendNode($nav);
                }
            }
        }
    }
}
