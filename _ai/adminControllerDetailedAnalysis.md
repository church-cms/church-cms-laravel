# DETAILED ADMIN CONTROLLER ANALYSIS

## ANALYSIS OVERVIEW
Total Admin Controllers: 75

## INDIVIDUAL CONTROLLER DETAILS

### ActivityLogController.php
**Path:** app/Http/Controllers/Admin/ActivityLogController.php
- **Class-level PHPDoc:** ✗ No
- **Methods:** 1 (1 documented)
- **Empty/stub methods:** 0
- **Unused imports:** 1
- **Lines of code:** 22
- **Commented code blocks:** 0
- **Methods:** index
- **Unused imports:** Request

### AttachSubscriberController.php
**Path:** app/Http/Controllers/Admin/AttachSubscriberController.php
- **Class-level PHPDoc:** ✗ No
- **Methods:** 6 (0 documented)
- **Empty/stub methods:** 0
- **Unused imports:** 7
- **Lines of code:** 263
- **Commented code blocks:** 3
- **Issues:** debugging (dd/dump), loose comparisons
- **Unused imports:** Mailinglist as MailinglistResource, MaillistSubscriberRequest, ImportMailingListRequest, +4 more

### AttendancesController.php
**Path:** app/Http/Controllers/Admin/AttendancesController.php
- **Class-level PHPDoc:** ✗ No
- **Methods:** 6 (3 documented)
- **Empty/stub methods:** 0
- **Unused imports:** 8
- **Lines of code:** 169
- **Commented code blocks:** 5
- **Issues:** debugging (dd/dump)
- **Unused imports:** ImportMemberRequest, SummaryImport, Request, +5 more

### AudioController.php
**Path:** app/Http/Controllers/Admin/AudioController.php
- **Class-level PHPDoc:** ✗ No
- **Methods:** 4 (3 documented)
- **Empty/stub methods:** 0
- **Unused imports:** 6
- **Lines of code:** 131
- **Commented code blocks:** 5
- **Issues:** debugging (dd/dump), loose comparisons
- **Methods:** create, storeAudio, audiostore, store
- **Unused imports:** AudioRequest, Request, LogActivity, +3 more

### BirthdayController.php
**Path:** app/Http/Controllers/Admin/BirthdayController.php
- **Class-level PHPDoc:** ✗ No
- **Methods:** 8 (0 documented)
- **Empty/stub methods:** 0
- **Unused imports:** 10
- **Lines of code:** 181
- **Commented code blocks:** 4
- **Issues:** debugging (dd/dump)
- **Unused imports:** AnniversaryUser as AnniversaryUserResource, Anniversary as AnniversaryResource, Birthday as BirthdayResource, +7 more

### BlogsController.php
**Path:** app/Http/Controllers/Admin/BlogsController.php
- **Class-level PHPDoc:** ✗ No
- **Methods:** 1 (1 documented)
- **Empty/stub methods:** 0
- **Unused imports:** 1
- **Lines of code:** 19
- **Commented code blocks:** 0
- **Methods:** index
- **Unused imports:** Request

### BotmanMasterController.php
**Path:** app/Http/Controllers/Admin/BotmanMasterController.php
- **Class-level PHPDoc:** ✗ No
- **Methods:** 13 (8 documented)
- **Empty/stub methods:** 0
- **Unused imports:** 6
- **Lines of code:** 368
- **Commented code blocks:** 0
- **Issues:** loose comparisons
- **Unused imports:** Request, BotMan, StorageClient, +3 more

### BulletinsController.php
**Path:** app/Http/Controllers/Admin/BulletinsController.php
- **Class-level PHPDoc:** ✗ No
- **Methods:** 7 (5 documented)
- **Empty/stub methods:** 0
- **Unused imports:** 5
- **Lines of code:** 251
- **Commented code blocks:** 6
- **Issues:** debugging (dd/dump), loose comparisons
- **Unused imports:** Bulletin as BulletinResource, BulletinRequest, LogActivity, +2 more

### CampaignController.php
**Path:** app/Http/Controllers/Admin/CampaignController.php
- **Class-level PHPDoc:** ✗ No
- **Methods:** 9 (1 documented)
- **Empty/stub methods:** 0
- **Unused imports:** 7
- **Lines of code:** 196
- **Commented code blocks:** 3
- **Issues:** debugging (dd/dump), loose comparisons
- **Unused imports:** Campaign as CampaignResource, CampaignRequest, Request, +4 more

