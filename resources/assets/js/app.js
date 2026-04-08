
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');
Vue.config.ignoredElements = ['trix-editor'];
/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.component('example-component', require('./components/ExampleComponent.vue'));

//Register
Vue.component('register-tab', require('./components/register/Tab.vue').default);

//campaign
Vue.component('campaign-list', require('./components/campaign/List.vue').default);
Vue.component('create-campaign', require('./components/campaign/Create.vue').default);
Vue.component('edit-campaign', require('./components/campaign/Edit.vue').default);

//email
Vue.component('email-list', require('./components/email/List.vue').default);
Vue.component('create-email', require('./components/email/Create.vue').default);
Vue.component('edit-email', require('./components/email/Edit.vue').default);

//test Mail
Vue.component('test-mail', require('./components/email/TestMail.vue').default);

Vue.component('create-campaign-email', require('./components/campaignemail/Create.vue').default);
Vue.component('edit-campaign-email', require('./components/campaignemail/Edit.vue').default);

//notification

Vue.component('notification-list', require('./components/notification/List.vue').default);
Vue.component('notification', require('./components/notification/Show.vue').default);

//payaccount
Vue.component('payaccount-list', require('./components/payaccount/List.vue').default);
Vue.component('create-payaccount', require('./components/payaccount/Create.vue').default);
Vue.component('edit-payaccount', require('./components/payaccount/Edit.vue').default);


//mailinglist
Vue.component('list-mailinglist', require('./components/mailinglist/List.vue').default);
Vue.component('create-mailinglist', require('./components/mailinglist/Create.vue').default);
Vue.component('edit-mailinglist', require('./components/mailinglist/Edit.vue').default);
Vue.component('import-csv', require('./components/mailinglist/ImportSubscribers.vue').default);
Vue.component('attach-subscriber', require('./components/mailinglist/AttachSubscriber.vue').default);

//subscriber
Vue.component('subscriber-list', require('./components/subscriber/List.vue').default);
Vue.component('create-subscriber', require('./components/subscriber/Create.vue').default);
Vue.component('edit-subscriber', require('./components/subscriber/Edit.vue').default);

Vue.component('attach-maillist-subscriber', require('./components/subscriber/AttachMaillist.vue').default);

//smtp
Vue.component('smtp-list', require('./components/smtp/List.vue').default);
Vue.component('create-smtp', require('./components/smtp/Create.vue').default);
Vue.component('edit-smtp', require('./components/smtp/Edit.vue').default);

//mail queue
Vue.component('mailqueue', require('./components/mailqueue/List.vue').default);
Vue.component('edit-queue', require('./components/mailqueue/Edit.vue').default);

//rule
Vue.component('list-rule', require('./components/emailblaster/rule/List.vue').default);
Vue.component('create-rule', require('./components/emailblaster/rule/Create.vue').default);
Vue.component('edit-rule', require('./components/emailblaster/rule/Edit.vue').default);

//mails-delivered
Vue.component('list-mails-delivered', require('./components/emailblaster/maildelivered/List.vue').default);

//webhooks
Vue.component('list-webhook', require('./components/emailblaster/webhook/List.vue').default);
Vue.component('create-webhook', require('./components/emailblaster/webhook/Create.vue').default);
Vue.component('edit-webhook', require('./components/emailblaster/webhook/Edit.vue').default);

//email-template
Vue.component('create-email-template', require('./components/emailblaster/emailtemplates/Create.vue').default);


//Sub Admin
Vue.component('sub-admin-list', require('./components/subadmin/List.vue').default);
Vue.component('create-subadmin', require('./components/subadmin/Create.vue').default);
Vue.component('edit-subadmin', require('./components/subadmin/Edit.vue').default);
Vue.component('filter-subadmin', require('./components/subadmin/Filter.vue').default);
Vue.component('subadmin-permissions', require('./components/member/profile/subadminPermissions.vue').default);

//Member
Vue.component('member-list', require('./components/member/List.vue').default);
Vue.component('profile-tab', require('./components/member/profile/ProfileTab.vue').default);
Vue.component('create-member', require('./components/member/Create.vue').default);
Vue.component('edit-member', require('./components/member/Edit.vue').default);
Vue.component('search-filter', require('./components/member/Filter.vue').default);
Vue.component('send-message', require('./components/member/sendMessage.vue').default);
Vue.component('exit-member', require('./components/member/Exit.vue').default);
Vue.component('family-tree', require('./components/member/profile/familytree.vue').default);
Vue.component('member-family-tree', require('./components/member/profile/MemberFamilyTree.vue').default);
Vue.component('app-widgets', require('./components/AppWidgets.vue').default);

//Guest
Vue.component('guest-list', require('./components/guest/List.vue').default);
Vue.component('guest-profile-tab', require('./components/guest/profile/ProfileTab.vue').default);
Vue.component('create-guest', require('./components/guest/Create.vue').default);
Vue.component('edit-guest', require('./components/guest/Edit.vue').default);
Vue.component('search-filter-guest', require('./components/guest/Filter.vue').default);
Vue.component('guest-send-message', require('./components/guest/sendMessage.vue').default);
Vue.component('exit-guest', require('./components/guest/Exit.vue').default);
Vue.component('family-tree', require('./components/guest/profile/familytree.vue').default);

//Video Conference
Vue.component('create-video-room', require('./components/videoconference/Create.vue').default);
Vue.component('edit-video-room', require('./components/videoconference/Edit.vue').default);
Vue.component('add-invitee', require('./components/videoconference/addInvitee.vue').default);

//Bulletin
Vue.component('bulletin-tab', require('./components/bulletin/listTab.vue').default);
Vue.component('create-bulletin', require('./components/bulletin/Create.vue').default);

