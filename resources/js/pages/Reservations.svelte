<script>
    import Reservation from '../components/Reservation.svelte';
    import Text from "../components/Text.svelte";

    let message = null;
    let qr = null;

    let data = [];

    fetch("/api/reservations")
        .then(response => response.json())
        .then(res => {
            data = res.data;
        })
        .catch(error => {
            console.error(error);
        });

</script>

<div class="text-2xl font-bold">
    <Text text="reservations-title" />
</div>

<hr class="my-2 border-t-4 rounded-sm border-secondary">

<div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 2xl:grid-cols-4 gap-2">
    {#each data as reservation, index}
        <Reservation reservation={reservation} index={index} bind:message={message} bind:qr={qr} />
    {/each}
</div>
