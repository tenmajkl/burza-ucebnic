<script>
    import Text from "./Text.svelte";

    export let items;

    export let selected;

    export let data;
</script>

{#each items as [item, _, icon], index}
    {#if item}
        <div class="flex flex-col items-center text-xs md:flex-row md:gap-2 md:text-sm {selected == index ? 'cursor-default' : 'cursor-pointer'}">
            <div class="{selected === index ? 'text-blue' : ''} flex flex-col md:flex-row md:gap-2 items-center transition ease-in-out" on:click={() => selected = index}>
                {#if typeof icon == 'string'}
                    <i class="bi bi-{icon} text-2xl" />
                {:else} 
                    <svelte:component this={icon} bind:data={data} />
                {/if}
                <span class="hidden sm:block"><Text text="{item}" /></span>
            </div>
        </div>
    {/if}
{/each}
