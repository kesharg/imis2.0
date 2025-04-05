<footer class="main-footer" style ="margin-left: 0px !important">
    <div class="float-right d-none d-sm-inline ">
        <strong>Developed by:</strong> <a href="http://www.innovativesolution.com.np">@lang('public.waterborne.insol')</a>
    </div>

<strong> Base IMIS <i class="fa-regular fa-copyright"> </i>  2022-2024 by <a href="http://www.innovativesolution.com.np"> 
    @lang('public.waterborne.insol')</a> is licensed under <a href="https://creativecommons.org/licenses/by-nc-sa/4.0/?ref=chooser-v1">CC BY-NC-SA 4.0 </a>
</strong>
</footer>
<aside class="control-sidebar control-sidebar-dark" >
    <div class="p-3" >
    <h4>{{ Auth::user()->name }}</h4>
    <hr class="mb-2">
    <div class="mb-4">
                <p>
                    {{implode(', ', get_current_user_roles())}}<br>
                    <small>Added at {{ Carbon\Carbon::parse(Auth::user()->created_at)->format('d F Y') }} </small>
                </p>
                <hr/>
            <div class="row">
            <div class="col-sm-6">
                {{--@if(Auth::user()->id != 1)
                <a href="{{ route('users.show', ['user' => Auth::user()->id]) }}" class="btn btn-block btn-dark">Profile</a>
                @endif--}}
            </div>
            <div class="col-sm-6"><a href="{{ route('logout.perform') }}" class="btn btn-block btn-dark" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a></div>
            <form id="logout-form" action="{{ route('logout.perform') }}" method="POST" style="display: none;">
                  {{ csrf_field() }}
            </form>
          </div>
    </div>
    </div>

</aside>
