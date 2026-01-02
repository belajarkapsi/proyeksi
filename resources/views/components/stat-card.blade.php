@props(['id', 'label', 'value', 'color', 'icon', 'subValue' => null])

<div class="group flex items-center p-5 bg-white rounded-xl shadow-sm border border-gray-100 transition-all hover:shadow-md">
    <div class="p-3 mr-4 rounded-lg transition-colors duration-300 
        {{ $color == 'blue' ? 'text-blue-600 bg-blue-100' : '' }}
        {{ $color == 'orange' ? 'text-orange-500 bg-orange-100' : '' }}
        {{ $color == 'indigo' ? 'text-indigo-500 bg-indigo-100' : '' }}
        {{ $color == 'emerald' ? 'text-emerald-500 bg-emerald-100' : '' }}
        {{ $color == 'purple' ? 'text-purple-500 bg-purple-100' : '' }}
        {{ $color == 'pink' ? 'text-pink-500 bg-pink-100' : '' }}
        {{ $color == 'green' ? 'text-green-500 bg-green-100' : '' }}
    ">
        <i class="{{ $icon }} text-xl"></i>
    </div>
    <div>
        <p class="mb-1 text-sm font-medium text-gray-500">{{ $label }}</p>
        <div class="flex items-baseline space-x-2">
            <p id="val-{{ $id }}" class="text-2xl font-bold text-gray-800">{{ $value }}</p>
            @if($subValue)
                <span class="text-xs text-gray-400">{{ $subValue }}</span>
            @endif
        </div>
    </div>
</div>