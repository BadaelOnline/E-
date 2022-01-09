<?php

namespace App\Service\Comment;

use App\Http\Requests\Comment\CommentRequest;
use App\Models\Comment\Comment;
use App\Models\Offer\Offer;
use App\Traits\GeneralTrait;

class CommentService
{
    use GeneralTrait;
     private $CommentModel;
     private $OfferModel;

     public function __construct(Comment $comment ,Offer $offer)
     {
         $this->CommentModel=$comment;
         $this->OfferModel=$offer;
     }
     public function get()
     {
         try{
            $comment=  $this->CommentModel::paginate(5);
             return $this->returnData('Comment',$comment,'done');
         }
         catch (\Exception $ex)
         {
             return $this->returnError($ex->getCode(),$ex->getMessage());
         }
     }
     public function getById($id)
     {
         try{
           $comment=$this->CommentModel::find($id);
           if(!$id)
           {
               return $this->returnError('400','not found this comment');
           }
           else{
               return $this->returnData('comment',$comment,'done');
           }
         }
         catch (\Exception $ex){
            return  $this->returnError($ex->getCode(),$ex->getMessage());
         }
     }
  public function create(CommentRequest $request)
     {
         try{
             $comment=new $this->CommentModel();

             $comment->user_id          =$request->user_id;
             $comment->offer_id         =$request->offer_id;
             $comment->comment          =$request->comment;
             $comment->is_active        =$request->is_active;
             $comment->is_approved      =$request->is_approved;

             $result=$comment->save();

             if(!$result){
                 return $this->returnError('400','saving failed');
             }
             else{
              return   $this->returnData('Comment',$comment,'saving done');
             }
         }
         catch (\Exception $ex){
            return  $this->returnError($ex->getCode(),$ex->getMessage());
         }
     }
  public function update(CommentRequest $request,$id)
     {
         try{
             $comment=$this->CommentModel::find($id);
             if(!$comment){return $this->returnError('400','not found this comment');}

             $comment->user_id          =$request->user_id;
             $comment->offer_id         =$request->offer_id;
             $comment->comment          =$request->comment;
             $comment->is_active        =$request->is_active;
             $comment->is_approved      =$request->is_approved;

             $result= $comment->save();

             if(!$result){
                 return $this->returnError('400','updating failed');
             }
             else{
                return  $this->returnData('Comment',$comment,'done');
             }
         }
         catch (\Exception $ex){
            return  $this->returnError($ex->getCode(),$ex->getMessage());
         }
     }

  public function trash($id)
     {
         try{
             $comment=$this->CommentModel::find($id);
             if(!$comment)
             {
                 return  $this->returnError('400','not found this Comment');
             }
             else
             {
                 $comment->is_active=0;
                 $comment->save();
                 return $this->returnData('comment',$comment,'this comment is trashed now');
             }
         }
         catch (\Exception $ex){
            return  $this->returnError($ex->getCode(),$ex->getMessage());
         }
     }

  public function gettrashed()
     {
         try{
             $comment=$this->CommentModel::NotActive();
             return $this->returnData('comment',$comment,'done');
         }
         catch (\Exception $ex){
            return  $this->returnError($ex->getCode(),$ex->getMessage());
         }
     }
  public function restoreTrashe($id)
     {
         try{
             $comment=$this->CommentModel::find($id);
             if(!$comment)
             {
                 return  $this->returnError('400','not found this Comment');
             }
             else
             {
                 $comment->is_active=1;
                 $comment->save();
                 return $this->returnData('comment',$comment,'this comment is trashed now');
             }
         }
         catch (\Exception $ex){
            return  $this->returnError($ex->getCode(),$ex->getMessage());
         }
     }
     public function delete($id)
     {
         try{
             $comment=$this->CommentModel::find($id);
             if(!$comment)
             {
                 return $this->returnError('400','not found this comment');
             }
             elseif($comment->is_active==0){
                 $comment->delete();
                 return $this->returnData('comment',$comment,'this comment is deleted now');
             }
             else{
                 return $this->returnError('400','this comment can not deleted now');
             }
         }

         catch (\Exception $ex){
            return  $this->returnError($ex->getCode(),$ex->getMessage());
         }
     }

    public function getOfferByCommentId($comment_id)
    {
        try{
            $comment=$this->CommentModel::find($comment_id);
            if(!$comment)
            {return $this->returnError('400','not found this comment');}
            else{
                $comment=$this->CommentModel->with('Offer')->find($comment_id);
                return $this->returnData('comment',$comment,'done');
            }
        }
        catch (\Exception $ex)
        {
            return $this->returnError($ex->getCode(),$ex->getMessage());
        }
    }
    public function getcomments($offer_id)
    {
        try{
            $comment=$this->OfferModel::find($offer_id);
            if(!$comment)
            {return $this->returnError('400','not found this Offer');}
            else{
                $comment=$this->OfferModel->with('Comment')->find($offer_id);
                return $this->returnData('comment',$comment,'done');
            }
        }
        catch (\Exception $ex)
        {
            return $this->returnError($ex->getCode(),$ex->getMessage());
        }
    }

}
