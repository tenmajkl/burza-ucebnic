<script>
    import Text from "../components/Text.svelte";
    import { slide } from 'svelte/transition';
    import { fade } from 'svelte/transition';
    import { getNotificationCount } from '../lib';

    export let notification;
    export let emoji;
    export let title;
    export let arg;    
    export let created_at;
    export let data;

    let openned = false;

    function open() {
        if (notification.seen) {
            return;
        }
        openned = !openned;
        notification.seen = true;
        fetch('/api/notifications/'+notification.id, {
            method: 'post',
            headers: {
                'Content-Type': 'application/json',
            },
            body: '{}'
        })

        getNotificationCount().then((res) => { data = res })
    }
</script>
<div class="gap-2 flex flex-col">
    <div on:click={open} class="flex gap-2 items-center {notification.seen ? 'text-secondary' : 'text-black'} transition duration-400 justify-between">
        <div class="flex gap-2 items-center">
            <span class="text-4xl {notification.seen ? 'grayscale' : ''} transition duration-400"><Text text={'emoji-' + title} /></span>
            <span>
                <div>{created_at}</div>
                <div class="text-xl"> 
                    <Text text={'notification-' + title} arg={arg} />
                </div>
            </span>
        </div>
        {#if !notification.seen}
            <i class="bi bi-eye text-2xl" transition:fade={{ duration: 200 }}></i>
        {/if}
    </div>

    {#if openned}
        <div transition:slide={{}}>
            <slot/>
        </div>
    {/if}
</div>
