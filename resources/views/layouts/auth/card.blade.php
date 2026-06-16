<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-slate-100 antialiased dark:bg-zinc-950">
        <div class="flex min-h-svh flex-col items-center justify-center gap-6 p-6 md:p-10">
            <div class="flex w-full max-w-md flex-col gap-6">
                {{-- Brand Logo --}}
                <a href="{{ route('home') }}" class="flex flex-col items-center gap-2 font-medium" wire:navigate>
                    <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-teal-700 shadow-sm">
                        <x-app-logo-icon class="size-6 fill-current text-white" />
                    </span>
                    <span class="text-sm font-semibold text-slate-700 dark:text-zinc-300">
                        {{ config('app.name', 'Laravel') }}
                    </span>
                </a>

                {{-- Login Card --}}
                <div class="flex flex-col gap-6">
                    <div class="rounded-2xl border border-slate-200 bg-white shadow-sm dark:border-zinc-800 dark:bg-zinc-900">
                        <div class="px-8 py-8">{{ $slot }}</div>
                    </div>
                </div>
            </div>
        </div>

        @persist('toast')
            <flux:toast.group>
                <flux:toast />
            </flux:toast.group>
        @endpersist

        @fluxScripts
    </body>
</html>
