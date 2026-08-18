@php
    $__setting = \App\Models\Setting::first();
@endphp
<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ trans('panel.site_title') }}</title>

    {{-- Favicon --}}
    <link rel="icon" type="image/x-icon"
          href="{{ $__setting?->getFirstMediaUrl('favicon') ?: asset('assets/img/logo/favicon.png') }}">

    {{-- Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    {{-- Tailwind CDN --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Inter', 'ui-sans-serif', 'system-ui'] },
                }
            }
        }
    </script>

    {{-- Alpine.js (dropdown, sidebar toggle etc.) --}}
    <script src="//unpkg.com/alpinejs" defer></script>

    {{-- Font Awesome --}}
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>

    {{-- Bootstrap Icons (used by premium modules) --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    {{-- Datatables --}}
    <link rel="stylesheet" href="//cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="//cdn.datatables.net/buttons/1.2.4/css/buttons.dataTables.min.css">
    <link rel="stylesheet" href="//cdn.datatables.net/select/1.3.0/css/select.dataTables.min.css">

    {{-- Select2 / Dropzone --}}
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/select2/4.0.5/css/select2.min.css">
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/dropzone/5.5.1/min/dropzone.min.css">

    {{-- Custom CSS (optional) --}}
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">

    @yield('styles')
</head>

<body class="bg-[#f7f9fc] text-slate-800 font-sans antialiased">

<div class="flex min-h-screen" x-data="{ sidebarOpen: false }">

    {{-- SIDEBAR --}}
    @include('partials.menu')

    {{-- MOBILE OVERLAY --}}
    <div x-show="sidebarOpen"
         x-transition.opacity
         @click="sidebarOpen = false"
         class="fixed inset-0 z-30 bg-slate-900/50 lg:hidden"
         style="display:none"></div>

    {{-- MAIN --}}
    <div class="flex-1 flex flex-col min-w-0">

        {{-- HEADER --}}
        <header class="sticky top-0 z-20 bg-white/80 backdrop-blur-md border-b border-slate-200 shadow-sm px-4 sm:px-6 py-3.5 flex justify-between items-center">

    {{-- LEFT --}}
    <div class="flex items-center gap-4">
        <button @click="sidebarOpen = !sidebarOpen"
                class="lg:hidden h-10 w-10 flex items-center justify-center rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-50">
            <i class="fas fa-bars text-lg"></i>
        </button>

        <h1 class="font-extrabold text-lg text-slate-900 tracking-tight">
            @yield('page-title', trans('panel.site_title'))
        </h1>
    </div>

    {{-- RIGHT --}}
    <div class="flex items-center gap-6">

        {{-- LANGUAGE --}}
        @if(count(config('panel.available_languages', [])) > 1)
            <div class="relative" x-data="{open:false}">
                <button @click="open = !open"
                        class="uppercase text-sm font-medium flex items-center gap-2">
                    {{ app()->getLocale() }}
                    <i class="fas fa-chevron-down text-xs"></i>
                </button>

                <div x-show="open"
                     x-transition
                     @click.outside="open=false"
                     class="absolute right-0 mt-2 bg-white border border-slate-200 rounded-xl shadow-xl min-w-[140px] overflow-hidden">
                    @foreach(config('panel.available_languages') as $langLocale => $langName)
                        <a class="block px-4 py-2 text-sm hover:bg-slate-50"
                           href="{{ url()->current() }}?change_language={{ $langLocale }}">
                            {{ strtoupper($langLocale) }} ({{ $langName }})
                        </a>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- NOTIFICATIONS --}}
        <div x-data="{ notifOpen: false }">
            <button @click="notifOpen = true; window.loadAdminNotifications && window.loadAdminNotifications()"
                    class="relative h-10 w-10 flex items-center justify-center rounded-xl border border-slate-200 text-slate-500 hover:bg-slate-50">
                <i class="fas fa-bell"></i>
                <span id="admin-notif-badge"
                      class="hidden absolute -top-1 -right-1 min-w-[18px] h-[18px] px-1 rounded-full bg-red-500 text-white text-[10px] font-bold flex items-center justify-center">0</span>
            </button>

            {{-- MODAL --}}
            <div x-show="notifOpen"
                 x-transition.opacity
                 @keydown.escape.window="notifOpen = false"
                 class="fixed inset-0 z-[9999] bg-slate-900/50 flex items-start justify-center pt-20 px-4"
                 style="display:none">
                <div @click.outside="notifOpen = false"
                     x-transition
                     class="w-full max-w-md bg-white rounded-2xl shadow-2xl overflow-hidden">

                    <div class="flex items-center justify-between px-5 py-4 bg-gradient-to-br from-indigo-600 to-violet-600 text-white">
                        <h3 class="font-bold flex items-center gap-2"><i class="fas fa-bell"></i> Notifications</h3>
                        <button @click="notifOpen = false" class="text-white/80 hover:text-white">&times;</button>
                    </div>

                    <div id="admin-notif-list" class="max-h-96 overflow-y-auto divide-y divide-slate-100">
                        <div class="text-center text-slate-400 text-sm py-10">No notifications yet.</div>
                    </div>

                    <div class="flex items-center justify-between px-5 py-3 border-t border-slate-100">
                        <a href="{{ route('notifications.page') }}" class="text-sm font-semibold text-slate-500 hover:text-slate-700">View all</a>
                        <button id="admin-notif-mark-all" class="text-sm font-semibold text-indigo-600 hover:text-indigo-800">Mark all as read</button>
                    </div>
                </div>
            </div>
        </div>

        {{-- USER DROPDOWN --}}
        <div class="relative" x-data="{open:false}">
            <button @click="open = !open"
                    class="flex items-center gap-3 focus:outline-none">

                {{-- Avatar --}}
                <div class="w-9 h-9 rounded-full bg-gradient-to-br from-indigo-600 to-violet-600 text-white flex items-center justify-center font-bold shadow-lg shadow-indigo-200">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>

                {{-- Name --}}
                <div class="hidden sm:block text-left">
                    <p class="text-sm font-bold leading-none text-slate-800">
                        {{ auth()->user()->name }}
                    </p>
                    <p class="text-xs text-slate-400 mt-1">
                        {{ auth()->user()->email }}
                    </p>
                </div>

                <i class="fas fa-chevron-down text-xs text-slate-400"></i>
            </button>

            {{-- DROPDOWN --}}
            <div x-show="open"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100 scale-100"
                 x-transition:leave-end="opacity-0 scale-95"
                 @click.outside="open=false"
                 class="absolute right-0 mt-2 w-56 bg-white rounded-2xl shadow-2xl border border-slate-100 overflow-hidden">

                <div class="px-4 py-3 border-b border-slate-100 bg-slate-50">
                    <p class="text-sm font-bold text-slate-800">{{ auth()->user()->name }}</p>
                    <p class="text-xs text-slate-400">{{ auth()->user()->email }}</p>
                </div>

                <a href="{{ route('profile.password.edit') }}"
                   class="flex items-center gap-2 px-4 py-2.5 text-sm font-medium text-slate-600 hover:bg-slate-50">
                    <i class="fas fa-key text-slate-400 w-4"></i>
                    Change Password
                </a>

                <a href="#"
                   onclick="event.preventDefault(); document.getElementById('logoutform').submit();"
                   class="flex items-center gap-2 px-4 py-2.5 text-sm font-semibold text-red-600 hover:bg-red-50">
                    <i class="fas fa-sign-out-alt w-4"></i>
                    Logout
                </a>
            </div>
        </div>
    </div>
</header>


        {{-- CONTENT --}}
        <main class="flex-1 p-4 sm:p-6 lg:p-8">

            @include('partials.admin-flash-toast')

            @yield('content')

        </main>

        {{-- LOGOUT --}}
        <form id="logoutform" action="{{ route('logout') }}" method="POST" class="hidden">
            @csrf
        </form>

    </div>
</div>

{{-- JS --}}
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

<script src="//cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
<script src="//cdn.datatables.net/buttons/1.2.4/js/dataTables.buttons.min.js"></script>
<script src="//cdn.datatables.net/buttons/1.2.4/js/buttons.html5.min.js"></script>
<script src="//cdn.datatables.net/buttons/1.2.4/js/buttons.print.min.js"></script>
<script src="//cdn.datatables.net/select/1.3.0/js/dataTables.select.min.js"></script>

<script src="//cdn.ckeditor.com/ckeditor5/16.0.0/classic/ckeditor.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/select2/4.0.5/js/select2.full.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/dropzone/5.5.1/min/dropzone.min.js"></script>

<script>
$(function () {
    $.extend(true, $.fn.dataTable.defaults, {
        scrollX: true,
        pageLength: 25
    });
});
</script>

<script>
(function () {
    var badge      = document.getElementById('admin-notif-badge');
    var list       = document.getElementById('admin-notif-list');
    var countLabel = document.getElementById('admin-notif-count-label');
    var markAllBtn = document.getElementById('admin-notif-mark-all');
    var csrf       = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    var styles = {
        success: { icon: 'fa-circle-check', color: 'text-emerald-500' },
        error:   { icon: 'fa-circle-exclamation', color: 'text-red-500' },
        warning: { icon: 'fa-triangle-exclamation', color: 'text-amber-500' },
        info:    { icon: 'fa-circle-info', color: 'text-indigo-500' }
    };

    function updateBadge(count) {
        if (count > 0) {
            badge.textContent = count > 99 ? '99+' : count;
            badge.classList.remove('hidden');
        } else {
            badge.classList.add('hidden');
        }
    }

    function renderList(items) {
        list.innerHTML = '';

        if (!items.length) {
            list.innerHTML = '<div class="text-center text-slate-400 text-sm py-10">No notifications yet.</div>';
            countLabel.textContent = '';
            return;
        }

        var unread = items.filter(function (n) { return !n.read; }).length;
        countLabel.textContent = unread + ' unread';

        items.forEach(function (n) {
            var s = styles[n.type] || styles.info;
            var row = document.createElement('div');
            row.className = 'flex gap-3 px-5 py-3.5 cursor-pointer hover:bg-slate-50 ' + (n.read ? '' : 'bg-indigo-50/50');

            var icon = document.createElement('i');
            icon.className = 'fas ' + s.icon + ' mt-0.5 ' + s.color;

            var body = document.createElement('div');
            body.className = 'flex-1 min-w-0';
            body.innerHTML =
                '<p class="text-sm font-semibold text-slate-800"></p>' +
                '<p class="text-xs text-slate-500 mt-0.5"></p>' +
                '<p class="text-xs text-slate-400 mt-1"></p>';
            body.children[0].textContent = n.title;
            body.children[1].textContent = n.message;
            body.children[2].textContent = n.time;

            row.appendChild(icon);
            row.appendChild(body);

            row.addEventListener('click', function () {
                fetch('/notifications/' + n.id + '/read', {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' }
                }).then(function () {
                    if (n.url) {
                        window.location.href = n.url;
                    } else {
                        window.loadAdminNotifications();
                    }
                });
            });

            list.appendChild(row);
        });
    }

    window.loadAdminNotifications = function () {
        fetch('{{ route('notifications.index') }}', { headers: { 'Accept': 'application/json' } })
            .then(function (r) { return r.json(); })
            .then(function (data) {
                updateBadge(data.unread_count);
                renderList(data.notifications);
            })
            .catch(function () {});
    };

    if (markAllBtn) {
        markAllBtn.addEventListener('click', function () {
            fetch('{{ route('notifications.readAll') }}', {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' }
            }).then(window.loadAdminNotifications);
        });
    }

    fetch('{{ route('notifications.index') }}', { headers: { 'Accept': 'application/json' } })
        .then(function (r) { return r.json(); })
        .then(function (data) { updateBadge(data.unread_count); })
        .catch(function () {});

    setInterval(function () {
        fetch('{{ route('notifications.index') }}', { headers: { 'Accept': 'application/json' } })
            .then(function (r) { return r.json(); })
            .then(function (data) { updateBadge(data.unread_count); })
            .catch(function () {});
    }, 60000);
})();
</script>

@yield('scripts')
</body>
</html>