### CampaignEmailController.php
**Path:** app/Http/Controllers/Admin/CampaignEmailController.php
- **Class-level PHPDoc:** ✗ No
- **Methods:** 7 (1 documented)
- **Empty/stub methods:** 0
- **Unused imports:** 7
- **Lines of code:** 187
- **Commented code blocks:** 3
- **Issues:** debugging (dd/dump)
- **Unused imports:** Showcampaign as ShowcampaignResource, Email as EmailResource, CampaignEmailRequest, +4 more

### ChurchDetailsController.php
**Path:** app/Http/Controllers/Admin/ChurchDetailsController.php
- **Class-level PHPDoc:** ✗ No
- **Methods:** 3 (2 documented)
- **Empty/stub methods:** 0
- **Unused imports:** 5
- **Lines of code:** 126
- **Commented code blocks:** 3
- **Issues:** debugging (dd/dump), loose comparisons
- **Methods:** index, edit, update
- **Unused imports:** DetailRequest, Request, LogActivity, +2 more

### ContactController.php
**Path:** app/Http/Controllers/Admin/ContactController.php
- **Class-level PHPDoc:** ✗ No
- **Methods:** 2 (2 documented)
- **Empty/stub methods:** 0
- **Unused imports:** 0
- **Lines of code:** 45
- **Commented code blocks:** 2
- **Methods:** index, show

### DashboardController.php
**Path:** app/Http/Controllers/Admin/DashboardController.php
- **Class-level PHPDoc:** ✗ No
- **Methods:** 4 (1 documented)
- **Empty/stub methods:** 0
- **Unused imports:** 3
- **Lines of code:** 64
- **Commented code blocks:** 2
- **Methods:** index, event, sermon, absent
- **Unused imports:** ShowSermonLink as ShowSermonLinkResource, ShowEvent as ShowEventResource, Dashboard

### EmailController.php
**Path:** app/Http/Controllers/Admin/EmailController.php
- **Class-level PHPDoc:** ✗ No
- **Methods:** 9 (1 documented)
- **Empty/stub methods:** 0
- **Unused imports:** 6
- **Lines of code:** 175
- **Commented code blocks:** 3
- **Issues:** debugging (dd/dump)
- **Unused imports:** Email as EmailResource, EmailRequest, Request, +3 more

### EventGalleryController.php
**Path:** app/Http/Controllers/Admin/EventGalleryController.php
- **Class-level PHPDoc:** ✗ No
- **Methods:** 5 (5 documented)
- **Empty/stub methods:** 0
- **Unused imports:** 9
- **Lines of code:** 164
- **Commented code blocks:** 3
- **Issues:** debugging (dd/dump)
- **Methods:** index, getPhoto, store, show, destroy
- **Unused imports:** Request, Storage, EventGalleryRequest, +6 more

### EventsController.php
**Path:** app/Http/Controllers/Admin/EventsController.php
- **Class-level PHPDoc:** ✗ No
- **Methods:** 13 (4 documented)
- **Empty/stub methods:** 0
- **Unused imports:** 15
- **Lines of code:** 401
- **Commented code blocks:** 4
- **Issues:** debugging (dd/dump), loose comparisons
- **Unused imports:** ShowEventGallery as ShowEventGalleryResource, Attendance as AttendanceResource, EditEvent as EditEventResource, +12 more

### ExportMemberController.php
**Path:** app/Http/Controllers/Admin/ExportMemberController.php
- **Class-level PHPDoc:** ✗ No
- **Methods:** 3 (3 documented)
- **Empty/stub methods:** 0
- **Unused imports:** 8
- **Lines of code:** 186
- **Commented code blocks:** 0
- **Issues:** loose comparisons
- **Methods:** create, exportUsers, exportGuests
- **Unused imports:** MemberProcess, Request, LogActivity, +5 more

### ExportSubscriberController.php
**Path:** app/Http/Controllers/Admin/ExportSubscriberController.php
- **Class-level PHPDoc:** ✗ No
- **Methods:** 1 (1 documented)
- **Empty/stub methods:** 0
- **Unused imports:** 8
- **Lines of code:** 67
- **Commented code blocks:** 0
- **Methods:** exportSubscribers
- **Unused imports:** Subscriber as SubscriberResource, Request, LogActivity, +5 more

### FaqCategoryController.php
**Path:** app/Http/Controllers/Admin/FaqCategoryController.php
- **Class-level PHPDoc:** ✗ No
- **Methods:** 1 (1 documented)
- **Empty/stub methods:** 0
- **Unused imports:** 6
- **Lines of code:** 58
- **Commented code blocks:** 1
- **Issues:** debugging (dd/dump)
- **Methods:** store
- **Unused imports:** FaqCategoryRequest, Request, FaqCategory, +3 more

