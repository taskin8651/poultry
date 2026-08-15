<aside
    id="admin-sidebar"
    x-cloak
    :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
    class="w-72 bg-gradient-to-b from-slate-900 via-slate-900 to-indigo-950 text-slate-100 min-h-screen
           fixed inset-y-0 left-0 z-40 flex flex-col
           transform transition-transform duration-300 ease-in-out
           lg:static lg:translate-x-0 lg:flex shadow-2xl">

    {{-- BRAND --}}
    <div class="px-6 py-5 border-b border-white/10 flex items-center gap-3">
        @php
            $__logoUrl = ($__setting ?? null)?->getFirstMediaUrl('logo');
        @endphp
        @if($__logoUrl)
            <img src="{{ $__logoUrl }}" alt="Logo"
                 class="h-10 w-10 shrink-0 rounded-xl object-cover shadow-lg shadow-indigo-900/50">
        @else
            <div class="h-10 w-10 shrink-0 rounded-xl bg-gradient-to-br from-indigo-500 to-violet-500
                        flex items-center justify-center shadow-lg shadow-indigo-900/50">
                <i class="fas fa-feather-pointed text-white"></i>
            </div>
        @endif
        <div class="min-w-0">
            <div class="text-base font-extrabold tracking-wide truncate">
                {{ trans('panel.site_title') }}
            </div>
            <div class="text-[11px] font-medium text-indigo-300/80 tracking-wider uppercase">
                Admin Panel
            </div>
        </div>
    </div>

    {{-- NAV --}}
    <nav class="flex-1 px-3 py-4 space-y-1 text-sm overflow-y-auto premium-scroll">

        {{-- DASHBOARD --}}
        <a href="{{ route('admin.home') }}"
           class="group flex items-center gap-3 px-3 py-2 rounded-xl transition
           {{ request()->routeIs('admin.home')
                ? 'nav-active bg-white/10 text-white shadow-inner shadow-black/20'
                : 'text-slate-300 hover:bg-white/5 hover:text-white hover:pl-4' }}">
            <i class="fas fa-tachometer-alt text-slate-400 group-hover:text-white transition"></i>
            {{ trans('global.dashboard') }}
        </a>

        {{-- USER MANAGEMENT --}}
        @can('user_management_access')
            <div x-data="{ open:
                {{ request()->is('admin/permissions*')
                || request()->is('admin/roles*')
                || request()->is('admin/users*')
                || request()->is('admin/audit-logs*') ? 'true' : 'false' }}
            }">

                <button @click="open = !open"
                        class="group w-full flex items-center justify-between px-3 py-2 rounded
                               text-slate-300 hover:bg-white/5 hover:text-white transition">
                    <span class="flex items-center gap-3">
                        <i class="fas fa-users text-slate-400 group-hover:text-white transition"></i>
                        {{ trans('cruds.userManagement.title') }}
                    </span>

                    <i class="fas fa-chevron-down text-xs transition-transform duration-300"
                       :class="open ? 'rotate-180' : ''"></i>
                </button>

                {{-- DROPDOWN --}}
                <div x-show="open"
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 -translate-y-2"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     x-transition:leave="transition ease-in duration-150"
                     x-transition:leave-start="opacity-100 translate-y-0"
                     x-transition:leave-end="opacity-0 -translate-y-2"
                     class="ml-5 mt-1 space-y-1 border-l border-white/10 pl-3">

                    @can('permission_access')
                        <a href="{{ route('admin.permissions.index') }}"
                           class="block px-3 py-2 rounded-xl transition
                           {{ request()->is('admin/permissions*')
                                ? 'nav-active bg-white/10 text-white shadow-inner shadow-black/20'
                                : 'text-slate-300 hover:bg-white/5 hover:text-white hover:pl-4' }}">
                            {{ trans('cruds.permission.title') }}
                        </a>
                    @endcan

                    @can('role_access')
                        <a href="{{ route('admin.roles.index') }}"
                           class="block px-3 py-2 rounded-xl transition
                           {{ request()->is('admin/roles*')
                                ? 'nav-active bg-white/10 text-white shadow-inner shadow-black/20'
                                : 'text-slate-300 hover:bg-white/5 hover:text-white hover:pl-4' }}">
                            {{ trans('cruds.role.title') }}
                        </a>
                    @endcan

                    @can('user_access')
                        <a href="{{ route('admin.users.index') }}"
                           class="block px-3 py-2 rounded-xl transition
                           {{ request()->is('admin/users*')
                                ? 'nav-active bg-white/10 text-white shadow-inner shadow-black/20'
                                : 'text-slate-300 hover:bg-white/5 hover:text-white hover:pl-4' }}">
                            {{ trans('cruds.user.title') }}
                        </a>
                    @endcan

                    @can('audit_log_access')
                        <a href="{{ route('admin.audit-logs.index') }}"
                           class="block px-3 py-2 rounded-xl transition
                           {{ request()->is('admin/audit-logs*')
                                ? 'nav-active bg-white/10 text-white shadow-inner shadow-black/20'
                                : 'text-slate-300 hover:bg-white/5 hover:text-white hover:pl-4' }}">
                            {{ trans('cruds.auditLog.title') }}
                        </a>
                    @endcan

                </div>
            </div>
        @endcan

        {{-- PRODUCT MANAGEMENT --}}
