<script>
    import { slide } from 'svelte/transition';
    import { onMount } from 'svelte';
    import Text from './Text.svelte';

    export let reservation;
    export let opponent;
    let messages = [];
    let message = '';
    let session_id;

    function getMessages() {
        fetch(`/api/messages/${reservation.id}`, {
            method: 'get',
        })
        .then(res => res.json())
        .then(data => {
            messages = data.data;
            session_id = data.session_id;
            if (ws_online) {
                ws.send(JSON.stringify({
                    type: 0,
                    session: session_id,
                    reservation: reservation.id,
                }));
            }
            })
    }

    function send()
    {
        if (!ws_online) {
            fetch(`/api/messages/${reservation.id}`, {
                method: 'post',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    content: message,
                    notify: 1,
                })
            });
            getMessages();
            message = '';
            return;
        }

        ws.send(JSON.stringify({
            type: 1,
            content: message,
        }))
        message = '';
    }

    function scroll() 
    { 
        let chat = document.getElementById("chat");
        setTimeout(() => chat ? chat.scrollTop = chat.scrollHeight + 10 : null, 100);
    }

    $: messages, scroll();

    let ws = new WebSocket('ws://localhost:2346');

    let ws_online = false;

    ws.onopen = function() {
        ws_online = true;
    }

    ws.onerror = function() {
        ws_online = false;
    }

    getMessages();

    ws.onmessage = function(e) {
        let data = JSON.parse(e.data);
        messages = [...messages, data];
    };

    onMount(() => {
        return () => {
            ws.close();
        }
    });

</script>

<div class="flex flex-col justify-between h-64 md:h-96 gap-2">
<div class="font-bold"><Text text="chat"></Text></div>
    <div class="overflow-y-auto h-full flex flex-col gap-2 overflow-x-clip" id="chat">
        {#each messages as message}
            <div class="flex flex-col">
                <div class="flex justify-between">
                    <span class="text-xs text-{message.author == opponent ? 'secondary' : 'blue'}">{message.author}</span>
                    <span class="text-xs text-secondary">{message.createdAt}</span>
                </div>
                <div class="flex justify-between text-wrap text-clip">
                    <span class="text-sm">{message.content}</span>
                </div>
            </div>
        {/each}
    </div>

    <div class="flex items-center gap-2">
        <input type="text" class="w-full border-2 border-black input" placeholder="..." bind:value={message} on:keydown={(e) => { if (e.key == 'Enter') { send() } } }>
        <button on:click={send} disabled={message ===''} ><i class="text-2xl bi bi-send"></i></button>
    </div>
</div>