//video
Vue.component('create-audio',require('./components/mediafile/Audio.vue').default);
Vue.component('create-video',require('./components/mediafile/Video.vue').default);
Vue.component('create-image',require('./components/mediafile/Image.vue').default);
Vue.component('mediafile-tab',require('./components/mediafile/listTab.vue').default);

//Quote
Vue.component('quote-tab', require('./components/quote/Tab.vue').default);
Vue.component('add-quote-tab', require('./components/quote/CreateTab.vue').default);

//Group
Vue.component('create-group', require('./components/group/Create.vue').default);
Vue.component('add-groupmember', require('./components/group/AddMember.vue').default);

//dashboard
Vue.component('birthday', require('./components/Birthday.vue').default);
Vue.component('view-birthday', require('./components/ViewBirthday.vue').default);
Vue.component('anniversary', require('./components/Anniversary.vue').default);
Vue.component('view-anniversary', require('./components/ViewAnniversary.vue').default);

//prayerrequest
Vue.component('prayer-tab', require('./components/prayerrequest/listTab.vue').default);
Vue.component('create-prayer', require('./components/prayerrequest/Create.vue').default);
Vue.component('edit-prayer', require('./components/prayerrequest/Edit.vue').default);

//help
Vue.component('help-tab', require('./components/help/listTab.vue').default);
Vue.component('create-help', require('./components/help/Create.vue').default);
Vue.component('edit-help', require('./components/help/Edit.vue').default);

//seo
Vue.component('seo-tab', require('./components/setting/seo/tab.vue').default);

//Vue.component('invite-member', require('./components/member/Invite.vue').default);
//Vue.component('Faq', require('./components/Faq.vue').default);

//Preacher
Vue.component('preacher-list', require('./components/preacher/List.vue').default);
//Vue.component('profile-tab', require('./components/preacher/profile/ProfileTab.vue').default);
Vue.component('create-preacher', require('./components/preacher/Create.vue').default);
Vue.component('search-preacher', require('./components/preacher/Filter.vue').default);
Vue.component('edit-preacher', require('./components/preacher/Edit.vue').default);

//preacher-changepwd and avatar

Vue.component('change-preacher-password', require('./components/preacher/ChangePassword.vue').default);
Vue.component('change-preacher-avatar', require('./components/preacher/ChangeAvatar.vue').default);

//Event
Vue.component('create-event', require('./components/event/Create.vue').default);
Vue.component('edit-event', require('./components/event/Edit.vue').default);
Vue.component('event-tab', require('./components/event/details/EventTab.vue').default);
Vue.component('show-event', require('./components/event/show.vue').default);
Vue.component('event-popup', require('./components/event/Popup.vue').default);

//Edit Userprofile
Vue.component('edit-profile', require('./components/admin/EditProfile.vue').default);
Vue.component('change-password', require('./components/admin/ChangePassword.vue').default);
Vue.component('change-avatar', require('./components/admin/ChangeAvatar.vue').default);

Vue.component('showimage', require('./components/event/details/ShowImage.vue').default);
//Vue.component('galleryimage', require('./components/event/details/GalleryImage.vue').default);
Vue.component('showgallery', require('./components/gallery/ShowGallery.vue').default);

//Vue.component('showchapter', require('./components/sermon/ShowChapter.vue').default);

//Contact
Vue.component('contact', require('./components/contact.vue').default);

Vue.component('dashboard-event', require('./components/Event.vue').default);
Vue.component('dashboard-sermon', require('./components/sermon.vue').default);

Vue.component('create-series', require('./components/preacher/sermonlink/Create.vue').default);
Vue.component('edit-series', require('./components/preacher/sermonlink/Edit.vue').default);

//fund
Vue.component('list-fund', require('./components/fund/List.vue').default);
Vue.component('add-fund', require('./components/fund/Create.vue').default);
Vue.component('edit-fund', require('./components/fund/Edit.vue').default);

//page
Vue.component('newsletter-send', require('./components/newsletter/Send.vue').default);

//page category
Vue.component('page-category-list', require('./components/page_category/List.vue').default);
Vue.component('edit-page-category', require('./components/page_category/Edit.vue').default);
Vue.component('faq-category-list', require('./components/faq_category/List.vue').default);
Vue.component('edit-faq-category', require('./components/faq_category/Edit.vue').default);
Vue.component('post-category-list', require('./components/post_category/List.vue').default);
Vue.component('edit-post-category', require('./components/post_category/Edit.vue').default);

//page
Vue.component('tiptap-editor', require('./components/TiptapEditor.vue').default);
Vue.component('page-list', require('./components/page/List.vue').default);
Vue.component('create-page', require('./components/page/Create.vue').default);
Vue.component('edit-page', require('./components/page/Edit.vue').default);

//post
Vue.component('post-list', require('./components/post/List.vue').default);
Vue.component('create-post', require('./components/post/Create.vue').default);
Vue.component('edit-post', require('./components/post/Edit.vue').default);
Vue.component('show-post', require('./components/post/Show.vue').default);
Vue.component('comment-list', require('./components/post/Comments.vue').default);
Vue.component('emoji', require('./components/post/Emoji.vue').default);

//faq
Vue.component('faq-list', require('./components/faq/List.vue').default);
Vue.component('create-faq', require('./components/faq/Create.vue').default);
Vue.component('edit-faq', require('./components/faq/Edit.vue').default);
Vue.component('faq', require('./components/faq/Faq.vue').default);

export const bus = new Vue();
import VueSwal from 'vue-swal';
Vue.use(VueSwal);

const app = new Vue({
    el: '#app'
});

import Paginate from 'vuejs-paginate'
Vue.component('paginate', Paginate);
