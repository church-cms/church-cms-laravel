# Lift Prayer Button - UX/UI Specification

**Component:** Interactive Prayer Lift Button  
**Context:** Public prayer board (web & mobile)  
**Purpose:** Users record intercession for active prayers  

---

## BUTTON STATES

### State 1: DEFAULT (Idle)
```
Label: "🙏 Lift Prayer"
Color: Category gradient (blue/cyan/green/orange/purple depending on prayer category)
Style: Rounded pill button (rounded-full)
Size: Full width on mobile, ~150px on desktop
Padding: py-3 px-6
Font: Bold (font-semibold), white text
Cursor: pointer (hover state)
Icon: Hand prayer emoji 🙏
```

**Hover Effect (Desktop):**
- Darken gradient 10%
- Slight scale up (scale-105)
- Subtle shadow increase
- Button slightly lifts (translateY -1px)

**Example Visual:**
```
┌──────────────────────────────┐
│  🙏 Lift Prayer              │  ← Gradient background
│                              │     White text, bold
└──────────────────────────────┘
```

---

### State 2: SUBMITTING (Loading)
```
Trigger: Immediately on click
Duration: Until server responds (typically 1-2 seconds)
Label: "⏳ Submitting..."
Spinner: Rotating circular loader (left of text)
Color: Same gradient as State 1 (no change)
Opacity: Slightly dimmed (opacity-75)
Cursor: not-allowed (disabled)
Click Prevention: Disabled (onClick returns false)
```

**Animations:**
- Text stays visible
- Spinner rotates 360° every 0.8 seconds
- Button slightly grayed/dimmed
- No interaction allowed

**Example Visual:**
```
┌──────────────────────────────┐
│  ⏳ Submitting... (loading)   │  ← Spinner animates
│                              │     Button disabled
└──────────────────────────────┘
```

---

### State 3A: SUCCESS (Prayer Recorded)
```
Trigger: After successful API response (200)
Duration: Display for 2-3 seconds, then fade out
Label: "✓ Thank you for Praying"
Icon: Check mark ✓ (green)
Color: Green gradient (#10B981 → #059669)
Opacity: Starts at 100%, fades to 0% over 1 second after 2 seconds
Cursor: not-allowed (disabled until reset)
Font: Normal weight (not bold), white text
```

**Animation Timeline:**
```
0s:     Show check mark button
2000ms: Begin fade-out
3000ms: Fully faded out
4000ms: Hidden from DOM OR reset to State 1 when swiping to next card
```

**Example Visual:**
```
┌──────────────────────────────┐
│  ✓ Thank you for Praying     │  ← Green gradient
│  (fading out...)             │
└──────────────────────────────┘
```

---

### State 3B: ERROR (Failed to Record)
```
Trigger: If API returns error (403/422/500)
Duration: Displayed until user retries or navigates
Label: "❌ Could not record (try again)"
Icon: X mark ❌ (red)
Color: Red gradient (#EF4444 → #DC2626)
Cursor: pointer (can retry)
Font: Bold, white text
Toast/Alert: Red error message above button
```

**Error Messages (Toast):**
- "You already prayed for this" (403) → Button grayed, not clickable
- "Prayer is no longer active" (422) → Button disabled
- "Network error" (500) → Button shows retry state
- "Prayer not found" (404) → Hide button entirely

**Example Visual:**
```
┌──────────────────────────────┐
│  ❌ Could not record          │  ← Red gradient
│                              │
└──────────────────────────────┘

⚠️  You already prayed for this  ← Toast message
```

---

## INTERACTION FLOW

### Desktop Web (Scroll-based)

```
User sees prayer card (landscape)
        ↓
Reads prayer text
        ↓
Sees "🙏 Lift Prayer" button at bottom
        ↓
Hovers button (darkens, lifts)
        ↓
Clicks button
        ↓
Button → "⏳ Submitting..." (spinner)
        ↓
API request: POST /prayers/{id}/lift
        ↓
Success → "✓ Thank you for Praying"
        ↓
Fades out over 1 second (after 2 seconds visible)
        ↓
User scrolls to next prayer
```

---

### Mobile App (Swipe-based)

