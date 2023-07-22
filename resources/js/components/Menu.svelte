<script>
    import Text from "./Text.svelte";

    export let items;

    export let selected;
</script>

{#each items as [item, _, icon], index}
    <div class="flex flex-col items-center text-xs md:flex-row md:gap-2 md:text-sm">
        <div class="{selected === index ? 'text-blue' : ''} flex flex-col md:flex-row md:gap-2 items-center transition ease-in-out" on:click={() => selected = index}>
            <i class="bi bi-{icon} text-2xl" />
            <Text text="{item}" />
        </div>
    </div>
{/each}
