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
        tags: ['Groups'],
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

    #[OA\Get(
        path: '/api/v1/grouppost/list/{group_id}',
        tags: ['Groups'],
        summary: 'List posts for a group',
        description: 'Returns a paginated list of posts for the given group. Only accessible to authenticated members.',
        parameters: [
            new OA\Parameter(
                name: 'group_id',
                in: 'path',
                required: true,
                description: 'The ID of the group',
                schema: new OA\Schema(type: 'integer', example: 1)
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Paginated group post list',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(
                            property: 'data',
                            type: 'array',
                            items: new OA\Items(
                                properties: [
                                    new OA\Property(property: 'id', type: 'integer'),
                                    new OA\Property(property: 'group_id', type: 'integer'),
                                    new OA\Property(property: 'user_id', type: 'integer'),
                                    new OA\Property(property: 'title', type: 'string', nullable: true),
                                    new OA\Property(property: 'message', type: 'string'),
                                    new OA\Property(property: 'attachments', type: 'string', nullable: true),
                                    new OA\Property(property: 'attachment_type', type: 'string', enum: ['image', 'video', 'url'], nullable: true),
                                    new OA\Property(property: 'status', type: 'string'),
                                    new OA\Property(property: 'created_at', type: 'string', format: 'date-time'),
                                ]
                            )
                        ),
                        new OA\Property(property: 'current_page', type: 'integer'),
                        new OA\Property(property: 'per_page', type: 'integer'),
                        new OA\Property(property: 'total', type: 'integer'),
                    ]
                )
            ),
            new OA\Response(response: 401, description: 'Unauthenticated'),
        ],
        security: [['sanctum' => []]]
    )]
    public function postindex($group_id)
    {


        $messages = GroupPost::where([['group_id', $group_id], ['church_id', Auth::user()->church_id]])->orderBy('id', 'DESC')->paginate(15);


        $grouppost = GroupPostResource::collection($messages);

        return $grouppost;
    }

    #[OA\Post(
        path: '/api/v1/group/sendmessage/{group_id}',
        tags: ['Groups'],
        summary: 'Send a message/post to a group',
        description: 'Creates a new post in the specified group. The authenticated user must be a member of the group. Supports optional image attachment (multipart/form-data).',
        parameters: [
            new OA\Parameter(
                name: 'group_id',
                in: 'path',
                required: true,
                description: 'The ID of the group to post in',
                schema: new OA\Schema(type: 'integer', example: 1)
            )
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\MediaType(
                mediaType: 'multipart/form-data',
                schema: new OA\Schema(
                    required: ['message'],
                    properties: [
                        new OA\Property(
                            property: 'message',
                            type: 'string',
                            maxLength: 1000,
                            description: 'Post message content',
                            example: 'Hello group members!'
                        ),
                        new OA\Property(
                            property: 'attachments',
                            type: 'string',
                            format: 'binary',
                            description: 'Optional image attachment (jpeg, png, jpg, gif, webp — max 20 MB)'
                        ),
                    ]
                )
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Post created successfully',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'success', type: 'boolean', example: true),
                        new OA\Property(property: 'message', type: 'string', example: 'Post created successfully.'),
                    ]
                )
            ),
            new OA\Response(
                response: 403,
                description: 'Not a member of this group',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(
                            property: 'errors',
                            type: 'object',
                            properties: [
                                new OA\Property(
                                    property: 'auth',
                                    type: 'array',
                                    items: new OA\Items(type: 'string', example: 'You are not a member of this group.')
                                )
                            ]
                        )
                    ]
                )
            ),
            new OA\Response(response: 422, description: 'Validation error — message is required or attachment is invalid'),
            new OA\Response(response: 401, description: 'Unauthenticated'),
        ],
        security: [['sanctum' => []]]
    )]
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