@can('product_management_access')
<div x-data="{ open:
    {{ request()->is('admin/categories*')
    || request()->is('admin/tags*')
    || request()->is('admin/products*') ? 'true' : 'false' }}
}">

    <button @click="open = !open"
            class="group w-full flex items-center justify-between px-3 py-2 rounded
                   text-slate-300 hover:bg-white/5 hover:text-white transition">
        <span class="flex items-center gap-3">
            <i class="fas fa-box text-slate-400 group-hover:text-white transition"></i>
            Product Management
        </span>

        <i class="fas fa-chevron-down text-xs transition-transform duration-300"
           :class="open ? 'rotate-180' : ''"></i>
    </button>

    {{-- DROPDOWN --}}
    <div x-show="open" x-transition class="ml-5 mt-1 space-y-1 border-l border-white/10 pl-3">

        @can('category_access')
        <a href="{{ route('admin.categories.index') }}"
           class="block px-3 py-2 rounded-xl transition
           {{ request()->is('admin/categories*')
                ? 'nav-active bg-white/10 text-white shadow-inner shadow-black/20'
                : 'text-slate-300 hover:bg-white/5 hover:text-white hover:pl-4' }}">
            Categories
        </a>
        @endcan

        @can('tag_access')
        <a href="{{ route('admin.tags.index') }}"
           class="block px-3 py-2 rounded-xl transition
           {{ request()->is('admin/tags*')
                ? 'nav-active bg-white/10 text-white shadow-inner shadow-black/20'
                : 'text-slate-300 hover:bg-white/5 hover:text-white hover:pl-4' }}">
            Tags
        </a>
        @endcan

        @can('product_access')
        <a href="{{ route('admin.products.index') }}"
           class="block px-3 py-2 rounded-xl transition
           {{ request()->is('admin/products*')
                ? 'nav-active bg-white/10 text-white shadow-inner shadow-black/20'
                : 'text-slate-300 hover:bg-white/5 hover:text-white hover:pl-4' }}">
            Products
        </a>
        @endcan

    </div>
</div>
@endcan

 {{-- ORDERS --}}
@can('order_access')
<a href="{{ route('admin.orders.index') }}"
   class="group flex items-center gap-3 px-3 py-2 rounded-xl transition
   {{ request()->is('admin/orders*')
        ? 'nav-active bg-white/10 text-white shadow-inner shadow-black/20'
        : 'text-slate-300 hover:bg-white/5 hover:text-white hover:pl-4' }}">

    <i class="fas fa-shopping-cart text-slate-400 group-hover:text-white transition"></i>
    Orders

</a>
@endcan

{{-- REFERRALS --}}
@can('referral_access')
<a href="{{ route('admin.referrals.index') }}"
   class="group flex items-center gap-3 px-3 py-2 rounded-xl transition
   {{ request()->is('admin/referrals*')
        ? 'nav-active bg-white/10 text-white shadow-inner shadow-black/20'
        : 'text-slate-300 hover:bg-white/5 hover:text-white hover:pl-4' }}">

    <i class="fas fa-gift text-slate-400 group-hover:text-white transition"></i>
    Referrals

</a>
@endcan