### FaqController.php
**Path:** app/Http/Controllers/Admin/FaqController.php
- **Class-level PHPDoc:** ✗ No
- **Methods:** 10 (10 documented)
- **Empty/stub methods:** 0
- **Unused imports:** 8
- **Lines of code:** 248
- **Commented code blocks:** 8
- **Issues:** debugging (dd/dump)
- **Unused imports:** FaqCategory as FaqCategoryResource, FaqList as FaqListResource, FaqUpdateRequest, +5 more

### FeedbackController.php
**Path:** app/Http/Controllers/Admin/FeedbackController.php
- **Class-level PHPDoc:** ✗ No
- **Methods:** 3 (3 documented)
- **Empty/stub methods:** 0
- **Unused imports:** 5
- **Lines of code:** 94
- **Commented code blocks:** 3
- **Issues:** debugging (dd/dump)
- **Methods:** index, edit, updateStatus
- **Unused imports:** Request, LogActivity, Common, +2 more

### FundController.php
**Path:** app/Http/Controllers/Admin/FundController.php
- **Class-level PHPDoc:** ✗ No
- **Methods:** 10 (7 documented)
- **Empty/stub methods:** 0
- **Unused imports:** 8
- **Lines of code:** 430
- **Commented code blocks:** 6
- **Issues:** debugging (dd/dump), loose comparisons
- **Unused imports:** Fund as FundResource, User as UserResource, FundRequest, +5 more

### GalleryController.php
**Path:** app/Http/Controllers/Admin/GalleryController.php
- **Class-level PHPDoc:** ✗ No
- **Methods:** 8 (1 documented)
- **Empty/stub methods:** 0
- **Unused imports:** 9
- **Lines of code:** 222
- **Commented code blocks:** 3
- **Issues:** debugging (dd/dump)
- **Unused imports:** ShowGallery as ShowGalleryResource, GalleryUpdateRequest, GalleryRequest, +6 more

### GetResponseController.php
**Path:** app/Http/Controllers/Admin/GetResponseController.php
- **Class-level PHPDoc:** ✗ No
- **Methods:** 3 (0 documented)
- **Empty/stub methods:** 0
- **Unused imports:** 3
- **Lines of code:** 70
- **Commented code blocks:** 3
- **Issues:** debugging (dd/dump)
- **Methods:** getcampaigns, getContacts, refreshCache
- **Unused imports:** Request, Exception, Common

### GoogleAnalyticsController.php
**Path:** app/Http/Controllers/Admin/GoogleAnalyticsController.php
- **Class-level PHPDoc:** ✗ No
- **Methods:** 2 (2 documented)
- **Empty/stub methods:** 0
- **Unused imports:** 0
- **Lines of code:** 61
- **Commented code blocks:** 0
- **Methods:** __construct, index

### GroupLinksController.php
**Path:** app/Http/Controllers/Admin/GroupLinksController.php
- **Class-level PHPDoc:** ✗ No
- **Methods:** 6 (6 documented)
- **Empty/stub methods:** 0
- **Unused imports:** 6
- **Lines of code:** 250
- **Commented code blocks:** 7
- **Issues:** debugging (dd/dump)
- **Unused imports:** User as UserResource, Request, EventProcess, +3 more

### GroupsController.php
**Path:** app/Http/Controllers/Admin/GroupsController.php
- **Class-level PHPDoc:** ✗ No
- **Methods:** 10 (7 documented)
- **Empty/stub methods:** 0
- **Unused imports:** 7
- **Lines of code:** 327
- **Commented code blocks:** 8
- **Issues:** debugging (dd/dump)
- **Unused imports:** GroupUpdateRequest, GroupAddRequest, SendMailRequest, +4 more

### GuestAddController.php
**Path:** app/Http/Controllers/Admin/GuestAddController.php
- **Class-level PHPDoc:** ✗ No
- **Methods:** 4 (4 documented)
- **Empty/stub methods:** 0
- **Unused imports:** 8
- **Lines of code:** 131
- **Commented code blocks:** 2
- **Issues:** debugging (dd/dump), loose comparisons
- **Methods:** create, member, validationUser, store
- **Unused imports:** GuestAddRequest, RegisterUser, Request, +5 more

### GuestDetailsController.php
**Path:** app/Http/Controllers/Admin/GuestDetailsController.php
- **Class-level PHPDoc:** ✗ No
- **Methods:** 4 (1 documented)
- **Empty/stub methods:** 0
- **Unused imports:** 10
- **Lines of code:** 108
- **Commented code blocks:** 4
- **Methods:** showDetails, showActivity, showMessages, show
- **Unused imports:** ActivityLog as ActivityLogResource, UserDetail as UserDetailResource, SendMail as SendMailResource, +7 more

