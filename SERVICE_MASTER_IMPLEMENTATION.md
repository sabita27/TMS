# Service Master Implementation Summary

This document outlines the implementation of the "Service Master" module, which allows for the management of services within the application. This module mirrors the functionality and design of the Project and Product masters.

## 1. Database Schema
Created a new migration `2026_02_17_102353_create_services_table.php` with the following structure:
- `id`: Primary Key
- `category_id`: Foreign Key to `product_categories` (nullable)
- `subcategory_id`: Foreign Key to `product_sub_categories` (nullable)
- `name`: String, unique
- `price`: Decimal (10, 2), default 0
- `description`: Text (nullable)
- `status`: Boolean (active/inactive)
- `timestamps`: Created at / Updated at

## 2. Model
Created `App\Models\Service.php` with:
- Fillable fields matches the schema.
- Relationships:
  - `category()`: BelongsTo `ProductCategory`
  - `subcategory()`: BelongsTo `ProductSubCategory`

## 3. Controller Logic (`MasterController.php`)
Added the following methods to `MasterController`:
- `services()`: Lists services with pagination (10 items), including category and subcategory data.
- `storeService(Request $request)`: Validates and creates a new service.
- `editService(Service $service)`: Returns service data as JSON for the edit modal.
- `updateService(Request $request, Service $service)`: Validates and updates an existing service.
- `destroyService(Service $service)`: Deletes a service.

## 4. Routes (`web.php`)
Added a new route group under `admin` prefix and `auth` middleware:
- `GET /admin/services`: List services
- `POST /admin/services`: Store new service
- `GET /admin/services/{service}/edit`: Fetch service for editing
- `PUT /admin/services/{service}`: Update service
- `DELETE /admin/services/{service}`: Delete service

## 5. UI Implementation (`services.blade.php`)
Created a new view `resources/views/admin/masters/services.blade.php` featuring:
- **Professional Table Layout**: Displays Name, Category, Sub-Category, Price, and Status with color-coded badges.
- **Dynamic Interaction**:
  - "Add Service" Modal with dynamic Category -> Subcategory dropdowns.
  - "Edit Service" Modal that pre-fills data and handles dynamic subcategory loading.
  - **CKEditor**: Integrated for rich text editing of the service description.
- **Validation**: Client-side (HTML5) and Server-side validation handles errors gracefully.

## 6. Navigation
Updated `resources/views/layouts/backend/navbar.blade.php` to include the "Service Master" link in the sidebar, positioned after "Project Master".

## 7. Notification Adjustment
Decreased the global notification (toast) display time from 5 seconds to 3 seconds in `footer.blade.php` for a snappier user experience.
