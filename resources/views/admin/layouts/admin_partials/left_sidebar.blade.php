<aside class="sidebar-wrapper" data-simplebar="true">
    <div class="sidebar-header">
        <div>
            <i class="bi bi-house-fill text-primary fs-3"></i>
        </div>
        <div>
            <h4 class="logo-text" data-i18n="app_name">@lang('messages.app_name')</h4>
        </div>
        <div class="toggle-icon ms-auto"><i class="bi bi-list"></i></div>
    </div>
    <ul class="metismenu" id="menu">
        <li class="menu-label" data-i18n="menu">@lang('messages.menu')</li>

        <li>
            <a href="{{ route('admin.dashboard') }}">
                <div class="parent-icon"><i class="bi bi-house-door"></i></div>
                <div class="menu-title" data-i18n="dashboard">@lang('messages.dashboard')</div>
            </a>
        </li>

        {{-- Core --}}
        <li>
            <a href="javascript:;" class="has-arrow">
                <div class="parent-icon"><i class="bi bi-people"></i></div>
                <div class="menu-title" data-i18n="core">@lang('messages.core')</div>
            </a>
            <ul>
                <li><a href="{{ route('admin.branches.index') }}"><i class="bi bi-buildings"></i> <span data-i18n="branches">@lang('messages.branches')</span></a></li>
                <li><a href="{{ route('admin.staff.index') }}"><i class="bi bi-person-badge"></i> <span data-i18n="staff">@lang('messages.staff')</span></a></li>
                <li><a href="{{ route('admin.staff_attendances.index') }}"><i class="bi bi-clipboard-check"></i> <span data-i18n="staff_attendance">@lang('messages.staff_attendance')</span></a></li>
                <li><a href="{{ route('admin.users.index') }}"><i class="bi bi-person"></i> <span data-i18n="users">@lang('messages.users')</span></a></li>
                <li><a href="{{ route('admin.roles.index') }}"><i class="bi bi-shield-check"></i> <span data-i18n="roles">@lang('messages.roles')</span></a></li>
                <li><a href="{{ route('admin.permissions.index') }}"><i class="bi bi-key"></i> <span data-i18n="permissions">@lang('messages.permissions')</span></a></li>
            </ul>
        </li>

        {{-- Rooms --}}
        <li>
            <a href="javascript:;" class="has-arrow">
                <div class="parent-icon"><i class="bi bi-door-open"></i></div>
                <div class="menu-title" data-i18n="rooms_management">@lang('messages.rooms_management')</div>
            </a>
            <ul>
                <li><a href="{{ route('admin.room_types.index') }}"><i class="bi bi-grid"></i> <span data-i18n="room_types">@lang('messages.room_types')</span></a></li>
                <li><a href="{{ route('admin.rooms.index') }}"><i class="bi bi-door-closed"></i> <span data-i18n="rooms">@lang('messages.rooms')</span></a></li>
                <li><a href="{{ route('admin.facilities.index') }}"><i class="bi bi-tv"></i> <span data-i18n="facilities">@lang('messages.facilities')</span></a></li>
            </ul>
        </li>

        {{-- Guests --}}
        <li>
            <a href="javascript:;" class="has-arrow">
                <div class="parent-icon"><i class="bi bi-person-vcard"></i></div>
                <div class="menu-title" data-i18n="guest_management">@lang('messages.guest_management')</div>
            </a>
            <ul>
                <li><a href="{{ route('admin.guests.index') }}"><i class="bi bi-person-vcard"></i> <span data-i18n="guests">@lang('messages.guests')</span></a></li>
                <li><a href="{{ route('admin.guest_documents.index') }}"><i class="bi bi-file-earmark"></i> <span data-i18n="guest_documents">@lang('messages.guest_documents')</span></a></li>
            </ul>
        </li>

        {{-- Bookings --}}
        <li>
            <a href="javascript:;" class="has-arrow">
                <div class="parent-icon"><i class="bi bi-calendar-check"></i></div>
                <div class="menu-title" data-i18n="booking_management">@lang('messages.booking_management')</div>
            </a>
            <ul>
                <li><a href="{{ route('admin.bookings.index') }}"><i class="bi bi-calendar"></i> <span data-i18n="bookings">@lang('messages.bookings')</span></a></li>
                <li><a href="{{ route('admin.online_booking_requests.index') }}"><i class="bi bi-globe"></i> <span data-i18n="online_booking_requests">@lang('messages.online_booking_requests')</span></a></li>
            </ul>
        </li>

        {{-- Stays --}}
        <li>
            <a href="javascript:;" class="has-arrow">
                <div class="parent-icon"><i class="bi bi-arrow-down-up"></i></div>
                <div class="menu-title" data-i18n="stay_management">@lang('messages.stay_management')</div>
            </a>
            <ul>
                <li><a href="{{ route('admin.stays.index') }}"><i class="bi bi-house"></i> <span data-i18n="stays">@lang('messages.stays')</span></a></li>
                <li><a href="{{ route('admin.room_transfers.index') }}"><i class="bi bi-arrow-left-right"></i> <span data-i18n="room_transfers">@lang('messages.room_transfers')</span></a></li>
            </ul>
        </li>

        {{-- Finance --}}
        <li>
            <a href="javascript:;" class="has-arrow">
                <div class="parent-icon"><i class="bi bi-cash-coin"></i></div>
                <div class="menu-title" data-i18n="finance">@lang('messages.finance')</div>
            </a>
            <ul>
                <li><a href="{{ route('admin.payment_methods.index') }}"><i class="bi bi-wallet"></i> <span data-i18n="payment_methods">@lang('messages.payment_methods')</span></a></li>
                <li><a href="{{ route('admin.invoices.index') }}"><i class="bi bi-file-text"></i> <span data-i18n="invoices">@lang('messages.invoices')</span></a></li>
                <li><a href="{{ route('admin.payments.index') }}"><i class="bi bi-credit-card"></i> <span data-i18n="payments">@lang('messages.payments')</span></a></li>
                <li><a href="{{ route('admin.receipts.index') }}"><i class="bi bi-receipt"></i> <span data-i18n="receipts">@lang('messages.receipts')</span></a></li>
                <li><a href="{{ route('admin.refunds.index') }}"><i class="bi bi-arrow-counterclockwise"></i> <span data-i18n="refunds">@lang('messages.refunds')</span></a></li>
            </ul>
        </li>

        {{-- Services --}}
        <li>
            <a href="javascript:;" class="has-arrow">
                <div class="parent-icon"><i class="bi bi-bell"></i></div>
                <div class="menu-title" data-i18n="service_management">@lang('messages.service_management')</div>
            </a>
            <ul>
                <li><a href="{{ route('admin.services.index') }}"><i class="bi bi-list-stars"></i> <span data-i18n="services">@lang('messages.services')</span></a></li>
                <li><a href="{{ route('admin.service_charges.index') }}"><i class="bi bi-cash-stack"></i> <span data-i18n="service_charges">@lang('messages.service_charges')</span></a></li>
            </ul>
        </li>

        {{-- Housekeeping --}}
        <li>
            <a href="javascript:;" class="has-arrow">
                <div class="parent-icon"><i class="bi bi-droplet"></i></div>
                <div class="menu-title" data-i18n="housekeeping">@lang('messages.housekeeping')</div>
            </a>
            <ul>
                <li><a href="{{ route('admin.housekeeping_tasks.index') }}"><i class="bi bi-list-task"></i> <span data-i18n="housekeeping_tasks">@lang('messages.housekeeping_tasks')</span></a></li>
                <li><a href="{{ route('admin.housekeeping_checklist_items.index') }}"><i class="bi bi-check2-square"></i> <span data-i18n="checklist_items">@lang('messages.checklist_items')</span></a></li>
            </ul>
        </li>

        {{-- Maintenance --}}
        <li>
            <a href="javascript:;" class="has-arrow">
                <div class="parent-icon"><i class="bi bi-tools"></i></div>
                <div class="menu-title" data-i18n="maintenance">@lang('messages.maintenance')</div>
            </a>
            <ul>
                <li><a href="{{ route('admin.maintenance_requests.index') }}"><i class="bi bi-wrench"></i> <span data-i18n="maintenance_requests">@lang('messages.maintenance_requests')</span></a></li>
            </ul>
        </li>

        {{-- Inventory --}}
        <li>
            <a href="javascript:;" class="has-arrow">
                <div class="parent-icon"><i class="bi bi-boxes"></i></div>
                <div class="menu-title" data-i18n="inventory">@lang('messages.inventory')</div>
            </a>
            <ul>
                <li><a href="{{ route('admin.suppliers.index') }}"><i class="bi bi-truck"></i> <span data-i18n="suppliers">@lang('messages.suppliers')</span></a></li>
                <li><a href="{{ route('admin.stock_categories.index') }}"><i class="bi bi-tag"></i> <span data-i18n="stock_categories">@lang('messages.stock_categories')</span></a></li>
                <li><a href="{{ route('admin.stock_items.index') }}"><i class="bi bi-box-seam"></i> <span data-i18n="stock_items">@lang('messages.stock_items')</span></a></li>
                <li><a href="{{ route('admin.stock_movements.index') }}"><i class="bi bi-arrow-left-right"></i> <span data-i18n="stock_movements">@lang('messages.stock_movements')</span></a></li>
            </ul>
        </li>

        {{-- Accounting --}}
        <li>
            <a href="javascript:;" class="has-arrow">
                <div class="parent-icon"><i class="bi bi-calculator"></i></div>
                <div class="menu-title" data-i18n="accounting">@lang('messages.accounting')</div>
            </a>
            <ul>
                <li><a href="{{ route('admin.expense_categories.index') }}"><i class="bi bi-tag"></i> <span data-i18n="expense_categories">@lang('messages.expense_categories')</span></a></li>
                <li><a href="{{ route('admin.expenses.index') }}"><i class="bi bi-cash"></i> <span data-i18n="expenses">@lang('messages.expenses')</span></a></li>
                <li><a href="{{ route('admin.salaries.index') }}"><i class="bi bi-cash-stack"></i> <span data-i18n="salaries">@lang('messages.salaries')</span></a></li>
            </ul>
        </li>

        {{-- Notification --}}
        <li>
            <a href="javascript:;" class="has-arrow">
                <div class="parent-icon"><i class="bi bi-bell"></i></div>
                <div class="menu-title" data-i18n="notification">@lang('messages.notification')</div>
            </a>
            <ul>
                <li><a href="{{ route('admin.notifications.index') }}"><i class="bi bi-bell"></i> <span data-i18n="notifications">@lang('messages.notifications')</span></a></li>
                <li><a href="{{ route('admin.notification_templates.index') }}"><i class="bi bi-envelope"></i> <span data-i18n="notification_templates">@lang('messages.notification_templates')</span></a></li>
            </ul>
        </li>

        {{-- Website --}}
        <li>
            <a href="javascript:;" class="has-arrow">
                <div class="parent-icon"><i class="bi bi-globe"></i></div>
                <div class="menu-title" data-i18n="website">@lang('messages.website')</div>
            </a>
            <ul>
                <li><a href="{{ route('admin.website_pages.index') }}"><i class="bi bi-file-earmark-richtext"></i> <span data-i18n="website_pages">@lang('messages.website_pages')</span></a></li>
            </ul>
        </li>

        {{-- Security --}}
        <li>
            <a href="javascript:;" class="has-arrow">
                <div class="parent-icon"><i class="bi bi-shield-lock"></i></div>
                <div class="menu-title" data-i18n="security">@lang('messages.security')</div>
            </a>
            <ul>
                <li><a href="{{ route('admin.login_histories.index') }}"><i class="bi bi-clock-history"></i> <span data-i18n="login_histories">@lang('messages.login_histories')</span></a></li>
                <li><a href="{{ route('admin.audit_logs.index') }}"><i class="bi bi-journal-text"></i> <span data-i18n="audit_logs">@lang('messages.audit_logs')</span></a></li>
            </ul>
        </li>

        {{-- Settings --}}
        <li>
            <a href="javascript:;" class="has-arrow">
                <div class="parent-icon"><i class="bi bi-gear"></i></div>
                <div class="menu-title" data-i18n="settings">@lang('messages.settings')</div>
            </a>
            <ul>
                <li><a href="{{ route('admin.guest_house_settings.index') }}"><i class="bi bi-house"></i> <span data-i18n="guest_house_settings">@lang('messages.guest_house_settings')</span></a></li>
                <li><a href="{{ route('admin.code_settings.index') }}"><i class="bi bi-hash"></i> <span data-i18n="code_settings">@lang('messages.code_settings')</span></a></li>
                <li><a href="{{ route('admin.system_settings.index') }}"><i class="bi bi-sliders"></i> <span data-i18n="system_settings">@lang('messages.system_settings')</span></a></li>
                <li><a href="{{ route('admin.backups.index') }}"><i class="bi bi-archive"></i> <span data-i18n="backups">@lang('messages.backups')</span></a></li>
            </ul>
        </li>
    </ul>
</aside>
