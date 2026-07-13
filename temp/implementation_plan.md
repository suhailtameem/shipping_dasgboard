# Receiver Table — Clean Architecture (Approved)

## Background

Two previous approaches were abandoned:
1. Storing receiver data inline on `shipping_requests` (fields like `rec_name`, `rec_phone`)
2. Reusing the `customers` table with a `type` column

The new approach: **a dedicated `receivers` table** with a FK to `customers`, and `shipping_requests.rid` points to `receivers.id`.

---

## Amendments from Review

> [!IMPORTANT]
> **1 · Drop old inline fields** — `rec_name`, `rec_phone`, `rec_phone2` will be **removed** from `shipping_requests`. The migration file is updated and SQL is provided below for manual execution.

> [!IMPORTANT]
> **2 · Dedicated receiver-profile modal** — A brand-new `receiver-profile-modal.blade.php` component is created instead of reusing `customer-profile-modal`. It reads from the `receivers` table.

> [!IMPORTANT]
> **3 · Extra columns on receivers** — `prof_id_img` (string, nullable) and `verify_id` (boolean, default false) added to `receivers` table.

---

## SQL — Manual Table Alterations

Run these statements manually against your database after migration:

```sql
-- Remove old inline receiver fields from shipping_requests
ALTER TABLE shipping_requests
  DROP COLUMN IF EXISTS rec_name,
  DROP COLUMN IF EXISTS rec_phone,
  DROP COLUMN IF EXISTS rec_phone2;

-- send_name / send_phone are sender-side inline fields — leave them for now
-- (sender is still from customers table, no change)
```

---

## Proposed Changes

### 1 · Database

#### [NEW] Migration — `create_receivers_table`
| Column | Type | Notes |
|---|---|---|
| `id` | bigint PK | |
| `cid` | string | Customer who owns this receiver |
| `first` | string nullable | |
| `last` | string nullable | |
| `full` | string nullable | |
| `phone` | string nullable | |
| `phone2` | string nullable | |
| `email` | string nullable | |
| `country` | string nullable | |
| `address` | string nullable | |
| `prof_id_img` | string nullable | Optional ID proof image path |
| `verify_id` | boolean default false | Identity verified flag |
| `timestamps` | | |

#### [MODIFY] Migration — `create_shipping_requests_table`
- Remove `rec_name`, `rec_phone`, `rec_phone2` columns from the `up()` method.
- *(Existing table is altered manually via SQL above)*

---

### 2 · Models

#### [NEW] [receiver.php](file:///d:/Projects/Shipping_app/Shipping_v2/app/Models/receiver.php)
- `$fillable` with all above columns.
- `belongsTo(customers::class, 'cid')`.

#### [MODIFY] [customers.php](file:///d:/Projects/Shipping_app/Shipping_v2/app/Models/customers.php)
- Add `hasMany(receiver::class, 'cid')`.

#### [MODIFY] [ShippingRequest.php](file:///d:/Projects/Shipping_app/Shipping_v2/app/Models/ShippingRequest.php)
- Change `receiver()` → `belongsTo(receiver::class, 'rid')`.
- Remove `rec_name`, `rec_phone`, `rec_phone2` from `$fillable`.

---

### 3 · Controller

#### [NEW] [receiverController.php](file:///d:/Projects/Shipping_app/Shipping_v2/app/Http/Controllers/receiverController.php)
- `static getReceiversByCustomer($cid)` — returns receivers for a customer.
- `store(Request $request)` — validates & creates a receiver linked to `cid`.
- `assignToRequest(Request $request)` — sets `shipping_requests.rid`.
- `updateReceiver(Request $request)` — updates an existing receiver record.

#### [MODIFY] [requestsControllrt.php](file:///d:/Projects/Shipping_app/Shipping_v2/app/Http/Controllers/requestsControllrt.php)
- In `showRequestDetails()` pass `'receivers' => receiverController::getReceiversByCustomer($shipment->cid)`.

#### [MODIFY] [ShippingRequestController.php](file:///d:/Projects/Shipping_app/Shipping_v2/app/Http/Controllers/ShippingRequestController.php)
- Remove old `assignReceiver()` method (replaced by `receiverController@assignToRequest`).

---

### 4 · Routes

#### [MODIFY] [web.php](file:///d:/Projects/Shipping_app/Shipping_v2/routes/web.php)
```php
Route::controller(receiverController::class)->group(function () {
    Route::post('/storeReceiver',       'store');
    Route::post('/updateReceiver',      'updateReceiver');
    Route::post('/adminAssignReceiver', 'assignToRequest'); // replaces old route
});
```

---

### 5 · Blade Components

#### [NEW] [receiver-profile-modal.blade.php](file:///d:/Projects/Shipping_app/Shipping_v2/resources/views/components/receiver-profile-modal.blade.php)
- Dedicated edit modal for a receiver row (`$receiver` prop, `$countries`, `$dir`, `id`).
- Form posts to `POST /updateReceiver`.
- Fields: First, Last, Phone, Phone2, Email, Country, Address, ID Image upload, Verified toggle.

#### [MODIFY] [assign-customer-modal.blade.php](file:///d:/Projects/Shipping_app/Shipping_v2/resources/views/components/assign-customer-modal.blade.php)
- For `type='receiver'`: iterate `$receivers` variable (not `$customers`). Columns: Name, Phone, Email.
- For `type='sender'`: unchanged.

---

### 6 · View — `requests.blade.php`

#### [MODIFY] [requests.blade.php](file:///d:/Projects/Shipping_app/Shipping_v2/resources/views/shipping/requests.blade.php)
- Receiver card "New" button → opens `#CreateReceiverModal` (new, not `#CreateCustomer`).
- Add `#CreateReceiverModal` with `storeReceiver` form fields.
- Replace `x-customer-profile-modal` (editReceiver) with `x-receiver-profile-modal`.
- Keep all sender-side UI unchanged.

---

### 7 · API

#### [MODIFY] [apiController.php](file:///d:/Projects/Shipping_app/Shipping_v2/app/Http/Controllers/apiController.php)
`getCustomerRequests()` returns:
```json
{
  "customer": { "id": 1, "first": "...", ... },
  "receivers": [ { "id": 1, "first": "...", "phone": "...", "verify_id": true, ... } ],
  "requests": [
    {
      "id": 10,
      "tno": "ABC12",
      "receiver": { "id": 1, "first": "...", "phone": "...", "verify_id": true }
    }
  ]
}
```

---

## Verification Plan

### Automated Tests
```bash
php artisan migrate
vendor/bin/phpunit
```

### Manual Verification
1. Run the SQL alter statements to drop old columns.
2. Open a request detail page → "New" under Receiver → Create Receiver modal opens.
3. Submit → receiver saved, page reloads with success flash.
4. "Assign" → modal shows only receivers for that customer.
5. Assign → receiver card updates with new receiver's data.
6. Edit receiver (pencil icon) → new `receiver-profile-modal` opens pre-filled.
7. `POST /api/getCusRequests` with `cid` → JSON has `customer`, `receivers[]`, `requests[].receiver`.