### GuestEditController.php
**Path:** app/Http/Controllers/Admin/GuestEditController.php
- **Class-level PHPDoc:** ✗ No
- **Methods:** 4 (4 documented)
- **Empty/stub methods:** 0
- **Unused imports:** 9
- **Lines of code:** 149
- **Commented code blocks:** 5
- **Issues:** debugging (dd/dump), loose comparisons
- **Methods:** editList, edit, validationGuestEdit, update
- **Unused imports:** GuestUpdateRequest, MemberProcess, Request, +6 more

### GuestsController.php
**Path:** app/Http/Controllers/Admin/GuestsController.php
- **Class-level PHPDoc:** ✗ No
- **Methods:** 9 (5 documented)
- **Empty/stub methods:** 0
- **Unused imports:** 7
- **Lines of code:** 311
- **Commented code blocks:** 8
- **Issues:** debugging (dd/dump), loose comparisons
- **Unused imports:** ExitMemberRequest, SendMailRequest, ResetPasswordProcess, +4 more

### HelpAddController.php
**Path:** app/Http/Controllers/Admin/HelpAddController.php
- **Class-level PHPDoc:** ✗ No
- **Methods:** 3 (2 documented)
- **Empty/stub methods:** 0
- **Unused imports:** 7
- **Lines of code:** 81
- **Commented code blocks:** 2
- **Issues:** debugging (dd/dump)
- **Methods:** __construct, create, store
- **Unused imports:** HelpRepositoryInterface, HelpAddRequest, Request, +4 more

### HelpsController.php
**Path:** app/Http/Controllers/Admin/HelpsController.php
- **Class-level PHPDoc:** ✗ No
- **Methods:** 7 (5 documented)
- **Empty/stub methods:** 0
- **Unused imports:** 7
- **Lines of code:** 211
- **Commented code blocks:** 9
- **Issues:** debugging (dd/dump), loose comparisons
- **Unused imports:** HelpRepositoryInterface, Help as HelpResource, HelpUpdateRequest, +4 more

### ImportMemberController.php
**Path:** app/Http/Controllers/Admin/ImportMemberController.php
- **Class-level PHPDoc:** ✗ No
- **Methods:** 3 (2 documented)
- **Empty/stub methods:** 0
- **Unused imports:** 12
- **Lines of code:** 137
- **Commented code blocks:** 2
- **Issues:** debugging (dd/dump)
- **Methods:** index, importUsers, downloadFormat
- **Unused imports:** ImportMemberRequest, Request, RegisterUser, +9 more

### MailController.php
**Path:** app/Http/Controllers/Admin/MailController.php
- **Class-level PHPDoc:** ✗ No
- **Methods:** 1 (0 documented)
- **Empty/stub methods:** 0
- **Unused imports:** 3
- **Lines of code:** 31
- **Commented code blocks:** 1
- **Issues:** debugging (dd/dump)
- **Methods:** store
- **Unused imports:** TestSendMailRequest, Request, Exception

### MailQueueController.php
**Path:** app/Http/Controllers/Admin/MailQueueController.php
- **Class-level PHPDoc:** ✗ No
- **Methods:** 6 (1 documented)
- **Empty/stub methods:** 0
- **Unused imports:** 6
- **Lines of code:** 112
- **Commented code blocks:** 2
- **Issues:** debugging (dd/dump)
- **Unused imports:** MailQueue as MailQueueResource, MailQueueRequest, Request, +3 more

### MailinglistController.php
**Path:** app/Http/Controllers/Admin/MailinglistController.php
- **Class-level PHPDoc:** ✗ No
- **Methods:** 11 (1 documented)
- **Empty/stub methods:** 0
- **Unused imports:** 7
- **Lines of code:** 223
- **Commented code blocks:** 4
- **Issues:** debugging (dd/dump)
- **Unused imports:** Mailinglist as MailinglistResource, MailingListRequest, Request, +4 more

### MaillistSubscriberController.php
**Path:** app/Http/Controllers/Admin/MaillistSubscriberController.php
- **Class-level PHPDoc:** ✗ No
- **Methods:** 2 (0 documented)
- **Empty/stub methods:** 0
- **Unused imports:** 7
- **Lines of code:** 65
- **Commented code blocks:** 1
- **Issues:** debugging (dd/dump)
- **Methods:** create, store
- **Unused imports:** MailingListSubscriberRequest, MailinglistSubscriber, Request, +4 more

### MediaFilesController.php
**Path:** app/Http/Controllers/Admin/MediaFilesController.php
- **Class-level PHPDoc:** ✗ No
- **Methods:** 4 (4 documented)
- **Empty/stub methods:** 0
- **Unused imports:** 4
- **Lines of code:** 125
- **Commented code blocks:** 3
- **Issues:** debugging (dd/dump), loose comparisons
- **Methods:** list, index, show, destroy
- **Unused imports:** MediaFile as MediaFileResource, LogActivity, Common, +1 more

