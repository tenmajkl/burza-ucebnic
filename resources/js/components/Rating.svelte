<script>
    export let offer;
    let can = false;
    fetch('/api/rating/verify/'+offer.id)
        .then(res => res.json())
        .then(res => {
            can = res.data;
        });

    async function send(value) {
        const response = await fetch('/api/rating/'+offer.id, {
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

{#if can}
{/if}

<i class="bi bi-check text-4xl text-blue" on:click={async () => await send(1)}></i>
<i class="bi bi-x text-4xl text-red" on:click={async () => await send(1)}></i>


