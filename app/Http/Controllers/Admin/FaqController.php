<?php

namespace App\Http\Controllers\Admin;

use App\Http\Resources\FaqCategory as FaqCategoryResource;
use App\Http\Resources\FaqList as FaqListResource;
use App\Http\Requests\FaqUpdateRequest;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\FaqAddRequest;
use Illuminate\Http\Request;
use App\Models\FaqCategory;
use App\Traits\LogActivity;
use App\Traits\Common;
use App\Models\Faq;
use Exception;
use Log;

class FaqController extends Controller
{
    use LogActivity;
    use Common;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function list()
    {
        //
        $faq  = Faq::where([['church_id',Auth::user()->church_id],['status', 1]])->paginate(6);

        $faq = FaqListResource::collection($faq);

        return $faq;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return view('/admin/faq/index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function createList()
    {
        //
        $faqcategory = FaqCategory::where([['church_id',Auth::user()->church_id],['status', 1]])->get();

        $faqcategory = FaqCategoryResource::collection($faqcategory);

        return $faqcategory;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('/admin/faq/create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(FaqAddRequest $request)
    {
        //
        try
        {
            $faq = new Faq;

            $faq->church_id         =   Auth::user()->church_id;
            $faq->faq_category_id   =   $request->category;
            $faq->question          =   $request->question;
            $faq->answer            =   $request->answer;
            $faq->order             =   $request->order;
            $faq->status            =   1;

            $faq->save();

            $message = 'FAQ Added Successfully';

            $ip= $this->getRequestIP();
            $this->doActivityLog(
                $faq,
                Auth::user(),
                ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT'] ],
                LOGNAME_ADD_FAQ,
                $message
            ); 
 
            $res['success'] = $message;
            return $res;
        }
        catch(Exception $e)
        {
            Log::info($e->getMessage());

        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        $faq = Faq::where('id',$id)->first();

        return view('/admin/faq/show', ['faq' => $faq]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function editList($id)
    {
        //
        $faq = Faq::where('id',$id)->first();

        $faqcategory = FaqCategory::where([['church_id',Auth::user()->church_id],['status', 1]])->get();

        $array = [];

        $array['faq_category_id']   =   $faq->faq_category_id;
        $array['question']          =   $faq->question;
        $array['answer']            =   $faq->answer;
        $array['order']             =   $faq->order;
        $array['categorylist']      =   FaqCategoryResource::collection($faqcategory);

        return $array;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        $faq = Faq::where('id',$id)->first();

        return view('/admin/faq/edit', ['faq' => $faq]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(FaqUpdateRequest $request, $id)
    {
        //
        try
        {
            $faq = Faq::where('id',$id)->first();

            $faq->faq_category_id   =   $request->category;
            $faq->question          =   $request->question;
            $faq->answer            =   $request->answer;
            $faq->order             =   $request->order;
            $faq->status            =   1;

            $faq->save();

            $message = 'FAQ Updated Successfully';

            $ip= $this->getRequestIP();
            $this->doActivityLog(
                $faq,
                Auth::user(),
                ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT'] ],
                LOGNAME_EDIT_FAQ,
                $message
            ); 
 
            $res['success'] = $message;
            return $res;
        }
        catch(Exception $e)
        {
            Log::info($e->getMessage());

        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        try
        {
            $faq = Faq::where('id',$id)->first();

            $faq->delete();

            $message = 'FAQ Deleted Successfully';

            $ip= $this->getRequestIP();
            $this->doActivityLog(
                $faq,
                Auth::user(),
                ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT'] ],
                LOGNAME_DELETE_FAQ,
                $message
            ); 
 
            $res['success'] = $message;
            return $res;
        }
        catch(Exception $e)
        {
            Log::info($e->getMessage());

        }
    }
}