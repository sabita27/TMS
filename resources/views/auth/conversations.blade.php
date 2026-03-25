@extends('layouts.backend.master')

@section('styles')
<style>
    .conversation-wrapper {
        position: relative; 
        display: flex; 
        align-items: center; 
        border-bottom: 1px solid #f1f5f9;
        transition: background 0.3s;
    }

    .conversation-wrapper:hover {
        background: #f8fafc;
    }

    .conversation-card {
        background: white;
        border-radius: 1.5rem;
        border: none;
        box-shadow: 0 10px 25px -5px rgba(0,0,0,0.05);
    }

    .conversation-list {
        display: flex;
        flex-direction: column;
    }

    .conversation-item {
        padding: 1.5rem 2rem;
        display: flex;
        align-items: center;
        gap: 1.5rem;
        transition: 0.3s;
        text-decoration: none;
        flex-grow: 1;
        min-width: 0;
    }

    .user-avatar-init {
        width: 54px;
        height: 54px;
        background: #3b82f6;
        border-radius: 1rem;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.25rem;
        font-weight: 800;
        flex-shrink: 0;
        box-shadow: 0 4px 6px -1px rgba(59, 130, 246, 0.2);
    }

    .convo-content {
        flex-grow: 1;
        min-width: 0;
    }

    .convo-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 0.25rem;
    }

    .convo-name {
        font-weight: 800;
        color: #0f172a;
        font-size: 1rem;
        margin: 0;
    }

    .convo-meta {
        font-size: 0.75rem;
        color: #94a3b8;
        font-weight: 600;
    }

    .convo-subject {
        font-size: 0.9rem;
        font-weight: 700;
        color: #334155;
        margin-bottom: 0.25rem;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .convo-last-msg {
        font-size: 0.85rem;
        color: #64748b;
        margin: 0;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .convo-status-area {
        text-align: right; 
        display: flex; 
        flex-direction: column; 
        gap: 0.5rem; 
        align-items: flex-end; 
        margin-right: 1.5rem;
        flex-shrink: 0;
    }

    .convo-actions {
        padding-right: 2rem;
        flex-shrink: 0;
    }

    .status-pill {
        padding: 0.35rem 0.85rem;
        border-radius: 2rem;
        font-size: 0.65rem;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        white-space: nowrap;
    }

    .solved-badge {
        background: #dcfce7;
        color: #15803d;
        border: 1px solid #bbf7d0;
    }

    .unsolved-badge {
        background: #fef2f2;
        color: #991b1b;
        border: 1px solid #fecaca;
    }

    @media (max-width: 768px) {
        .hub-header h2 {
            font-size: 1.4rem !important;
        }
        
        .conversation-item {
            padding: 1.25rem;
            gap: 1rem;
            flex-wrap: wrap;
        }

        .user-avatar-init {
            width: 44px;
            height: 44px;
            font-size: 1rem;
        }

        .convo-content {
            width: calc(100% - 60px);
        }

        .convo-header {
            flex-direction: column;
            gap: 2px;
        }

        .convo-meta {
            font-size: 0.7rem;
        }

        .convo-status-area {
            width: 100%;
            margin-top: 0.5rem;
            margin-right: 0;
            padding-left: 3.75rem; /* Align with content */
            text-align: left;
            align-items: center;
            flex-direction: row;
            flex-wrap: wrap;
            gap: 0.75rem;
        }

        .convo-status-area > div, .convo-status-area > span {
            margin: 0 !important;
        }

        .convo-actions {
            padding-right: 1rem;
            position: absolute;
            top: 1.25rem;
            right: 0;
        }

        .convo-actions button {
            width: 32px !important;
            height: 32px !important;
            border-radius: 0.5rem !important;
        }
    }

    #conversationTable, #conversationTable tbody, #conversationTable tr, #conversationTable td {
        width: 100% !important;
    }
    
    #conversationTable td {
        padding: 0 !important;
        border: none !important;
        background: transparent !important;
    }
    #conversationTable tbody tr {
        border-bottom: none !important;
        background: transparent !important;
        box-shadow: none !important;
    }
    #conversationTable tbody tr:hover {
        background: transparent !important;
    }
    #conversationTable tbody tr.odd, #conversationTable tbody tr.even {
        background-color: transparent !important;
    }
    #conversationTable tbody tr td.sorting_1 {
        background: transparent !important;
    }
    #conversationTable.dataTable.no-footer {
        border-bottom: none !important;
    }
</style>

@endsection

@section('content')
<div class="hub-header" style="margin-bottom: 2rem; display: flex; justify-content: space-between; align-items: flex-end; flex-wrap: wrap; gap: 1.5rem;">
    <div>
        <h2 style="margin: 0; font-size: 1.75rem; font-weight: 900; color: #0f172a;">Support Messaging Hub</h2>
        <p style="margin: 0.5rem 0 0 0; color: #64748b; font-size: 0.95rem;">Monitor and engage in all customer support conversations.</p>
    </div>
    </div>
</div>

<div class="conversation-card" style="padding: 1.5rem;">
    <div class="conversation-list" style="width: 100%;">
        <table id="conversationTable" style="width: 100%; border-collapse: collapse;">
            <thead style="display: none;">
                <tr>
                    <th>Conversation Details</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        $('#conversationTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('manager.conversations') }}",
            columns: [
                {data: 'card', name: 'card', orderable: false}
            ],
            "pageLength": 15,
            "ordering": false,
            "dom": '<"top"Bf>rt<"bottom"ip><"clear">',
            "buttons": [
                'copy', 'csv', 'excel', 'pdf', 'print'
            ],
            "language": {
                "search": "_INPUT_",
                "searchPlaceholder": "Search by Ticket ID or Subject...",
                "emptyTable": '<div style="padding: 5rem 2rem; text-align: center;"><div style="width: 80px; height: 80px; background: #f1f5f9; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.5rem; color: #cbd5e1; font-size: 2rem;"><i class="fas fa-comments"></i></div><h3 style="margin: 0; color: #1e293b; font-weight: 800;">No Conversations Found</h3><p style="margin: 0.5rem 0 0 0; color: #64748b;">Start by raising or assigning a support ticket.</p></div>'
            }
        });
    });
</script>
@endsection
