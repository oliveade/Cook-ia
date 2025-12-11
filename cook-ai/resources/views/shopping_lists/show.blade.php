<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ $list->title }}
            </h2>

            <a href="{{ route('shopping-lists.index') }}" class="text-sm text-indigo-600 hover:underline">
                ← Retour aux listes
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white p-6 rounded shadow">
                @if($items->isEmpty())
                    <p class="text-sm text-gray-500">
                        Cette liste est vide.
                    </p>
                @else
                    <ul class="space-y-2">
                        @foreach($items as $item)
                            <li class="flex justify-between text-sm text-gray-800">
                                <span>
                                    {{ ucfirst($item->ingredient->name) }}
                                </span>
                                <span>
                                    @if($item->quantity)
                                        {{ $item->quantity }} {{ $item->unit }}
                                    @else
                                        — {{ $item->unit }}
                                    @endif
                                </span>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
