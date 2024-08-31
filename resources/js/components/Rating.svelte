<script>
    export let notification;
    async function send(value) {
        return await fetch('/api/rating/'+notification.rating.id, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                rating: value
            })
        });
    }
</script>

{#if notification.can_rate}
    <i class="bi bi-check text-4xl text-blue" on:click={async () => {await send(1); notification.can_rate = false;} }></i>
    <i class="bi bi-x text-4xl text-red" on:click={async () => {await send(-1); notification.can_rate = false;} }></i>
{/if}


