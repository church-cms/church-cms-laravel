<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Http\Requests\SendMailRequest;
use App\Traits\SendMessageProcess;
use App\Models\GroupLink;
use App\Models\Group;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\SendMail;
use App\Models\GroupPost;

class MemberController extends Controller
{
    use SendMessageProcess;

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function groupList()
    {
        $user = auth()->user();

        $group_link = $user->groupLink;

        return view('member.mygroup', ['group_link' => $group_link]);
    }

    public function groupDetails($group_id)
    {
        $user = auth()->user();

        $group_link = GroupLink::with(['group.groupCategory'])
            ->where([['user_id', $user->id], ['group_id', $group_id]])
            ->first();

        if ($group_link->role == 'group_admin') {

           $messages =GroupPost::where([['group_id', $group_id], ['church_id', $group_link->church_id]])->paginate(15);

            //$messages = SendMail::where([['entity_id', $group_id], ['entity_name', 'App\Models\Group'], ['church_id', $group_link->church_id]])
            //     ->orderBy('executed_at', 'desc')
            //     ->paginate(15);
        } else {
            // $messages = SendMail::where([['entity_id', $group_id], ['entity_name', 'App\Models\Group'], ['church_id', $group_link->church_id], ['user_id', $user->id]])
            //     ->orderBy('executed_at', 'desc')
            //     ->paginate(15);
          $messages =GroupPost::where([['group_id', $group_id], ['church_id', $group_link->church_id]])->paginate(15);

        }
        $grouplinks = GroupLink::with(['user.userprofile'])
            ->where([['group_id', $group_id], ['church_id', $group_link->church_id]])
            ->paginate(12);

        // Eager-load sender userprofile for message feed
        $messages->load('user.userprofile');

        return view('member.mygroup_details', ['grouplinks' => $grouplinks, 'grouplist' => $group_link, 'messages' => $messages]);
    }

    /**
     * Remove the authenticated user's GroupLink for the given group.
     * Only the group admin can perform this action from the member portal.
     */
    public function removeGroup($group_id)
    {
        $user = auth()->user();

        $groupLink = GroupLink::where('user_id', $user->id)
            ->where('group_id', $group_id)
            ->first();

        if (!$groupLink) {
            return redirect()->route('member.mygrouplist')
                ->with('error', 'Group not found or you are not a member.');
        }



        $groupLink->delete(); // soft delete

        return redirect()->route('member.mygrouplist')
            ->with('success', 'You have been removed from the group successfully.');
    }

    /**
     * Send a message to all members of a group.
     * Only the group admin may trigger this.
     */
    public function sendGroupMessages(SendMailRequest $request, $group_id)
    {
        try {
            $user = auth()->user();

            // Verify the current user is a group admin for this group
            $groupLink = GroupLink::where('user_id', $user->id)
                ->where('group_id', $group_id)
                ->where('role', 'group_admin')
                ->first();

            if (!$groupLink) {
                return response()->json(['errors' => ['auth' => ['You do not have permission to send messages to this group.']]], 403);
            }

            $group      = Group::findOrFail($group_id);
            $members    = GroupLink::where('group_id', $group_id)
                ->where('church_id', $user->church_id)
                ->get();
            $batch_id   = (string) Str::uuid();

            foreach ($members as $member) {
                $recipient = User::find($member->user_id);
                if (!$recipient) continue;

                $request->entity_id   = $group_id;
                $request->entity_name = get_class($group);

                $this->sendMessage($request, $user->church_id, $user->email, $recipient, $user, $batch_id);
            }

            return response()->json(['success' => 'Message sent successfully to all group members.']);
        } catch (\Exception $e) {
            \Log::error('sendGroupMessage error: ' . $e->getMessage());
            return response()->json(['errors' => ['server' => ['Something went wrong. Please try again.']]], 500);
        }
    }

    /**
     * Create a group post (message + optional image/video/file attachment).
     * Any group member can post; attachment is uploaded to storage/group_posts/{group_id}/
     */
    public function sendGroupMessage(Request $request, $group_id)
    {


            //dd($request);

        // Validate
        $request->validate([
            'message'     => 'required|string|max:1000',
            //'title'       => 'nullable|string|max:100',
            'attachments' => 'nullable|file|max:20480', // 20 MB
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
                'success' => 'Post created successfully.',
            ]);

        } catch (\Exception $e) {
            \Log::error('sendGroupMessage error: ' . $e->getMessage());
            return response()->json([
                'errors' => ['server' => ['Something went wrong. Please try again.']],
            ], 500);
        }
    }
}
