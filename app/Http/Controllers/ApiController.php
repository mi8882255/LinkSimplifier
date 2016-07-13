<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;

class ApiController extends Controller
{
    /**
     * @param Request $request request object from controller
     * @param bool $state response arrived or not
     * @param mixed $resp responce body
     * @param array $err errors array
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     * ex.: callback({"state":true,"resp":4});
     */
    public function prepareJSONPresp(Request $request, $state, $resp, $err=[]) {
        if (!$state) {
            return response()->json(["state"=>$state,"err"=>$err])->setCallback($request->input('callback'));
        } else {
            return response()->json(["state"=>$state,"resp"=>$resp])->setCallback($request->input('callback'));
        }
    }

    /**
     * Get last 10 bookmarks
     * request: -
     * response: 0:['id','url'],1:...
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function get10(Request $request)
    {
        $resp = [];
        $bookmarks = \App\Bookmark::orderBy('id', 'desc')->take(10)->get();
        foreach ($bookmarks as $bookmark) {
            $bmArr = [];
            $bmArr['uid'] = $bookmark->id;
            $bmArr['url'] = $bookmark->url;
            $resp[] = $bmArr;
        }

        return $this->prepareJSONPresp($request, true, $resp);
    }

    /**
     * Get bookmark by id with comments
     * request: POST['id']
     * response: 'id','url','comments':[0:['id','created','updated','text'],1:...]
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function getByIdwComments(Request $request) {
        $id = $request->input('id');

        $bookmark=\App\Bookmark::where('id', $id)->first();
        $resp=[];
        $resp['uid']=$bookmark->id;
        $resp['url']=$bookmark->url;
        foreach ($bookmark->comments as $comment) {
            $comArr=[];
            $comArr['id'] = $comment->id;
            $comArr['created']=$comment->created_at;
            $comArr['updated']=$comment->updated_at;
            $comArr['text']=$comment->body;

            $resp['comments'][]=$comArr;
        }


        return $this->prepareJSONPresp($request,true,$resp);
    }
    /**
     * Add new bookmark
     * request: POST['url']
     * response: bookmark id
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function addNew(Request $request) {
        $url = $request->input('url');
        $bookmark=\App\Bookmark::where('url', $url)->first();
        if ($bookmark===null) {
            $bookmark = new \App\Bookmark;
            $bookmark->url=$request->input('url');
            $bookmark->save();
        }
        $resp = $bookmark->id;

        return $this->prepareJSONPresp($request,true,$resp);
    }

    /**
     * Add new comment
     * request: POST['bm_id','text']: bm_id - parrent bookmark id
     * response: comment id
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function addComment(Request $request) {
        $bookmarkId = $request->input('bm_id');
        $text = $request->input('text');
        $bookmark=\App\Bookmark::where('id', $bookmarkId)->first();

        if ($bookmark===null) {
            return $this->prepareJSONPresp($request,false,false,['No bookmark found for this id']);
        }

        $comment=new \App\Comment();
        $comment->body=htmlspecialchars($text);
        $comment->bookmark_id=$bookmarkId;
        $comment->ip=$request->ip();
        $comment->save();
        $resp = $comment->id;

        return $this->prepareJSONPresp($request,true,$resp);
    }
    /**
     * Modify comment
     * request: POST['id','text']
     * response: comment id
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function modifyComment(Request $request) {
        $id = $request->input('id');
        $text = $request->input('text');
        $userIp=$request->ip();
        $oneHourAgo = Carbon::now()->subHour();
        $comment=\App\Comment::where([['id', $id],['ip',$userIp],['created_at','>',$oneHourAgo]])->first();

        if ($comment===null) {
            return $this->prepareJSONPresp($request,false,false,['No comment found for this id or time is gone']);
        } else {
            $comment->body=htmlspecialchars($text);
            $comment->save();
            $resp = $comment->id;
        }



        return $this->prepareJSONPresp($request,true,$resp);
    }
    /**
     * Delete comment
     * request: POST['id']
     * response: true|false
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function deleteComment(Request $request) {
        $id = $request->input('id');
        $userIp=$request->ip();
        $oneHourAgo = Carbon::now()->subHour();
        $comment=\App\Comment::where([['id', $id],['ip',$userIp],['created_at','>',$oneHourAgo]])->first();

        if ($comment===null) {
            return $this->prepareJSONPresp($request,false,false,['No comment found for this id']);
        } else {
            $comment->delete();
            return $this->prepareJSONPresp($request,true,true);
        }



        return $this->prepareJSONPresp($request,true,$resp);
    }
}
