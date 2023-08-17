<script>
    export let reservation;
    export let opponent
    let messages = [];
    let message = '';

    function getMessages() {
        fetch(`/api/messages/${reservation.id}`, {
            method: 'get',
        })
        .then(res => res.json())
        .then(data => {
            messages = data;
            })
    }

    function send()
    {
        fetch(`/api/messages/${reservation.id}`, {
            method: 'post',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                content: message,
            })
        });
        getMessages();
        message = '';
    }

    getMessages();

</script>

<div class="flex flex-col justify-between h-96">
    <div class="overflow-y-auto">
        {#each messages as message}
            <div class="flex flex-col gap-1">
                <div class="flex justify-between">
                    <span class="text-xs text-gray-500">{message.author}</span>
                    <span class="text-xs text-gray-500">{message.createdAt}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-sm">{message.content}</span>
                </div>
            </div>
        {/each}
    </div>

    <div class="flex items-center gap-2">
        <input type="text" class="w-full border-2 border-black input" placeholder="..." bind:value={message}>
        <button on:click={send} disabled={message ===''} ><i class="text-2xl bi bi-send"></i></button>
    </div>
</div>
