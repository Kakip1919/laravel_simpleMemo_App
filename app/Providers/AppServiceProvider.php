<?php

namespace App\Providers;

use App\Tag;
use Illuminate\Support\ServiceProvider;
use App\Memo;
use Illuminate\Support\Facades\Auth;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //全てのメソッドが呼ばれる前に先に呼ばれるメソッド
        view()->composer("*", function($view){

            $user = Auth::user();
            $memoModel = new Memo();
            $memos = $memoModel->MyMemo( Auth::id());

            $tagModel = new Tag();
            $tags = $tagModel->where("user_id", Auth::id()->get());

            $view->with("user", $user)->with("memos", $memos)->with("tags", $tags);
        });
    }
}
