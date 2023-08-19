<script>
    import Conversation from "../components/Conversation.svelte";

    // todo upravy cecky zpravy pindik
    // maybe show all reservations?
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

    function interestedText(interested)
    {
        if (interested === 1) {
            return 'interested-1';
        }
        if (interested == 2 || interested == 3) {
            return 'interested-2-3';
        }

        return 'interested';
    }

    function getReservation(offer)
    {
        let result;
        fetch('/api/reservations/' + offer.id)
            .then(response => response.json())
            .then(res => {
                result = res.data;
            });
        return result;
    }

    function edit(index)
    {
        fetch('/api/offers/' + data[index].id, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                price: data[index].price
            })
        })
            .then(response => response.json())
            .then(res => {
                if (res.success) {
                    // todo alert
                }
            });
    }

</script>

<div class="text-2xl font-bold">
    <Text text="my-offers-title" />
</div>

<hr class="my-2 border-t-4 rounded-sm border-secondary">

<div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 2xl:grid-cols-4 gap-2">
    {#each data as offer, index}
        <div class="flex flex-col card gap-5 {openned === index ? 'md:col-span-2 xl:col-span-3 2xl:col-span-4' : ''}">
            <div class="flex gap-5">
                <img src="/static/img/offers/{offer.id}" alt="cecko" class="card-image">
                <div class="flex flex-col justify-between font-bold">
                    <div>
                        <div class="text-xl">{offer.name}</div>
                        <div class="text-sm text-secondary">{offer.reservations} <Text text="{interestedText(offer.reservations)}" /></div>
                    </div>
                    <div>
                        <div class="text-sm text-secondary"><Text text="state-{offer.state}" /></div>
                        <div class="flex gap-3">
                            <div class="text-xl flex items-center">
                                <input type="number" bind:value={offer.price} class="w-20 input"> <Text text="currency" />
                            </div>
                            <button on:click={() => edit(index)}><i class="text-lg bi bi-pen text-blue"></i></button>
                            <button on:click={() => {reservation = getReservation(offer); openned = index;}} disabled={offer.reservations == 0}><i class="text-lg bi bi-chat text-blue"></i></button>
                        </div>
                    </div>
                </div>
            </div>
        <div>
            {#if reservation}
                <Conversation reservation={reservation} opponent={reservation.author} />
            {/if}</div>
        </div>
    {/each}
</div>
