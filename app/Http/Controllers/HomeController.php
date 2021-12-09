<?php

namespace App\Http\Controllers;

use App\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Memo;


class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }


    public function index(): \Illuminate\Contracts\Support\Renderable
    {
        $user = Auth::user();
        $memos = Memo::where("user_id", $user["id"])->where("status", 1)->orderBy("updated_at", "DESC")->get();

        return view('create', compact("user", "memos"));
    }

    public function create()
    {
        $user = Auth::user();
        $memos = Memo::where("user_id", $user["id"])->where("status", 1)->orderBy("updated_at", "DESC")->get();

        return view("create", compact("user", "memos"));
    }

    public function store(Request $request): \Illuminate\Http\RedirectResponse
    {
        $data = $request->all();
        $exist = Tag::where("name", $data["tag"])->where("user_id",$data["user_id"])->first();
        if( empty($exist["id"]) ){
            $tag_id = Tag::insertGetId(["name" => $data["tag"], "user_id" => $data["user_id"]]);
        }
        if( !empty($exist["id"]) ){
            $tag_id = $exist["id"];
        }

        $memo_id = Memo::insertGetId(["content" => $data["content"], "user_id" => $data["user_id"], "status" => 1, "tag_id" => $tag_id]);

        return redirect()->route("home");
    }

    public function edit($id)
    {
        $user = Auth::user();
        $memo = Memo::where("status", 1)->where("id", $id)->where("user_id", $user["id"])->first();
        $memos = Memo::where("user_id", $user["id"])->where("status", 1)->orderBy("updated_at", "DESC")->get();
        $tags = Tag::where("user_id", $user["id"])->get();

        return view("edit", compact("memo", "user", "memos", "tags"));
    }

    public function update(Request $request, $id): \Illuminate\Http\RedirectResponse
    {
        $inputs = $request->all();
        Memo::where("id", $id)->update(["content" => $inputs["content"], "tag_id" => $inputs["tag_id"]]);

        return redirect()->route("home");
    }

    public function delete(Request $request, $id): \Illuminate\Http\RedirectResponse
    {
        Memo::where("id", $id)->update( ["status" => 2] );

        return redirect()->route("home")->with("success", "メモの削除が完了しました。");
    }

}
