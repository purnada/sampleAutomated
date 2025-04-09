@extends('layouts.app')

@section('content')
<div class="flex justify-center min-h-screen align-middle">
    <main id="content" class="w-full max-w-md mx-auto my-auto p-6">
        <a href="index.html" class="header-logo"> <img src="{{ asset('assets/img/brand-logos/desktop-dark.png') }}" alt="logo"
                class="mx-auto block" /> </a>
        <div class="mt-7 bg-white rounded-sm shadow-sm dark:bg-bgdark">
            <div class="p-4 sm:p-7">
                <div class="text-center">
                    <h1 class="block text-2xl font-bold text-gray-800 dark:text-white">Forgot password?</h1>
                    <p class="mt-3 text-sm text-gray-600 dark:text-white/70">Remember your password? <a
                            class="text-primary decoration-2 hover:underline font-medium" href="{{ route('login') }}"> Sign in
                            here </a></p>
                </div>
                @if (session('status'))
                <div class="bg-success/10 border border-success/10 alert text-success" role="alert">
                    <span class="font-bold">Success</span> {{ session('status') }}
                </div>

                @endif
                <div class="mt-5">
                    <form method="POST" action="{{ route('password.email') }}">
                        @csrf
                        <div class="grid gap-y-4">
                            <div>
                                <label for="email" class="block text-sm mb-2 dark:text-white">Email address</label>
                                <div class="relative">
                                    <input type="email" id="email" name="email" value="{{ old('email') }}"
                                        class="py-2 px-3 block w-full border-gray-200 rounded-sm text-sm focus:border-primary focus:ring-primary dark:bg-bgdark dark:border-white/10 dark:text-white/70 @error('email') border-red-500 @enderror"
                                        required />
                                        @error('email')
                                        <span class="text-red-500 text-xs">{{ $message }}</span>
                                        @enderror
                                </div>
                            </div>
                            <button type="submit"
                                class="py-2 px-3 inline-flex justify-center items-center gap-2 rounded-sm border border-transparent font-semibold bg-primary text-white hover:bg-primary focus:outline-none focus:ring-0 focus:ring-primary focus:ring-offset-0 transition-all text-sm dark:focus:ring-offset-white/10">
                                Send Reset Link
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>
</div>

@endsection
