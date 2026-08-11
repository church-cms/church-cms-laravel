<?php

namespace App\Http\Controllers\Admin;

use App\Http\Resources\BibleEnglishVerses as BibleEnglishVersesResource;
use App\Http\Resources\BibleTamilVerses as BibleTamilVersesResource;
use App\Http\Resources\BibleEnglish as BibleEnglishResource;
use App\Http\Resources\BibleTamil as BibleTamilResource;
use App\Http\Resources\Quote as QuoteResource;
use App\Http\Requests\QuoteRescheduleRequest;
use App\Http\Requests\QuoteUpdateDateRequest;
use App\Http\Requests\QuoteAddDateRequest;
use App\Http\Requests\QuoteUpdateRequest;
use App\Http\Requests\QuoteAddRequest;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Request;
use App\Traits\LogActivity;
use App\Models\BibleVerse;
use App\Models\BibleBook;
use App\Events\PushEvent;
use App\Traits\Common;
use App\Models\Quote;
use Exception;
use Log;

/**
 * QuotesController
 *
 * Manages inspirational quotes and daily devotionals for the church.
 * Handles quote creation, scheduling, updates, and Bible verse associations.
 * Supports quote publishing by date and multi-language (English/Tamil) scripture references.
 * Integrates with push notifications for daily quote delivery.
 *
 * @package App\Http\Controllers\Admin
 * @uses LogActivity Trait for audit logging
 * @uses Common Trait for helper functions
 */
