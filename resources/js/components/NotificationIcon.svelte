<script>
    export let data;
</script>

<div class="relative inline-flex">
    <i class="bi bi-bell text-2xl"></i>
    {#if data.notifications > 0}
        <span class="right-0 top-0 bg-red rounded-full text-white text-xs absolute inline-flex border-white border-2 justify-center items-center {data.notifications < 10 ? 'w-5' : 'w-6'} h-5 -m-1">{data.notifications < 10 ? data.notifications : '9+'}</span>
    {/if}
</div>
