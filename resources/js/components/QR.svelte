<script>
    import Text from "../components/Text.svelte";
    export let reservation;
    let qr = null;
    fetch("/api/reservations/qr/" + reservation.id)
        .then(response => response.json())
        .then(res => {
            qr = res.data;
        })
    ; 
</script>

<div class="flex flex-col justify-between gap-4">
    <Text text="qr-info" />
    {#if qr}
        <img src="https://api.qrserver.com/v1/create-qr-code/?data=http://{window.location.hostname}/reservations/acceptance/{qr}" alt="http://{window.location.hostname}/reservations/show/{qr}" class="w-full border-2 border-blue">
    {/if}
</div>
