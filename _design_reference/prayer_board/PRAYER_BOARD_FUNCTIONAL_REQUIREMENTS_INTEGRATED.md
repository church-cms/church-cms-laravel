# Prayer Board - Updated Functional Requirements (Integrated)

**Version:** 2.0  
**Updated:** April 2026  
**Status:** Leveraging Global Infrastructure

---

## Overview: Simplified Architecture

The Prayer Board system now integrates with existing infrastructure:

```
┌──────────────────────────────────────────────────┐
│    Global Infrastructure (Existing)               │
├──────────────────────────────────────────────────┤
│ • GlobalSettings (key-value with JSON)            │
│ • GlobalAuditLog (centralized audit trail)        │
│ • User management                                 │
└──────────────────────────────────────────────────┘
         ↓
┌──────────────────────────────────────────────────┐
│    Prayer Board System (New)                      │
├──────────────────────────────────────────────────┤
│ • Prayer (core entity)                            │
│ • PrayerParticipant (intercession tracking)      │
│ • Admin Dashboard (UI layer)                      │
└──────────────────────────────────────────────────┘
```

---

## Module 1: Category Management (Simplified)

### FR 1.1: List Prayer Categories
**Query From:** GlobalSettings table  
**Key Pattern:** `prayer:categories:*`

**SQL:**
```sql
SELECT 
  REPLACE(key, 'prayer:categories:', '') as categoryId,
  JSON_EXTRACT(value, '$.name') as name,
  JSON_EXTRACT(value, '$.emoji') as emoji,
  JSON_EXTRACT(value, '$.cssClass') as cssClass,
  JSON_EXTRACT(value, '$.displayColor') as color,
  JSON_EXTRACT(value, '$.sortOrder') as sortOrder,
  JSON_EXTRACT(value, '$.isActive') as isActive,
  value as fullConfig
FROM GlobalSettings
WHERE key LIKE 'prayer:categories:%'
AND JSON_EXTRACT(value, '$.isActive') = true
ORDER BY JSON_EXTRACT(value, '$.sortOrder') ASC
```

**Output:**
```javascript
[
  {
    categoryId: "health",
    name: "Health",
    emoji: "🏥",
    cssClass: "category-health",
    color: "#EF4444",
    sortOrder: 1,
    isActive: true
  },
  // ... more categories
]
```

### FR 1.2: Get Category Details
**Query From:** GlobalSettings  
**Key:** `prayer:categories:{categoryId}`

**Process:**
1. Query GlobalSettings by key
2. Parse JSON value
3. Return full configuration

**Output:**
```javascript
{
  categoryId: "health",
  name: "Health",
  emoji: "🏥",
  cssClass: "category-health",
  displayColor: "#EF4444",
  gradientStart: "#FEE2E2",
  gradientEnd: "#FCE7F3",
  sortOrder: 1,
  isActive: true,
  allowAnonymousSubmission: false,
  allowAnonymousParticipation: true,
  defaultExpiryDays: 60,
  minExpiryDays: 7,
  maxExpiryDays: 90,
  autoDeleteRejectedAfterDays: 7,
  description: "Physical and mental health concerns"
}
```

### FR 1.3: Create Prayer Category
**Actor:** Head Admin only  
**Input:** Category configuration  
**Process:**

1. Generate key from category name:
   ```javascript
   key = "prayer:categories:" + categoryName.toLowerCase().replace(/\s+/g, '-')
   // e.g., "Health" → "prayer:categories:health"
   ```

2. Validate input:
   - name: not empty, unique (no other setting with same categoryId)
   - emoji: single character, not already used
   - colorStomach values: valid hex
   - expiry ranges: min <= default <= max

3. Check for existing category:
   ```sql
   SELECT COUNT(*) FROM GlobalSettings
   WHERE key = 'prayer:categories:' + categoryId
   ```
   - If exists, throw error: "Category already exists"

