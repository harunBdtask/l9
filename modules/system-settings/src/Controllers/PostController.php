<?php


namespace SkylarkSoft\GoRMG\SystemSettings\Controllers;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use SkylarkSoft\GoRMG\SystemSettings\Models\Comment;
use SkylarkSoft\GoRMG\SystemSettings\Models\Like;
use SkylarkSoft\GoRMG\SystemSettings\Models\Post;

class PostController extends Controller
{
    public function showCommentCount(Request $request)
    {
        $postId = $request->post_id;

        return  Comment::where('post_id', $postId)->count();
    }

    private function getCount($post_id)
    {
        return  Comment::where('post_id', $post_id)->count();
    }

    public function showStatus()
    {
        $posts = Post::with('user')->latest()->get();
        $html = '';
        foreach ($posts as $post) {
            $imageHtml = '';
            if ($post->user->profile_image == null) {
                $imageHtml = '<img class="img-circle"  src='.asset('flatkit/assets/images/avatar2.png').' >';
            } elseif (Storage::disk('public')->exists('profile_image/'.$post->user->profile_image)) {
                $imageHtml = '<img class="img-circle" src='.asset('storage/profile_image/'.$post->user->profile_image).'  >';
            } else {
                $imageHtml = "<img class='img-circle' src=".asset('flatkit/assets/images/avatar2.png')."  >";
            }
            $hasLike = '';
            if ($this->userHasLike($post->id)) {
                $hasLike = 'fa fa-fw fa-star text-info';
            } else {
                $hasLike = 'fa fa-fw fa-star-o text-dark';
            }
            $likeCount = '';
            if ($this->getLikeCount($post->id) < 2) {
                $likeCount = $this->getLikeCount($post->id).' like';
            } else {
                $likeCount = $this->getLikeCount($post->id).' likes';
            }
            $commentCount = '';
            if ($this->getCount($post->id) < 2) {
                $commentCount = $this->getCount($post->id).' comment';
            } else {
                $commentCount = $this->getCount($post->id).' comments';
            }
            $deleteTag = '';
            if ($post->user->id == auth()->user()->id) {
                $deleteTag = '<a class="btn white btn-xs  status-body" data-status="'.$post->id.'">
                        <i class="fa fa-fw fa-remove text-muted""></i>
                    </a>';
            }
            $html .= '<div class="sl-item" >
                <div class="sl-left">
                 '.$imageHtml.'
                </div>
                <div class="sl-content">
                  <div class="sl-date text-muted">'.$post->created_at->diffForHumans().'</div>
                  <div class="sl-author">
                    <a>'.$post->user->screen_name.'</a>
                  </div>
                <div>
                    <p>'.$post->post_body.'</p>
                  </div>
                <div>
                    <blockquote>
                            <small class="text-dark">
                            <b id="b-like-'.$post->id.'">'.$likeCount.'</b>,<b> <a data-post="'.$post->id.'" id="commentCount-'.$post->id.'" class="comment-count"> '.$commentCount.'</a></b> </small>
                      </blockquote>
                </div>

                  <div class="like-class sl-footer-'.$post->id.'" style="margin-bottom: 5px!important;">
                    <a class="btn white btn-xs" >
                        <i id="'.$post->id.'" class="like-button '.$hasLike.'"></i>
                    </a>
                    <a class="btn white btn-xs" data-toggle="collapse" data-target="#reply-'.$post->id.'">
                        <i class="fa fa-fw fa-mail-reply text-muted"></i>
                    </a>
                    <a class="btn white btn-xs remove-comment"  style="display: none" id="toggle-post-'.$post->id.'" data-post="'.$post->id.'">
                        <i class="fa fa-fw fa-toggle-down text-muted""></i>
                    </a>
                   '.$deleteTag.'

                 </div>
                  <div class="box collapse m-a-0 comment-div " id="reply-'.$post->id.'">
                    <form>
                      <textarea class="form-control form-control-sm no-border comment-context" rows="2" name="commentStore"  id="'.$post->id.'" placeholder="Type something..."></textarea>
                        <input type="hidden" class="postId" value="'.$post->id.'">
                        <div class="box-footer clearfix">
                            <input type="button"   value="Comment" data-toggle="collapse" data-target="#reply-'.$post->id.'" class="btn btn-sm white m-b pull-right btn-sm comment">

                        </div>
                     </form>
                  </div>
                  </div>
                </div>
              </div>';
        }

        echo $html;
        exit;
    }