```
User swipes left/right through prayer cards
        ↓
Active prayer fills screen
        ↓
Sees "🙏 Lift Prayer" button at bottom (full width)
        ↓
Taps button
        ↓
Button → "⏳ Submitting..." (spinner, haptic feedback)
        ↓
API request: POST /prayers/{id}/lift
        ↓
Success → "✓ Thank you for Praying"
        ↓
Fades out (after 2 seconds visible)
        ↓
15-second timer displayed at top (countdown to next auto-swipe)
        ↓
User swipes or waits → Next prayer card
        ↓
Button resets to State 1 for new prayer
```

---

## TECHNICAL SPECIFICATIONS

### API Request
```
Method: POST
Endpoint: /api/prayers/{prayerId}/lift
Headers: Authorization: Bearer {token}
Body: {}
Success Response: 
  {
    "success": true,
    "message": "Prayer recorded",
    "participant_count": 52,
    "participant_breakdown": {
      "total": 52,
      "members": 34,
      "guests": 12,
      "anonymous": 6
    }
  }
Error Response:
  {
    "success": false,
    "error": "Already prayed for this",
    "code": "DUPLICATE_PARTICIPATION"
  }
```

### Button Properties
```
ID: lift-prayer-btn-{prayerId}
Data Attributes:
  - data-prayer-id={prayerId}
  - data-prayer-category={categoryName}
  - data-is-submitted=false (toggles to true after success)
  - data-participant-count={currentCount}

Accessibility:
  - aria-label="Lift prayer for {categoryName}"
  - aria-disabled=true (during submit/success)
  - role="button"
  - keyboard accessible (Enter/Space to activate)
```

---

## MOBILE HAPTIC FEEDBACK

**On Click:**
- Light haptic pulse (amplitude: 50%)

**On Success:**
- Success haptic: 2x short pulses (100ms each)

**On Error:**
- Error haptic: 3x weak pulses (50ms each)

---

## LOADING STATES - VISUAL TIMING

```
Click
  ↓ (0ms)
Spinner appears, text changes
  ↓ (50ms - debounce)
API in-flight
  ↓ (1000-2000ms typical)
Success response received
  ↓ (0ms)
Show checkmark, change color to green
  ↓ (2000ms)
Begin fade-out animation
  ↓ (1000ms fade duration)
Fully transparent
  ↓ (on next swipe/scroll)
Button resets to State 1
```

---

## VARIANTS BY CATEGORY

Button gradient color changes per prayer category:

```
🏥 Health      → Red to Pink (#EF4444 → #EC4899)
👨‍👩‍👧‍👦 Family      → Pink to Purple (#EC4899 → #A855F7)
💼 Employment  → Blue to Cyan (#3B82F6 → #06B6D4)
💰 Financial   → Yellow to Orange (#FBBF24 → #F97316)
✨ Personal    → Purple to Indigo (#A855F7 → #6366F1)
❤️ Other       → Green to Emerald (#10B981 → #059669)
```

