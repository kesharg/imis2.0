


        
            <p class="login-box-msg">Log in</p>
            @if(isset ($errors) && count($errors) > 0)
                <div class="alert alert-warning" role="alert">
                    <ul class="list-unstyled mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if(Session::get('success', false))
                <?php $data = Session::get('success'); ?>
                @if (is_array($data))
                    @foreach ($data as $msg)
                        <div class="alert alert-warning" role="alert">
                            <i class="fa fa-check"></i>
                            {{ $msg }}
                        </div>
                    @endforeach
                @else
                    <div class="alert alert-warning" role="alert">
                        <i class="fa fa-check"></i>
                        {{ $data }}
                    </div>
                @endif
            @endif
    
                    <form method="POST" action="{{ route('login.perform') }}">
                        @csrf
                        <div class="input-group mb-3">
                            <input type="username" class="form-control @error('username') is-invalid @enderror" name="username" value="{{ old('username') }}" required placeholder="Email or Username">
                            <div class="input-group-append">
                            <div class="input-group-text">
                            <span class="fas fa-envelope"></span>
                            </div>
                            </div>
                             @error('username')
                             <span id="exampleInputEmail1-error" class="error invalid-feedback">
                                <strong>{{ $message }}</strong>
                            </span>
                             @enderror
                        </div>

                 

                        <div class="input-group mb-3">
                            <input type="password" id ="password" class="form-control @error('password') is-invalid @enderror" name="password" required placeholder="Password"  value="{{ old('password') }}">
                           <div class="input-group-append">
                           
                            <div class="input-group-text">
                            
                            <span class="fas fa-lock"></span>
                            </div>
                            </div>
                             @error('password')
                                    <span id="exampleInputEmail1-error" class="error invalid-feedback">
                                        <strong>{{ $message }}</strong>
                                    </span>
                            @enderror
                        </div>
                        
                        <div class="row">
                             <div class="col-6">
                                <div class="icheck-primary">
                                <input type="checkbox" name="showpassword" id="showpassword" onclick="myFunction()">
                                <label for="showpassword">
                                Show password
                                </label>
                                </div>
                                
                            </div>
                            <div class="col-6 ">
                               
                                    <div class="icheck-primary " style=" text-align: end;">
                                        <input type="checkbox" name="remember" id="remember" value="1" >
                                        <label for="remember">
                                        Remember Me
                                        </label>
                                    <div>
                            </div>
                        </div>
                        </div>
                        </div>
                        <div class="row justify-content-end">
                            <div class="col-12 "> <!-- Adjust the column width as needed -->
                                <button type="submit" class="btn btn-primary btn-block">Log In</button>
                            </div>
                            <div class="col-12 mt-2 text-center">
                                @if (Route::has('password.request'))
                                    <a href="{{ route('password.request') }}">
                                        {{ __('Forgot Your Password?') }}
                                    </a>
                                @endif
                            </div>
                        </div>

            </form>
            
                         
           