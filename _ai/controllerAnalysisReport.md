# CHURCH CMS LARAVEL - CONTROLLER ANALYSIS REPORT

## SUMMARY STATISTICS
- **Total Controllers Analyzed:** 171
- **Controllers with Class PHPDoc:** 0 (0%)
- **Controllers without Class PHPDoc:** 171 (100%)

## CONTROLLERS BY FOLDER
- **root:** 9 controllers (0/9 documented)
- **Admin:** 75 controllers (0/75 documented)
- **EmailBlaster:** 3 controllers (0/3 documented)
- **Setting:** 3 controllers (0/3 documented)
- **Angular:** 19 controllers (0/19 documented)
- **Api:** 27 controllers (0/27 documented)
- **Guest:** 14 controllers (0/14 documented)
- **Auth:** 7 controllers (0/7 documented)
- **Member:** 2 controllers (0/2 documented)
- **Preacher:** 7 controllers (0/7 documented)
- **Siteadmin:** 5 controllers (0/5 documented)

## CONTROLLERS WITHOUT CLASS-LEVEL PHPDOC
Total: 171

### Controllers without documentation by folder:

**root** (9):
- AboutController.php
- ChangePasswordController.php
- ContactController.php
- Controller.php
- FaqController.php
- HomeController.php
- PageController.php
- PricingController.php
- TestController.php

**Admin** (75):
- ActivityLogController.php
- AttachSubscriberController.php
- AttendancesController.php
- AudioController.php
- BirthdayController.php
- BlogsController.php
- BotmanMasterController.php
- BulletinsController.php
- CampaignController.php
- CampaignEmailController.php
- ChurchDetailsController.php
- ContactController.php
- DashboardController.php
- EmailController.php
- EventGalleryController.php
- EventsController.php
- ExportMemberController.php
- ExportSubscriberController.php
- FaqCategoryController.php
- FaqController.php
- FeedbackController.php
- FundController.php
- GalleryController.php
- GetResponseController.php
- GoogleAnalyticsController.php
- GroupLinksController.php
- GroupsController.php
- GuestAddController.php
- GuestDetailsController.php
- GuestEditController.php
- GuestsController.php
- HelpAddController.php
- HelpsController.php
- ImportMemberController.php
- MailController.php
- MailQueueController.php
- MailinglistController.php
- MaillistSubscriberController.php
- MediaFilesController.php
- MemberAddController.php
- MemberController.php
- MemberEditController.php
- NewsLetterController.php
- NotesController.php
- NotificationController.php
- PageAttachmentsController.php
- PageCategoryController.php
- PageDetailsController.php
- PagesController.php
- PaymentController.php
- PhotosController.php
- PostAddController.php
- PostCommentDetailsController.php
- PostCommentsController.php
- PostDetailController.php
- PostEditController.php
- PostReplyCommentsController.php
- PostsController.php
- PrayerRequestAddController.php
- PrayerRequestsController.php
- PreacherController.php
- QuotesController.php
- ReportsController.php
- SendMessageController.php
- SermonsController.php
- SmtpController.php
- SubAdminController.php
- SubscribeController.php
- SubscribersController.php
- UnSubscribeController.php
- UserController.php
- UserProfileController.php
- VideoConferencesController.php
- VideoController.php
- WidgetController.php

**EmailBlaster** (3):
- MailsDeliveredController.php
- RulesController.php
- WebhooksController.php

**Setting** (3):
- GeneralController.php
- MaintenanceController.php
- SeoDetailController.php

**Angular** (19):
- BlogsController.php
- BulletinsController.php
- ChurchDetailsController.php
- ContactController.php
- EventsController.php
- FaqController.php
- FundController.php
- GalleryController.php
- GroupsController.php
- HelpsController.php
- MediaFilesController.php
- NewsLetterController.php
- PagesController.php
- PrayerRequestsController.php
- QuotesController.php
- SeoDetailController.php
- SermonsController.php
- VideoConferencesController.php
- WidgetsController.php

