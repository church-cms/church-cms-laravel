<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\API\GroupLink as GroupLinkResource;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\GroupLink;
use App\Models\User;
use OpenApi\Attributes as OA;
use App\Models\GroupPost;
use App\Http\Resources\API\GroupPost as GroupPostResource;

/**
 * GroupsController
 *
 * Provides group listings and group information via API.
 * Returns user group memberships and group details.
 *
 * @package App\Http\Controllers\Api
 */
class GroupsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    #[OA\Get(
        path: '/api/v1/groups/list',
        summary: "List the current user's group memberships",
        responses: [
            new OA\Response(
                response: 200,
                ref: '#/components/responses/GroupLinkResponse'
            )
        ],
        security: [['sanctum' => []]]
    )]
    public function index()
    {
        //
        $user = User::where('name', Auth::user()->name)->first();
        $grouplinks = GroupLink::where('user_id', $user->id)->get();

        $group = GroupLinkResource::collection($grouplinks);

        return $group;
    }

    public function postindex($group_id)
    {

     
        $messages = GroupPost::where([['group_id', $group_id], ['church_id', Auth::user()->church_id]])->orderBy('id', 'DESC')->paginate(15);
        

        $grouppost = GroupPostResource::collection($messages);

        return $grouppost;
    }

    public function sendGroupMessage(Request $request, $group_id)
    {

        //dd($request);

        // Validate
        $request->validate([
            'message'     => 'required|string|max:1000',
            //'title'       => 'nullable|string|max:100',
            'attachments' =>  'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:20480',
        ]);

        try {
            $user = auth()->user();

            // Verify membership
            $groupLink = GroupLink::where('user_id', $user->id)
                ->where('group_id', $group_id)
                ->first();

            if (!$groupLink) {
                return response()->json([
                    'errors' => ['auth' => ['You are not a member of this group.']],
                ], 403);
            }

            // ── Handle file attachment ────────────────────────────
            $attachmentPath = null;
            $attachmentType = null;
            $attachmentType = 'image';

            if ($request->hasFile('attachments') && $request->file('attachments')->isValid()) {
                $file   = $request->file('attachments');
                $mime   = $file->getMimeType();
                $folder = 'group_posts/' . $group_id;

                // Store file in storage/public/group_posts/{group_id}/
                $attachmentPath = $file->store($folder, 'public');

                // Determine attachment_type for the enum column
                if (str_starts_with($mime, 'image/')) {
                    $attachmentType = 'image';
                } elseif (str_starts_with($mime, 'video/')) {
                    $attachmentType = 'video';
                } else {
                    $attachmentType = 'url';   // pdf, doc, csv, etc.
                }
            }

            // ── Create group post ─────────────────────────────────
            GroupPost::create([
                'church_id'       => $user->church_id,
                'user_id'         => $user->id,
                'group_id'        => $group_id,
                'title'           => $request->input('subject'),
                'message'         => $request->input('message'),
                'attachments'     => $attachmentPath,
                'attachment_type' => $attachmentType,
                'status'          => 'active',
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Post created successfully.',
            ], 200);
        } catch (\Exception $e) {

            dd($e->getMessage());
            \Log::error('sendGroupMessage error: ' . $e->getMessage());
            return response()->json([
                'errors' => ['server' => ['Something went wrong. Please try again.']],
            ], 500);
        }
    }
}