4. Insert to GlobalSettings:
   ```sql
   INSERT INTO GlobalSettings (key, value, createdAt, createdBy)
   VALUES (
     'prayer:categories:health',
     JSON_OBJECT(
       'name', 'Health',
       'emoji', '🏥',
       'cssClass', 'category-health',
       'displayColor', '#EF4444',
       'gradientStart', '#FEE2E2',
       'gradientEnd', '#FCE7F3',
       'sortOrder', 1,
       'isActive', true,
       'allowAnonymousSubmission', false,
       'allowAnonymousParticipation', true,
       'defaultExpiryDays', 60,
       'minExpiryDays', 7,
       'maxExpiryDays', 90,
       'autoDeleteRejectedAfterDays', 7,
       'autoDeleteExpiredAfterDays', 365,
       'description', 'Physical health concerns'
     ),
     NOW(),
     'admin-001'
   )
   ```

5. Create audit log entry:
   ```javascript
   logAuditEvent(
     entityType: "category",
     entityId: "health",
     action: "CREATED",
     userId: "admin-001",
     changes: { name, emoji, cssClass, ... }
   )
   ```

6. Return created category

**Outcome:** New category available for prayer submission

### FR 1.4: Update Prayer Category Settings
**Actor:** Head Admin  
**Input:** Category ID, fields to update  
**Process:**

1. Query existing setting:
   ```sql
   SELECT value FROM GlobalSettings
   WHERE key = 'prayer:categories:' + categoryId
   ```

2. Parse current JSON value

3. Validate update data:
   - Don't allow changing `name`, `emoji`, `cssClass` (immutable)
   - Allow: `sortOrder`, `isActive`, `allowAnonymous*`, `defaultExpiryDays`, etc.

4. Merge updates:
   ```javascript
   const currentConfig = JSON.parse(setting.value);
   const newConfig = { ...currentConfig, ...updateData };
   ```

5. Update GlobalSettings:
   ```sql
   UPDATE GlobalSettings
   SET value = JSON_OBJECT(...),
       updatedAt = NOW(),
       updatedBy = 'admin-001'
   WHERE key = 'prayer:categories:health'
   ```

6. Log audit event:
   ```javascript
   logAuditEvent(
     entityType: "category",
     entityId: "health",
     action: "UPDATED",
     userId: "admin-001",
     changes: {
       oldValues: { field1: oldValue },
       newValues: { field1: newValue }
     }
   )
   ```

7. Invalidate category cache (if using caching)

8. Return updated category

**Outcome:** Category configuration updated, reflected in UI

### FR 1.5: Deactivate Prayer Category
**Actor:** Head Admin  
**Input:** Category ID  
**Process:**

1. Query category setting
2. Set `isActive = false`
3. Update GlobalSettings:
   ```sql
   UPDATE GlobalSettings
   SET value = JSON_SET(value, '$.isActive', false),
       updatedAt = NOW(),
       updatedBy = 'admin-001'
   WHERE key = 'prayer:categories:health'
   ```
4. Category hidden from submission form
5. Existing prayers in category remain active
6. Log audit event: action = "DEACTIVATED"

**Outcome:** Category no longer available for new prayers

### FR 1.6: Delete Prayer Category
**Actor:** Head Admin  
**Precondition:** No ACTIVE or PENDING prayers in category  
**Process:**

1. Check constraint:
   ```sql
   SELECT COUNT(*) FROM Prayer
   WHERE categoryId = 'health'
   AND status IN ('ACTIVE', 'PENDING')
   ```
   - If count > 0, throw error: "Cannot delete category with active prayers"

2. Delete from GlobalSettings:
   ```sql
   DELETE FROM GlobalSettings
   WHERE key = 'prayer:categories:health'
   ```

3. Log audit event:
   ```javascript
   logAuditEvent(
     entityType: "category",
     entityId: "health",
     action: "DELETED",
     userId: "admin-001",
     changes: { deletedAt: NOW() }
   )
   ```

**Outcome:** Category completely removed

---

## Module 2: Prayer Request Management (Audit Integration)

### FR 2.1: Submit Prayer Request
**Process Changes:**
1. Create Prayer record (as before)
2. **NEW:** Log to GlobalAuditLog:
   ```javascript
   logAuditEvent(
     entityType: "prayer",
     entityId: prayerId,
     action: "CREATED",
     userId: currentUser.id,
     changes: {
       categoryId: request.categoryId,
       text: prayerText.substring(0, 50) + "...", // preview
       submittedAt: NOW()
     }
   )
   ```
