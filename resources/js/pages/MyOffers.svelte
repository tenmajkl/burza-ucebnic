<script>
    import MyOffer from "../components/MyOffer.svelte";

    import Text from "../components/Text.svelte";

    export let selected;

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
        <MyOffer {offer} bind:editing={editing} bind:openned={openned} {index} />
    {:else}
        <span>
            <Text text="my-offers-none" />
            <span  class="cursor-pointer" on:click={() => selected = 3}><Text text="add-title"></Text></span>
        </span>
    {/each}
</div>
