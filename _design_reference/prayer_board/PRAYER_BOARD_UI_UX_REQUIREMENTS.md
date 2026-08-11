# Prayer Board - UI/UX Requirements Document

**Version:** 1.0  
**Last Updated:** April 2026  
**Status:** Design Phase Complete

---

## Table of Contents

1. [Module Scope](#module-scope)
2. [Objectives](#objectives)
3. [User Roles & Personas](#user-roles--personas)
4. [Use Cases by User Type](#use-cases-by-user-type)
5. [Key Workflows](#key-workflows)
6. [Admin Dashboard Pages](#admin-dashboard-pages)
7. [Member/Guest Pages](#memberguest-pages)
8. [Design System](#design-system)
9. [Technical Specifications](#technical-specifications)

---

## Module Scope

### Overview
A mobile and web-based prayer request management system for Grace Community Church. The system allows church members, guests, and anonymous visitors to submit prayer requests, view active prayers from the community, and intercede for fellow believers.

### Core Functionality
- **Prayer Request Submission:** Users create and submit prayer requests with category classification
- **Prayer Moderation:** Admin team reviews, approves, edits, and manages prayer requests
- **Prayer Discovery:** Public board displays approved prayers with community engagement metrics
- **Community Intercession:** Users pray for requests and see real-time prayer counts
- **Prayer Lifecycle Management:** Track prayers from submission through completion/expiry
- **Personal Prayer Management:** Users track their own submitted prayers and participation

### System Boundaries
- **In Scope:** Prayer request CRUD, moderation workflow, public board, user engagement, analytics
- **Out of Scope:** Church member directory, service registration, giving/donations, messaging

---

## Objectives

### Primary Objectives
1. **Enable Community Prayer:** Create a user-friendly platform for church members to share prayer needs
2. **Streamline Moderation:** Provide admins with efficient tools to review, approve, and manage requests
3. **Foster Engagement:** Increase community intercession through visible prayer counts and categories
4. **Build Accountability:** Track prayer lifecycle from request to answered prayer
5. **Ensure Safety:** Implement content moderation and member verification

### Success Metrics
- Prayer request submission rate: Target 5-10 new requests per week
- Approval time: Target < 24 hours average
- Community engagement: Target 50+ prayers per request
- Mobile app adoption: Target 40% of active members
- User retention: Target 70% monthly active users

---

## User Roles & Personas

### 1. **Anonymous User**
**Definition:** Unregistered visitor to the prayer board  
**Permissions:** View-only access  
**Constraints:** Cannot submit or intercede  
**Primary Goal:** Browse church prayers and learn about community needs

### 2. **Guest User**
**Definition:** User with email registration, no member verification  
**Permissions:** View prayers, submit requests, intercede  
**Requirements:** Email address, name, agree to terms  
**Primary Goal:** Participate in prayer community, share personal needs

### 3. **Member (App)**
**Definition:** Verified church member using mobile app  
**Permissions:** All guest permissions + member profile features  
**Requirements:** Full profile: name, email, city, address, DOB  
**Primary Goal:** Active prayer participation, track personal prayers

### 4. **Member (Web)**
**Definition:** Verified church member using web platform  
**Permissions:** Same as Member (App)  
**Primary Goal:** Manage prayers, browse board, participate at leisure

### 5. **Sub-Admin**
**Definition:** Church staff with moderation privileges  
**Permissions:** Review pending prayers, approve/reject, edit text, set expiry, pin prayers  
**Requirements:** Admin credentials, role assignment  
**Primary Goal:** Ensure quality prayer content, manage community tone

### 6. **Head Admin**
**Definition:** Lead administrator with full system access  
**Permissions:** All sub-admin permissions + user management, analytics, settings  
**Requirements:** System administrator credentials  
**Primary Goal:** System oversight, analytics, policy enforcement

---

## Use Cases by User Type

### Anonymous User
| Use Case | Description | Starting Point | Expected Outcome |
|----------|-------------|-----------------|------------------|
| **View Public Board** | Browse active prayer requests | Public board page | See list of 50+ prayers with filters |
| **View Prayer Details** | Read full prayer text, see prayer counts | Prayer card | Full prayer context, category, user name, prayer stats |
| **Filter by Category** | Find specific types of prayers | Public board | Filtered list (Health, Family, Employment, etc.) |
| **View Expiry Timeline** | Check prayer expiration dates | Prayer card | See countdown days remaining with progress bar |

---

### Guest User
| Use Case | Description | Starting Point | Expected Outcome |
|----------|-------------|-----------------|------------------|
| **Submit Prayer Request** | Create and submit new prayer need | "Share Prayer" button | Request enters pending queue, confirmation shown |
| **Register/Verify Email** | Sign up for account | Sign-up modal | Account created, email verification sent |
| **Lift Prayer** | Intercede for a request | Prayer card | Prayer count increments, "Thank you" confirmation |
| **View My Prayers** | See personal submitted requests | "My Prayers" dashboard | List of own prayers with status badges |
| **Track Prayer Status** | Monitor personal prayer request approval | "My Prayers" → Prayer detail | Status: Pending / Live / Answered / Expired |
| **Browse Prayer Board** | Same as Anonymous + all features | Public board | Browse as Guest with intercession capability |

---

### Member (Mobile App)
| Use Case | Description | Starting Point | Expected Outcome |
|----------|-------------|-----------------|------------------|
| **Swipe Prayer Board** | Quick swipe-based prayer browsing | Home screen | View prayers 1-by-1, 10-15 sec per prayer |
| **Lift Prayer with Timer** | Intercede for request (10-15 sec timer) | Prayer slide | Prayer count increments, button shows "✓ Thank you" |
| **Submit Prayer Request** | Create prayer with full member profile | "Share Prayer" button | Request submitted, enters pending queue |
| **View My Prayer Activity** | See all personal prayers & participation | "My Prayers" tab | Dashboard with all prayer statuses |
| **Manage Personal Prayer** | Edit, mark answered, or delete prayer | "My Prayer" detail | Update prayer status or expiry |
| **See Prayer Breakdown** | View Members/Guests/Anonymous counts | Prayer card | See 3-box counter: Members (15) Guests (8) Anon (6) |

---

### Member (Web App)
| Use Case | Description | Starting Point | Expected Outcome |
|----------|-------------|-----------------|------------------|
| **Browse Prayer Board** | Scroll-based prayer board browsing | "Prayer Board" page | See multiple prayers, apply filters/search |
| **View Prayer Details** | Rich text prayer view with full context | Prayer card | Prayer text, expiry countdown, prayer stats |
| **Intercede for Prayer** | Click button to lift prayer in intercession | Prayer card | Prayer count increments, confirmation message |
| **Filter & Sort** | Find prayers by category/date/activity | Filter sidebar | Narrowed prayer list matching criteria |
| **My Prayer Dashboard** | View personal prayer submissions | "My Prayers" page | Organized by status: Pending/Live/Answered/Ended |
| **Prayer Statistics** | See personal prayer impact metrics | "My Prayers" dashboard | Total prayers lifted, prayers answered, days active |

---

### Sub-Admin (Moderation)
| Use Case | Description | Starting Point | Expected Outcome |
|----------|-------------|-----------------|------------------|
| **Review Pending Requests** | See queue of requests awaiting approval | Pending tab | List of pending prayers with timestamps |
| **View User History** | Check requester's submission history | User icon popup | Previous requests, member type, dates |
| **Edit Prayer Text** | Improve grammar, clarity, remove PII | Pending card | Edited text ready for publishing |
| **Set Custom Expiry** | Choose request lifespan (1-90 days) | Expiry dropdown | Preview of expiry date calculated |
| **Approve Prayer Request** | Publish prayer to public board | "Approve" button | Prayer moves to Active tab, goes live |
| **Reject Prayer Request** | Decline request, record reason | "Reject" button + reason input | Prayer moves to Rejected tab, reason logged |
| **Mark Prayer Answered** | Move prayer to answered/testimony archive | Active card action | Prayer moves to Answered tab, counted as victory |
| **Extend Prayer Expiry** | Add more days to active prayer | Active card button | Expiry date recalculated and extended |
| **Pin Prayer to Top** | Feature important prayers | Active card button | Prayer appears at top of board |
| **Filter & Organize** | View prayers by month/category | Month dropdown + filters | Dashboard filtered by specific month |
| **Monthly Statistics** | See approval/rejection rates | Stats dashboard | Count of approved/rejected/answered prayers |

---

### Head Admin (System)
| Use Case | Description | Starting Point | Expected Outcome |
|----------|-------------|-----------------|------------------|
| **All Sub-Admin Functions** | Full moderation capabilities | Admin dashboard | Access to all moderation features |
| **Manage Sub-Admins** | Add/remove/edit admin accounts | Settings → Sub-Admins | Sub-admin list with role assignments |
| **View System Analytics** | High-level prayer statistics | Analytics page | Charts: submissions, approvals, user growth |
| **Configure Expiry Defaults** | Set default prayer duration | Settings → Defaults | Default 60 days (or custom value) |
| **Manage Categories** | Add/edit prayer categories | Settings → Categories | Category list with emoji assignments |
| **System Settings** | Configure global system parameters | Settings | Prayer board configuration options |

---

## Key Workflows

### Workflow 1: Creating a Prayer Request

**Actors:** Guest, Member  
**Preconditions:** User is logged in or creates account  
**Flow:**

1. User taps/clicks "Share Prayer" or "Submit Prayer Request" button
2. Modal/form opens with fields:
   - Name (pre-filled for members)
   - Email (optional for members)
   - Prayer text (required, ~100-500 words)
   - Category dropdown (Health, Family, Employment, Financial, Personal, Other)
3. User enters prayer details
4. System validates:
   - Prayer text not empty
   - Category selected
   - Email valid (if provided)
5. User clicks "Submit"
6. System shows confirmation: "Thank you! Your prayer request has been submitted for review"
7. Prayer request stored with status: **PENDING**
8. Email confirmation sent to user
9. User returned to prayer board

**Outcome:** Prayer request enters admin queue  
**Alternative:** User cancels form → returns to previous screen

---

### Workflow 2: Approving Prayer Request

**Actors:** Sub-Admin, Head Admin  
**Preconditions:** Prayer exists in PENDING status  
**Flow:**

1. Admin opens admin dashboard → "Pending Review" tab
2. Admin sees list of pending prayers with filters (month, category)
3. Admin clicks prayer card to expand
4. Admin can:
   - **View** user history (click user icon → popup shows previous requests)
   - **Edit** prayer text in textarea (fix grammar, remove PII, improve clarity)
   - **Select** expiry duration from dropdown:
     - 1 Day (Urgent)
     - 2-3 Days
     - 7 Days (1 Week)
     - 14 Days (2 Weeks)
     - 30 Days (1 Month)
     - 60 Days (2 Months) - **Default**
     - 90 Days (3 Months)
   - **Preview** expiry date (shows "Expires: Jun 7, 2026")
5. Admin clicks "✓ Approve & Publish" button
6. System:
   - Validates expiry duration selected
   - Calculates expiry date
   - Creates prayer with status: **ACTIVE**
   - Records "Approved by: [Admin Name] (Sub-admin)"
   - Sends email to requester: "Your prayer has been published!"
7. Prayer appears on public board immediately
8. Admin sees toast: "✓ Approved! Will expire in 60 days"
9. Prayer moves from Pending tab to Active tab

**Outcome:** Prayer now visible to all users on public board  
**Alternative:** Admin cancels → returns to pending list without changes

---

### Workflow 3: Rejecting Prayer Request

**Actors:** Sub-Admin, Head Admin  
**Preconditions:** Prayer exists in PENDING status  
**Flow:**

1. Admin opens admin dashboard → "Pending Review" tab
2. Admin reviews prayer and determines it doesn't meet guidelines
3. Admin clicks "✗ Reject" button
4. System shows modal: "Why are you rejecting this prayer request?"
5. Admin selects/enters reason:
   - Does not meet community guidelines
   - Contains personal identifying information
   - Inappropriate language
   - Duplicate request
   - Other [text input]
6. Admin confirms rejection
7. System:
   - Creates prayer with status: **REJECTED**
   - Records rejection reason and timestamp
   - Stores in Rejected tab
   - Sends email to requester (optional): "Your prayer request was not approved. Reason: [reason]"
8. Prayer moves from Pending tab to Rejected tab
9. Admin sees toast: "✗ Request rejected and archived"
10. Rejected prayers auto-delete after 7 days

**Outcome:** Prayer removed from public board, archived in Rejected tab  
**Note:** Rejected prayers stored for 7 days, then permanently deleted

---

### Workflow 4: Marking Prayer as Answered

**Actors:** Sub-Admin, Head Admin, Prayer Requester (Member/Guest)  
**Preconditions:** Prayer exists in ACTIVE status  
**Flow - Admin Action:**

1. Admin opens admin dashboard → "Live" tab
2. Admin reviews active prayers
3. Admin sees prayer that requester indicated was answered (or admin determines)
4. Admin clicks "✓ Mark Answered" button on prayer card
5. System shows optional modal: "Add answered prayer note (optional)"
6. Admin enters optional testimony/update
7. Admin clicks "Confirm"
8. System:
   - Changes status from ACTIVE to ANSWERED
   - Records answer date and admin who marked it
   - Moves prayer to "Answered" tab
   - Sends email to requester: "Your prayer has been marked as answered! Thank you for sharing your testimony."
9. Prayer now appears in "Answered Prayers" archive section

**Flow - Member Action:**

1. Member opens "My Prayers" dashboard
2. Member finds ACTIVE prayer they want to mark answered
3. Member clicks "✓ Mark as Answered" button
4. Modal shows: "Share how God answered this prayer (optional)"
5. Member enters update text
6. System moves prayer to ANSWERED status
7. Admin receives notification to review answered prayer

**Outcome:** Prayer moved to Answered/Testimony archive, counts as church victory

---

### Workflow 5: Extending Prayer Expiry

**Actors:** Sub-Admin, Head Admin  
**Preconditions:** Prayer exists in ACTIVE status, approaching expiry  
**Flow:**

1. Admin opens admin dashboard → "Live" tab
2. Admin sees prayer with expiry countdown (e.g., "3 days remaining")
3. Admin clicks "⏰ Extend Expiry" button
4. System shows modal: "Extend prayer expiry by:"
   - Options: 7, 14, 30, 60 days
5. Admin selects duration
6. System calculates new expiry date
7. Admin clicks "Confirm"
8. System:
   - Updates prayer expiry date
   - Resets countdown timer on board
   - Sends email to requester: "Your prayer has been extended!"
9. Prayer remains in Active tab with new expiry date
10. Admin sees toast: "✓ Prayer extended by 14 days"

**Outcome:** Prayer stays live longer, more time for community intercession

---

### Workflow 6: Pinning Prayer to Top

**Actors:** Sub-Admin, Head Admin  
**Preconditions:** Prayer exists in ACTIVE status  
**Flow:**

1. Admin opens admin dashboard → "Live" tab
2. Admin identifies important/urgent prayer (health emergency, new request, etc.)
3. Admin clicks "📌 Pin to Top" button on prayer card
4. System:
   - Marks prayer as "pinned"
   - Moves prayer to top of Active list on public board
   - Other pinned prayers stay in chronological order below
5. Admin sees toast: "✓ Prayer pinned to top"
6. All users see pinned prayer at top of their feed/list
7. Admin can click "📌 Unpin" to remove pin

**Outcome:** High-priority prayers get maximum visibility

---

## Admin Dashboard Pages

### Page 1: Pending Review Tab

**URL Path:** `/admin/dashboard?tab=pending`  
**Access:** Sub-Admin, Head Admin only  
**Visibility:** Month filter + pagination

#### Header Section
- **Title:** "📋 Prayer Requests Awaiting Review"
- **Stats Bar:**
  - Pending count: 12
  - Approved today: 8
  - Rejected today: 2
  - Active on board: 156
- **Controls:** Refresh button, month dropdown (default: current month)

#### Pending Prayer Card (Repeating)
**For each pending prayer:**

**Header:**
- User icon (👤) with hover popup showing:
  - "Previous Requests: 3"
  - "View all submissions" link
  - "Member Type: Guest"
  - "Submitted: Apr 8, 2026"
- User name (clickable, goes to profile)
- User email
- Submission timestamp
- Status badge: "⏳ PENDING"

**Body:**
- Category badge (🏥 Health, 👨‍👩‍👧‍👦 Family, etc.)
- Prayer request text (read-only display in gray box)
- Help text: "💡 Admin tip: You can edit for clarity, grammar, or remove personal details"

**Controls - Two Column Grid:**
- **Left:** Set Expiry Duration
  - Dropdown with options: 1 Day, 2 Days, 3 Days, 7 Days, 14 Days, 30 Days, **60 Days (Default)**, 90 Days
- **Right:** Expiry Date Preview
  - Displays: "Expires: Jun 7, 2026"
  - Shows: "Duration: 60 days"

**Action Buttons (Full Width, Two Column):**
- "✓ Approve & Publish" (Green gradient)
- "✗ Reject" (Red gradient)

**Status Indicator:** Bottom bar shows "TAB: ⏳ PENDING REVIEW"

#### Pagination
- "Page 1 of 5" | ← Previous | Next →
- Shows 10 prayers per page

---

### Page 2: Published/Active Tab

**URL Path:** `/admin/dashboard?tab=active`  
**Access:** Sub-Admin, Head Admin  
**Display:** Currently live prayers with expiry tracking

#### Header Section
- **Title:** "🟢 Published & Active Prayers"
- **Stats:**
  - Active count: 156
  - Expiring this week: 12
  - Expiring this month: 45
  - Average intercession: 32 prayers

#### Active Prayer Card (Repeating)

**Header:**
- User icon (👤) + name (🔗 profile link)
- Posted date
- "✓ Approved by: Sarah Chen (Sub-admin)"
- Status badge: "🟢 ACTIVE"

**Body:**
- Prayer text (read-only)
- Expiry countdown bar (orange gradient, time-based progress)
  - "⏰ Expires in: 23 days"
  - Visual progress bar showing 38% remaining

**Prayer Statistics - 3 Box Grid:**
- Members: 15 (indigo)
- Guests: 8 (purple)
- Anonymous: 5 (gray)
- Total text: "28 people praying"

**Action Buttons (Full Width, 2x2 Grid):**
- "📌 Pin to Top" (Blue)
- "✓ Mark Answered" (Green)
- "⏰ Extend Expiry" (Purple)
- "✗ Unpublish" (Red)

#### Pagination
- Month filter (dropdown: Apr 2026)
- "Page 1 of 8 | Show 10 per page"
- Navigation: ← Previous | Next →

---

### Page 3: Ended/Expired Tab

**URL Path:** `/admin/dashboard?tab=ended`  
**Access:** Sub-Admin, Head Admin  
**Display:** Prayers that have passed expiry date (archive)

#### Header Section
- **Title:** "⏰ Ended / Expired Prayers"
- **Filter:** Month dropdown (default: current month)
- **Note:** "These prayers have naturally expired after their set duration"

#### Ended Prayer Card (Repeating)

**Header:**
- User icon (👤) + name (🔗)
- Posted & Expired dates
- "✓ Approved by: James Park (Sub-admin)"
- Status badge: "⏰ ENDED"

**Body:**
- Prayer text (read-only, italicized)
- Summary Stats:
  - Duration: 60 days
  - Prayers: 💝 34
  - Status: Archived

**Note:** "Read-only archive — No actions available"

#### Pagination
- Month filter
- "Page 1 of 3"
- ← Previous | Next →

---

### Page 4: Answered Prayers Tab

**URL Path:** `/admin/dashboard?tab=answered`  
**Access:** Sub-Admin, Head Admin  
**Display:** Prayers marked as answered (testimony archive)

#### Header Section
- **Title:** "✓ Answered Prayers"
- **Message:** "Celebrating God's faithfulness to our community"
- **Filter:** Month dropdown
- **Stats:**
  - Answered this month: 5
  - Average days to answer: 18 days
  - Total answered: 47

#### Answered Prayer Card (Repeating)

**Header:**
- User icon (👤) + name (🔗)
- Posted & Answered dates
- "✓ Approved by: Sarah Chen (Sub-admin)"
- Status badge: "✓ ANSWERED" (Green)

**Body:**
- Prayer text (read-only, green background light)
- Summary Stats:
  - Days on board: 13
  - Prayers lifted: 💝 28
  - Status: ✓ Testified

**Note:** "Testimony Archive — Shared as church victory"

#### Pagination
- Month filter
- "Page 1 of 2"
- ← Previous | Next →

---

### Page 5: Rejected Prayers Tab

**URL Path:** `/admin/dashboard?tab=rejected`  
**Access:** Sub-Admin, Head Admin  
**Display:** Declined requests (auto-delete after 7 days)

#### Header Section
- **Title:** "✗ Rejected Prayers"
- **Message:** "Auto-deletes after 7 days"
- **Filter:** Month dropdown

#### Rejected Prayer Card (Repeating)

**Header:**
- User icon (👤) + name (🔗)
- Rejected date
- "✗ Rejected by: James Park (Sub-admin)"
- Status badge: "✗ REJECTED"

**Body:**
- Rejection reason box (red background)
  - "❌ Does not meet community guidelines"
- Original prayer text (read-only, struck-through or grayed)
- Auto-delete countdown:
  - "⏳ Deletes in: 5 days"
  - Visual progress bar

**Note:** "Read-only — Automatically purged after 7 days"

#### Pagination
- Month filter
- "Page 1 of 1"

---

## Member/Guest Pages

### Page 1: Public Prayer Board (Web)

**URL Path:** `/prayers` or `/prayer-board`  
**Access:** All users (Anonymous, Guest, Member)  
**Layout:** Scroll-based browsing

#### Header Section
- **Hero:** Large gradient background with "Our Prayer Board" title
- **Stats Bar (4 Boxes):**
  - Active Prayers: 156
  - Total Interceding: 1,247
  - Answered This Month: 5
  - Avg Days Active: 18

#### Filter Section
- **Category Buttons (Horizontal):**
  - Active Requests (default)
  - 🏥 Health
  - 👨‍👩‍👧‍👦 Family
  - 💼 Employment
  - 💰 Financial
  - ✓ Answered Prayers

#### Prayer Card Grid (Repeating)

**For Each Prayer:**

**Header:**
- Posted by: [Name] (only first name shown)
- Posted: "2 days ago"
- Category badge

**Body:**
- Prayer text (truncated to ~120 characters with "...")
- Expiry countdown (orange bar)
- Prayer counter:
  - Members: 34 (indigo box)
  - Guests: 12 (purple box)
  - Anonymous: 6 (gray box)
  - Total: "52 people praying"

**Action Button:**
- "🙏 Lift This Prayer" (gradient, category-matched)

**Layout:** 1 column mobile, 2-3 columns tablet/desktop

#### Pagination
- Infinite scroll OR "Load More" button
- Shows 20 prayers per page

---

### Page 2: Mobile Prayer Slide (App)

**URL Path:** Mobile app home  
**Access:** All users logged into mobile app  
**Layout:** Full-screen swipe interface

#### Header Bar (Fixed Dark)
- **Left:** ← Back button (exit)
- **Center:** "Prayer Board" title + "1 of 247" subtitle
- **Right:** ⏳ Timer (15 seconds countdown)
- **Below:** Thin progress bar (fills left to right by position)

#### Prayer Slide (Full Screen, Fills Remaining Space)

**Background:** Category-matched gradient (Health = pink, Family = pink-purple, etc.)

**Content Areas:**

**1. Category Badge** (Top)
- 🏥 Health (or other category)

**2. Prayer Text** (Center, Large, Readable)
- Centered, large font
- Full prayer text visible (no scrolling needed for 10-15 sec time)

**3. User Info** (Below Text)
- "Sarah • 2 days ago"

**4. Prayer Counter** (3 Boxes)
- Members: 34
- Guests: 12
- Anonymous: 6
- Text: "Total: 52 people praying"

**5. Action Button** (Bottom)
- "🙏 Lift This Prayer" (gradient)
- On click:
  - Shows: "⏳ Submitting..."
  - Then: "✓ Thank you for Praying"
  - Disabled for 5 seconds
  - Resets on card change

#### Navigation
- **Swipe Left:** Next prayer
- **Swipe Right:** Previous prayer
- **Keyboard:** Arrow keys (web version)

#### Timer Behavior
- Counts down 15 seconds per prayer
- Resets on card change
- Visual countdown in header

---

### Page 3: My Prayers Dashboard (Web)

**URL Path:** `/my-prayers` or `/dashboard`  
**Access:** Guest, Member logged in  
**Purpose:** View personal prayer submissions and participation

#### Header
- **Title:** "My Prayers"
- **Stats Bar (4 Boxes):**
  - Submitted: 3
  - Currently Live: 1
  - Answered: 1
  - Total Intercessions: 24 (for member only)

#### Tab Navigation (5 Tabs)
- **Pending** (Awaiting approval)
- **Live** (Published, active)
- **Ended** (Expired naturally)
- **Answered** (Marked as answered)
- **Rejected** (Not approved - guest only sees own rejections)

#### Tab 1: Pending Prayers

**Cards for each pending prayer:**
- Title/summary
- Status: "⏳ PENDING - Awaiting review"
- Message: "Church leaders are reviewing your request"
- Prayer text preview
- Action: "✗ Delete Request" button

#### Tab 2: Live Prayers

**Cards for each live prayer:**
- Title/summary
- Posted date
- Status: "🟢 LIVE"
- Expiry countdown bar
- Prayer counter breakdown:
  - Members: 15
  - Guests: 8
  - Anonymous: 3
  - Total: "26 interceding"
- Actions (2 buttons):
  - "👁️ View on Board" (goes to public board)
  - "✓ Mark Answered" (move to answered section)

#### Tab 3: Ended Prayers

**Cards for expired prayers:**
- Title/summary
- Posted & Expired dates
- Status: "⏰ ENDED"
- Summary stats:
  - Days on board: 60
  - Total prayers: 42
  - Status: "Archive"
- No actions (read-only)

#### Tab 4: Answered Prayers

**Cards for answered prayers:**
- Title/summary
- Posted & Answered dates
- Status: "✓ ANSWERED!"
- Prayer impact stats:
  - Days on board: 18
  - Total prayers: 28
  - Status: "Kept as Testimony"
- No actions (read-only)

#### Tab 5: Rejected Prayers (Guest Only)

**Cards for rejected prayers:**
- Original request (struck-through or grayed)
- Status: "✗ REJECTED"
- Rejection reason: "Does not meet community guidelines"
- Auto-delete countdown: "Deletes in 3 days"
- No actions available

---

### Page 4: My Prayers Dashboard (Mobile App)

**URL Path:** Mobile app menu → "My Prayers"  
**Access:** Guest, Member logged in  
**Layout:** Scrollable list with tabs

#### Header
- Back button
- "My Prayers" title
- Stats card showing summary

#### Tab Navigation (Scrollable Horizontal)
- Pending (0)
- Live (1)
- Answered (1)
- Ended (0)
- Rejected (0)

#### Prayer List Cards

**Each prayer shows:**
- Prayer title/first 50 chars
- Status badge (⏳ PENDING, 🟢 LIVE, ✓ ANSWERED, ⏰ ENDED)
- Sub-text: Date or "Awaiting review"
- Tap to expand → full details
- Swipe left → delete (pending only) or mark answered (live)

---

### Page 5: Participated Prayers

**URL Path:** `/participated` or under "Discover" tab  
**Access:** Member (shows prayers they've interceded for)  
**Purpose:** Track community prayers user has prayed for

#### Header
- **Title:** "Prayers You've Lifted"
- **Stats:**
  - Total prayers: 47
  - This month: 12
  - Unanswered: 39
  - Answered: 8

#### Filter
- Time period: This month, Last 3 months, All time
- Status: Active, Answered, Ended

#### Prayer Cards (List View)

**Each card shows:**
- Prayer title/summary
- Status badge
- Date lifted
- Current prayer count (how many total now)
- Answered status (if applicable)

#### Features
- Sort by: Date, Status, Prayer count
- Filter by category
- Search by user name
- Responsive grid (1 col mobile, 2 col tablet, 3 col desktop)

---

## Design System

### Color Palette

**Category Colors:**
- 🏥 **Health:** Red (#EF4444) → Pink (#EC4899)
- 👨‍👩‍👧‍👦 **Family:** Pink (#EC4899) → Purple (#A855F7)
- 💼 **Employment:** Blue (#3B82F6) → Cyan (#06B6D4)
- 💰 **Financial:** Yellow (#FBBF24) → Orange (#F97316)
- ✨ **Personal:** Purple (#A855F7) → Indigo (#6366F1)
- ❤️ **Other:** Green (#10B981) → Emerald (#059669)

**UI Status Colors:**
- **Primary:** Indigo (#4F46E5)
- **Success:** Green (#16A34A)
- **Warning:** Orange (#EA580C)
- **Danger:** Red (#DC2626)
- **Neutral:** Gray (#6B7280)

**Backgrounds:**
- **Dark (Admin):** #1F2937 (Dark Gray)
- **Light (Public):** #FAFAFA (Off-White)
- **Card:** #FFFFFF (White)

### Typography

**Display:**
- Font: Cormorant Garamond (serif)
- Sizes: 32px, 28px, 24px
- Weight: Bold (700)
- Use: Headers, titles, prominent text

**Body:**
- Font: Outfit (sans-serif)
- Sizes: 16px, 14px, 12px, 11px
- Weight: Regular (400), Semibold (600), Bold (700)
- Use: Body text, labels, buttons

### Component Specifications

**Buttons:**
- Height: 48px (mobile), 44px (desktop)
- Padding: 14px horizontal, 12px vertical
- Border-radius: 12px
- Font: 16px, Bold
- States: Default, Hover, Active, Disabled
- Shadow: 0 4px 12px rgba(0,0,0,0.15)

**Cards:**
- Border-radius: 12-16px (rounded)
- Shadow: 0 4px 12px rgba(0,0,0,0.1) → 0 12px 24px on hover
- Padding: 24px (desktop), 16px (mobile)
- Border-left: 4px (admin cards, category-matched)

**Progress Bars:**
- Height: 2-3px
- Border-radius: 2px
- Background: Light gray/white overlay
- Fill: Gradient (category-matched)

**Prayer Counter Boxes:**
- Width: Equal thirds in grid
- Background: White/60 with backdrop blur OR category-light color
- Border: 1px, subtle color
- Border-radius: 8px
- Padding: 12px
- Text: 11px label + 20px number

---

## Technical Specifications

### Platform Requirements

**Mobile App:**
- iOS: 13.0+
- Android: 8.0+
- Framework: Native or React Native
- Screen sizes: 375px - 428px width (iPhone)
- Aspect ratio: 9:16 (full-screen slides)

**Web App:**
- Browsers: Chrome 90+, Safari 14+, Firefox 88+, Edge 90+
- Responsive breakpoints:
  - Mobile: 320px - 767px
  - Tablet: 768px - 1024px
  - Desktop: 1025px+
- Framework: React, Vue, or similar SPA

### Performance Requirements

- Page load: < 2 seconds (Core Web Vitals)
- Prayer card render: < 100ms
- Swipe animation: 60fps smooth
- API response: < 500ms

### Accessibility Requirements

- WCAG 2.1 Level AA compliance
- Color contrast: 4.5:1 text, 3:1 large text
- Touch targets: 48px minimum
- Keyboard navigation: Full support
- Screen reader: Compatible (ARIA labels)

### Data Storage

**Local Storage:**
- Recent prayers viewed (mobile)
- User preferences (theme, notifications)
- Draft prayers (unsaved submissions)

**Backend Storage:**
- Prayer requests (full CRUD)
- User accounts & profiles
- Admin logs (approval history)
- Analytics (engagement metrics)

### Security & Privacy

- HTTPS only
- User passwords: Bcrypt hashing
- PII: Encryption at rest
- Admin access: Role-based (RBAC)
- Audit logs: All admin actions logged
- GDPR: User data export/deletion options

---

## Appendix: Workflow Diagrams

### Prayer Lifecycle

```
SUBMISSION (Guest/Member)
    ↓
PENDING (Admin Review)
    ├→ APPROVED → ACTIVE (Live on Board)
    │              ├→ ANSWERED (Marked answered by admin/member)
    │              ├→ ENDED (Auto-expire after duration)
    │              └→ UNPUBLISHED (Admin removes)
    └→ REJECTED (Reason logged, auto-delete 7 days)
```

### Admin Approval Flow

```
Pending Tab
  ↓
Admin Reviews
  ├→ Edit Text (grammar/clarity)
  ├→ Select Expiry (1-90 days)
  ├→ Approve → Active Tab
  └→ Reject → Rejected Tab
```

### Member Dashboard States

```
My Prayers
  ├→ Pending (submitted, waiting approval)
  ├→ Live (approved, on public board)
  ├→ Ended (expired after duration)
  ├→ Answered (marked as victory)
  └→ Rejected (not approved)
```

---

## Version History

| Version | Date | Author | Changes |
|---------|------|--------|---------|
| 1.0 | Apr 2026 | Design Team | Initial comprehensive requirements |

---

**End of Document**