**Success state:** All categories use green (#10B981)

---

## EDGE CASES & ERROR HANDLING

### Case 1: User Already Prayed
```
Response: 403 Forbidden
Message: "You already prayed for this"
Button State: Disabled (grayed, not clickable)
Tooltip: "You've already lifted this prayer"
```

### Case 2: Prayer Expired
```
Response: 422 Unprocessable
Message: "This prayer is no longer active"
Button State: Hidden or disabled
Reason: Prayer auto-expired or admin unpublished
```

### Case 3: User Not Authenticated
```
Response: 401 Unauthorized
Action: Redirect to login modal
Button State: Shows "Please sign in to pray"
```

### Case 4: Network Error
```
Response: Network timeout or 500+
Message: "Network error. Try again?"
Button State: Shows "Try Again" (state resets to default)
Allow Retry: User can click again immediately
```

### Case 5: Prayer Deleted
```
Response: 404 Not Found
Message: "Prayer no longer available"
Button State: Hidden
Action: Removed from DOM, card shows archived message
```

---

## ANIMATION KEYFRAMES

### Hover Animation (Desktop)
```css
@keyframes liftPrayerHover {
  0% { 
    transform: translateY(0px);
    box-shadow: 0 4px 6px rgba(0,0,0,0.1);
  }
  100% { 
    transform: translateY(-2px);
    box-shadow: 0 8px 12px rgba(0,0,0,0.15);
  }
}
Duration: 200ms, Easing: ease-out
```

### Submit Spinner
```css
@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}
Duration: 800ms, Repeat: infinite, Easing: linear
```

### Success Fade
```css
@keyframes fadeOut {
  0% { opacity: 1; }
  100% { opacity: 0; }
}
Delay: 2000ms, Duration: 1000ms, Easing: ease-out
```

---

## ACCESSIBILITY

- ✅ Keyboard navigable (Tab key)
- ✅ ARIA labels for screen readers
- ✅ Color not the only indicator (icon + text)
- ✅ Sufficient contrast (WCAG AA)
- ✅ Focus visible (2px outline on focus)
- ✅ Disabled state has visual indicator
- ✅ Loading state announced via aria-busy
- ✅ Toast messages announced via aria-live="polite"

---

## RESPONSIVE DESIGN

### Mobile (< 640px)
```
Width: 100% (full screen minus padding)
Height: 48px (tap target minimum)
Margin: 16px bottom spacing
Font-size: 16px (prevent zoom on iOS)
Padding: 12px vertical, 24px horizontal
```

### Tablet (640px - 1024px)
```
Width: 80%
Height: 48px
Center aligned
Font-size: 16px
```

### Desktop (> 1024px)
```
Width: 160px (fit in card)
Height: 44px
Right-aligned in prayer card
Font-size: 14px
```

---

## CODE HINTS FOR IMPLEMENTATION

```javascript
// Button click handler
async function liftPrayer(prayerId) {
  const btn = document.getElementById(`lift-prayer-btn-${prayerId}`);
  
  // Guard: Check if already submitted
  if (btn.dataset.isSubmitted === 'true') return;
  
  // State 2: Submitting
  btn.innerHTML = '⏳ Submitting...';
  btn.disabled = true;
  btn.classList.add('opacity-75', 'cursor-not-allowed');
  
  try {
    // API call
    const response = await fetch(`/api/prayers/${prayerId}/lift`, {
      method: 'POST',
      headers: { 'Authorization': `Bearer ${token}` }
    });
    
    if (response.ok) {
      // State 3A: Success
      btn.innerHTML = '✓ Thank you for Praying';
      btn.classList.replace('bg-blue-600', 'bg-green-600');
      btn.dataset.isSubmitted = 'true';
      
      // Fade out after 2 seconds
      setTimeout(() => {
        btn.classList.add('fade-out');
      }, 2000);
    } else {
      // State 3B: Error
      showError(await response.json());
      btn.innerHTML = '❌ Could not record';
      btn.classList.add('bg-red-600');
      btn.disabled = false;
    }
  } catch (error) {
    // Network error
    btn.innerHTML = '🔄 Try Again';
    btn.disabled = false;
  }
}

// On next swipe (mobile) or scroll (desktop)
function resetLiftButton(prayerId) {
  const btn = document.getElementById(`lift-prayer-btn-${prayerId}`);
  btn.innerHTML = '🙏 Lift Prayer';
  btn.classList.remove('fade-out', 'bg-red-600', 'opacity-75');
  btn.classList.add(`bg-${getPrayerCategoryColor(prayerId)}`);
  btn.disabled = false;
  btn.dataset.isSubmitted = 'false';
}
```

---

## SUMMARY FOR CO-PILOT

**One sentence:** A category-colored gradient button that shows spinner while submitting, displays success confirmation with green checkmark (auto-fades after 2s), and prevents duplicate participation.

**Key States:**
1. 🙏 Lift Prayer (default)
2. ⏳ Submitting... (loading)
3. ✓ Thank you for Praying (success, fades after 2s)
4. ❌ Could not record (error, red)

**Must-haves:**
- Gradient matches prayer category
- Disabled during submit
- Success auto-fades out
- Resets on next swipe/scroll
- Full width mobile, fixed width desktop
- Works with API response handling
- Handles all error cases gracefully