**Api** (27):
- ApkController.php
- BulletinsController.php
- ChurchController.php
- ChurchDetailsController.php
- ContactController.php
- EventGalleryController.php
- EventsController.php
- FavoritesController.php
- FeedbackController.php
- FundController.php
- GalleryController.php
- GroupsController.php
- HelpsController.php
- LoginController.php
- MediaFilesController.php
- PhotosController.php
- PrayerRequestsController.php
- PrayerResponsesController.php
- QuotesController.php
- SendMessageController.php
- SermonLinkController.php
- SermonsController.php
- TestController.php
- UserController.php
- UserprofileController.php
- VideoConferencesController.php
- VotesController.php

**Guest** (14):
- BulletinsController.php
- ChurchDetailsController.php
- ContactController.php
- EventsController.php
- FeedbackController.php
- FundController.php
- GalleryController.php
- GroupsController.php
- HelpsController.php
- LoginController.php
- MediaFilesController.php
- PrayerRequestsController.php
- QuotesController.php
- SermonsController.php

**Auth** (7):
- EmailVerificationController.php
- ForgotPasswordController.php
- ImpersonateController.php
- LoginController.php
- RegisterController.php
- ResetPasswordController.php
- VerificationController.php

**Member** (2):
- HomeController.php
- VideoConferencesController.php

**Preacher** (7):
- ActivityLogController.php
- DashboardController.php
- NotificationController.php
- PreacherController.php
- SermonLinkController.php
- SermonsController.php
- VideoConferencesController.php

**Siteadmin** (5):
- ActivityLogController.php
- ContactController.php
- DashboardController.php
- NotificationController.php
- SiteadminController.php

## UNUSED IMPORTS ANALYSIS
Total files with unused imports: 103

Common unused imports:
- Request (from Illuminate\Http\Request): found 85 times
- Exception (from Exception): found 69 times
- Common (from App\Traits\Common): found 56 times
- LogActivity (from App\Traits\LogActivity): found 48 times
- SiteHelper (from App\Helpers\SiteHelper): found 11 times
- Carbon (from Carbon\Carbon): found 7 times
- SplFileObject (from SplFileObject): found 6 times
- Userprofile (from App\Models\Userprofile): found 6 times
- MemberProcess (from App\Traits\MemberProcess): found 5 times
- User (from App\Models\User): found 5 times
- DB (from DB): found 4 times
- User as UserResource (from App\Http\Resources\User as UserResource): found 4 times
- RegisterUser (from App\Traits\RegisterUser): found 4 times
- Auth (from Illuminate\Support\Facades\Auth): found 4 times
- ChangePasswordRequest (from App\Http\Requests\ChangePasswordRequest): found 4 times
- Mailinglist as MailinglistResource (from App\Http\Resources\Mailinglist as MailinglistResource): found 3 times
- ImportMemberRequest (from App\Http\Requests\ImportMemberRequest): found 3 times
- Email (from App\Models\Email): found 3 times
- Country (from App\Models\Country): found 3 times
- State (from App\Models\State): found 3 times

## EMPTY/STUB METHODS ANALYSIS
Total empty/stub methods: 82 controllers, 255 total methods