3. Return created prayer

### FR 2.2-2.9: Admin Actions (All Log to GlobalAuditLog)

#### FR 2.4: Approve Prayer Request
**Process:**
1. Update Prayer.status = "ACTIVE"
2. **NEW:** Log approval:
   ```javascript
   logAuditEvent(
     entityType: "prayer",
     entityId: prayerId,
     action: "APPROVED",
     userId: adminId,
     changes: {
       status: "PENDING → ACTIVE",
       expiryDays: 60,
       approvedAt: NOW(),
       approvedBy: adminId,
       expiresAt: expiryDate
     }
   )
   ```
3. Send confirmation email
4. Return updated prayer

#### FR 2.5: Reject Prayer Request
**Process:**
1. Update Prayer.status = "REJECTED"
2. **NEW:** Log rejection:
   ```javascript
   logAuditEvent(
     entityType: "prayer",
     entityId: prayerId,
     action: "REJECTED",
     userId: adminId,
     changes: {
       status: "PENDING → REJECTED",
       rejectionReason: input.reason,
       rejectedAt: NOW(),
       rejectedBy: adminId,
       shouldDeleteAt: NOW() + 7 days
     }
   )
   ```
3. Send rejection email
4. Return updated prayer

#### FR 2.6: Mark Prayer Answered
**Process:**
1. Update Prayer.status = "ANSWERED"
2. **NEW:** Log answer:
   ```javascript
   logAuditEvent(
     entityType: "prayer",
     entityId: prayerId,
     action: "ANSWERED",
     userId: currentUser.id,
     changes: {
       status: "ACTIVE → ANSWERED",
       answerTestimony: input.testimony,
       answeredAt: NOW(),
       answeredBy: currentUser.id
     }
   )
   ```
3. Move to testimony archive
4. Return updated prayer

#### FR 2.7: Extend Prayer Expiry
**Process:**
1. Update Prayer.expiryDays and expiresAt
2. **NEW:** Log extension:
   ```javascript
   logAuditEvent(
     entityType: "prayer",
     entityId: prayerId,
     action: "EXTENDED",
     userId: adminId,
     changes: {
       oldExpiryDays: 60,
       newExpiryDays: 90,
       oldExpiresAt: expiresAt1,
       newExpiresAt: expiresAt2
     }
   )
   ```
3. Return updated prayer with new expiry

#### FR 2.8: Pin Prayer to Top
**Process:**
1. Update Prayer.pinnedAt = NOW()
2. **NEW:** Log pin:
   ```javascript
   logAuditEvent(
     entityType: "prayer",
     entityId: prayerId,
     action: "PINNED",
     userId: adminId,
     changes: {
       pinnedAt: NOW(),
       pinnedBy: adminId
     }
   )
   ```
3. Return updated prayer

#### FR 2.9: Unpublish Prayer
**Process:**
1. Update Prayer.status = "UNPUBLISHED"
2. **NEW:** Log unpublish:
   ```javascript
   logAuditEvent(
     entityType: "prayer",
     entityId: prayerId,
     action: "UNPUBLISHED",
     userId: adminId,
     changes: {
       status: "ACTIVE → UNPUBLISHED",
       removedAt: NOW()
     }
   )
   ```
3. Return updated prayer

### FR 2.10: Auto-Expire Prayers (Nightly Job)
**Process:**
1. Query expired prayers
2. For each:
   - Update status = "ENDED"
   - **NEW:** Log expiry:
     ```javascript
     logAuditEvent(
       entityType: "prayer",
       entityId: prayerId,
       action: "EXPIRED",
       userId: "system",
       changes: {
         status: "ACTIVE → ENDED",
         expiredAt: NOW(),
         daysActive: daysActive,
         finalParticipantCount: participantCount
       }
     )
     ```

### FR 2.11: Auto-Delete Rejected Prayers (Nightly Job)
**Process:**
1. Query rejected prayers past TTL
2. For each:
   - Delete from Prayer & PrayerParticipant
   - **NEW:** Log deletion:
     ```javascript
     logAuditEvent(
       entityType: "prayer",
       entityId: prayerId,
       action: "DELETED",
       userId: "system",
       changes: {
         status: "REJECTED",
         deletedAt: NOW(),
         retentionDays: 7
       }
     )
     ```

