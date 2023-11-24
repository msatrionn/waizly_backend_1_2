<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Article;
use App\Models\AuthenticationLog;
use Illuminate\Support\Facades\Auth;

class ArticleController extends Controller
{
    public function index()
    {
        $articles = Article::all();
        return response()->json($articles);
    }

    public function store(Request $request)
    {
        $article = Article::create($request->all());
        AuthenticationLog::create([
            'user_id' => Auth::user()->id,
            'table' => 'article',
            'action' => 'create',
        ]);
        return response()->json($article, 201);
    }

    public function show(Article $article)
    {
        return response()->json($article);
    }

    public function update(Request $request, Article $article)
    {
        $article->update($request->all());
        AuthenticationLog::create([
            'user_id' => Auth::user()->id,
            'table' => 'article',
            'action' => 'update',
        ]);
        return response()->json($article);
    }

    public function destroy(Article $article)
    {
        $article->delete();
        AuthenticationLog::create([
            'user_id' => Auth::user()->id,
            'table' => 'article',
            'action' => 'delete',
        ]);
        return response()->json(["data"=>$article, "status"=>200, "message"=>"success"]);

    }
}
