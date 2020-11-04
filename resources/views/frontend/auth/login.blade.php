@extends('frontend.layouts.login')
@section('title', __('Login'))
@section('content')
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="form-container">
                <div class="header">
                    <div class="logo">
                        <img src="https://d1gi394wp2tyv2.cloudfront.net/app/hashed-v6.1/segment-icon-cae6beffb4890ba1c5fb.svg" alt="Segment">
                    </div>
                    <h1 class="m-0 text-center">Log in to Segment</h1>
                </div>
                
                @error('email')
                <div class="alert-container">
                    <div class="alert-body">
                        <div class="left">
                            <svg data-icon="warning-sign" viewBox="0 0 16 16"><path d="M15.84 13.5l.01-.01-7-12-.01.01c-.17-.3-.48-.5-.85-.5s-.67.2-.85.5l-.01-.01-7 12 .01.01c-.09.15-.15.31-.15.5 0 .55.45 1 1 1h14c.55 0 1-.45 1-1 0-.19-.06-.35-.15-.5zm-6.85-.51h-2v-2h2v2zm0-3h-2v-5h2v5z" fill-rule="evenodd"></path></svg>
                        </div>
                        <div class="right">
                            <h4 class="m-0">
                                Email does not exist
                            </h4>
                            <p class="text-message">
                                {!! $message !!}
                            </p>
                        </div>
                    </div>
                </div>
                @enderror    
                @error('password')
                <div class="alert-container">
                     <div class="alert-body">
                        <div class="left">
                            <svg data-icon="warning-sign" viewBox="0 0 16 16"><path d="M15.84 13.5l.01-.01-7-12-.01.01c-.17-.3-.48-.5-.85-.5s-.67.2-.85.5l-.01-.01-7 12 .01.01c-.09.15-.15.31-.15.5 0 .55.45 1 1 1h14c.55 0 1-.45 1-1 0-.19-.06-.35-.15-.5zm-6.85-.51h-2v-2h2v2zm0-3h-2v-5h2v5z" fill-rule="evenodd"></path></svg>
                        </div>
                        <div class="right">
                            <h4 class="m-0">
                                Password incorrect
                            </h4>
                            <p class="text-message">
                                {!! $message !!}
                            </p>
                        </div>
                    </div>
                </div>
                @enderror
                <x-forms.post :action="route('frontend.auth.login')">
                    <div class="form-group">
                        <label for="email" class="col-form-label text-md-left">@lang('Email')</label>
                        <input type="email" name="email" id="email" class="form-control" placeholder="{{ __('E-mail Address') }}" value="{{ old('email') }}" maxlength="255" required autofocus autocomplete="email" />
                    </div>
                    <div class="form-group">
                        <label for="password" class="col-form-label text-md-left">@lang('Password')</label>
                        <input type="password" name="password" id="password" class="form-control" placeholder="{{ __('Password') }}" maxlength="100" required autocomplete="current-password" />
                    </div>
                    <div class="form-group">
                        <button class="btn btn-block btn-blue" type="submit">@lang('Log In')</button>
                    </div>
                </x-forms.post>
                <div class="footer">
                    <div class="text-center">
                        <span class="mb-0">Forgot your password? <a href="{{ route('frontend.auth.password.request') }}">Reset your password <svg data-icon="caret-right" viewBox="0 0 16 16" style="vertical-align: middle;"><path d="M11 8c0-.15-.07-.28-.17-.37l-4-3.5A.495.495 0 006 4.5v7a.495.495 0 00.83.37l4-3.5c.1-.09.17-.22.17-.37z" fill-rule="evenodd"></path></svg></a></span>
                    </div>
                    <div class="text-center">
                        <span class="mb-0">Don't have an account? <a href="{{ route('frontend.auth.register') }}">Sign up <svg data-icon="caret-right" viewBox="0 0 16 16" style="vertical-align: middle;"><path d="M11 8c0-.15-.07-.28-.17-.37l-4-3.5A.495.495 0 006 4.5v7a.495.495 0 00.83.37l4-3.5c.1-.09.17-.22.17-.37z" fill-rule="evenodd"></path></svg></a></span>
                    </div>
                    <div class="text-center small">
                        <small>This site is protected by reCAPTCHA and the Google <x-utils.link href="https://policies.google.com/privacy" :text="__('Privacy Policy')" /> and <x-utils.link href="https://policies.google.com/terms" :text="__('Terms of Service')" /> apply.</small>
                    </div>
                </div>
            </div><!--col-md-8-->
        </div><!--row-->
    </div><!--container-->
@endsection