    public function store(Request $request)
    {
        $request->validate([
            'post_body' => 'required',
        ]);

        $newPost = new Post();
        $newPost->post_body = $request->post_body;
        $newPost->user_id = userId();
        $newPost->save();

        return response(['success' => 'data send successfully']);
    }

    public function storeComment(Request $request)
    {
        $request->validate([
            'comment' => 'required',
        ]);
        $newComment = new Comment();
        $newComment->comments = $request->comment;
        $newComment->post_id = $request->post_id;
        $newComment->user_id = auth()->user()->id;
        $newComment->save();

        return response(['success' => 'comment send successfully']);
    }

    public function showComment(Request $request)
    {
        $postId = $request->post_id;
        $comments = Comment::where('post_id', $postId)->get();
        $html = '';
        foreach ($comments as $comment) {
            $imageHtml = '';
            if ($comment->user->profile_image == null) {
                $imageHtml = '<img src='.asset('flatkit/assets/images/avatar2.png').' class="img-circle" >';
            } elseif (Storage::disk('public')->exists('profile_image/'.$comment->user->profile_image)) {
                $imageHtml = '<img src='.asset('storage/profile_image/'.$comment->user->profile_image).' class="img-circle" >';
            } else {
                $imageHtml = "<img src=".asset('flatkit/assets/images/avatar2.png')." class='img-circle' >";
            }
            $html .= '<div data-comment-id="'.$comment->id.'"  data-comment="'.$comment->post_id.'" class="sl-content comment-remove comments-box-user remove-'.$comment->post_id.'">
                        <div class="sl-item ma">
                            <div class="sl-left">
                              '.$imageHtml.'
                            </div>
                            <div class="sl-content">
                            <div class="sl-date text-muted">'.Carbon::parse($comment->created_at)->diffForHumans().'</div>
                            <p>'.$comment->comments.'</p>
                        </div>
                        </div>
                    </div>';
        }
        echo  $html ;
        exit;
    }

    public function sendLike(Request $request)
    {
        $postId = $request->post_id;
        $userId = $request->user_id;
        $query = Like::where('post_id', $postId)->where('user_id', $userId);
        $like = $query->get();
        if (count($like) > 0) {
            foreach ($like as $l) {
                if ($l->like == true) {
                    $query->update([
                    'like' => false,
                    ]);

                    return response(['like' => 'false']);
                } else {
                    $query->update([
                        'like' => true,
                    ]);

                    return response(['like' => 'true']);
                }
            }
        } else {
            $like = new Like();
            $like->post_id = $postId;
            $like->user_id = $userId;
            $like->like = true;
            $like->save();

            return response(['like' => 'new']);
        }
    }

    public function userHasLike($postId)
    {
        $data = Like::where('user_id', auth()->user()->id)->where('post_id', $postId)->where('like', true)->count();

        return $data;
    }

    public function getLikeCount($postId)
    {
        $data = Like::where('post_id', $postId)->where('like', true)->pluck('like')->count();

        return $data;
    }

    public function ajaxGetLikeCount(Request $request)
    {
        return response(['like_count' => $this->getLikeCount($request->post_id)]) ;
    }

    public function deletePost(Request $request)
    {
        $postId = $request->post_id;
        $post = Post::find($postId);
        $post->comments()->delete();
        $post->likes()->delete();
        $post->delete();

        return response(['status' => 'data deleted successfully']);
    }

    public function deleteComment(Request $request)
    {
        $commentId = $request->comment_id;
        $postId = $request->post_id;
        Comment::find($commentId)->delete();

        return response(['status' => 'comment deleted','post_id' => $postId]);
    }

    public function getCommentDeleteStatus(Request $request)
    {
        $commentId = $request->comment_id;
        $postId = $request->post_id;
        $postUserId = Post::where('id', $postId)->first()->user_id;
        $postUserCheck = auth()->user()->id == $postUserId ? true : false;
        $commentUserId = Comment::where('id', $commentId)->first()->user_id;
        $commentUserCheck = auth()->user()->id == $commentUserId ? true : false;
        if ($postUserCheck || $commentUserCheck) {
            return response(['status' => true,'comment_id' => $commentId,'post_id' => $postId]);
        } else {
            return response(['status' => false]);
        }
    }
}