---

## Module 3: Prayer Participation Tracking (Unchanged)

### FR 3.1: Lift Prayer (Record Intercession)
**No changes to core logic, but can log if needed:**

```javascript
// Optional: Log participation for advanced analytics
logAuditEvent(
  entityType: "prayer",
  entityId: prayerId,
  action: "PARTICIPATED",
  userId: currentUser.id,
  changes: {
    participantType: userType,
    prayedAt: NOW()
  }
)
// Note: May be too verbose if thousands of participations.
// Only log if analytics requirement exists.
```

### FR 3.2-3.4: Query Participation (Unchanged)

---

## Module 4: Admin Moderation (Query from GlobalAuditLog)

### FR 4.1-4.8: Admin Dashboard

**Admin tabs unchanged, but queries now pull from Prayer + GlobalAuditLog:**

#### Get Prayer Approval History
```sql
SELECT 
  p.*,
  gal.action,
  gal.userId as adminId,
  gal.timestamp,
  u.name as adminName
FROM Prayer p
LEFT JOIN GlobalAuditLog gal 
  ON p.id = gal.entityId 
  AND gal.entityType = 'prayer'
  AND gal.action IN ('APPROVED', 'REJECTED', 'EXTENDED', 'PINNED')
LEFT JOIN User u ON gal.userId = u.id
WHERE p.id = 'pr-001'
ORDER BY gal.timestamp DESC
```

#### Get All Admin Actions on Prayer
```sql
SELECT 
  gal.*,
  u.name as adminName,
  u.email
FROM GlobalAuditLog gal
LEFT JOIN User u ON gal.userId = u.id
WHERE gal.entityType = 'prayer'
AND gal.entityId = 'pr-001'
ORDER BY gal.timestamp DESC
```

#### Get All Actions by Admin
```sql
SELECT 
  gal.*,
  p.status,
  p.categoryId
FROM GlobalAuditLog gal
LEFT JOIN Prayer p ON gal.entityId = p.id AND gal.entityType = 'prayer'
WHERE gal.userId = 'admin-001'
AND gal.entityType = 'prayer'
ORDER BY gal.timestamp DESC
```

#### Dashboard Statistics
```sql
-- Approvals this week
SELECT COUNT(*) FROM GlobalAuditLog
WHERE entityType = 'prayer'
AND action = 'APPROVED'
AND timestamp >= DATE_SUB(NOW(), INTERVAL 7 DAY);

-- Rejections this week
SELECT COUNT(*) FROM GlobalAuditLog
WHERE entityType = 'prayer'
AND action = 'REJECTED'
AND timestamp >= DATE_SUB(NOW(), INTERVAL 7 DAY);

-- Average approval time
SELECT 
  AVG(TIMESTAMPDIFF(HOUR, 
    MIN(CASE WHEN action = 'CREATED' THEN timestamp END),
    MIN(CASE WHEN action = 'APPROVED' THEN timestamp END)
  )) as avgHoursToApprove
FROM GlobalAuditLog
WHERE entityType = 'prayer'
GROUP BY entityId
```

---

## Module 5 & 6: Public Board & User Dashboard (Unchanged)

No changes needed - Prayer & PrayerParticipant tables unchanged.

---

## Background Jobs (Updated with Audit Logging)

### Job 1: Nightly Prayer Expiry (2:00 AM)
```javascript
async function nightly_ExpirePrayers() {
  const expiredPrayers = await Prayer.findAll({
    status: 'ACTIVE',
    where: { expiresAt: { [Op.lte]: NOW() } }
  });

  for (const prayer of expiredPrayers) {
    // Update prayer
    await prayer.update({ status: 'ENDED' });

    // Log to GlobalAuditLog
    await logAuditEvent(
      'prayer',
      prayer.id,
      'EXPIRED',
      'system',
      {
        status: 'ACTIVE → ENDED',
        expiredAt: NOW(),
        participantCount: prayer.participantCount
      }
    );
  }

  console.log(`[EXPIRE] ${expiredPrayers.length} prayers expired`);
}
```

