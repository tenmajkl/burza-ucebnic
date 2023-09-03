<script>
    import MyOffer from "../components/MyOffer.svelte";

    import Text from "../components/Text.svelte";

    let message = null;
    let data = [];
    let reservation;
    let editing; 
    let openned;

    fetch('/api/offers/mine')
        .then(response => response.json())
        .then(res => {
            data = res.data;
        });

</script>

<div class="text-2xl font-bold">
    <Text text="my-offers-title" />
</div>

<div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 2xl:grid-cols-4 gap-2">
    {#each data as offer, index}
        <MyOffer offer={offer} bind:editing={editing} bind:openned={openned} />
    {:else}
        <Text text="my-offers-none" />
    {/each}
</div>
