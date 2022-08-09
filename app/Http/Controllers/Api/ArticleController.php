<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Article;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    private $articleModel;

    public function __construct(Article $articleModel)
    {
        $this->articleModel = $articleModel; 
    }
    public function welcomeInfo(){
        $list = $this->articleModel->getWelcomeInfo();

        //$list['article_content']=html_entity_decode($list['article_content']);

        foreach ($list as $item){
            $item['article_content'] = strip_tags($item['article_content']);
            $item['article_content'] = preg_replace("/&#?[a-z0-9]+;/i"," ",$item['article_content']); 
        }
        return response()->json($list);
    }
}