### MemberAddController.php
**Path:** app/Http/Controllers/Admin/MemberAddController.php
- **Class-level PHPDoc:** ✗ No
- **Methods:** 4 (4 documented)
- **Empty/stub methods:** 0
- **Unused imports:** 7
- **Lines of code:** 139
- **Commented code blocks:** 4
- **Issues:** debugging (dd/dump), loose comparisons
- **Methods:** create, member, validationUser, store
- **Unused imports:** UserProfileAddRequest, RegisterUser, Request, +4 more

### MemberController.php
**Path:** app/Http/Controllers/Admin/MemberController.php
- **Class-level PHPDoc:** ✗ No
- **Methods:** 11 (1 documented)
- **Empty/stub methods:** 0
- **Unused imports:** 9
- **Lines of code:** 417
- **Commented code blocks:** 7
- **Issues:** loose comparisons
- **Unused imports:** ActivityLog as ActivityLogResource, UserDetail as UserDetailResource, SendMail as SendMailResource, +6 more

### MemberEditController.php
**Path:** app/Http/Controllers/Admin/MemberEditController.php
- **Class-level PHPDoc:** ✗ No
- **Methods:** 3 (3 documented)
- **Empty/stub methods:** 0
- **Unused imports:** 9
- **Lines of code:** 175
- **Commented code blocks:** 8
- **Issues:** debugging (dd/dump), loose comparisons
- **Methods:** editList, edit, update
- **Unused imports:** UserProfileUpdateRequest, MemberProcess, Request, +6 more

### NewsLetterController.php
**Path:** app/Http/Controllers/Admin/NewsLetterController.php
- **Class-level PHPDoc:** ✗ No
- **Methods:** 4 (4 documented)
- **Empty/stub methods:** 0
- **Unused imports:** 5
- **Lines of code:** 149
- **Commented code blocks:** 3
- **Issues:** debugging (dd/dump), loose comparisons
- **Methods:** create, store, updateMemberStatus, updateStatus
- **Unused imports:** NewsletterRequest, Request, LogActivity, +2 more

### NotesController.php
**Path:** app/Http/Controllers/Admin/NotesController.php
- **Class-level PHPDoc:** ✗ No
- **Methods:** 5 (0 documented)
- **Empty/stub methods:** 0
- **Unused imports:** 8
- **Lines of code:** 111
- **Commented code blocks:** 2
- **Issues:** debugging (dd/dump), loose comparisons
- **Methods:** index, create, store, edit, delete
- **Unused imports:** NotesRequest, Request, NotesProcess, +5 more

### NotificationController.php
**Path:** app/Http/Controllers/Admin/NotificationController.php
- **Class-level PHPDoc:** ✗ No
- **Methods:** 4 (3 documented)
- **Empty/stub methods:** 0
- **Unused imports:** 4
- **Lines of code:** 136
- **Commented code blocks:** 3
- **Issues:** debugging (dd/dump)
- **Methods:** indexList, index, store, showList
- **Unused imports:** NewMessageNotification, Request, Notification, +1 more

### PageAttachmentsController.php
**Path:** app/Http/Controllers/Admin/PageAttachmentsController.php
- **Class-level PHPDoc:** ✗ No
- **Methods:** 2 (2 documented)
- **Empty/stub methods:** 0
- **Unused imports:** 5
- **Lines of code:** 117
- **Commented code blocks:** 2
- **Issues:** debugging (dd/dump)
- **Methods:** store, destroy
- **Unused imports:** Request, LogActivity, SiteHelper, +2 more

### PageCategoryController.php
**Path:** app/Http/Controllers/Admin/PageCategoryController.php
- **Class-level PHPDoc:** ✗ No
- **Methods:** 2 (2 documented)
- **Empty/stub methods:** 0
- **Unused imports:** 8
- **Lines of code:** 79
- **Commented code blocks:** 2
- **Issues:** debugging (dd/dump)
- **Methods:** list, store
- **Unused imports:** PageCategory as PageCategoryResource, PageCategoryRequest, Request, +5 more

### PageDetailsController.php
**Path:** app/Http/Controllers/Admin/PageDetailsController.php
- **Class-level PHPDoc:** ✗ No
- **Methods:** 3 (3 documented)
- **Empty/stub methods:** 0
- **Unused imports:** 5
- **Lines of code:** 246
- **Commented code blocks:** 9
- **Issues:** debugging (dd/dump), loose comparisons
- **Methods:** follow, like, dislike
- **Unused imports:** Request, LogActivity, SiteHelper, +2 more

