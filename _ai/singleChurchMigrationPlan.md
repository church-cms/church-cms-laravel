## Plan: Single-Church Open Source Migration

Convert the app from request-driven multi-tenancy to enforced single-church behavior without immediate schema drops, while removing multi-church code paths in app logic and preserving backward-compatible public APIs. The approach is to introduce a canonical church resolver (auto-detect first active church), route all reads/writes through it, ignore incoming church_id from clients, then prune tenant-only code paths and keep church_id columns temporarily for safe rollback.

**Steps**
1. Phase 1: Canonical church context foundation
   - Add a single source of truth for church resolution (service/helper) that selects the first active church and caches the result per request; fail fast with a clear admin error if no active church exists. 
   - Wire this resolver into request lifecycle (middleware/service provider) so controllers, repositories, and listeners can consume the same current church id without using Auth::user()->church_id or URL church_id. (*blocks phases 2-4*)

2. Phase 2: Data access refactor (core behavior)
   - Replace direct tenant filters in high-traffic admin controllers and shared traits/scopes with resolver-based filtering; where feature requires church filter, force canonical id from resolver. (*depends on 1-3*)
   - Refactor create/update flows to set church_id from resolver/server-side only, never from request payload. (*depends on 1-3; parallel with step 5 per module*)
   - Remove or neutralize user-level by-church scope usage that assumed SaaS separation when it conflicts with single-church behavior. Keep minimal compatibility wrappers to reduce regression risk. (*depends on 5*)

3. Phase 3: Public API compatibility pass
   - Keep existing guest endpoints that include {church_id} or request church_id, but ignore provided values and use canonical church id internally. Return unchanged response contracts. (*depends on 1-3*)
   - Update request validation rules to make church_id optional/ignored in guest/admin request objects where currently required from client input. (*parallel with step 9*)
   - Add deprecation logging for church_id values received from clients to measure old client usage before eventual API cleanup. (*parallel with step 9*)

4. Phase 4: Remove multi-church code paths in app layer
   - Remove admin flows and route actions that manage/select multiple churches, per your decision to remove code paths now; keep minimal migration notes for operators. (*depends on 5-10*)
   - Prune observers/listeners/events that pass church_id around when redundant under canonical context. Maintain notification audience behavior by using canonical context instead of event payload church_id. (*depends on 5-10*)

5. Phase 5: Stability + optional schema follow-up
   - Keep church_id columns and FK constraints in this release (reversible mode), but add defaults/backfill where needed to reduce null drift.
   - Prepare a separate, delayed hard-cleanup plan (drop columns/routes/contracts) only after one stable release and usage telemetry confirms no dependency on tenant inputs. (*future, excluded from this implementation*)

**Relevant files**
- routes/guestapi.php — preserve endpoint paths while ignoring incoming church_id.
- routes/api.php — review church discovery endpoints and remove/adjust multi-church listing behavior.
- app/Models/User.php — revise scopeByChurch and church-dependent query helpers.
- app/Traits/MemberProcess.php — replace ByChurch usage with canonical resolver strategy.
- app/Http/Controllers/Admin/DashboardController.php — remove Auth::user()->church_id-based filtering.
- app/Http/Controllers/Admin/VideoConferencesController.php — refactor repeated direct church filters.
- app/Http/Controllers/Admin/ReportsController.php — refactor report queries to canonical church context.
- app/Http/Controllers/Api/Guest/FundController.php — ignore payload church_id and enforce canonical church assignment.
- app/Http/Controllers/Api/Guest/FeedbackController.php — same compatibility behavior for feedback flow.
- app/Http/Controllers/Api/Guest/ContactController.php — enforce server-side church context.
- app/Http/Controllers/Api/Guest/LoginController.php — remove guest registration dependency on request church_id.
- app/Repositories/HelpRepository.php — remove church_id method parameter assumptions.
- app/Listeners/PushEventListener.php — replace event church_id filtering with canonical context.
- app/Providers/AppServiceProvider.php — bind canonical church resolver.
- config/church-cms.php — single-church runtime toggles/config.

**Verification**
1. Automated grep gate: no new writes should trust request church_id (controllers/requests/listeners) except backward-compat input fields marked ignored.
2. Route contract check: existing guest routes with {church_id} still resolve and return 200 for known payloads.
3. Functional smoke tests:
   - Admin dashboard, reports, video conference list, church details pages load with expected records under canonical church.
   - Guest donation, feedback, contact, and guest registration succeed when sending wrong church_id and still persist to canonical church.
   - Notification/listener flows still target expected users under single-church context.
   - Data integrity checks in DB: new rows consistently use canonical church id; no unexpected null church_id growth.
   - Log review confirms deprecation events are emitted for inbound church_id usage.

**Decisions**
- Migration style: Safe/reversible mode (do not drop church_id columns in this release).
- Single church source: Auto-detect first active church at runtime.
- Guest API contract: Keep old endpoints and ignore provided church_id server-side.
- Admin scope handling: Remove multi-church code paths now (not just hide).
- Included scope: application logic, routes/controllers/requests/listeners, compatibility and telemetry.
- Excluded scope: destructive DB schema removal of church_id columns and immediate API contract removal.

**Further Considerations**
1. Church resolver tie-break rule should be deterministic (recommended: lowest id among active churches) to avoid surprises.
2. If multiple active churches exist in existing DBs, include an operator command to set exactly one canonical active church before rollout.
3. Decide when to cut v2 API deprecation window for church_id path/payload removal after telemetry confirms low usage.
