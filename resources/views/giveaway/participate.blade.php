@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto">
        <div class="bg-white rounded-lg shadow p-6">
            <h1 class="text-3xl font-bold mb-2">{{ $giveaway->title }}</h1>

            @if($giveaway->description)
                <div class="text-gray-600 mb-6">
                    {!! $giveaway->description !!}
                </div>
            @endif

            <div class="grid grid-cols-2 gap-4 mb-6 p-4 bg-gray-50 rounded">
                <div>
                    <p class="text-sm text-gray-600">Participants</p>
                    <p class="text-2xl font-bold">{{ $giveaway->getParticipantCount() }} / {{ $giveaway->max_participants }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Remaining Slots</p>
                    <p class="text-2xl font-bold text-green-600">{{ $giveaway->getRemainingSlots() }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Winners</p>
                    <p class="text-2xl font-bold">{{ $giveaway->winner_count }}</p>
                </div>
                @if($giveaway->prize_amount)
                    <div>
                        <p class="text-sm text-gray-600">Prize</p>
                        <p class="text-2xl font-bold">${{ number_format($giveaway->prize_amount, 2) }}</p>
                    </div>
                @endif
            </div>

            @if($giveaway->isFull())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                    This giveaway has reached maximum participants.
                </div>
            @else
                @if($errors->any())
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                        <ul>
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if(session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                        {{ session('success') }}
                    </div>
                @endif

                <form action="{{ route('giveaway.participate.store', $giveaway) }}" method="POST" class="space-y-4">
                    @csrf

                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">
                            Name <span class="text-red-500">*</span>
                        </label>
                        <input
                            type="text"
                            id="name"
                            name="name"
                            value="{{ old('name') }}"
                            required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            placeholder="Enter your name"
                        >
                        @error('name')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">
                            Email <span class="text-red-500">*</span>
                        </label>
                        <input
                            type="email"
                            id="email"
                            name="email"
                            value="{{ old('email', auth()->user()?->email) }}"
                            required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            placeholder="Enter your email"
                        >
                        @error('email')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">
                            Phone <span class="text-gray-500">(Optional)</span>
                        </label>
                        <input
                            type="tel"
                            id="phone"
                            name="phone"
                            value="{{ old('phone') }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            placeholder="Enter your phone number"
                        >
                        @error('phone')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <button
                        type="submit"
                        class="w-full bg-blue-600 text-white font-semibold py-2 px-4 rounded-lg hover:bg-blue-700 transition-colors"
                    >
                        Enter Giveaway
                    </button>
                </form>
            @endif
        </div>
    </div>
</div>
@endsection
