@extends('layouts.app')

@section('content')
<div class="flex justify-center min-h-screen align-middle">
    <main id="content" class="w-full max-w-md mx-auto my-auto p-6">
        <a href="#" class="header-logo"> <img src="{{ asset('assets/img/brand-logos/desktop-dark.png') }}" alt="logo" class="mx-auto block" /> </a>
        <div class="mt-7 bg-white rounded-sm shadow-sm dark:bg-bgdark">
            <div class="p-4 sm:p-7">
                <div class="text-center">
                    <h1 class="block text-2xl font-bold text-gray-800 dark:text-white">Sign in</h1>

                </div>
                <div class="mt-5">
                    <div>
                        <div class="grid gap-y-4">
                            <form method="POST" action="{{ route('login') }}">
                                @csrf
                            <div class="mb-5">
                                <label for="email" class="block text-sm mb-2 dark:text-white">Email address</label>
                                <div class="relative">
                                    <input
                                        type="email"
                                        id="email"
                                        name="email"
                                        class="py-2 px-3 block w-full border-gray-200 rounded-sm text-sm focus:border-primary focus:ring-primary dark:bg-bgdark dark:border-white/10 dark:text-white/70 @error('email') border-red-500 @enderror"
                                        required
                                    />
                                    @error('email')
                                    <span class="pincodeError text-red-500 text-xs">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="mb-5">
                                <div class="flex justify-between items-center">
                                    <label for="password" class="block text-sm mb-2 dark:text-white">Password</label> <a
                                        class="text-sm text-primary decoration-2 hover:underline font-medium" href="{{ route('password.request') }}">Forgot password?</a>
                                </div>

                                <div class="relative">
                                    <input
                                        type="password"
                                        id="password"
                                        name="password"
                                        class="py-2 px-3 block w-full border-gray-200 rounded-sm text-sm focus:border-primary focus:ring-primary dark:bg-bgdark dark:border-white/10 dark:text-white/70"
                                        required
                                    />
                                </div>
                            </div>
                            <div class="flex items-center mb-5">
                                <div class="flex">
                                    <input
                                        id="remember-me"
                                        name="remember"
                                        type="checkbox"
                                        {{ old('remember') ? 'checked' : '' }}
                                        class="shrink-0 mt-0.5 border-gray-200 rounded text-primary pointer-events-none focus:ring-primary dark:bg-bgdark dark:border-white/10 dark:checked:bg-primary dark:checked:border-primary dark:focus:ring-offset-white/10"
                                    />
                                </div>
                                <div class="ltr:ml-3 rtl:mr-3"><label for="remember-me" class="text-sm dark:text-white">Remember me</label></div>
                            </div>
                            <button
                                class="py-2 px-3 inline-flex justify-center items-center gap-2 rounded-sm border border-transparent font-semibold bg-primary text-white hover:bg-primary focus:outline-none focus:ring-0 focus:ring-primary focus:ring-offset-0 transition-all text-sm dark:focus:ring-offset-white/10"
                                type="submit"
                            >
                                Sign in
                            </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>

@endsection
