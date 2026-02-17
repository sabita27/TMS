# Client Service Engagement Update

This update adds "Service" as a primary engagement type for clients, alongside "Product" and "Project".

## Changes Implemented

1.  **Database Migration**:
    - Added `service_id` JSON column to `clients` table to store multiple selected services.

2.  **Model Update (`Client.php`)**:
    - Added `service_id` to `$fillable`.
    - Added `service_id` to `$casts` as `array`.

3.  **Controller Logic (`MasterController.php`)**:
    - Fetched `services` list in `clients()` method.
    - Updated `storeClient` and `updateClient` validation to allow `service` business type and validate `service_id` array.

4.  **UI Enhancements (`clients.blade.php`)**:
    - **Engagement Toggle**: Added "Service" option. Renamed "Both" to "All / Mix" to represent any combination.
    - **Form Fields**: Added a multi-select dropdown for Services, powered by Select2.
    - **Data Table**: Added a "Services" column to the client list.
    - **View Modal**: Updated the view logic to dynamically display selected services with color-coded badges (Purple/Fuchsia theme for services).

Now, when adding or editing a client, you can select "Service" to assign services, or "All / Mix" to assign a combination of Products, Projects, and Services.
