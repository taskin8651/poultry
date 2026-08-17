@php
    $__flashToasts = [];

    foreach (['success' => 'success', 'message' => 'success', 'error' => 'error', 'warning' => 'warning', 'info' => 'info'] as $__key => $__type) {
        if (session($__key)) {
            $__flashToasts[] = ['type' => $__type, 'text' => session($__key)];
        }
    }

    foreach ($errors->all() as $__error) {
        $__flashToasts[] = ['type' => 'error', 'text' => $__error];
    }
@endphp

<div id="app-toast-stack" class="fixed top-5 right-5 z-[9999] flex flex-col gap-2.5 w-[360px] max-w-[calc(100vw-24px)]" aria-live="polite" aria-atomic="true"></div>

<script>
(function () {
    var stack = document.getElementById('app-toast-stack');

    var styles = {
        success: { icon: 'fa-circle-check', border: 'border-emerald-500', text: 'text-emerald-600' },
        error:   { icon: 'fa-circle-exclamation', border: 'border-red-500', text: 'text-red-600' },
        warning: { icon: 'fa-triangle-exclamation', border: 'border-amber-500', text: 'text-amber-600' },
        info:    { icon: 'fa-circle-info', border: 'border-indigo-500', text: 'text-indigo-600' }
    };

    window.showToast = function (text, type, duration) {
        type = type && styles[type] ? type : 'success';
        duration = duration || 4500;
        var s = styles[type];

        var el = document.createElement('div');
        el.className = 'flex items-start gap-3 bg-white rounded-2xl shadow-2xl border border-slate-100 border-l-4 ' + s.border +
            ' px-4 py-3.5 text-sm font-medium text-slate-700 opacity-0 translate-x-6 transition-all duration-250 ease-out';

        var icon = document.createElement('i');
        icon.className = 'fas ' + s.icon + ' mt-0.5 ' + s.text;

        var span = document.createElement('span');
        span.className = 'flex-1 break-words';
        span.textContent = text;

        var close = document.createElement('button');
        close.type = 'button';
        close.className = 'text-slate-300 hover:text-slate-500';
        close.setAttribute('aria-label', 'Close');
        close.innerHTML = '&times;';

        el.appendChild(icon);
        el.appendChild(span);
        el.appendChild(close);
        stack.appendChild(el);

        requestAnimationFrame(function () {
            el.classList.remove('opacity-0', 'translate-x-6');
        });

        var remove = function () {
            el.classList.add('opacity-0', 'translate-x-6');
            setTimeout(function () { el.remove(); }, 250);
        };

        close.addEventListener('click', remove);
        if (duration > 0) { setTimeout(remove, duration); }
    };

    var flashes = @json($__flashToasts);
    flashes.forEach(function (f, i) {
        setTimeout(function () { window.showToast(f.text, f.type); }, i * 200);
    });
})();
</script>