### PagesController.php
**Path:** app/Http/Controllers/Admin/PagesController.php
- **Class-level PHPDoc:** ✗ No
- **Methods:** 11 (10 documented)
- **Empty/stub methods:** 0
- **Unused imports:** 9
- **Lines of code:** 316
- **Commented code blocks:** 8
- **Issues:** debugging (dd/dump)
- **Unused imports:** Page as PageResource, PageUpdateRequest, PageAddRequest, +6 more

### PaymentController.php
**Path:** app/Http/Controllers/Admin/PaymentController.php
- **Class-level PHPDoc:** ✗ No
- **Methods:** 3 (3 documented)
- **Empty/stub methods:** 0
- **Unused imports:** 6
- **Lines of code:** 114
- **Commented code blocks:** 2
- **Issues:** debugging (dd/dump), loose comparisons
- **Methods:** index, response, Subscription
- **Unused imports:** Subscription as SubscriptionResource, PaymentProcess, Request, +3 more

### PhotosController.php
**Path:** app/Http/Controllers/Admin/PhotosController.php
- **Class-level PHPDoc:** ✗ No
- **Methods:** 6 (5 documented)
- **Empty/stub methods:** 0
- **Unused imports:** 10
- **Lines of code:** 172
- **Commented code blocks:** 4
- **Issues:** debugging (dd/dump)
- **Unused imports:** Request, Storage, Gallery, +7 more

### PostAddController.php
**Path:** app/Http/Controllers/Admin/PostAddController.php
- **Class-level PHPDoc:** ✗ No
- **Methods:** 4 (4 documented)
- **Empty/stub methods:** 0
- **Unused imports:** 5
- **Lines of code:** 181
- **Commented code blocks:** 4
- **Issues:** debugging (dd/dump), loose comparisons
- **Methods:** createList, create, store, attachment
- **Unused imports:** PostRequest, LogActivity, SiteHelper, +2 more

### PostCommentDetailsController.php
**Path:** app/Http/Controllers/Admin/PostCommentDetailsController.php
- **Class-level PHPDoc:** ✗ No
- **Methods:** 2 (2 documented)
- **Empty/stub methods:** 0
- **Unused imports:** 9
- **Lines of code:** 219
- **Commented code blocks:** 2
- **Issues:** debugging (dd/dump), loose comparisons
- **Methods:** like, dislike
- **Unused imports:** Gate, Request, LogActivity, +6 more

### PostCommentsController.php
**Path:** app/Http/Controllers/Admin/PostCommentsController.php
- **Class-level PHPDoc:** ✗ No
- **Methods:** 4 (4 documented)
- **Empty/stub methods:** 0
- **Unused imports:** 8
- **Lines of code:** 248
- **Commented code blocks:** 4
- **Issues:** debugging (dd/dump), loose comparisons
- **Methods:** addComment, editCommentList, editComment, destroy
- **Unused imports:** PostCommentEditRequest, PostCommentAddRequest, Gate, +5 more

### PostDetailController.php
**Path:** app/Http/Controllers/Admin/PostDetailController.php
- **Class-level PHPDoc:** ✗ No
- **Methods:** 4 (4 documented)
- **Empty/stub methods:** 0
- **Unused imports:** 5
- **Lines of code:** 327
- **Commented code blocks:** 4
- **Issues:** debugging (dd/dump), loose comparisons
- **Methods:** like, dislike, save, unsave
- **Unused imports:** Request, LogActivity, SiteHelper, +2 more

### PostEditController.php
**Path:** app/Http/Controllers/Admin/PostEditController.php
- **Class-level PHPDoc:** ✗ No
- **Methods:** 4 (4 documented)
- **Empty/stub methods:** 0
- **Unused imports:** 7
- **Lines of code:** 192
- **Commented code blocks:** 6
- **Issues:** debugging (dd/dump), loose comparisons
- **Methods:** editList, edit, update, editAttachment
- **Unused imports:** PostAttachmentRequest, PostRequest, Request, +4 more

### PostReplyCommentsController.php
**Path:** app/Http/Controllers/Admin/PostReplyCommentsController.php
- **Class-level PHPDoc:** ✗ No
- **Methods:** 4 (4 documented)
- **Empty/stub methods:** 0
- **Unused imports:** 10
- **Lines of code:** 290
- **Commented code blocks:** 4
- **Issues:** debugging (dd/dump), loose comparisons
- **Methods:** list, addComment, editComment, destroy
- **Unused imports:** PostReplyComment as PostReplyCommentResource, PostCommentEditRequest, PostCommentAddRequest, +7 more

