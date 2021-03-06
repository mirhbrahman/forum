<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Discussion;
use App\Reply;
use Session;
use Auth;

class DiscussionsController extends Controller
{
    public function create()
    {
        return view('discussion.create');
    }
    public function store(Request $request)
    {
        $this->validate($request,[
            'channel_id' => 'required',
            'title' => 'required|min:2|max:150|unique:discussions',
            'content' => 'required',
        ]);

        $input = $request->all();
        $input['user_id'] = Auth::id();
        $input['slug'] = str_slug($request->title);
        $discussion = [];
        if ($discussion = Discussion::create($input)) {
            Session::flash('success','Discussion create successfull.');
        }

        return redirect()->route('discussion.show',['slug'=>$discussion->slug]);
    }

    public function show($slug)
    {
        $d = Discussion::where('slug',$slug)->first();
        $best_ans = $d->replies()->where('best_ans', 1)->first();
        return view('discussion.show',compact('d','best_ans'));
    }

    public function edit($slug)
    {
        return view('discussion.edit')->with('discussion', Discussion::where('slug',$slug)->first());
    }

    public function update(Request $request, $id)
    {
        $this->validate($request,[
            'content' => 'required',
        ]);
        $d = Discussion::find($id);
        if ($d->update($request->all())) {
            Session::flash('success','Discussion update successfull.');
        }

        return redirect()->route('discussion.show',$d->slug);

    }


}
