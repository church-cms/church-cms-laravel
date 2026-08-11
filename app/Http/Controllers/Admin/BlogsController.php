<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

/**
 * BlogsController
 *
 * Manages blog posts and articles within the church CMS.
 * Provides CRUD operations for creating, viewing, and managing blog content.
 *
 * @package App\Http\Controllers\Admin
 */
class BlogsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return view('/admin/blogs/index');
    }
}
