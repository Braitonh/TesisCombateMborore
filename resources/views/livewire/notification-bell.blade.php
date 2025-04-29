<div
    class="relative bg-gray-50 w-10 h-10 flex items-center justify-center rounded-full cursor-pointer"
    @if($count >= 1) wire:click="viewNotification" @endif
    >

    <i @class([
        'fa-solid fa-bell text-gray-700 text-2xl animate-wiggle',
        'text-red-500' => $count >= 1,])
    >
    </i>

    @if($count > 0)
        <div class="px-1 py-0.5 bg-red-100 min-w-5 rounded-full text-center text-white text-xs absolute -top-2 -end-1 translate-x-1/4">
            <div
            @class([
              'absolute top-0 start-0 rounded-full -z-10 bg-red-500 w-full h-full',
              'animate-ping' => $count > 0,
            ])
          ></div>
            {{ $count }}
        </div>
    @endif
</div>