### PostsController.php
**Path:** app/Http/Controllers/Admin/PostsController.php
- **Class-level PHPDoc:** ✗ No
- **Methods:** 6 (5 documented)
- **Empty/stub methods:** 0
- **Unused imports:** 5
- **Lines of code:** 212
- **Commented code blocks:** 7
- **Issues:** debugging (dd/dump), loose comparisons
- **Unused imports:** Post as PostResource, LogActivity, SiteHelper, +2 more

### PrayerRequestAddController.php
**Path:** app/Http/Controllers/Admin/PrayerRequestAddController.php
- **Class-level PHPDoc:** ✗ No
- **Methods:** 2 (2 documented)
- **Empty/stub methods:** 0
- **Unused imports:** 7
- **Lines of code:** 83
- **Commented code blocks:** 1
- **Issues:** debugging (dd/dump)
- **Methods:** create, store
- **Unused imports:** PrayerAddRequest, PrayerRequest, Request, +4 more

### PrayerRequestsController.php
**Path:** app/Http/Controllers/Admin/PrayerRequestsController.php
- **Class-level PHPDoc:** ✗ No
- **Methods:** 9 (8 documented)
- **Empty/stub methods:** 0
- **Unused imports:** 9
- **Lines of code:** 345
- **Commented code blocks:** 9
- **Issues:** debugging (dd/dump), loose comparisons
- **Unused imports:** PrayerResponse as PrayerResponseResource, PrayerRequest as PrayerRequestResource, PrayerUpdateRequest, +6 more

### PreacherController.php
**Path:** app/Http/Controllers/Admin/PreacherController.php
- **Class-level PHPDoc:** ✗ No
- **Methods:** 9 (9 documented)
- **Empty/stub methods:** 0
- **Unused imports:** 10
- **Lines of code:** 254
- **Commented code blocks:** 7
- **Issues:** debugging (dd/dump), loose comparisons
- **Unused imports:** UserDetail as UserDetailResource, PreacherUpdateRequest, PreacherAddRequest, +7 more

### QuotesController.php
**Path:** app/Http/Controllers/Admin/QuotesController.php
- **Class-level PHPDoc:** ✗ No
- **Methods:** 14 (12 documented)
- **Empty/stub methods:** 0
- **Unused imports:** 14
- **Lines of code:** 445
- **Commented code blocks:** 10
- **Issues:** debugging (dd/dump), loose comparisons
- **Unused imports:** BibleEnglishVerses as BibleEnglishVersesResource, BibleTamilVerses as BibleTamilVersesResource, BibleEnglish as BibleEnglishResource, +11 more

### ReportsController.php
**Path:** app/Http/Controllers/Admin/ReportsController.php
- **Class-level PHPDoc:** ✗ No
- **Methods:** 10 (3 documented)
- **Empty/stub methods:** 0
- **Unused imports:** 11
- **Lines of code:** 413
- **Commented code blocks:** 7
- **Issues:** debugging (dd/dump), loose comparisons
- **Unused imports:** ImportMemberRequest, SummaryImport, UsersExport, +8 more

### SendMessageController.php
**Path:** app/Http/Controllers/Admin/SendMessageController.php
- **Class-level PHPDoc:** ✗ No
- **Methods:** 4 (3 documented)
- **Empty/stub methods:** 0
- **Unused imports:** 11
- **Lines of code:** 128
- **Commented code blocks:** 4
- **Issues:** debugging (dd/dump)
- **Methods:** index, show, store, memberstore
- **Unused imports:** SendMailRequest, SendMessageProcess, SendMessageEvent, +8 more

### SermonsController.php
**Path:** app/Http/Controllers/Admin/SermonsController.php
- **Class-level PHPDoc:** ✗ No
- **Methods:** 3 (3 documented)
- **Empty/stub methods:** 0
- **Unused imports:** 5
- **Lines of code:** 89
- **Commented code blocks:** 2
- **Issues:** debugging (dd/dump)
- **Methods:** index, show, download
- **Unused imports:** Request, LogActivity, Common, +2 more

### SmtpController.php
**Path:** app/Http/Controllers/Admin/SmtpController.php
- **Class-level PHPDoc:** ✗ No
- **Methods:** 9 (1 documented)
- **Empty/stub methods:** 0
- **Unused imports:** 6
- **Lines of code:** 190
- **Commented code blocks:** 3
- **Issues:** debugging (dd/dump), loose comparisons
- **Unused imports:** Smtps as SmtpsResource, SmtpRequest, Request, +3 more

### SubAdminController.php
**Path:** app/Http/Controllers/Admin/SubAdminController.php
- **Class-level PHPDoc:** ✗ No
- **Methods:** 11 (10 documented)
- **Empty/stub methods:** 0
- **Unused imports:** 8
- **Lines of code:** 308
- **Commented code blocks:** 6
- **Issues:** debugging (dd/dump), loose comparisons
- **Unused imports:** SubAdminUpdateRequest, SubAdminAddRequest, Mail, +5 more