{{-- CMS MANAGEMENT --}}
<div x-data="{ open:
    {{ request()->is('admin/heroes*')
    || request()->is('admin/abouts*')
    || request()->is('admin/services*')
    || request()->is('admin/testimonials*')
    || request()->is('admin/offers*') ? 'true' : 'false' }}
}">

    <button @click="open = !open"
            class="group w-full flex items-center justify-between px-3 py-2 rounded
                   text-slate-300 hover:bg-white/5 hover:text-white transition">

        <span class="flex items-center gap-3">
            <i class="fas fa-layer-group text-slate-400 group-hover:text-white transition"></i>
            CMS Management
        </span>

        <i class="fas fa-chevron-down text-xs transition-transform duration-300"
           :class="open ? 'rotate-180' : ''"></i>
    </button>

    {{-- DROPDOWN --}}
    <div x-show="open" x-transition class="ml-5 mt-1 space-y-1 border-l border-white/10 pl-3">

        @can('hero_access')
        <a href="{{ route('admin.heroes.index') }}"
           class="block px-3 py-2 rounded-xl transition
           {{ request()->is('admin/heroes*')
                ? 'nav-active bg-white/10 text-white shadow-inner shadow-black/20'
                : 'text-slate-300 hover:bg-white/5 hover:text-white hover:pl-4' }}">
            Hero
        </a>
        @endcan

        @can('about_access')
        <a href="{{ route('admin.abouts.index') }}"
           class="block px-3 py-2 rounded-xl transition
           {{ request()->is('admin/abouts*')
                ? 'nav-active bg-white/10 text-white shadow-inner shadow-black/20'
                : 'text-slate-300 hover:bg-white/5 hover:text-white hover:pl-4' }}">
            About
        </a>
        @endcan

        @can('service_access')
        <a href="{{ route('admin.services.index') }}"
           class="block px-3 py-2 rounded-xl transition
           {{ request()->is('admin/services*')
                ? 'nav-active bg-white/10 text-white shadow-inner shadow-black/20'
                : 'text-slate-300 hover:bg-white/5 hover:text-white hover:pl-4' }}">
            Services
        </a>
        @endcan

        @can('testimonial_access')
        <a href="{{ route('admin.testimonials.index') }}"
           class="block px-3 py-2 rounded-xl transition
           {{ request()->is('admin/testimonials*')
                ? 'nav-active bg-white/10 text-white shadow-inner shadow-black/20'
                : 'text-slate-300 hover:bg-white/5 hover:text-white hover:pl-4' }}">
            Testimonials
        </a>
        @endcan

        @can('offer_access')
        <a href="{{ route('admin.offers.index') }}"
           class="block px-3 py-2 rounded-xl transition
           {{ request()->is('admin/offers*')
                ? 'nav-active bg-white/10 text-white shadow-inner shadow-black/20'
                : 'text-slate-300 hover:bg-white/5 hover:text-white hover:pl-4' }}">
            Offers
        </a>
        @endcan

    </div>
</div>

 {{-- CONTACT --}}
@can('contact_access')
<a href="{{ route('admin.contacts.index') }}"
   class="group flex items-center gap-3 px-3 py-2 rounded-xl transition
   {{ request()->is('admin/contacts*')
        ? 'nav-active bg-white/10 text-white shadow-inner shadow-black/20'
        : 'text-slate-300 hover:bg-white/5 hover:text-white hover:pl-4' }}">

    <i class="fas fa-envelope text-slate-400 group-hover:text-white transition"></i>
    Contacts

</a>
@endcan

{{-- SETTINGS --}}
@can('setting_access')
<a href="{{ route('admin.settings.index') }}"
   class="group flex items-center gap-3 px-3 py-2 rounded-xl transition
   {{ request()->is('admin/settings*')
        ? 'nav-active bg-white/10 text-white shadow-inner shadow-black/20'
        : 'text-slate-300 hover:bg-white/5 hover:text-white hover:pl-4' }}">

    <i class="fas fa-cog text-slate-400 group-hover:text-white transition"></i>
    Settings

</a>
@endcan

        {{-- CHANGE PASSWORD --}}
        @if(file_exists(app_path('Http/Controllers/Auth/ChangePasswordController.php')))
            @can('profile_password_edit')
                <a href="{{ route('profile.password.edit') }}"
                   class="group flex items-center gap-3 px-3 py-2 rounded-xl transition
                   {{ request()->is('profile/password*')
                        ? 'nav-active bg-white/10 text-white shadow-inner shadow-black/20'
                        : 'text-slate-300 hover:bg-white/5 hover:text-white hover:pl-4' }}">
                    <i class="fas fa-key text-slate-400 group-hover:text-white transition"></i>
                    {{ trans('global.change_password') }}
                </a>
            @endcan
        @endif

    </nav>

    {{-- LOGOUT --}}
    <div class="border-t border-white/10 p-3">
        <a href="#"
           onclick="event.preventDefault(); document.getElementById('logoutform').submit();"
           class="group flex items-center gap-3 px-3 py-2 rounded-xl transition
                  text-slate-300 hover:bg-red-600 hover:text-white">
            <i class="fas fa-sign-out-alt transition group-hover:translate-x-1"></i>
            {{ trans('global.logout') }}
        </a>
    </div>

</aside>