### Job 2: Rejected Prayer Cleanup (2:30 AM)
```javascript
async function nightly_DeleteRejectedPrayers() {
  const prayersToDelete = await Prayer.findAll({
    status: 'REJECTED',
    where: { shouldDeleteAt: { [Op.lte]: NOW() } }
  });

  for (const prayer of prayersToDelete) {
    const prayerId = prayer.id;

    // Delete participants
    await PrayerParticipant.destroy({
      where: { prayerId: prayerId }
    });

    // Delete prayer
    await prayer.destroy();

    // Log to GlobalAuditLog
    await logAuditEvent(
      'prayer',
      prayerId,
      'DELETED',
      'system',
      {
        status: 'REJECTED',
        deletedAfterDays: 7
      }
    );
  }

  console.log(`[DELETE] ${prayersToDelete.length} rejected prayers deleted`);
}
```

### Job 3: Settings Cache Invalidation (On Change)
```javascript
// Listen for GlobalSettings changes
EventBus.on('setting:updated', (key) => {
  if (key.startsWith('prayer:categories:')) {
    // Invalidate category cache
    CacheManager.invalidate('prayer:categories');
    console.log(`[CACHE] Invalidated categories cache for ${key}`);
  }
});
```

---

## API Specifications (Updated)

### Category Endpoints (Query GlobalSettings)

```
GET /api/prayer-categories
  Query: GlobalSettings where key LIKE 'prayer:categories:%'
  Returns: Array of active categories

GET /api/prayer-categories/:categoryId
  Query: GlobalSettings where key = 'prayer:categories:' + categoryId
  Returns: Full category configuration

POST /api/prayer-categories
  Insert to GlobalSettings
  Log: logAuditEvent('category', categoryId, 'CREATED', ...)
  Returns: Created category

PUT /api/prayer-categories/:categoryId
  Update GlobalSettings JSON
  Log: logAuditEvent('category', categoryId, 'UPDATED', ...)
  Returns: Updated category

DELETE /api/prayer-categories/:categoryId
  Delete from GlobalSettings
  Log: logAuditEvent('category', categoryId, 'DELETED', ...)
  Returns: Deletion confirmation
```

### Audit Log Queries

```
GET /api/admin/prayers/:id/audit
  Query: GlobalAuditLog where entityId = prayerId
  Returns: Complete audit trail for prayer

GET /api/admin/audit-log
  Query: GlobalAuditLog with filters
  Filters: entityType, entityId, action, userId, dateRange
  Returns: Filtered audit entries (paginated)

GET /api/admin/users/:userId/actions
  Query: GlobalAuditLog where userId = adminId
  Returns: All actions performed by admin
```

---

## Benefits of This Integration

| Aspect | Benefit |
|--------|---------|
| **Simplified Schema** | Only 3 tables instead of 6 |
| **Centralized Config** | Prayer categories managed like other system settings |
| **Unified Audit Trail** | All system changes in one audit log table |
| **Easier Maintenance** | Use existing GlobalSettings/GlobalAuditLog infrastructure |
| **Better Scalability** | Infrastructure already handles system-wide load |
| **Compliance Ready** | Centralized audit log meets regulatory requirements |
| **Extensibility** | Add new prayer settings without schema changes |
| **Cache Friendly** | Settings cached at application level |

---

## Implementation Checklist

- [ ] Create Prayer table
- [ ] Create PrayerParticipant table
- [ ] Insert prayer categories into GlobalSettings
- [ ] Create category helper functions (getCategory, listCategories)
- [ ] Implement logAuditEvent wrapper for consistency
- [ ] Create Prayer management endpoints
- [ ] Create Admin moderation endpoints
- [ ] Create Public board endpoints
- [ ] Create User dashboard endpoints
- [ ] Implement nightly jobs with audit logging
- [ ] Add category caching
- [ ] Add audit log retention policy
- [ ] Test all prayer workflows
- [ ] Test all audit logging events
- [ ] Performance testing (especially audit queries)

---

**End of Updated Functional Requirements Document**
