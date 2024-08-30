<script>
    import MyOffer from "../components/MyOffer.svelte";

    import Text from "../components/Text.svelte";

    export let selected;

    let message = null;
    let data = null;
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

{#if data === null}
    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" class="bi bi-arrow-clockwise animate-spin" viewBox="0 0 16 16">
      <path fill-rule="evenodd" d="M8 3a5 5 0 1 0 4.546 2.914.5.5 0 0 1 .908-.417A6 6 0 1 1 8 2z"/>
      <path d="M8 4.466V.534a.25.25 0 0 1 .41-.192l2.36 1.966c.12.1.12.284 0 .384L8.41 4.658A.25.25 0 0 1 8 4.466"/>
    </svg>
{:else}
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
{/if}
