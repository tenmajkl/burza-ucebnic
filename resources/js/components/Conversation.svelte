<script>
    export let opponent;
    let messages = [
        {
            from: 'me',
            content: 'csfid'
        },
        {
            from: 'xharabis',
            content: 'csmoravak'
        }
    ];
</script>

<div class="flex flex-col justify-between h-96">
    <div class="overflow-y-auto">
        {#each messages as message}
            <div class="flex flex-col gap-1">
                <div class="flex justify-between">
                    <span class="text-xs text-gray-500">{message.from}</span>
                    <span class="text-xs text-gray-500">12:00</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-sm">{message.content}</span>
                </div>
            </div>
        {/each}
    </div>

    <div class="flex items-center gap-2">
        <input type="text" class="w-full border-2 border-black input" placeholder="...">
        <i class="text-2xl bi bi-send"></i>
    </div>
</div>
