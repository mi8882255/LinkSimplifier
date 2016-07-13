<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ApiController extends Controller
{
    public function get10(Request $request) {
        $resp=[];
        $bookmarks=\App\Bookmark::orderBy('id', 'desc')->take(10)->get();
        foreach ($bookmarks as $bookmark) {
            $bmArr=[];
            $bmArr['uid']=$bookmark->id;
            $bmArr['url']=$bookmark->url;
            foreach ($bookmark->comments as $comment) {
                $comArr=[];
                $comArr['created']=$comment->created_at;
                $comArr['updated']=$comment->updated_at;
                $comArr['text']=$comment->body;

                $bmArr['comments'][]=$comArr;
            }
            $resp[]=$bmArr;
        }

        return response()->json($resp)->setCallback($request->input('callback'));

    }
    public function getByIdwComments(Request $request) {
        $id = $request->input('id');

        $bookmark=\App\Bookmark::where('id', $id)->first();
        $resp=[];
        $resp['uid']=$bookmark->id;
        $resp['url']=$bookmark->url;
        foreach ($bookmark->comments as $comment) {
            $comArr=[];
            $comArr['created']=$comment->created_at;
            $comArr['updated']=$comment->updated_at;
            $comArr['text']=$comment->body;

            $resp['comments'][]=$comArr;
        }


        return response()->json($resp)->setCallback($request->input('callback'));
    }
    public function addNew(Request $request) {
        $url = $request->input('url');
        $bookmark=\App\Bookmark::where('url', $url)->first();
        if ($bookmark===null) {
            $bookmark = new \App\Bookmark;
            $bookmark->url=$request->input('url');
            $bookmark->save();
        }
        $resp = $bookmark->id;

        return response()->json($resp)->setCallback($request->input('callback'));
    }
    public function addComment(Request $request) {
        $bookmarkId = $request->input('bm_id');
        $text = $request->input('text');
        $bookmark=\App\Bookmark::where('id', $bookmarkId)->first();

        if ($bookmark===null) {
            return response()->json(false)->setCallback($request->input('callback'));
        }

        $comment=new \App\Comment();
        $comment->body=htmlspecialchars($text);
        $comment->bookmark_id=$bookmarkId;
        $comment->ip=$request->ip();
        $comment->save();
        $resp = $comment->id;

        return response()->json($resp)->setCallback($request->input('callback'));
    }
    public function modifyComment(Request $request) {
        $id = $request->input('id');
        $text = $request->input('text');
        $userIp=$request->ip();
        $oneHourAgo = Carbon::now()->subHour();
        $comment=\App\Comment::where([['id', $id],['ip',$userIp],['created_at','>',$oneHourAgo]])->first();

        if ($comment===null) {
            $resp = false;
        } else {
            $comment->body=htmlspecialchars($text);
            $comment->save();
            $resp = $comment->id;
        }



        return response()->json($resp)->setCallback($request->input('callback'));
    }
    public function deleteComment(Request $request) {
        $id = $request->input('id');
        $userIp=$request->ip();
        $oneHourAgo = \Carbon::now()->subHour();
        $comment=\App\Comment::where([['id', $id],['ip',$userIp],['created_at','>',$oneHourAgo]])->first();

        if ($comment===null) {
            $resp = false;
        } else {
            $comment->delete();
            $resp = true;
        }



        return response()->json($resp)->setCallback($request->input('callback'));
    }
}