Controllers with empty methods:
- **AttendancesController.php:** index, create, store, summary, createAttendance, saveAttendance
- **AudioController.php:** create, audiostore, store
- **BirthdayController.php:** showBirthday, showAnniversary
- **BlogsController.php:** index, show
- **BotmanMasterController.php:** show, destroy, searchIndex, nativeGoogle
- **BulletinsController.php:** list, index, create, store, destroy, downloadattachments
- **CampaignController.php:** destroy
- **CampaignEmailController.php:** destroy
- **ChurchDetailsController.php:** show, socialMedia
- **ContactController.php:** store, edit, update, destroy
- **DashboardController.php:** __construct
- **MailsDeliveredController.php:** index, list, show
- **RulesController.php:** list, index, createList, create, store, show, editList, edit, update, destroy
- **WebhooksController.php:** list, index, createList, create, store, show, editList, edit, update, destroy
- **EmailController.php:** destroy
- **EventGalleryController.php:** index, destroy
- **EventsController.php:** validateedit, events
- **FaqCategoryController.php:** store
- **FaqController.php:** list, index
- **FeedbackController.php:** list, index, store
- **FundController.php:** store
- **GalleryController.php:** destroy
- **GetResponseController.php:** getcampaigns, getContacts
- **GoogleAnalyticsController.php:** __construct
- **GroupLinksController.php:** index, create, store, edit, update, destroy
- **GroupsController.php:** index
- **GuestAddController.php:** create, validationUser, store
- **GuestDetailsController.php:** showDetails, showActivity, showMessages, show
- **GuestEditController.php:** editList, edit, validationGuestEdit, update
- **GuestsController.php:** find, index, exitCreate, exitStore
- **HelpAddController.php:** create, store
- **HelpsController.php:** index, show, update
- **ImportMemberController.php:** index
- **MailQueueController.php:** destroy
- **MailinglistController.php:** destroy
- **MediaFilesController.php:** list, show, destroy
- **MemberAddController.php:** create, validationUser, store
- **MemberController.php:** showDetails, showActivity, showGroups, showMessages, show
- **MemberEditController.php:** editList, edit, update
- **NewsLetterController.php:** index
- **NotificationController.php:** index
- **PageAttachmentsController.php:** store, destroy
- **PageCategoryController.php:** list, store
- **PageDetailsController.php:** follow, like, dislike
- **PagesController.php:** index, show
- **PaymentController.php:** index
- **PhotosController.php:** index, store, destroy
- **PostAddController.php:** createList, create, store, attachment
- **PostCommentDetailsController.php:** like, dislike
- **PostCommentsController.php:** addComment, editCommentList, editComment, destroy
- **PostDetailController.php:** like, dislike, save, unsave
- **PostEditController.php:** editList, edit, update, editAttachment
- **PostReplyCommentsController.php:** list, addComment, editComment, destroy
- **PostsController.php:** indexList, index, showList, imageList, show, destroy
- **PrayerRequestAddController.php:** create, store
- **PrayerRequestsController.php:** index, store, show
- **PreacherController.php:** find, create, store, showDetails, show, editList, edit, update
- **QuotesController.php:** list, index, validation, store, show, editList, edit, editValidation, update, reschedule, destroy
- **ReportsController.php:** report, messageHistory, index, create, show, exportBirthday
- **SendMessageController.php:** index, readMessage, notificationList
- **SermonsController.php:** index
- **MaintenanceController.php:** create, store
- **SeoDetailController.php:** basic, advanced
- **SmtpController.php:** destroy
- **SubAdminController.php:** find, create, validationUser, store, show, editList, edit, editValidationUser, update
- **SubscribersController.php:** destroy, import
- **UserController.php:** updatetoken, changePassword, resetPassword, storePassword, resetChangePassword
- **VideoConferencesController.php:** index, update, saveinvites
- **VideoController.php:** storeVideo
- **WidgetController.php:** show, destroy
- **ApkController.php:** index
- **ChurchController.php:** locationList, churchList
- **LoginController.php:** login
- **PrayerResponsesController.php:** index, store
- **UserprofileController.php:** country, state, city, create, update
- **RegisterController.php:** stepOne, stepTwo, validator
- **PageController.php:** news, privacy, terms, show
- **SermonLinkController.php:** getDownload, validateedit
- **PricingController.php:** create
- **ActivityLogController.php:** create, store, show, edit, update, destroy
- **SiteadminController.php:** index, create, store, show, showSubscription, edit, update, destroy
- **TestController.php:** verses

## COMMENTED-OUT CODE ANALYSIS
Files with commented code blocks: 89

