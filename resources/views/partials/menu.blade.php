<aside
    class="w-64 bg-slate-900 text-slate-100 min-h-screen hidden lg:flex flex-col
           transition-all duration-300 ease-in-out">

    {{-- BRAND --}}
    <div class="px-6 py-4 text-xl font-semibold border-b border-slate-700
                tracking-wide">
        {{ trans('panel.site_title') }}
    </div>

    {{-- NAV --}}
    <nav class="flex-1 px-3 py-4 space-y-1 text-sm">

        {{-- DASHBOARD --}}
        <a href="{{ route('admin.home') }}"
           class="group flex items-center gap-3 px-3 py-2 rounded transition
           {{ request()->routeIs('admin.home')
                ? 'bg-slate-800 text-white'
                : 'hover:bg-slate-800 hover:pl-4' }}">
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
                               hover:bg-slate-800 transition">
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
                     class="ml-6 mt-1 space-y-1">

                    @can('permission_access')
                        <a href="{{ route('admin.permissions.index') }}"
                           class="block px-3 py-2 rounded transition
                           {{ request()->is('admin/permissions*')
                                ? 'bg-slate-800 text-white'
                                : 'hover:bg-slate-800 hover:pl-4' }}">
                            {{ trans('cruds.permission.title') }}
                        </a>
                    @endcan

                    @can('role_access')
                        <a href="{{ route('admin.roles.index') }}"
                           class="block px-3 py-2 rounded transition
                           {{ request()->is('admin/roles*')
                                ? 'bg-slate-800 text-white'
                                : 'hover:bg-slate-800 hover:pl-4' }}">
                            {{ trans('cruds.role.title') }}
                        </a>
                    @endcan

                    @can('user_access')
                        <a href="{{ route('admin.users.index') }}"
                           class="block px-3 py-2 rounded transition
                           {{ request()->is('admin/users*')
                                ? 'bg-slate-800 text-white'
                                : 'hover:bg-slate-800 hover:pl-4' }}">
                            {{ trans('cruds.user.title') }}
                        </a>
                    @endcan

                    @can('audit_log_access')
                        <a href="{{ route('admin.audit-logs.index') }}"
                           class="block px-3 py-2 rounded transition
                           {{ request()->is('admin/audit-logs*')
                                ? 'bg-slate-800 text-white'
                                : 'hover:bg-slate-800 hover:pl-4' }}">
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
                   hover:bg-slate-800 transition">
        <span class="flex items-center gap-3">
            <i class="fas fa-box text-slate-400 group-hover:text-white transition"></i>
            Product Management
        </span>

        <i class="fas fa-chevron-down text-xs transition-transform duration-300"
           :class="open ? 'rotate-180' : ''"></i>
    </button>

    {{-- DROPDOWN --}}
    <div x-show="open" x-transition class="ml-6 mt-1 space-y-1">

        @can('category_access')
        <a href="{{ route('admin.categories.index') }}"
           class="block px-3 py-2 rounded transition
           {{ request()->is('admin/categories*')
                ? 'bg-slate-800 text-white'
                : 'hover:bg-slate-800 hover:pl-4' }}">
            Categories
        </a>
        @endcan

        @can('tag_access')
        <a href="{{ route('admin.tags.index') }}"
           class="block px-3 py-2 rounded transition
           {{ request()->is('admin/tags*')
                ? 'bg-slate-800 text-white'
                : 'hover:bg-slate-800 hover:pl-4' }}">
            Tags
        </a>
        @endcan

        @can('product_access')
        <a href="{{ route('admin.products.index') }}"
           class="block px-3 py-2 rounded transition
           {{ request()->is('admin/products*')
                ? 'bg-slate-800 text-white'
                : 'hover:bg-slate-800 hover:pl-4' }}">
            Products
        </a>
        @endcan

    </div>
</div>
@endcan

 {{-- ORDERS --}}