### SubscribeController.php
**Path:** app/Http/Controllers/Admin/SubscribeController.php
- **Class-level PHPDoc:** ✗ No
- **Methods:** 3 (0 documented)
- **Empty/stub methods:** 0
- **Unused imports:** 5
- **Lines of code:** 117
- **Commented code blocks:** 5
- **Issues:** debugging (dd/dump), loose comparisons
- **Methods:** create, store, confirm
- **Unused imports:** Exception, SubscriberRequest, SubscriberProcess, +2 more

### SubscribersController.php
**Path:** app/Http/Controllers/Admin/SubscribersController.php
- **Class-level PHPDoc:** ✗ No
- **Methods:** 12 (1 documented)
- **Empty/stub methods:** 0
- **Unused imports:** 10
- **Lines of code:** 276
- **Commented code blocks:** 5
- **Issues:** debugging (dd/dump)
- **Unused imports:** Subscribers as SubscribersResource, SubscriberUpdateRequest, SubscriberAddRequest, +7 more

### UnSubscribeController.php
**Path:** app/Http/Controllers/Admin/UnSubscribeController.php
- **Class-level PHPDoc:** ✗ No
- **Methods:** 1 (0 documented)
- **Empty/stub methods:** 0
- **Unused imports:** 2
- **Lines of code:** 43
- **Commented code blocks:** 0
- **Methods:** create
- **Unused imports:** Exception, Request

### UserController.php
**Path:** app/Http/Controllers/Admin/UserController.php
- **Class-level PHPDoc:** ✗ No
- **Methods:** 10 (4 documented)
- **Empty/stub methods:** 0
- **Unused imports:** 8
- **Lines of code:** 317
- **Commented code blocks:** 9
- **Issues:** debugging (dd/dump), loose comparisons
- **Unused imports:** UserRepositoryInterface, ExitMemberRequest, SendMailRequest, +5 more

### UserProfileController.php
**Path:** app/Http/Controllers/Admin/UserProfileController.php
- **Class-level PHPDoc:** ✗ No
- **Methods:** 8 (2 documented)
- **Empty/stub methods:** 0
- **Unused imports:** 8
- **Lines of code:** 269
- **Commented code blocks:** 3
- **Issues:** debugging (dd/dump), loose comparisons
- **Unused imports:** Country as CountryResource, State as StateResource, City as CityResource, +5 more

### VideoConferencesController.php
**Path:** app/Http/Controllers/Admin/VideoConferencesController.php
- **Class-level PHPDoc:** ✗ No
- **Methods:** 16 (7 documented)
- **Empty/stub methods:** 0
- **Unused imports:** 7
- **Lines of code:** 463
- **Commented code blocks:** 3
- **Issues:** debugging (dd/dump), loose comparisons
- **Unused imports:** VideoConferenceInUpdateRequest, VideoConferenceUpdateRequest, VideoConferenceRequest, +4 more

### VideoController.php
**Path:** app/Http/Controllers/Admin/VideoController.php
- **Class-level PHPDoc:** ✗ No
- **Methods:** 3 (3 documented)
- **Empty/stub methods:** 0
- **Unused imports:** 7
- **Lines of code:** 118
- **Commented code blocks:** 3
- **Issues:** debugging (dd/dump), loose comparisons
- **Methods:** create, storeVideo, store
- **Unused imports:** VideoSaveRequest, SendPushNotification, VideoRequest, +4 more

### WidgetController.php
**Path:** app/Http/Controllers/Admin/WidgetController.php
- **Class-level PHPDoc:** ✗ No
- **Methods:** 7 (7 documented)
- **Empty/stub methods:** 0
- **Unused imports:** 2
- **Lines of code:** 114
- **Commented code blocks:** 0
- **Issues:** loose comparisons
- **Unused imports:** Request, WidgetRequest


## ADMIN CONTROLLERS SUMMARY STATISTICS

| Metric | Count | Percentage |
|--------|-------|------------|
| Total controllers | 75 | 100% |
| Controllers with class PHPDoc | 0 | 0% |
| Controllers without class PHPDoc | 75 | 100% |
| Total methods | 430 | - |
| Methods with PHPDoc | 250 | 58.1% |
| Empty/stub methods | 0 | 0% |
| Files with unused imports | 515 | - |
| Total unused imports instances | 515 | - |
| Total commented code blocks | 290 | - |
| Total lines of code | 14271 | - |
| Controllers with dd()/dump() | 63 | 84% |
| Controllers with loose comparisons (==) | 40 | 53.3% |
| Controllers with TODO/FIXME | 0 | 0% |