class QuotesController extends Controller
{
    //
    use LogActivity;
    use Common;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function list(Request $request, $type)
    {
        //
        $quotes  = Quote::where('church_id',Auth::user()->church_id);
        $today = date('Y-m-d H:i:s');

        if($type != '')
        {
            if($type === 'upcoming')
            {
                $quotes = $quotes->where('publish_on','>',$today);
            }
            elseif($type === 'published')
            {
                $quotes = $quotes->where('published_at','<=',$today);
            }
        }
        else
        {
            $quotes = $quotes->where('publish_on','<=',$today);
        }
        if(\Request::getQueryString() != null)
        {
            if($request->search != null)
            {
                $quotes = $quotes->where('text','LIKE','%'.$request->search.'%')->orWhere('tamil_quotes','LIKE','%'.$request->search.'%')->orWhere('english_quotes','LIKE','%'.$request->search.'%');
            }
        }

        $quotes = $quotes->orderBy('publish_on')->get();

        $quotes = QuoteResource::collection($quotes);

        return $quotes;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return view('/admin/quotes/index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('/admin/quotes/create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function validation(QuoteAddDateRequest $request)
    {
        //
        try
        {
            //
        }
        catch(Exception $e)
        {
            Log::info($e->getMessage());

        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(QuoteAddRequest $request)
    {
        //
        try
        {
            $quote              = new Quote;

            $quote->church_id   = Auth::user()->church_id;
            $quote->user_id     = Auth::id();
            if($request->tab === 'images')
            {
                $image = $request->file('image');
                if($image)
                {
                    $quote->image = $this->uploadFile(Auth::user()->church_id.'/quotes',$image);
                }
            }
            elseif ($request->tab === 'text')
            {
                $quote->text    = $request->text;
            }
            elseif($request->tab === 'bible')
            {
                $quote->tamil_quotes    = $request->tamil;
                $quote->english_quotes  = $request->english;
            }
            $quote->publish_on  = date('Y-m-d H:i:s',strtotime($request->publish_on));

            $quote->save();

            $message = 'Quotes Added Successfully';

            $ip= $this->getRequestIP();
            $this->doActivityLog(
                $quote,
                Auth::user(),
                ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT'] ],
                LOGNAME_ADD_QUOTE,
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
        $quote  = Quote::where('id',$id)->get();

        $quote  = QuoteResource::collection($quote);

        return $quote;
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
        $quote  = Quote::where('id',$id)->first();
        $array = [];

        $array['id']        =   $quote->id;
        $array['image']     =   $quote->image === null ? null:$quote->ImagePath;
        if($quote->image != null)
        {
            $array['tab']   = 'images';
        }
        $array['text']    =   $quote->text;
        if($quote->text != null)
        {
            $array['tab']   =   'text';
        }
        $array['tamil']     =   $quote->tamil_quotes;
        $array['english']   =   $quote->english_quotes;
        if( ($quote->tamil_quotes != null) && ($quote->english_quotes != null) )
        {
            $array['tab']   =   'bible';
        }
        $array['publish_on']  =   date('d-m-Y H:i:s',strtotime($quote->publish_on));

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
        $quote  = Quote::where('id',$id)->first();
        if(date('Y-m-d H:i:s',strtotime($quote->publish_on)) > date('Y-m-d H:i:s'))
        {
            return view('/admin/quotes/edit',['quote' => $quote]);
        }
        else
        {
            abort(403);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function editValidation(QuoteUpdateDateRequest $request)
    {
        //
        try
        {
            //
        }
        catch(Exception $e)
        {
            Log::info($e->getMessage());

        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(QuoteUpdateRequest $request, $id)
    {
        //
        try
        {
            $quote  = Quote::where('id',$id)->first();

            if($request->tab === 'images')
            {
                $image = $request->file('image');
                if($image)
                {
                    $quote->image = $this->uploadFile(Auth::user()->church_id.'/quotes',$image);
                }
                else
                {
                    $quote->image = $quote->image;
                }
            }
            elseif ($request->tab === 'text')
            {
                $quote->text    = $request->text;
            }
            elseif($request->tab === 'bible')
            {
                $quote->tamil_quotes    = $request->tamil;
                $quote->english_quotes  = $request->english;
            }
            $quote->publish_on = date('Y-m-d H:i:s',strtotime($request->publish_on));

            $quote->save();

            $message = 'Quotes Updated Successfully';

            $ip= $this->getRequestIP();
            $this->doActivityLog(
                $quote,
                Auth::user(),
                ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT'] ],
                LOGNAME_EDIT_QUOTE,
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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function reschedule(QuoteRescheduleRequest $request,$id)
    {
        //
        try
        {
            $old_quote = Quote::where('id',$id)->first();

            $quote                  = new Quote;

            $quote->church_id       = Auth::user()->church_id;
            $quote->user_id         = Auth::id();
            $quote->image           = $old_quote->image;
            $quote->text            = $old_quote->text;
            $quote->tamil_quotes    = $old_quote->tamil_quotes;
            $quote->english_quotes  = $old_quote->english_quotes;
            $quote->publish_on      = date('Y-m-d H:i:s',strtotime($request->reschedule_date));

            $quote->save();

            $message = 'Quotes Rescheduled Successfully';

            $ip= $this->getRequestIP();
            $this->doActivityLog(
                $quote,
                Auth::user(),
                ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT'] ],
                LOGNAME_RESCHEDULE_QUOTE,
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
            $quote = Quote::where('id',$id)->first();
            if(Gate::allows('quote',$quote))
            {
                $quote->delete();

                $message = 'Quote Deleted Successfully';

                $ip= $this->getRequestIP();
                $this->doActivityLog(
                    $quote,
                    Auth::user(),
                    ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT'] ],
                    LOGNAME_DELETE_QUOTE,
                    $message
                );
                $res['success'] = $message;
                return $res;
            }
            else
            {
                abort(403);
            }
        }
        catch(Exception $e)
        {
            Log::info($e->getMessage());

        }
    }

    public function books($type)
    {
        if($type==='tamil'){
            $tamilVerse = BibleBook::select('book_id','tamil_book','chapter_count')->get();
            $bible = BibleTamilResource::collection($tamilVerse);
        }else{
            $englishVerse = BibleBook::select('book_id','english_book','chapter_count')->get();
            $bible = BibleEnglishResource::collection($englishVerse);
        }

        return $bible;
    }

    public function bookByid($type,$book_id)
    {
        $chapterCount = BibleBook::select('chapter_count')->where('book_id',$book_id)->first();
        $verse = array();
        if(!empty($chapterCount)){
            $chapter_count = $chapterCount->chapter_count;
            $verse['chapter_count'] = $chapter_count;
            for ($i=1; $i <= $chapter_count; $i++) {
                $chapter_id = $i;
                if($type==='tamil'){
                    $tamilVerse = BibleVerse::select('id','tamil_verse','book_id','chapter_id','verse_id')->where('chapter_id',$chapter_id)->where('book_id',$book_id)->get();
                    $bibleVerse = BibleTamilVersesResource::collection($tamilVerse);
                }else{
                    $englishVerse = BibleVerse::select('id','english_verse','book_id','chapter_id','verse_id')->where('chapter_id',$chapter_id)->where('book_id',$book_id)->get();
                    $bibleVerse = BibleEnglishVersesResource::collection($englishVerse);
                }

                 $verse[$chapter_id] = $bibleVerse;
            }
        }

        return $verse;
    }
}
