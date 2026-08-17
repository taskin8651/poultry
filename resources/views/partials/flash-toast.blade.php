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

<div id="app-toast-stack" class="app-toast-stack" aria-live="polite" aria-atomic="true"></div>

<style>
    .app-toast-stack{
        position:fixed; top:20px; right:20px; z-index:99999;
        display:flex; flex-direction:column; gap:10px;
        width:340px; max-width:calc(100vw - 24px);
    }
    .app-toast{
        display:flex; align-items:flex-start; gap:10px;
        background:#fff; border-radius:12px; padding:14px 16px;
        box-shadow:0 12px 28px rgba(20,20,30,.16); border-left:4px solid #EE7D21;
        font-family:inherit; font-size:14px; color:#2b2b2b; line-height:1.4;
        opacity:0; transform:translateX(30px); transition:opacity .25s ease, transform .25s ease;
    }
    .app-toast.show{opacity:1; transform:translateX(0);}
    .app-toast.hide{opacity:0; transform:translateX(30px);}
    .app-toast-success{border-left-color:#16a34a;}
    .app-toast-error{border-left-color:#dc2626;}
    .app-toast-warning{border-left-color:#d97706;}
    .app-toast-info{border-left-color:#2563eb;}
    .app-toast i{font-size:17px; margin-top:1px; flex-shrink:0;}
    .app-toast-success i{color:#16a34a;}
    .app-toast-error i{color:#dc2626;}
    .app-toast-warning i{color:#d97706;}
    .app-toast-info i{color:#2563eb;}
    .app-toast-text{flex:1; word-break:break-word;}
    .app-toast-close{
        background:none; border:none; color:#9ca3af; font-size:16px;
        line-height:1; cursor:pointer; padding:0 0 0 6px; flex-shrink:0;
    }
    .app-toast-close:hover{color:#4b5563;}
    @media (max-width:576px){
        .app-toast-stack{left:12px; right:12px; top:12px; width:auto;}
    }
</style>

<script>
(function () {
    var stack = document.getElementById('app-toast-stack');

    var icons = {
        success: 'fa-check-circle',
        error: 'fa-exclamation-circle',
        warning: 'fa-exclamation-triangle',
        info: 'fa-info-circle'
    };

    window.showToast = function (text, type, duration) {
        type = type && icons[type] ? type : 'success';
        duration = duration || 4500;

        var el = document.createElement('div');
        el.className = 'app-toast app-toast-' + type;

        var icon = document.createElement('i');
        icon.className = 'far ' + icons[type];

        var span = document.createElement('span');
        span.className = 'app-toast-text';
        span.textContent = text;

        var close = document.createElement('button');
        close.type = 'button';
        close.className = 'app-toast-close';
        close.setAttribute('aria-label', 'Close');
        close.innerHTML = '&times;';

        el.appendChild(icon);
        el.appendChild(span);
        el.appendChild(close);
        stack.appendChild(el);

        requestAnimationFrame(function () { el.classList.add('show'); });

        var remove = function () {
            el.classList.remove('show');
            el.classList.add('hide');
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
