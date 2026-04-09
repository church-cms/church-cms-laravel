<?php

namespace App\Http\Controllers\Admin;

use App\Http\Resources\Page as PageResource;
use App\Http\Requests\PageUpdateRequest;
use App\Http\Requests\PageAddRequest;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Traits\LogActivity;
use App\Helpers\SiteHelper;
use App\Models\PageDetail;
use App\Traits\Common;
use App\Models\Page;
use App\Models\PageVersion;
use App\Models\User;
use Exception;
use Log;

/**
 * PagesController
 *
 * Manages static and dynamic content pages for the church website.
 * Handles page CRUD operations, page categorization, content publishing, and page detail tracking.
 * Supports page attachments for media content and interaction tracking via PageDetail model.
 * Implements role-based access control for page management.
 *
 * @package App\Http\Controllers\Admin
 * @uses LogActivity Trait for audit logging
 * @uses Common Trait for file and path utilities
 */
class PagesController extends Controller
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
        $pages = Page::where([
            ['church_id',Auth::user()->church_id],
            ['status',1],
        ])->latest()->paginate(10);
        $pages = PageResource::collection($pages);

        return $pages;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return view('/admin/page/index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('/admin/page/create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PageAddRequest $request)
    {
        //
        try
        {
            $page = new Page;

            $page->church_id        = Auth::user()->church_id;
            $page->page_name        = $request->page_name;
            $page->category_id      = $request->category;
            $page->description      = $request->description;
            $page->slug             = $request->slug ?: \Illuminate\Support\Str::slug($request->page_name);
            $page->menu_text        = $request->menu_text;
            $page->menu_order       = $request->menu_order ?? 0;
            $page->meta_title       = $request->meta_title;
            $page->meta_description = $request->meta_description;
            $page->meta_keywords    = $request->meta_keywords;
            $page->og_image         = $request->og_image;
            $page->layout_template  = $request->layout_template ?? 'left-sidebar';
            if ($request->content) {
                $page->content = json_decode($request->content, true);
            }
            $page->created_by       = Auth::id();
            $page->status           = 1;

            $file = $request->file('cover_image');
            if ($file) {
                $folder = Auth::user()->church_id . '/pages';
                $path   = $this->uploadFile($folder, $file);
                $page->cover_image = $path;
            }

            $page->save();

            $message = trans('messages.add_success_msg',['module' => 'Page']);

            $ip= $this->getRequestIP();
            $this->doActivityLog(
                $page,
                Auth::user(),
                ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT'] ],
                LOGNAME_ADD_PAGE,
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

    public function storeImage(Request $request)
    {
        if($request->hasFile('file')) {
            //get filename with extension
            $filenamewithextension = $request->file('file')->getClientOriginalName();

            //get filename without extension
            $filename = pathinfo($filenamewithextension, PATHINFO_FILENAME);

            //get file extension
            $extension = $request->file('file')->getClientOriginalExtension();

            //filename to store
            $filenametostore = $filename.'_'.time().'.'.$extension;

            //Upload File
            $file =  $request->file('file');

            $pathName = $this->uploadFile('uploads/trix',$file);

            // you can save image path below in database
            $path = asset($pathName);

            echo $path;exit;
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function showList($id)
    {
        //
        $page = Page::where('id',$id)->first();

        $array = [];

        $array['id']            = $page->id;
        $array['page_name']     = $page->page_name;
        $array['category']      = $page->category_id;
        $array['description']   = $page->description;
        $array['cover_image']   = $page->CoverImagePath;
        $array['like_count']    = $page->pageDetail()->where('like',1)->count();
        $array['unlike_count']  = $page->pageDetail()->where('dislike',1)->count();
        $array['follow_count']  = $page->pageDetail()->where('is_following',1)->count();
        $pagedetail = PageDetail::where([['user_id',Auth::id()],['page_id',$page->id]])->first();
        if($pagedetail != null)
        {
            $array['is_following']  =  $pagedetail->is_following;
            $array['like']          =  $pagedetail->like;
            $array['dislike']       =  $pagedetail->dislike;
        }

        return $array;
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
        $page = Page::where('id',$id)->first();
        $entity_id      = $page->id;
        $entity_name    = 'App\Models\Page';

        return view('/admin/page/show' , [ 'page' => $page , 'entity_id' => $entity_id , 'entity_name' => $entity_name ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function editList($id)
    {
        //
        $page = Page::where('id',$id)->first();

        $array = [];

        $array['page_name']         = $page->page_name;
        $array['category']          = $page->category_id;
        $array['description']       = $page->description;
        $array['cover_image']       = $page->CoverImagePath;
        $array['slug']              = $page->slug;
        $array['menu_text']         = $page->menu_text;
        $array['menu_order']        = $page->menu_order;
        $array['meta_title']        = $page->meta_title;
        $array['meta_description']  = $page->meta_description;
        $array['meta_keywords']     = $page->meta_keywords;
        $array['og_image']          = $page->og_image;
        $array['content']           = $page->content;       // array via $casts
        $array['layout_template']   = $page->layout_template ?? 'left-sidebar';

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
        $page = Page::where('id',$id)->first();

        return view('/admin/page/edit', [ 'page' => $page ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(PageUpdateRequest $request, $id)
    {
        //
        try
        {
            $page = Page::where('id',$id)->first();

            $page->page_name         = $request->page_name;
            $page->category_id       = $request->category;
            $page->description       = $request->description;
            $page->created_by        = Auth::id();
            $page->slug              = $request->slug ?: \Illuminate\Support\Str::slug($request->page_name) . '-' . $page->id;
            $page->menu_text         = $request->menu_text;
            $page->menu_order        = $request->menu_order ?? 0;
            $page->meta_title        = $request->meta_title;
            $page->meta_description  = $request->meta_description;
            $page->meta_keywords     = $request->meta_keywords;
            $page->og_image          = $request->og_image;
            $page->layout_template   = $request->layout_template ?? 'left-sidebar';
            if ($request->content) {
                $page->content = json_decode($request->content, true);
            }

            $file = $request->file('cover_image');
            if($file)
            {
                $folder = Auth::user()->church_id . '/pages';
                $path   = $this->uploadFile($folder,$file);
                $page->cover_image = $path;
            }

            $page->save();

            // Auto-save version on every update
            $nextVersion = (PageVersion::where('page_id', $id)->max('version_number') ?? 0) + 1;
            PageVersion::create([
                'page_id'         => $page->id,
                'version_number'  => $nextVersion,
                'content'         => $page->content ? json_encode($page->content) : null,
                'description'     => $page->description,
                'layout_template' => $page->layout_template,
                'saved_by'        => Auth::id(),
            ]);

            $message = trans('messages.update_success_msg',['module' => 'Page']);

            $ip= $this->getRequestIP();
            $this->doActivityLog(
                $page,
                Auth::user(),
                ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT'] ],
                LOGNAME_EDIT_PAGE,
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
            $page = Page::where('id',$id)->first();
            if(Gate::allows('page',$page))
            {
                $page->delete();

                $message=trans('messages.delete_success_msg',['module' => 'Page']);


                $ip= $this->getRequestIP();
                $this->doActivityLog(
                    $page,
                    Auth::user(),
                    ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT'] ],
                    LOGNAME_DELETE_PAGE,
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

    public function versions($id)
    {
        $versions = PageVersion::where('page_id', $id)
            ->orderByDesc('version_number')
            ->get(['id', 'version_number', 'saved_by', 'created_at'])
            ->map(function ($v) {
                return [
                    'id'             => $v->id,
                    'version_number' => $v->version_number,
                    'saved_by'       => optional(User::find($v->saved_by))->name ?? 'Unknown',
                    'created_at'     => $v->created_at ? $v->created_at->format('M d, Y H:i') : '',
                ];
            });

        return response()->json($versions);
    }

    public function revertVersion(Request $request, $id, $versionId)
    {
        try {
            $page    = Page::where([['church_id', Auth::user()->church_id], ['id', $id]])->firstOrFail();
            $version = PageVersion::where(['page_id' => $id, 'id' => $versionId])->firstOrFail();

            $page->content         = $version->content ? json_decode($version->content, true) : null;
            $page->description     = $version->description;
            $page->layout_template = $version->layout_template ?? $page->layout_template;
            $page->save();

            $next = (PageVersion::where('page_id', $id)->max('version_number') ?? 0) + 1;
            PageVersion::create([
                'page_id'         => $page->id,
                'version_number'  => $next,
                'content'         => $version->content,
                'description'     => $page->description,
                'layout_template' => $page->layout_template,
                'saved_by'        => Auth::id(),
            ]);

            return ['success' => "Reverted to version {$version->version_number}"];
        } catch (Exception $e) {
            Log::info($e->getMessage());
        }
    }
}