Controllers with commented code:
- **AttachSubscriberController.php:** 1 blocks
- **AttendancesController.php:** 4 blocks
- **AudioController.php:** 4 blocks
- **BirthdayController.php:** 3 blocks
- **BulletinsController.php:** 5 blocks
- **CampaignController.php:** 1 blocks
- **CampaignEmailController.php:** 1 blocks
- **ChurchDetailsController.php:** 2 blocks
- **ContactController.php:** 2 blocks
- **DashboardController.php:** 2 blocks
- **MailsDeliveredController.php:** 1 blocks
- **RulesController.php:** 4 blocks
- **WebhooksController.php:** 4 blocks
- **EmailController.php:** 1 blocks
- **EventGalleryController.php:** 3 blocks
- **EventsController.php:** 1 blocks
- **FaqCategoryController.php:** 1 blocks
- **FaqController.php:** 1 blocks
- **FeedbackController.php:** 3 blocks
- **FundController.php:** 3 blocks
- **GalleryController.php:** 1 blocks
- **GetResponseController.php:** 3 blocks
- **GroupLinksController.php:** 5 blocks
- **GroupsController.php:** 1 blocks
- **GuestAddController.php:** 2 blocks
- **GuestDetailsController.php:** 3 blocks
- **GuestEditController.php:** 3 blocks
- **GuestsController.php:** 4 blocks
- **HelpAddController.php:** 2 blocks
- **HelpsController.php:** 2 blocks
- **ImportMemberController.php:** 1 blocks
- **MailController.php:** 1 blocks
- **MailQueueController.php:** 2 blocks
- **MailinglistController.php:** 1 blocks
- **MaillistSubscriberController.php:** 1 blocks
- **MediaFilesController.php:** 3 blocks
- **MemberAddController.php:** 3 blocks
- **MemberController.php:** 5 blocks
- **MemberEditController.php:** 3 blocks
- **NewsLetterController.php:** 1 blocks
- **NotesController.php:** 1 blocks
- **NotificationController.php:** 2 blocks
- **PageAttachmentsController.php:** 1 blocks
- **PageCategoryController.php:** 2 blocks
- **PageDetailsController.php:** 2 blocks
- **PagesController.php:** 1 blocks
- **PaymentController.php:** 2 blocks
- **PhotosController.php:** 4 blocks
- **PostAddController.php:** 3 blocks
- **PostCommentDetailsController.php:** 1 blocks
- **PostCommentsController.php:** 2 blocks
- **PostDetailController.php:** 1 blocks
- **PostEditController.php:** 4 blocks
- **PostReplyCommentsController.php:** 2 blocks
- **PostsController.php:** 5 blocks
- **PrayerRequestAddController.php:** 1 blocks
- **PrayerRequestsController.php:** 3 blocks
- **PreacherController.php:** 2 blocks
- **QuotesController.php:** 3 blocks
- **ReportsController.php:** 6 blocks
- **SendMessageController.php:** 2 blocks
- **SermonsController.php:** 2 blocks
- **GeneralController.php:** 1 blocks
- **MaintenanceController.php:** 1 blocks
- **SeoDetailController.php:** 1 blocks
- **SmtpController.php:** 1 blocks
- **SubAdminController.php:** 5 blocks
- **SubscribeController.php:** 5 blocks
- **SubscribersController.php:** 1 blocks
- **UserController.php:** 3 blocks
- **UserProfileController.php:** 2 blocks
- **VideoConferencesController.php:** 2 blocks
- **VideoController.php:** 3 blocks
- **BlogsController.php:** 1 blocks
- **ApkController.php:** 1 blocks
- **ChurchController.php:** 1 blocks
- **FavoritesController.php:** 1 blocks
- **LoginController.php:** 3 blocks
- **PrayerResponsesController.php:** 2 blocks
- **TestController.php:** 2 blocks
- **UserprofileController.php:** 8 blocks
- **VotesController.php:** 2 blocks
- **ImpersonateController.php:** 1 blocks
- **RegisterController.php:** 1 blocks
- **ChangePasswordController.php:** 1 blocks
- **PageController.php:** 4 blocks
- **SermonLinkController.php:** 1 blocks
- **PricingController.php:** 1 blocks
- **SiteadminController.php:** 3 blocks

## CODE QUALITY ISSUES SUMMARY
Files with quality issues: 87

Most common issues:
- Contains debugging functions (dd/dump/var_dump): 76 controllers
- Contains loose comparisons (==): 52 controllers
- Possibly long/complex methods (high brace count): 27 controllers
- Multiple concerns in single file (may need refactoring): 12 controllers

## METHODS WITHOUT DOCUMENTATION
Controllers with undocumented methods: 59
Total undocumented methods: 200
