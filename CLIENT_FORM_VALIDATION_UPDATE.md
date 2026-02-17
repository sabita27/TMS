# Client Form Validation Update

This update enforces stricter validation rules for Client details, ensuring all primary contact information is captured.

## Changes Implemented

1.  **Validation Logic (`MasterController.php`)**:
    - **Marked as Required**:
        - **Organization Name**: (Existing)
        - **Email**: (Existing)
        - **Phone**: Now mandatory.
        - **Address**: Now mandatory.
        - **Primary Contact Name**: Now mandatory.
        - **Primary Contact Phone**: Now mandatory.
        - **Engagement Type**: (Existing)
    - **Remains Optional**:
        - **Secondary Contact Name**: Optional.
        - **Secondary Contact Phone**: Optional.
        - **Attachments**: Optional.
        - **Remarks/Description**: Optional.

2.  **User Interface (`clients.blade.php`)**:
    - **Add/Edit Modals**:
        - Added `required` HTML5 attribute to the following fields: **Phone**, **Address**, **Primary Contact Name**, and **Primary Contact Phone**.
        - Appended a red asterisk (`*`) to the labels of these required fields to explicitly indicate they are mandatory.
