<script>
    export let notification;
    async function send(value) {
        const response = await fetch('/api/rating/'+notification.reservation.id, {
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
    <i class="bi bi-check text-4xl text-blue" on:click={async () => await send(1)}></i>
    <i class="bi bi-x text-4xl text-red" on:click={async () => await send(-1)}></i>
{/if}


