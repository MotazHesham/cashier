<div id="sidebar" class="c-sidebar c-sidebar-fixed c-sidebar-lg-show">
    @php
        $sett = \App\Models\GeneralSetting::first();
    @endphp

    <div class="c-sidebar-brand d-md-down-none">
        <a class="c-sidebar-brand-full h4" href="#" style="margin: 12px;">
            <img src="{{$sett->logo ? $sett->logo->getUrl()  : ''}}" alt="" width="70" height="70">
        </a>
    </div>

    <ul class="c-sidebar-nav">
        <li class="c-sidebar-nav-item">
            <a href="{{ route("father.home") }}" class="c-sidebar-nav-link">
                <i class="c-sidebar-nav-icon fas fa-fw fa-tachometer-alt">

                </i>
                {{ trans('global.dashboard') }}
            </a>
        </li>
        <li class="c-sidebar-nav-item">
            <a href="{{ route("father.students.index") }}" class="c-sidebar-nav-link {{ request()->is("father/students") || request()->is("father/students/*") ? "c-active" : "" }}">
                <i class="fa-fw fas fa-user-graduate c-sidebar-nav-icon">

                </i>
                {{ trans('cruds.father.students') }}
            </a>
        </li>
        <li class="c-sidebar-nav-item">
            <a href="{{ route("father.payments.index") }}" class="c-sidebar-nav-link {{ request()->is("father/payments") || request()->is("father/payments/*") ? "c-active" : "" }}">
                <i class="fa-fw fas fa-credit-card c-sidebar-nav-icon">

                </i>
                {{ trans('cruds.payment.title') }}
            </a>
        </li>
        @php($unread = \App\Models\QaTopic::unreadCount())
            <li class="c-sidebar-nav-item">
                <a href="{{ route("admin.messenger.index") }}" class="{{ request()->is("admin/messenger") || request()->is("admin/messenger/*") ? "c-active" : "" }} c-sidebar-nav-link">
                    <i class="c-sidebar-nav-icon fa-fw fa fa-envelope">

                    </i>
                    <span>{{ trans('global.messages') }}</span>
                    @if($unread > 0)
                        <strong>( {{ $unread }} )</strong>
                    @endif

                </a>
            </li>
            @if(file_exists(app_path('Http/Controllers/Auth/ChangePasswordController.php')))
                <li class="c-sidebar-nav-item">
                    <a class="c-sidebar-nav-link {{ request()->is('profile/password') || request()->is('profile/password/*') ? 'c-active' : '' }}" href="{{ route('profile.password.edit') }}">
                        <i class="fa-fw fas fa-key c-sidebar-nav-icon">
                        </i>
                        {{ trans('global.change_password') }}
                    </a>
                </li>
            @endif
            <li class="c-sidebar-nav-item">
                <a href="#" class="c-sidebar-nav-link" onclick="event.preventDefault(); document.getElementById('logoutform').submit();">
                    <i class="c-sidebar-nav-icon fas fa-fw fa-sign-out-alt">

                    </i>
                    {{ trans('global.logout') }}
                </a>
            </li>
    </ul>

</div>
