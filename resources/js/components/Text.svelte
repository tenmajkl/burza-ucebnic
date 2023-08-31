<script>
    export let text;
    export let arg = null;
</script>

{#if arg}
    {translations[text].replace('%arg', arg)}
{:else}
    {translations[text]}
{/if}