@can('order_access')
<a href="{{ route('admin.orders.index') }}"
   class="group flex items-center gap-3 px-3 py-2 rounded transition
   {{ request()->is('admin/orders*')
        ? 'bg-slate-800 text-white'
        : 'hover:bg-slate-800 hover:pl-4' }}">

    <i class="fas fa-shopping-cart text-slate-400 group-hover:text-white transition"></i>
    Orders

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
                   hover:bg-slate-800 transition">

        <span class="flex items-center gap-3">
            <i class="fas fa-layer-group text-slate-400 group-hover:text-white transition"></i>
            CMS Management
        </span>

        <i class="fas fa-chevron-down text-xs transition-transform duration-300"
           :class="open ? 'rotate-180' : ''"></i>
    </button>

    {{-- DROPDOWN --}}
    <div x-show="open" x-transition class="ml-6 mt-1 space-y-1">

        @can('hero_access')
        <a href="{{ route('admin.heroes.index') }}"
           class="block px-3 py-2 rounded transition
           {{ request()->is('admin/heroes*')
                ? 'bg-slate-800 text-white'
                : 'hover:bg-slate-800 hover:pl-4' }}">
            Hero
        </a>
        @endcan

        @can('about_access')
        <a href="{{ route('admin.abouts.index') }}"
           class="block px-3 py-2 rounded transition
           {{ request()->is('admin/abouts*')
                ? 'bg-slate-800 text-white'
                : 'hover:bg-slate-800 hover:pl-4' }}">
            About
        </a>
        @endcan

        @can('service_access')
        <a href="{{ route('admin.services.index') }}"
           class="block px-3 py-2 rounded transition
           {{ request()->is('admin/services*')
                ? 'bg-slate-800 text-white'
                : 'hover:bg-slate-800 hover:pl-4' }}">
            Services
        </a>
        @endcan

        @can('testimonial_access')
        <a href="{{ route('admin.testimonials.index') }}"
           class="block px-3 py-2 rounded transition
           {{ request()->is('admin/testimonials*')
                ? 'bg-slate-800 text-white'
                : 'hover:bg-slate-800 hover:pl-4' }}">
            Testimonials
        </a>
        @endcan

        @can('offer_access')
        <a href="{{ route('admin.offers.index') }}"
           class="block px-3 py-2 rounded transition
           {{ request()->is('admin/offers*')
                ? 'bg-slate-800 text-white'
                : 'hover:bg-slate-800 hover:pl-4' }}">
            Offers
        </a>
        @endcan

    </div>
</div>

 {{-- CONTACT --}}
@can('contact_access')
<a href="{{ route('admin.contacts.index') }}"
   class="group flex items-center gap-3 px-3 py-2 rounded transition
   {{ request()->is('admin/contacts*')
        ? 'bg-slate-800 text-white'
        : 'hover:bg-slate-800 hover:pl-4' }}">

    <i class="fas fa-envelope text-slate-400 group-hover:text-white transition"></i>
    Contacts

</a>
@endcan

{{-- SETTINGS --}}
@can('setting_access')
<a href="{{ route('admin.settings.index') }}"
   class="group flex items-center gap-3 px-3 py-2 rounded transition
   {{ request()->is('admin/settings*')
        ? 'bg-slate-800 text-white'
        : 'hover:bg-slate-800 hover:pl-4' }}">

    <i class="fas fa-cog text-slate-400 group-hover:text-white transition"></i>
    Settings

</a>
@endcan

        {{-- CHANGE PASSWORD --}}
        @if(file_exists(app_path('Http/Controllers/Auth/ChangePasswordController.php')))
            @can('profile_password_edit')
                <a href="{{ route('profile.password.edit') }}"
                   class="group flex items-center gap-3 px-3 py-2 rounded transition
                   {{ request()->is('profile/password*')
                        ? 'bg-slate-800 text-white'
                        : 'hover:bg-slate-800 hover:pl-4' }}">
                    <i class="fas fa-key text-slate-400 group-hover:text-white transition"></i>
                    {{ trans('global.change_password') }}
                </a>
            @endcan
        @endif

    </nav>

    {{-- LOGOUT --}}
    <div class="border-t border-slate-700 p-3">
        <a href="#"
           onclick="event.preventDefault(); document.getElementById('logoutform').submit();"
           class="group flex items-center gap-3 px-3 py-2 rounded transition
                  hover:bg-red-600 hover:text-white">
            <i class="fas fa-sign-out-alt transition group-hover:translate-x-1"></i>
            {{ trans('global.logout') }}
        </a>
    </div>

</aside>
